<?php

namespace App\Http\Controllers;

use App\Models\TrialLead;
use App\Models\TrialPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TrialPaymentController extends Controller
{
    public function store(Request $request, TrialLead $trialLead)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:cash,transfer,promptpay,credit_card,other'],
            'transaction_at' => ['required', 'date'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'payment_proof' => ['nullable', 'required_if:payment_method,transfer,promptpay', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], ['payment_proof.required_if' => 'กรุณาแนบหลักฐานสำหรับการโอนหรือ PromptPay']);

        $outstanding = max(0, (float) $trialLead->trial_fee - $trialLead->confirmedPaidAmount());
        if ((float) $data['amount'] > $outstanding) {
            return back()->withInput()->withErrors(['amount' => 'ยอดรับเงินเกินค่าทดลองคงเหลือ ฿' . number_format($outstanding, 2)]);
        }

        $isImmediatelyConfirmed = in_array($data['payment_method'], ['cash', 'credit_card'], true);
        $path = $request->hasFile('payment_proof')
            ? $request->file('payment_proof')->store('trial-payment-proofs', 'local') : null;

        unset($data['payment_proof']);
        TrialPayment::create([
            ...$data,
            'transaction_no' => $this->nextTransactionNo('TP'),
            'trial_lead_id' => $trialLead->id,
            'type' => 'payment',
            'status' => $isImmediatelyConfirmed ? 'confirmed' : 'pending',
            'proof_path' => $path,
            'proof_original_name' => $request->file('payment_proof')?->getClientOriginalName(),
            'created_by' => $request->user()->displayName(),
            'confirmed_by' => $isImmediatelyConfirmed ? $request->user()->displayName() : null,
            'confirmed_at' => $isImmediatelyConfirmed ? now() : null,
        ]);

        $this->syncLeadPaymentStatus($trialLead);
        return back()->with('success', $isImmediatelyConfirmed ? 'บันทึกรับเงินค่าทดลองแล้ว' : 'บันทึกหลักฐานแล้ว รอตรวจสอบการชำระ');
    }

    public function confirm(Request $request, TrialPayment $trialPayment)
    {
        abort_unless($trialPayment->type === 'payment' && $trialPayment->status === 'pending', 422);
        $trialPayment->update([
            'status' => 'confirmed', 'confirmed_by' => $request->user()->displayName(), 'confirmed_at' => now(),
        ]);
        $this->syncLeadPaymentStatus($trialPayment->trialLead);
        return back()->with('success', 'ยืนยันการชำระค่าทดลองแล้ว');
    }

    public function cancel(Request $request, TrialPayment $trialPayment)
    {
        abort_unless($trialPayment->status === 'pending', 422, 'ยกเลิกได้เฉพาะรายการรอตรวจสอบ');
        $trialPayment->update(['status' => 'cancelled', 'confirmed_by' => $request->user()->displayName()]);
        $this->syncLeadPaymentStatus($trialPayment->trialLead);
        return back()->with('success', 'ยกเลิกรายการชำระแล้ว');
    }

    public function refund(Request $request, TrialPayment $trialPayment)
    {
        abort_unless($trialPayment->type === 'payment' && $trialPayment->status === 'confirmed', 422);
        $refunded = (float) $trialPayment->refunds()->where('status', 'confirmed')->sum('amount');
        $remaining = (float) $trialPayment->amount - $refunded;
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $remaining],
            'notes' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $trialPayment, $data) {
            TrialPayment::create([
                'transaction_no' => $this->nextTransactionNo('TR'),
                'trial_lead_id' => $trialPayment->trial_lead_id,
                'parent_payment_id' => $trialPayment->id,
                'type' => 'refund', 'amount' => $data['amount'],
                'payment_method' => $trialPayment->payment_method,
                'status' => 'confirmed', 'transaction_at' => now(),
                'notes' => $data['notes'], 'created_by' => $request->user()->displayName(),
                'confirmed_by' => $request->user()->displayName(), 'confirmed_at' => now(),
            ]);
        });

        $this->syncLeadPaymentStatus($trialPayment->trialLead);
        return back()->with('success', 'บันทึกคืนเงินค่าทดลองแล้ว');
    }

    public function downloadProof(Request $request, TrialPayment $trialPayment)
    {
        abort_unless($trialPayment->proof_path && Storage::disk('local')->exists($trialPayment->proof_path), 404);
        return Storage::disk('local')->download($trialPayment->proof_path, $trialPayment->proof_original_name);
    }

    private function syncLeadPaymentStatus(TrialLead $trialLead): void
    {
        $paid = $trialLead->confirmedPaidAmount();
        $hasPending = $trialLead->payments()->where('type', 'payment')->where('status', 'pending')->exists();
        $hasRefund = $trialLead->payments()->where('type', 'refund')->where('status', 'confirmed')->exists();

        $trialLead->update([
            'payment_status' => $paid > 0 ? 'paid' : ($hasRefund ? 'refunded' : 'unpaid'),
            'paid_at' => $paid > 0 ? ($trialLead->paid_at ?? now()) : null,
        ]);
    }

    private function nextTransactionNo(string $prefix): string
    {
        return $prefix . '-' . now()->format('Ymd') . '-' . str_pad(TrialPayment::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
    }
}
