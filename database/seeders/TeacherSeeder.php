<?php

namespace Database\Seeders;

use App\Models\Instrument;
use App\Models\Level;
use App\Models\Teacher;
use App\Models\TeachingSession;
use App\Models\TeachingType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        // ต้องมี master data (instruments, teaching_types, levels) อยู่ก่อนแล้ว
        // ถ้ายังไม่มี ให้รัน MasterDataSeeder ก่อน

        $teachers = [
            [
                'teacher_code' => 'T0001',
                'full_name' => 'ภานุพงศ์ เจริญสุข',
                'nickname' => 'พีท',
                'email' => 'peat.piano@viemus.school',
                'phone' => '081-234-5671',
                'employment_type' => 'full_time',
                'branch' => 'Cloud 11',
                'instruments' => ['เปียโน', 'คีย์บอร์ด'],
                'primary_instrument' => 'เปียโน',
                'teaching_types' => ['สอนประจำ'],
                'levels' => ['เริ่มต้น (Beginner)', 'ปานกลาง (Intermediate)', 'ระดับสูง (Advanced)', 'เตรียมสอบ / เตรียมแข่งขัน'],
                'rate_type' => 'per_hour',
                'rate_amount' => 600,
                'bio' => 'จบเปียโนคลาสสิกจากสถาบันดนตรี มีประสบการณ์สอนมากกว่า 8 ปี เชี่ยวชาญเตรียมสอบ Grade',
            ],
            [
                'teacher_code' => 'T0002',
                'full_name' => 'ณัฐริกา ปานทอง',
                'nickname' => 'เมย์',
                'email' => 'may.vocal@viemus.school',
                'phone' => '081-234-5672',
                'employment_type' => 'full_time',
                'branch' => 'Cloud 11',
                'instruments' => ['ขับร้อง (Vocal)'],
                'primary_instrument' => 'ขับร้อง (Vocal)',
                'teaching_types' => ['สอนประจำ'],
                'levels' => ['เริ่มต้น (Beginner)', 'ปานกลาง (Intermediate)', 'ระดับสูง (Advanced)'],
                'rate_type' => 'per_hour',
                'rate_amount' => 650,
                'bio' => 'นักร้องมืออาชีพ ปัจจุบันสอนขับร้องเพลงไทยสากลและสากล',
            ],
            [
                'teacher_code' => 'T0003',
                'full_name' => 'สรวิชญ์ พิทักษ์ธรรม',
                'nickname' => 'เต้ย',
                'email' => 'toey.guitar@viemus.school',
                'phone' => '081-234-5673',
                'employment_type' => 'freelance',
                'branch' => 'Astra Academy',
                'instruments' => ['กีตาร์', 'กีตาร์ไฟฟ้า', 'ยูคูเลเล่'],
                'primary_instrument' => 'กีตาร์',
                'teaching_types' => ['สอนประจำ', 'Workshop'],
                'levels' => ['เริ่มต้น (Beginner)', 'ปานกลาง (Intermediate)', 'ระดับสูง (Advanced)'],
                'rate_type' => 'per_session',
                'rate_amount' => 500,
                'bio' => 'มือกีตาร์วง Session รับสอนตั้งแต่พื้นฐานถึงระดับ Advanced และเปิด Workshop เป็นครั้งคราว',
            ],
            [
                'teacher_code' => 'T0004',
                'full_name' => 'ปวีณา แซ่ตั้ง',
                'nickname' => 'แพร',
                'email' => 'prae.violin@viemus.school',
                'phone' => '081-234-5674',
                'employment_type' => 'freelance',
                'branch' => 'Cloud 11',
                'instruments' => ['ไวโอลิน', 'ซอ'],
                'primary_instrument' => 'ไวโอลิน',
                'teaching_types' => ['สอนประจำ'],
                'levels' => ['เริ่มต้น (Beginner)', 'ปานกลาง (Intermediate)'],
                'rate_type' => 'per_hour',
                'rate_amount' => 700,
                'bio' => 'จบไวโอลินเกียรตินิยม ปัจจุบันเป็นนักไวโอลินอิสระและติวเตอร์',
                'is_active' => false, // ตัวอย่างอาจารย์ที่ลาพักงานชั่วคราว
            ],
            [
                'teacher_code' => 'T0005',
                'full_name' => 'ธนกร อินทรวิเศษ',
                'nickname' => 'บอส',
                'email' => 'boss.drums@viemus.school',
                'phone' => '081-234-5675',
                'employment_type' => 'full_time',
                'branch' => 'Cloud 11',
                'instruments' => ['กลองชุด'],
                'primary_instrument' => 'กลองชุด',
                'teaching_types' => ['สอนประจำ', 'Accompaniment'],
                'levels' => ['เริ่มต้น (Beginner)', 'ปานกลาง (Intermediate)', 'ระดับสูง (Advanced)'],
                'rate_type' => 'per_hour',
                'rate_amount' => 600,
                'bio' => 'มือกลองประจำวงดนตรีสด รับงาน Accompaniment ควบคู่กับการสอนประจำ',
            ],
            [
                'teacher_code' => 'T0006',
                'full_name' => 'ชนิดาภา วงศ์สุวรรณ',
                'nickname' => 'เฟิร์น',
                'email' => 'fern.piano@viemus.school',
                'phone' => '081-234-5676',
                'employment_type' => 'freelance',
                'branch' => 'Astra Academy',
                'instruments' => ['เปียโน'],
                'primary_instrument' => 'เปียโน',
                'teaching_types' => ['สอนประจำ'],
                'levels' => ['เริ่มต้น (Beginner)'],
                'rate_type' => 'per_hour',
                'rate_amount' => 550,
                'bio' => 'ครูเปียโนสำหรับเด็กเล็ก เชี่ยวชาญหลักสูตรปูพื้นฐานสำหรับผู้เริ่มต้น',
            ],
        ];

        foreach ($teachers as $t) {
            $teacher = Teacher::firstOrCreate(
                ['teacher_code' => $t['teacher_code']],
                [
                    'full_name'       => $t['full_name'],
                    'nickname'        => $t['nickname'],
                    'email'           => $t['email'],
                    'phone'           => $t['phone'],
                    'employment_type' => $t['employment_type'],
                    'branch'          => $t['branch'],
                    'bio'             => $t['bio'],
                    'is_active'       => $t['is_active'] ?? true,
                    'start_date'      => now()->subMonths(rand(3, 24)),
                ]
            );

            // ผูกประเภทอาจารย์
            $typeIds = TeachingType::whereIn('name', $t['teaching_types'])->pluck('id');
            $teacher->teachingTypes()->sync($typeIds);

            // ผูกระดับที่สอนได้
            $levelIds = Level::whereIn('name', $t['levels'])->pluck('id');
            $teacher->levels()->sync($levelIds);

            // ผูกเครื่องดนตรี + กำหนดเครื่องดนตรีหลัก
            $instrumentModels = Instrument::whereIn('name', $t['instruments'])->get();
            $primaryId = Instrument::where('name', $t['primary_instrument'])->value('id');
            $syncData = [];
            foreach ($instrumentModels as $ins) {
                $syncData[$ins->id] = ['is_primary' => $ins->id === $primaryId];
            }
            $teacher->instruments()->sync($syncData);

            // เรทค่าจ้าง
            $teacher->rates()->firstOrCreate(
                ['teacher_id' => $teacher->id, 'is_active' => true],
                [
                    'rate_type'      => $t['rate_type'],
                    'rate_amount'    => $t['rate_amount'],
                    'effective_from' => now()->subMonths(3)->toDateString(),
                ]
            );

            // ค่ารถ (เฉพาะ Freelance สมมติว่ามีค่ารถ)
            if ($t['employment_type'] === 'freelance') {
                $teacher->transportFees()->firstOrCreate(
                    ['teacher_id' => $teacher->id, 'is_active' => true],
                    [
                        'fee_type'       => 'fixed_per_day',
                        'fee_amount'     => 150,
                        'effective_from' => now()->subMonths(3)->toDateString(),
                    ]
                );
            }

            // Availability ตัวอย่าง: จันทร์-ศุกร์ 09:00-18:00, เสาร์ 09:00-16:00
            if ($teacher->availabilities()->count() === 0) {
                foreach ([1, 2, 3, 4, 5] as $dow) {
                    $teacher->availabilities()->create([
                        'day_of_week' => $dow, 'start_time' => '09:00', 'end_time' => '18:00', 'is_available' => true,
                    ]);
                }
                $teacher->availabilities()->create([
                    'day_of_week' => 6, 'start_time' => '09:00', 'end_time' => '16:00', 'is_available' => true,
                ]);
            }

            // ประวัติการสอนตัวอย่าง (ย้อนหลัง ~6 สัปดาห์) เพื่อให้เห็นสถิติ ชม.สอน/นักเรียน/รายได้
            if ($teacher->teachingSessions()->count() === 0 && ($t['is_active'] ?? true)) {
                $studentNames = ['น้องมิน', 'น้องปุณ', 'น้องข้าวปั้น', 'น้องเมโล่', 'น้องออม'];
                $instrumentId = $primaryId;
                $teachingTypeId = $typeIds->first();
                $levelId = $levelIds->first();
                $rate = $teacher->rates()->where('is_active', true)->first();

                for ($i = 0; $i < 10; $i++) {
                    $date = now()->subDays(rand(1, 42));
                    $start = Carbon::createFromTime(rand(9, 17), [0, 30][rand(0, 1)]);
                    $hours = [1, 1, 1.5][rand(0, 2)];

                    TeachingSession::create([
                        'teacher_id'             => $teacher->id,
                        'instrument_id'          => $instrumentId,
                        'teaching_type_id'       => $teachingTypeId,
                        'level_id'               => $levelId,
                        'student_name'           => $studentNames[array_rand($studentNames)],
                        'session_date'           => $date->toDateString(),
                        'start_time'             => $start->format('H:i'),
                        'end_time'               => $start->copy()->addMinutes($hours * 60)->format('H:i'),
                        'status'                 => 'completed',
                        'rate_applied'           => $rate->rate_amount ?? 0,
                        'transport_fee_applied'  => $t['employment_type'] === 'freelance' ? 150 : 0,
                    ]);
                }
            }
        }
    }
}
