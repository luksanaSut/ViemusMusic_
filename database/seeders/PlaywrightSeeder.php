<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use App\Models\ClassSchedule;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PlaywrightSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(MasterDataSeeder::class);

        User::create([
            'name' => 'Playwright Admin',
            'email' => 'playwright@viemus.test',
            'password' => Hash::make('Playwright123!'),
            'role' => 'admin',
            'is_active' => true,
            'must_change_password' => false,
        ]);

        $teacher = Teacher::create([
            'teacher_code' => 'DUP001',
            'full_name' => 'อาจารย์ข้อมูลซ้ำ',
            'email' => 'existing.teacher@gmail.com',
            'employment_type' => 'freelance',
            'is_active' => true,
        ]);

        $secondTeacher = Teacher::create([
            'teacher_code' => 'SCHED-T2',
            'full_name' => 'อาจารย์ตารางสอง',
            'nickname' => 'ครูสอง',
            'employment_type' => 'full_time',
            'is_active' => true,
        ]);

        $roomA = Room::create(['room_code' => 'SCH-R1', 'name' => 'ห้องตาราง A', 'capacity' => 4, 'is_active' => true]);
        $roomB = Room::create(['room_code' => 'SCH-R2', 'name' => 'ห้องตาราง B', 'capacity' => 8, 'is_active' => true]);

        $regularCourse = Course::create([
            'course_code' => 'SCH-REG', 'name' => 'คอร์สปกติ', 'structure_type' => 'regular',
            'class_type' => 'private', 'total_sessions' => 4, 'duration_months' => 3,
            'price' => 4000, 'is_active' => true,
        ]);
        $fullCourse = Course::create([
            'course_code' => 'SCH-FULL', 'name' => 'คอร์สหนึ่งครั้ง', 'structure_type' => 'regular',
            'class_type' => 'private', 'total_sessions' => 1, 'duration_months' => 3,
            'price' => 1000, 'is_active' => true,
        ]);
        $specialCourse = Course::create([
            'course_code' => 'SCH-SPC', 'name' => 'คอร์สพิเศษ', 'structure_type' => 'special',
            'class_type' => 'special_activity', 'days_count' => 3, 'hours_per_day' => 2,
            'course_start_date' => '2026-09-01', 'course_end_date' => '2026-09-03',
            'price' => 3000, 'is_active' => true,
        ]);
        $bulkCourse = Course::create([
            'course_code' => 'SCH-BULK', 'name' => 'คอร์สจัดชุด', 'structure_type' => 'regular',
            'class_type' => 'private', 'total_sessions' => 5, 'duration_months' => 3,
            'price' => 5000, 'is_active' => true,
        ]);

        $makeEnrollment = function (string $code, string $name, Course $course, Teacher $assignedTeacher) {
            $student = Student::create(['student_code' => $code, 'full_name' => $name, 'status' => 'active']);
            return Enrollment::create([
                'student_id' => $student->id, 'course_id' => $course->id, 'teacher_id' => $assignedTeacher->id,
                'enrolled_date' => '2026-08-01', 'status' => 'active',
            ]);
        };

        $enrollmentA = $makeEnrollment('SCH-ST-A', 'นักเรียนตาราง A', $regularCourse, $teacher);
        $enrollmentB = $makeEnrollment('SCH-ST-B', 'นักเรียนตาราง B', $regularCourse, $secondTeacher);
        $enrollmentFull = $makeEnrollment('SCH-ST-F', 'นักเรียนคอร์สเต็ม', $fullCourse, $teacher);
        $makeEnrollment('SCH-ST-S', 'นักเรียนคอร์สพิเศษ', $specialCourse, $teacher);
        $makeEnrollment('SCH-ST-X', 'นักเรียนจัดชุด', $bulkCourse, $secondTeacher);

        ClassSchedule::create([
            'enrollment_id' => $enrollmentA->id, 'teacher_id' => $teacher->id, 'room_id' => $roomA->id,
            'schedule_date' => '2026-09-15', 'start_time' => '10:00', 'end_time' => '11:00',
            'delivery_mode' => 'onsite', 'status' => 'scheduled', 'created_by' => 'Playwright',
        ]);
        ClassSchedule::create([
            'enrollment_id' => $enrollmentFull->id, 'teacher_id' => $teacher->id, 'room_id' => $roomB->id,
            'schedule_date' => '2026-10-01', 'start_time' => '13:00', 'end_time' => '14:00',
            'delivery_mode' => 'onsite', 'status' => 'scheduled', 'created_by' => 'Playwright',
        ]);
        ClassSchedule::create([
            'enrollment_id' => $enrollmentB->id, 'teacher_id' => $secondTeacher->id, 'room_id' => $roomB->id,
            'schedule_date' => '2026-09-20', 'start_time' => '09:00', 'end_time' => '10:00',
            'delivery_mode' => 'onsite', 'status' => 'scheduled', 'created_by' => 'Playwright',
        ]);
    }
}
