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
use App\Http\Controllers\TeacherLeaveController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MakeupRequestController;
use App\Http\Controllers\MyLeavesController;
use App\Http\Controllers\RescheduleRequestController;
use App\Http\Controllers\TeachingLogController;
use App\Http\Controllers\TeachingReportController;
use App\Http\Controllers\CourseEvaluationController;
use App\Http\Controllers\EvaluationCategoryController;
use App\Http\Controllers\TeachingEvidenceController;
use App\Http\Controllers\HomeworkSubmissionController;
use App\Http\Controllers\RunThroughController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\TransportFeeController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StoreSaleController;
use App\Http\Controllers\StorefrontController;









use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

// ===================================================================
// กลุ่ม 1: PUBLIC — ไม่ต้อง login
// ===================================================================
Route::middleware('throttle:10,1')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// ===================================================================
// กลุ่ม 2: AUTH ทุก ROLE — ล็อกอินแล้วเข้าได้หมด ไม่จำกัดบทบาท
// ===================================================================
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('change-password', [PasswordController::class, 'edit'])->name('password.change');
    Route::put('change-password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware('force-password-change')
        ->name('dashboard');

    Route::get('notifications', [NotificationController::class, 'index'])
        ->middleware('force-password-change')
        ->name('notifications.index');
    Route::get('notifications/{notification}/read', [NotificationController::class, 'markRead'])
        ->middleware('force-password-change')
        ->name('notifications.read');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])
        ->middleware('force-password-change')
        ->name('notifications.mark-all-read');
});

// ===================================================================
// กลุ่ม 3: ADMIN + TEACHER — แจ้งลา/เสนอวันเรียนชดเชยได้เอง
// แจ้งลา/เสนอวันเรียนชดเชยได้เอง (แต่อนุมัติไม่ได้ ยังเป็นสิทธิ์ Admin/อาจารย์)
// ===================================================================
Route::middleware(['throttle:30,1', 'auth', 'force-password-change', 'role:admin,teacher'])->group(function () {
    Route::post('teachers/{teacher}/leaves', [TeacherLeaveController::class, 'store'])->name('teachers.leaves.store');

    Route::get('makeup-requests/{makeupRequest}', [MakeupRequestController::class, 'show'])->name('makeup-requests.show');
    Route::post('makeup-requests/{makeupRequest}/approve-instructor', [MakeupRequestController::class, 'approveByInstructor'])->name('makeup-requests.approve-instructor');
    Route::post('makeup-requests/{makeupRequest}/reject', [MakeupRequestController::class, 'reject'])->name('makeup-requests.reject');

    Route::get('reschedule-requests/create', [RescheduleRequestController::class, 'create'])->name('reschedule-requests.create');
    Route::post('reschedule-requests', [RescheduleRequestController::class, 'store'])->name('reschedule-requests.store');
    Route::get('reschedule-requests-check-conflict', [RescheduleRequestController::class, 'checkConflict'])->name('reschedule-requests.check-conflict');

    Route::get('teaching-logs', [TeachingLogController::class, 'index'])->name('teaching-logs.index');
    Route::get('schedules/{classSchedule}/attendance', [TeachingLogController::class, 'show'])->name('teaching-logs.show');
    Route::post('teaching-logs/{teachingLog}/check-in', [TeachingLogController::class, 'checkIn'])->name('teaching-logs.check-in');
    Route::post('teaching-logs/{teachingLog}/confirm-duration', [TeachingLogController::class, 'confirmDuration'])->name('teaching-logs.confirm-duration');

    Route::post('teaching-logs/{teachingLog}/report', [TeachingReportController::class, 'store'])->name('teaching-reports.store');
    Route::delete('teaching-report-attachments/{attachment}', [TeachingReportController::class, 'destroyAttachment'])->name('teaching-reports.attachments.destroy');
    Route::get('enrollments/{enrollment}/evaluation', [CourseEvaluationController::class, 'edit'])->name('course-evaluations.edit');
    Route::post('enrollments/{enrollment}/evaluation', [CourseEvaluationController::class, 'store'])->name('course-evaluations.store');

    Route::post('teaching-logs/{teachingLog}/evidences', [TeachingEvidenceController::class, 'store'])->name('teaching-evidences.store');
    Route::delete('teaching-evidences/{teachingEvidence}', [TeachingEvidenceController::class, 'destroy'])->name('teaching-evidences.destroy');

    Route::get('homework-submissions', [HomeworkSubmissionController::class, 'index'])->name('homework-submissions.index');
    Route::post('homework-submissions/{homeworkSubmission}/review', [HomeworkSubmissionController::class, 'review'])->name('homework-submissions.review');

    Route::get('run-throughs', [RunThroughController::class, 'index'])->name('run-throughs.index');
    Route::get('enrollments/{enrollment}/run-throughs/create', [RunThroughController::class, 'create'])->name('run-throughs.create');
    Route::post('enrollments/{enrollment}/run-throughs', [RunThroughController::class, 'store'])->name('run-throughs.store');
    Route::post('run-throughs/{runThrough}/record-result', [RunThroughController::class, 'recordResult'])->name('run-throughs.record-result');
});

// ===================================================================
// กลุ่ม 3: Admin + Student + Guardian — อาจารย์แจ้งลาหยุดสอนของตัวเองได้
// (ควบคุมสิทธิ์เพิ่มในคอนโทรลเลอร์: อาจารย์แจ้งได้เฉพาะบัญชีของตัวเอง)
// ===================================================================
Route::middleware(['throttle:30,1', 'auth', 'force-password-change', 'role:admin,student,guardian'])->group(function () {
    Route::post('students/{student}/leaves', [StudentLeaveController::class, 'store'])->name('students.leaves.store');
    Route::get('makeup-requests-check-conflict', [MakeupRequestController::class, 'checkConflict'])->name('makeup-requests.check-conflict');

    Route::post('teaching-reports/{teachingReport}/homework-submissions', [HomeworkSubmissionController::class, 'store'])->name('homework-submissions.store');
});

// นักเรียน/ผู้ปกครอง: หน้าแจ้งลาเรียนของตัวเอง (เมนูแยกต่างหาก)
Route::middleware(['throttle:30,1', 'auth', 'force-password-change', 'role:student,guardian'])->group(function () {
    Route::get('my-leaves', [MyLeavesController::class, 'index'])->name('leaves.index');
    Route::get('my-leaves/create', [MyLeavesController::class, 'create'])->name('leaves.create');

    Route::get('my-teaching-reports', [TeachingReportController::class, 'myIndex'])->name('teaching-reports.my-index');
    Route::get('my-evaluations', [CourseEvaluationController::class, 'myIndex'])->name('course-evaluations.my-index');

    Route::get('my-homework', [HomeworkSubmissionController::class, 'myIndex'])->name('homework-submissions.my-index');

    Route::get('my-run-throughs', [RunThroughController::class, 'myIndex'])->name('run-throughs.my-index');

    Route::get('store', [StorefrontController::class, 'index'])->name('store.index');
    Route::post('store/checkout', [StorefrontController::class, 'checkout'])->name('store.checkout');
    Route::get('store/orders/{storeSale}', [StorefrontController::class, 'show'])->name('store.show');
    Route::get('store/orders/{storeSale}/edit', [StorefrontController::class, 'edit'])->name('store.edit');
    Route::put('store/orders/{storeSale}', [StorefrontController::class, 'update'])->name('store.update');
    Route::patch('store/orders/{storeSale}/cancel', [StorefrontController::class, 'cancelByCustomer'])->name('store.cancel');
    Route::post('store/orders/{storeSale}/confirm-payment', [StorefrontController::class, 'confirmPayment'])->name('store.confirm-payment');
    Route::get('my-orders', [StorefrontController::class, 'myOrders'])->name('store.my-orders');
});

// อาจารย์: หน้าแจ้งลาหยุดสอนของตัวเอง (เมนูแยกต่างหาก)
Route::middleware(['throttle:30,1', 'auth', 'force-password-change', 'role:teacher'])->group(function () {
    Route::get('my-teacher-leave', [TeacherLeaveController::class, 'myIndex'])->name('teacher-leaves.my-index');
    Route::get('my-makeup-requests', [MakeupRequestController::class, 'myIndex'])->name('makeup-requests.my-index');
    Route::get('my-payroll', [PayrollController::class, 'myIndex'])->name('payroll.my-index');
    Route::get('my-transport-fees', [TransportFeeController::class, 'myIndex'])->name('transport-fees.my-index');
});

Route::middleware('auth')->group(function () {
    Route::get('teaching-evidences/{teachingEvidence}/download', [TeachingEvidenceController::class, 'download'])
        ->middleware('force-password-change')
        ->name('teaching-evidences.download');
});


// ===================================================================
// กลุ่ม 4: ADMIN เท่านั้น — โมดูลจัดการทั้งหมดของระบบหลังบ้าน
// ===================================================================
Route::middleware(['throttle:30,1', 'auth', 'force-password-change', 'role:admin'])->group(function () {

    // ----- อาจารย์ -----
    Route::resource('teachers', TeacherController::class);
    Route::post('teachers/{teacher}/rates', [TeacherRateController::class, 'store'])->name('teachers.rates.store');
    Route::delete('teachers/{teacher}/rates/{rate}', [TeacherRateController::class, 'destroy'])->name('teachers.rates.destroy');
    Route::post('teachers/{teacher}/transport-fee', [TeacherRateController::class, 'storeTransportFee'])->name('teachers.transport-fee.store');
    Route::put('teachers/{teacher}/availability', [TeacherAvailabilityController::class, 'update'])->name('teachers.availability.update');
    Route::post('teachers/{teacher}/sessions', [TeachingSessionController::class, 'store'])->name('teachers.sessions.store');
    Route::put('teachers/{teacher}/sessions/{session}', [TeachingSessionController::class, 'update'])->name('teachers.sessions.update');
    Route::delete('teachers/{teacher}/sessions/{session}', [TeachingSessionController::class, 'destroy'])->name('teachers.sessions.destroy');
    Route::post('teachers/{teacher}/create-account', [UserController::class, 'quickCreateForTeacher'])->name('teachers.create-account');

    Route::post('instruments', [InstrumentController::class, 'store'])->name('instruments.store');

    // ----- จัดการคอร์สเรียน -----
    Route::resource('courses', CourseController::class)->except(['show']);
    Route::patch('courses/{course}/toggle-status', [CourseController::class, 'toggleStatus'])->name('courses.toggle-status');

    // ----- Promotion / Coupon -----
    Route::get('coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::post('coupons', [CouponController::class, 'store'])->name('coupons.store');
    Route::patch('coupons/{coupon}/toggle-status', [CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
    Route::delete('coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');

    // ----- จัดการข้อมูลนักเรียน -----
    Route::resource('students', StudentController::class);
    Route::post('students/{student}/enrollments', [StudentEnrollmentController::class, 'store'])->name('students.enrollments.store');
    Route::patch('students/{student}/enrollments/{enrollment}/status', [StudentEnrollmentController::class, 'updateStatus'])->name('students.enrollments.status');
    Route::post('students/{student}/enrollments/{enrollment}/extend', [StudentEnrollmentController::class, 'extend'])->name('students.enrollments.extend');
    Route::post('students/{student}/payments', [StudentFinanceController::class, 'storePayment'])->name('students.payments.store');
    Route::post('students/{student}/credits', [StudentFinanceController::class, 'storeCredit'])->name('students.credits.store');
    Route::post('students/{student}/skill-levels', [StudentAcademicController::class, 'storeSkillLevel'])->name('students.skill-levels.store');
    Route::post('students/{student}/exam-results', [StudentAcademicController::class, 'storeExamResult'])->name('students.exam-results.store');
    Route::post('students/{student}/create-account', [UserController::class, 'quickCreateForStudent'])->name('students.create-account');
    Route::get('students-search', StudentSearchController::class)->name('students.search');

    // ----- ลาเรียนของนักเรียน -----
    Route::patch('students/{student}/leaves/{leave}/makeup', [StudentLeaveController::class, 'updateMakeup'])->name('students.leaves.makeup');
    Route::post('students/{student}/leaves/{leave}/approve', [StudentLeaveController::class, 'approve'])->name('students.leaves.approve');
    Route::post('students/{student}/leaves/{leave}/reject', [StudentLeaveController::class, 'reject'])->name('students.leaves.reject');

    // ----- ผู้ปกครอง -----
    Route::get('guardians', [GuardianController::class, 'index'])->name('guardians.index');
    Route::get('guardians/search', [GuardianController::class, 'search'])->name('guardians.search');
    Route::post('guardians', [GuardianController::class, 'store'])->name('guardians.store');
    Route::put('guardians/{guardian}', [GuardianController::class, 'update'])->name('guardians.update');
    Route::delete('guardians/{guardian}', [GuardianController::class, 'destroy'])->name('guardians.destroy');
    Route::post('guardians/{guardian}/create-account', [UserController::class, 'quickCreateForGuardian'])->name('guardians.create-account');
    Route::post('students/{student}/guardians', [StudentGuardianController::class, 'store'])->name('students.guardians.store');
    Route::delete('students/{student}/guardians/{guardian}', [StudentGuardianController::class, 'destroy'])->name('students.guardians.destroy');

    // ----- ระดับ (master data) -----
    Route::post('levels', [LevelController::class, 'store'])->name('levels.store');

    // ----- จัดการห้องเรียน -----
    Route::resource('rooms', RoomController::class);
    Route::patch('rooms/{room}/maintenance', [RoomController::class, 'toggleMaintenance'])->name('rooms.maintenance');
    Route::get('rooms-availability-check', [RoomController::class, 'availabilityCheck'])->name('rooms.availability-check');
    Route::post('rooms/{room}/equipment', [RoomEquipmentController::class, 'store'])->name('rooms.equipment.store');
    Route::delete('rooms/{room}/equipment/{equipmentType}', [RoomEquipmentController::class, 'destroy'])->name('rooms.equipment.destroy');
    Route::post('equipment-types', [EquipmentTypeController::class, 'store'])->name('equipment-types.store');
    Route::post('rooms/{room}/bookings', [RoomBookingController::class, 'store'])->name('rooms.bookings.store');
    Route::patch('rooms/{room}/bookings/{booking}/cancel', [RoomBookingController::class, 'cancel'])->name('rooms.bookings.cancel');
    Route::get('rooms-schedule', [RoomScheduleController::class, 'index'])->name('rooms.schedule');

    // ----- ระบบขายคอร์สเรียน -----
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

    // ----- เปลี่ยนคอร์ส -----
    Route::get('course-transfers', [CourseTransferController::class, 'index'])->name('course-transfers.index');
    Route::get('course-transfers/create', [CourseTransferController::class, 'create'])->name('course-transfers.create');
    Route::post('course-transfers', [CourseTransferController::class, 'store'])->name('course-transfers.store');
    Route::get('course-transfers/{courseTransfer}', [CourseTransferController::class, 'show'])->name('course-transfers.show');
    Route::post('course-transfers/{courseTransfer}/confirm-payment', [CourseTransferController::class, 'confirmPayment'])->name('course-transfers.confirm-payment');
    Route::patch('course-transfers/{courseTransfer}/cancel', [CourseTransferController::class, 'cancel'])->name('course-transfers.cancel');

    // ----- ตารางเรียน -----
    Route::get('schedules', [ClassScheduleController::class, 'index'])->name('schedules.index');
    Route::get('schedules/create', [ClassScheduleController::class, 'create'])->name('schedules.create');
    Route::post('schedules', [ClassScheduleController::class, 'store'])->name('schedules.store');
    Route::get('schedules/check-conflict', [ClassScheduleController::class, 'checkConflict'])->name('schedules.check-conflict');
    Route::get('schedules/{classSchedule}/edit', [ClassScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('schedules/{classSchedule}', [ClassScheduleController::class, 'update'])->name('schedules.update');
    Route::patch('schedules/{classSchedule}/cancel', [ClassScheduleController::class, 'cancel'])->name('schedules.cancel');
    Route::delete('schedules/{classSchedule}', [ClassScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::get('schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('schedules/bulk-create', [ClassScheduleController::class, 'bulkCreate'])->name('schedules.bulk-create');
    Route::post('schedules/bulk-preview', [ClassScheduleController::class, 'bulkPreview'])->name('schedules.bulk-preview');
    Route::get('schedules/bulk-row-check-conflict', [ClassScheduleController::class, 'bulkRowCheckConflict'])->name('schedules.bulk-row-check-conflict');
    Route::post('schedules/bulk-confirm', [ClassScheduleController::class, 'bulkConfirm'])->name('schedules.bulk-confirm');

    // ----- อนุมัติคำขอลาหยุดสอนของอาจารย์ (แจ้งได้จากกลุ่ม admin+teacher ด้านบน แต่อนุมัติได้เฉพาะ admin) -----
    Route::get('teacher-leaves', [TeacherLeaveController::class, 'index'])->name('teacher-leaves.index');
    Route::post('teacher-leaves/{teacherLeave}/approve', [TeacherLeaveController::class, 'approve'])->name('teacher-leaves.approve');
    Route::post('teacher-leaves/{teacherLeave}/reject', [TeacherLeaveController::class, 'reject'])->name('teacher-leaves.reject');

    // ----- จัดการผู้ใช้งานระบบ -----
    Route::resource('users', UserController::class)->except(['show', 'edit', 'update']);
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

    // ระบบเรียนชดเชย
    Route::get('makeup-requests', [MakeupRequestController::class, 'index'])->name('makeup-requests.index');
    Route::post('makeup-requests/{makeupRequest}/approve-admin', [MakeupRequestController::class, 'approveByAdmin'])->name('makeup-requests.approve-admin');

    // ระบบสลับคลาส
    Route::get('reschedule-requests', [RescheduleRequestController::class, 'index'])->name('reschedule-requests.index');
    Route::post('reschedule-requests/{rescheduleRequest}/approve', [RescheduleRequestController::class, 'approve'])->name('reschedule-requests.approve');
    Route::post('reschedule-requests/{rescheduleRequest}/reject', [RescheduleRequestController::class, 'reject'])->name('reschedule-requests.reject');

    Route::get('evaluation-categories', [EvaluationCategoryController::class, 'index'])->name('evaluation-categories.index');
    Route::post('evaluation-categories', [EvaluationCategoryController::class, 'store'])->name('evaluation-categories.store');
    Route::patch('evaluation-categories/{evaluationCategory}/toggle-active', [EvaluationCategoryController::class, 'toggleActive'])->name('evaluation-categories.toggle-active');
    Route::delete('evaluation-categories/{evaluationCategory}', [EvaluationCategoryController::class, 'destroy'])->name('evaluation-categories.destroy');

    Route::get('payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('payroll/{teacher}/generate', [PayrollController::class, 'show'])->name('payroll.show');
    Route::post('payroll/{payrollRun}/adjust', [PayrollController::class, 'adjust'])->name('payroll.adjust');
    Route::post('payroll/{payrollRun}/confirm', [PayrollController::class, 'confirm'])->name('payroll.confirm');
    Route::post('payroll/{payrollRun}/mark-paid', [PayrollController::class, 'markPaid'])->name('payroll.mark-paid');
    Route::get('payroll/{payrollRun}/export-pdf', [PayrollController::class, 'exportPdf'])->name('payroll.export-pdf');
    Route::get('payroll/{payrollRun}/export-excel', [PayrollController::class, 'exportExcel'])->name('payroll.export-excel');
    Route::post('payroll/{payrollRun}/recalculate', [PayrollController::class, 'recalculate'])->name('payroll.recalculate');

    Route::get('transport-fees', [TransportFeeController::class, 'index'])->name('transport-fees.index');
    Route::get('transport-fees/{teacher}', [TransportFeeController::class, 'show'])->name('transport-fees.show');
    Route::post('transport-fees/{teacher}/compensations', [TransportFeeController::class, 'storeCompensation'])->name('transport-fees.compensations.store');
    Route::delete('transport-compensations/{transportCompensation}', [TransportFeeController::class, 'destroyCompensation'])->name('transport-fees.compensations.destroy');

    // ----- Music Store: จัดการสินค้า -----
    Route::get('product-categories', [ProductCategoryController::class, 'index'])->name('product-categories.index');
    Route::post('product-categories', [ProductCategoryController::class, 'store'])->name('product-categories.store');
    Route::patch('product-categories/{productCategory}/toggle-active', [ProductCategoryController::class, 'toggleActive'])->name('product-categories.toggle-active');
    Route::delete('product-categories/{productCategory}', [ProductCategoryController::class, 'destroy'])->name('product-categories.destroy');

    Route::resource('products', ProductController::class)->except(['show']);
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

    // ----- Music Store: ระบบสต็อก -----
    Route::get('stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('products/{product}/stock/adjust', [StockController::class, 'adjust'])->name('products.stock.adjust');

    // ----- Music Store: ขายสินค้า -----
    Route::get('store-sales', [StoreSaleController::class, 'index'])->name('store-sales.index');
    Route::get('store-sales/create', [StoreSaleController::class, 'create'])->name('store-sales.create');
    Route::post('store-sales', [StoreSaleController::class, 'store'])->name('store-sales.store');
    Route::get('store-sales/{storeSale}', [StoreSaleController::class, 'show'])->name('store-sales.show');
    Route::patch('store-sales/{storeSale}/cancel', [StoreSaleController::class, 'cancel'])->name('store-sales.cancel');
});
