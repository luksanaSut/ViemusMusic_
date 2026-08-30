<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Course;
use App\Models\ClassSchedule;
use App\Models\Guardian;
use App\Models\Room;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TrialLead;
use App\Models\TrialPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class TrialLeadController extends Controller
{
    public function index(Request $request)
    {
        $status = in_array($request->status, ['new', 'contacted', 'scheduled', 'completed', 'converted', 'lost'], true)
            ? $request->status : null;

        $leads = TrialLead::with(['course', 'teacher'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->q;
                $query->where(fn ($q) => $q->where('student_name', 'like', "%{$term}%")
                    ->orWhere('guardian_name', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%")
                    ->orWhere('lead_no', 'like', "%{$term}%"));
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderByRaw("status = 'new' desc")
            ->orderByDesc('created_at')->paginate(15)->withQueryString();

        $counts = TrialLead::selectRaw('status, count(*) total')->groupBy('status')->pluck('total', 'status');
        $followUpCount = TrialLead::whereNotIn('status', ['converted', 'lost'])
            ->whereDate('next_follow_up_date', '<=', today())->count();

        return view('trial-leads.index', compact('leads', 'counts', 'followUpCount', 'status'));
    }

    public function create()
    {
        return view('trial-leads.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $this->ensureTrialSlotIsAvailable($data);
        $paymentInput = $this->validatedPayment($request, (float) $data['trial_fee']);

        $data['lead_no'] = $this->nextLeadNo();
        $data['created_by'] = $request->user()->displayName();
        $data['status'] = !empty($data['trial_date']) ? 'scheduled' : 'new';
        $data['payment_status'] = 'unpaid';

        $lead = DB::transaction(function () use ($data, $paymentInput, $request) {
            $lead = TrialLead::create($data);
            if ($paymentInput) {
                $this->recordInitialPayment($lead, $paymentInput, $request);
            }
            return $lead;
        });

        if ($lead->teacher_id) {
            $this->notifyTeacherOfTrial($lead);
        }

        return redirect()->route('trial-leads.show', $lead)->with('success', 'เพิ่มผู้สนใจเรียบร้อยแล้ว');
    }

    public function show(TrialLead $trialLead)
    {
        $trialLead->load(['course', 'teacher', 'room', 'convertedStudent', 'payments.refunds']);
        return view('trial-leads.show', array_merge($this->formData(), compact('trialLead')));
    }

    public function update(Request $request, TrialLead $trialLead)
    {
        abort_if($trialLead->status === 'converted', 422, 'รายการนี้แปลงเป็นนักเรียนแล้ว');
        $data = $this->validated($request, true);
        $this->ensureTrialSlotIsAvailable($data, $trialLead->id);
        $trialLead->update($data);

        if ($trialLead->teacher_id && $trialLead->wasChanged(['teacher_id', 'trial_date', 'trial_start_time', 'trial_end_time'])) {
            $this->notifyTeacherOfTrial($trialLead);
        }

        return back()->with('success', 'บันทึกข้อมูลผู้สนใจและผลทดลองเรียนแล้ว');
    }

    public function convert(Request $request, TrialLead $trialLead)
    {
        if ($trialLead->converted_student_id) {
            return redirect()->route('students.show', $trialLead->converted_student_id);
        }

        $student = DB::transaction(function () use ($trialLead) {
            $student = Student::create([
                'student_code' => $this->nextStudentCode(),
                'full_name' => $trialLead->student_name,
                'nickname' => $trialLead->nickname,
                'date_of_birth' => $trialLead->date_of_birth,
                'phone' => $trialLead->phone,
                'email' => $trialLead->email,
                'line_id' => $trialLead->line_id,
                'status' => 'active',
                'notes' => trim("แปลงจากผู้สนใจ {$trialLead->lead_no}\n" . ($trialLead->notes ?? '')),
            ]);

            if ($trialLead->guardian_name) {
                $guardian = Guardian::firstOrCreate(
                    ['phone' => $trialLead->phone],
                    ['full_name' => $trialLead->guardian_name, 'email' => $trialLead->email, 'line_id' => $trialLead->line_id]
                );
                $student->guardians()->syncWithoutDetaching([$guardian->id]);
            }

            $trialLead->update(['status' => 'converted', 'converted_student_id' => $student->id, 'converted_at' => now()]);
            return $student;
        });

        return redirect()->route('students.show', $student)->with('success', 'สร้างข้อมูลนักเรียนจากผู้สนใจเรียบร้อยแล้ว สามารถขายคอร์สจริงต่อได้');
    }

    public function updateConfirmationStatus(Request $request, TrialLead $trialLead)
    {
        $data = $request->validate([
            'confirmation_status' => ['required', Rule::in([
                'pending', 'guardian_confirmed', 'unreachable', 'reschedule_requested', 'cancelled', 'no_show',
            ])],
            'confirmation_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $trialLead->setConfirmationStatus($data['confirmation_status'], $data['confirmation_notes'] ?? null, $request->user()->displayName());
        return back()->with('success', 'อัปเดตสถานะคอนเฟิร์มนัดทดลองแล้ว');
    }

    public function myIndex(Request $request)
    {
        $teacher = $this->teacherOf($request);
        $leads = TrialLead::with(['course', 'room'])
            ->where('teacher_id', $teacher->id)
            ->whereNotIn('status', ['converted', 'lost'])
            ->orderByRaw('trial_date is null')
            ->orderBy('trial_date')->orderBy('trial_start_time')
            ->get();

        return view('teacher-workspace.trial-leads-index', compact('leads'));
    }

    public function myShow(Request $request, TrialLead $trialLead)
    {
        $this->authorizeTeacherOwnsLead($request, $trialLead);
        $trialLead->load(['course', 'room']);
        return view('teacher-workspace.trial-lead-show', compact('trialLead'));
    }

    public function teacherConfirm(Request $request, TrialLead $trialLead)
    {
        $this->authorizeTeacherOwnsLead($request, $trialLead);
        $trialLead->markTeacherConfirmed($request->user()->displayName());
        return back()->with('success', 'ยืนยันนัดทดลองเรียบร้อยแล้ว');
    }

    public function checkIn(Request $request, TrialLead $trialLead)
    {
        $this->authorizeTeacherOwnsLead($request, $trialLead);
        $this->ensureTrialCanBeProcessed($trialLead);
        if (now()->lt($this->trialStartsAt($trialLead))) {
            throw ValidationException::withMessages(['trial' => 'ยังไม่ถึงเวลาเริ่มทดลองเรียน ไม่สามารถเช็กอินล่วงหน้าได้']);
        }
        if ($trialLead->checked_in_at) {
            throw ValidationException::withMessages(['trial' => 'นัดทดลองนี้เช็กอินแล้ว']);
        }
        $trialLead->update(['checked_in_at' => now(), 'checked_in_by' => $request->user()->displayName()]);
        return back()->with('success', 'เช็กอินผู้ทดลองเรียนแล้ว');
    }

    public function submitResult(Request $request, TrialLead $trialLead)
    {
        $this->authorizeTeacherOwnsLead($request, $trialLead);
        $data = $request->validate([
            'trial_result' => ['required', Rule::in(['interested', 'considering', 'not_interested', 'no_show'])],
            'teacher_feedback' => ['nullable', 'string', 'max:2000'],
        ]);
        $this->ensureTrialCanBeProcessed($trialLead);
        if (now()->lt($this->trialStartsAt($trialLead))) {
            throw ValidationException::withMessages(['trial_result' => 'ยังไม่ถึงเวลาเริ่มทดลองเรียน ไม่สามารถบันทึกผลล่วงหน้าได้']);
        }

        if ($data['trial_result'] === 'no_show') {
            if ($trialLead->checked_in_at) {
                throw ValidationException::withMessages(['trial_result' => 'รายการนี้เช็กอินแล้ว จึงไม่สามารถระบุว่าไม่มาตามนัดได้']);
            }
            $data['confirmation_status'] = 'no_show';
        } elseif (!$trialLead->checked_in_at) {
            throw ValidationException::withMessages(['trial_result' => 'กรุณาเช็กอินผู้ทดลองก่อนบันทึกผลการทดลองเรียน']);
        }

        $data['status'] = 'completed';
        $data['result_recorded_at'] = now();
        $data['result_recorded_by'] = $request->user()->displayName();
        $trialLead->update($data);

        return redirect()->route('trial-leads.my-show', $trialLead)->with('success', 'บันทึกผลทดลองเรียนแล้ว');
    }

    private function teacherOf(Request $request): Teacher
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 404, 'บัญชีนี้ยังไม่ได้ผูกกับข้อมูลอาจารย์');
        return $teacher;
    }

    private function authorizeTeacherOwnsLead(Request $request, TrialLead $trialLead): void
    {
        $user = $request->user();
        abort_unless($user->isTeacher() && $user->teacher_id && $user->teacher_id === $trialLead->teacher_id, 403, 'เข้าถึงได้เฉพาะนัดทดลองของตัวเองเท่านั้น');
    }

    private function ensureTrialCanBeProcessed(TrialLead $trialLead): void
    {
        if (!$trialLead->trial_date || !$trialLead->trial_start_time) {
            throw ValidationException::withMessages(['trial' => 'นัดทดลองนี้ยังไม่ได้กำหนดวันและเวลา']);
        }
        if (in_array($trialLead->confirmation_status, ['cancelled', 'no_show'], true)) {
            throw ValidationException::withMessages(['trial' => 'นัดทดลองนี้ถูกยกเลิกหรือบันทึกว่าไม่มาตามนัดแล้ว']);
        }
        if (in_array($trialLead->status, ['converted', 'lost'], true)) {
            throw ValidationException::withMessages(['trial' => 'สถานะผู้สนใจนี้ไม่สามารถดำเนินการทดลองเรียนได้']);
        }
    }

    private function trialStartsAt(TrialLead $trialLead): Carbon
    {
        return Carbon::parse($trialLead->trial_date->toDateString() . ' ' . $trialLead->trial_start_time);
    }

    private function notifyTeacherOfTrial(TrialLead $lead): void
    {
        $when = $lead->trial_date ? $lead->trial_date->format('d/m/Y') . ($lead->trial_start_time ? ' ' . substr($lead->trial_start_time, 0, 5) : '') : 'ยังไม่ระบุวันที่';
        AppNotification::notifyTeacher(
            $lead->teacher_id,
            'มีนัดทดลองเรียนใหม่',
            "{$lead->student_name} · {$when}",
            route('trial-leads.my-show', $lead)
        );
    }

    private function formData(): array
    {
        return [
            'courses' => Course::where('is_active', true)->with('instrument')->orderBy('name')->get(),
            'teachers' => Teacher::where('is_active', true)->with('availabilities')->orderBy('full_name')->get(),
            'rooms' => Room::where('is_active', true)->orderBy('name')->get(),
        ];
    }

    private function validated(Request $request, bool $updating = false): array
    {
        $data = $request->validate([
            'student_name' => ['required', 'string', 'max:150'], 'nickname' => ['nullable', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date', 'before:today'], 'age' => ['nullable', 'integer', 'between:1,100'],
            'guardian_name' => ['nullable', 'string', 'max:150'], 'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'], 'line_id' => ['nullable', 'string', 'max:100'],
            'course_id' => ['nullable', 'exists:courses,id'], 'teacher_id' => ['nullable', 'exists:teachers,id'],
            'room_id' => ['nullable', 'exists:rooms,id'], 'interest' => ['nullable', 'string', 'max:150'],
            'preferred_schedule' => ['nullable', 'string', 'max:255'], 'trial_date' => ['nullable', 'date'],
            'trial_start_time' => ['nullable', 'required_with:trial_date'],
            'trial_end_time' => ['nullable', 'required_with:trial_date', 'after:trial_start_time'],
            'delivery_mode' => ['required', 'in:onsite,online'], 'trial_fee' => ['required', 'numeric', 'min:0'],
            'status' => [$updating ? 'required' : 'nullable', Rule::in(['new', 'contacted', 'scheduled', 'completed', 'lost'])],
            'trial_result' => ['nullable', Rule::in(['interested', 'considering', 'not_interested', 'no_show'])],
            'teacher_feedback' => ['nullable', 'string', 'max:2000'], 'next_follow_up_date' => ['nullable', 'date'],
            'source' => ['nullable', 'string', 'max:100'], 'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $data['student_name'] = trim(strip_tags($data['student_name']));
        $data['phone'] = preg_replace('/[^0-9+]/', '', $data['phone']);
        return $data;
    }

    private function validatedPayment(Request $request, float $trialFee): ?array
    {
        if (!$request->filled('payment_method')) {
            return null;
        }

        return $request->validate([
            'payment_method' => ['required', 'in:transfer,promptpay,credit_card'],
            'payment_amount' => ['required', 'numeric', 'min:0.01', 'max:' . max($trialFee, 0.01)],
            'payment_reference_no' => ['nullable', 'string', 'max:100'],
            'payment_proof' => ['nullable', 'required_if:payment_method,transfer,promptpay', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'payment_notes' => ['nullable', 'string', 'max:1000'],
        ], ['payment_proof.required_if' => 'กรุณาแนบหลักฐานสำหรับการโอนหรือ PromptPay']);
    }

    private function recordInitialPayment(TrialLead $lead, array $paymentInput, Request $request): void
    {
        $isImmediatelyConfirmed = $paymentInput['payment_method'] === 'credit_card';
        $path = $request->hasFile('payment_proof')
            ? $request->file('payment_proof')->store('trial-payment-proofs', 'local') : null;

        TrialPayment::create([
            'transaction_no' => $this->nextPaymentTransactionNo(),
            'trial_lead_id' => $lead->id,
            'type' => 'payment',
            'amount' => $paymentInput['payment_amount'],
            'payment_method' => $paymentInput['payment_method'],
            'status' => $isImmediatelyConfirmed ? 'confirmed' : 'pending',
            'transaction_at' => now(),
            'reference_no' => $paymentInput['payment_reference_no'] ?? null,
            'proof_path' => $path,
            'proof_original_name' => $request->file('payment_proof')?->getClientOriginalName(),
            'notes' => $paymentInput['payment_notes'] ?? null,
            'created_by' => $request->user()->displayName(),
            'confirmed_by' => $isImmediatelyConfirmed ? $request->user()->displayName() : null,
            'confirmed_at' => $isImmediatelyConfirmed ? now() : null,
        ]);

        $paid = $lead->confirmedPaidAmount();
        $lead->update([
            'payment_status' => $paid > 0 ? 'paid' : 'unpaid',
            'paid_at' => $paid > 0 ? now() : null,
        ]);
    }

    private function nextPaymentTransactionNo(): string
    {
        return 'TP-' . now()->format('Ymd') . '-' . str_pad(TrialPayment::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
    }

    private function nextLeadNo(): string
    {
        return 'TL-' . now()->format('Ymd') . '-' . str_pad(TrialLead::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
    }

    private function ensureTrialSlotIsAvailable(array $data, ?int $excludeLeadId = null): void
    {
        if (empty($data['trial_date']) || empty($data['trial_start_time']) || empty($data['trial_end_time'])) return;

        $messages = ClassSchedule::findConflicts(
            $data['trial_date'], $data['trial_start_time'], $data['trial_end_time'], null,
            $data['teacher_id'] ?? null, $data['room_id'] ?? null
        );

        $trialConflict = (!empty($data['teacher_id']) || !empty($data['room_id'])) && TrialLead::where('status', 'scheduled')
            ->whereDate('trial_date', $data['trial_date'])
            ->when($excludeLeadId, fn ($query) => $query->whereKeyNot($excludeLeadId))
            ->where('trial_start_time', '<', $data['trial_end_time'])
            ->where('trial_end_time', '>', $data['trial_start_time'])
            ->where(function ($query) use ($data) {
                if (!empty($data['teacher_id'])) $query->orWhere('teacher_id', $data['teacher_id']);
                if (!empty($data['room_id'])) $query->orWhere('room_id', $data['room_id']);
            })->exists();

        if ($trialConflict) $messages[] = 'มีนัดทดลองเรียนของอาจารย์หรือห้องนี้ซ้อนอยู่แล้ว';
        if ($messages) back()->withInput()->withErrors(['trial_date' => implode(' / ', $messages)])->throwResponse();
    }

    private function nextStudentCode(): string
    {
        return 'ST-' . now()->format('Y') . '-' . str_pad(Student::whereYear('created_at', now()->year)->count() + 1, 4, '0', STR_PAD_LEFT);
    }
}
