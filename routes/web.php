<?php

use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TeacherAvailabilityController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherRateController;
use App\Http\Controllers\TeachingSessionController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentEnrollmentController;
use App\Http\Controllers\StudentFinanceController;
use App\Http\Controllers\StudentAcademicController;
use App\Http\Controllers\StudentLeaveController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\StudentGuardianController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomEquipmentController;
use App\Http\Controllers\EquipmentTypeController;
use App\Http\Controllers\RoomBookingController;
use App\Http\Controllers\RoomScheduleController;
use App\Http\Controllers\SaleOrderController;
use App\Http\Controllers\StudentSearchController;
use App\Http\Controllers\CourseTransferController;
use App\Http\Controllers\ClassScheduleController;



use Illuminate\Support\Facades\Route;

Route::redirect('/', '/teachers');

Route::middleware('throttle:30,1')->group(function () {
    Route::resource('teachers', TeacherController::class);
    Route::post('teachers/{teacher}/rates', [TeacherRateController::class, 'store'])->name('teachers.rates.store');
    Route::delete('teachers/{teacher}/rates/{rate}', [TeacherRateController::class, 'destroy'])->name('teachers.rates.destroy');
    Route::post('teachers/{teacher}/transport-fee', [TeacherRateController::class, 'storeTransportFee'])->name('teachers.transport-fee.store');
    Route::put('teachers/{teacher}/availability', [TeacherAvailabilityController::class, 'update'])->name('teachers.availability.update');
    Route::post('teachers/{teacher}/sessions', [TeachingSessionController::class, 'store'])->name('teachers.sessions.store');
    Route::put('teachers/{teacher}/sessions/{session}', [TeachingSessionController::class, 'update'])->name('teachers.sessions.update');
    Route::delete('teachers/{teacher}/sessions/{session}', [TeachingSessionController::class, 'destroy'])->name('teachers.sessions.destroy');

    Route::post('instruments', [InstrumentController::class, 'store'])->name('instruments.store');

    // จัดการคอร์สเรียน
    Route::resource('courses', CourseController::class)->except(['show']);
    Route::patch('courses/{course}/toggle-status', [CourseController::class, 'toggleStatus'])->name('courses.toggle-status');

    // Promotion / Coupon
    Route::get('coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::post('coupons', [CouponController::class, 'store'])->name('coupons.store');
    Route::patch('coupons/{coupon}/toggle-status', [CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
    Route::delete('coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');

    // จัดการข้อมูลนักเรียน
    Route::resource('students', StudentController::class);

    Route::post('students/{student}/enrollments', [StudentEnrollmentController::class, 'store'])->name('students.enrollments.store');
    Route::patch('students/{student}/enrollments/{enrollment}/status', [StudentEnrollmentController::class, 'updateStatus'])->name('students.enrollments.status');
    Route::post('students/{student}/enrollments/{enrollment}/extend', [StudentEnrollmentController::class, 'extend'])->name('students.enrollments.extend');

    Route::post('students/{student}/payments', [StudentFinanceController::class, 'storePayment'])->name('students.payments.store');
    Route::post('students/{student}/credits', [StudentFinanceController::class, 'storeCredit'])->name('students.credits.store');

    Route::post('students/{student}/skill-levels', [StudentAcademicController::class, 'storeSkillLevel'])->name('students.skill-levels.store');
    Route::post('students/{student}/exam-results', [StudentAcademicController::class, 'storeExamResult'])->name('students.exam-results.store');

    Route::post('students/{student}/leaves', [StudentLeaveController::class, 'store'])->name('students.leaves.store');
    Route::patch('students/{student}/leaves/{leave}/makeup', [StudentLeaveController::class, 'updateMakeup'])->name('students.leaves.makeup');

    // ผู้ปกครอง
    Route::get('guardians', [GuardianController::class, 'index'])->name('guardians.index');
    Route::get('guardians/search', [GuardianController::class, 'search'])->name('guardians.search');
    Route::post('guardians', [GuardianController::class, 'store'])->name('guardians.store');
    Route::put('guardians/{guardian}', [GuardianController::class, 'update'])->name('guardians.update');
    Route::delete('guardians/{guardian}', [GuardianController::class, 'destroy'])->name('guardians.destroy');

    Route::post('students/{student}/guardians', [StudentGuardianController::class, 'store'])->name('students.guardians.store');
    Route::delete('students/{student}/guardians/{guardian}', [StudentGuardianController::class, 'destroy'])->name('students.guardians.destroy');

    // เพิ่มระดับใหม่แบบ inline
    Route::post('levels', [LevelController::class, 'store'])->name('levels.store');

    // จัดการห้องเรียน
    Route::resource('rooms', RoomController::class);
    Route::patch('rooms/{room}/maintenance', [RoomController::class, 'toggleMaintenance'])->name('rooms.maintenance');
    Route::get('rooms-availability-check', [RoomController::class, 'availabilityCheck'])->name('rooms.availability-check');

    Route::post('rooms/{room}/equipment', [RoomEquipmentController::class, 'store'])->name('rooms.equipment.store');
    Route::delete('rooms/{room}/equipment/{equipmentType}', [RoomEquipmentController::class, 'destroy'])->name('rooms.equipment.destroy');

    Route::post('equipment-types', [EquipmentTypeController::class, 'store'])->name('equipment-types.store');

    Route::post('rooms/{room}/bookings', [RoomBookingController::class, 'store'])->name('rooms.bookings.store');
    Route::patch('rooms/{room}/bookings/{booking}/cancel', [RoomBookingController::class, 'cancel'])->name('rooms.bookings.cancel');

    Route::get('rooms-schedule', [RoomScheduleController::class, 'index'])->name('rooms.schedule');

    // ระบบขายคอร์สเรียน
    Route::get('sales', [SaleOrderController::class, 'index'])->name('sales.index');
    Route::get('sales/create', [SaleOrderController::class, 'create'])->name('sales.create');
    Route::get('sales/course-availability', [SaleOrderController::class, 'courseAvailability'])->name('sales.course-availability');
    Route::post('sales/quick-student', [SaleOrderController::class, 'quickCreateStudent'])->name('sales.quick-student');
    Route::post('sales', [SaleOrderController::class, 'store'])->name('sales.store');
    Route::get('sales/{saleOrder}', [SaleOrderController::class, 'show'])->name('sales.show');
    Route::get('sales/{saleOrder}/edit', [SaleOrderController::class, 'edit'])->name('sales.edit');
    Route::put('sales/{saleOrder}', [SaleOrderController::class, 'update'])->name('sales.update');
    Route::post('sales/{saleOrder}/apply-discount', [SaleOrderController::class, 'applyDiscount'])->name('sales.apply-discount');
    Route::post('sales/{saleOrder}/confirm-payment', [SaleOrderController::class, 'confirmPayment'])->name('sales.confirm-payment');
    Route::patch('sales/{saleOrder}/cancel', [SaleOrderController::class, 'cancel'])->name('sales.cancel');
    Route::get('sales/{saleOrder}/invoice/download', [SaleOrderController::class, 'downloadInvoice'])->name('sales.invoice.download');

    // เปลียนคอร์ส
    Route::get('course-transfers', [CourseTransferController::class, 'index'])->name('course-transfers.index');
    Route::get('course-transfers/create', [CourseTransferController::class, 'create'])->name('course-transfers.create');
    Route::post('course-transfers', [CourseTransferController::class, 'store'])->name('course-transfers.store');
    Route::get('course-transfers/{courseTransfer}', [CourseTransferController::class, 'show'])->name('course-transfers.show');
    Route::post('course-transfers/{courseTransfer}/confirm-payment', [CourseTransferController::class, 'confirmPayment'])->name('course-transfers.confirm-payment');
    Route::patch('course-transfers/{courseTransfer}/cancel', [CourseTransferController::class, 'cancel'])->name('course-transfers.cancel');

    // ตารางเรียน
    Route::get('schedules', [ClassScheduleController::class, 'index'])->name('schedules.index');
    Route::get('schedules/create', [ClassScheduleController::class, 'create'])->name('schedules.create');
    Route::post('schedules', [ClassScheduleController::class, 'store'])->name('schedules.store');
    Route::get('schedules/check-conflict', [ClassScheduleController::class, 'checkConflict'])->name('schedules.check-conflict');
    Route::get('schedules/{classSchedule}/edit', [ClassScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('schedules/{classSchedule}', [ClassScheduleController::class, 'update'])->name('schedules.update');
    Route::patch('schedules/{classSchedule}/cancel', [ClassScheduleController::class, 'cancel'])->name('schedules.cancel');
    Route::delete('schedules/{classSchedule}', [ClassScheduleController::class, 'destroy'])->name('schedules.destroy');

    Route::get('students-search', StudentSearchController::class)->name('students.search');
});

// ตารางสอนรวม รายวัน/สัปดาห์/เดือน
Route::get('schedule', [ScheduleController::class, 'index'])->name('schedule.index');
