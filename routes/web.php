<?php

use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TeacherAvailabilityController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherRateController;
use App\Http\Controllers\TeachingSessionController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\MyCoursesController;
use App\Http\Controllers\MyScheduleController;
use App\Http\Controllers\MembershipTierController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentEnrollmentController;
use App\Http\Controllers\StudentFinanceController;
use App\Http\Controllers\StudentMembershipController;
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
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\AuditLogController;
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
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\TransportFeeController;
use App\Http\Controllers\TeacherWorkspaceController;
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
$publicMiddleware = app()->environment('testing') ? [] : ['throttle:10,1'];
Route::middleware($publicMiddleware)->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// ===================================================================
// กลุ่ม 2: AUTH ทุก ROLE — ล็อกอินแล้วเข้าได้หมด ไม่จำกัดบทบาท
// ===================================================================
Route::middleware('auth')->group(function () {
    Route::get('teacher-leave-attachments/{attachment}/download', [TeacherLeaveController::class, 'downloadAttachment'])->middleware('force-password-change')->name('teacher-leaves.attachments.download');
    Route::delete('teacher-leave-attachments/{attachment}', [TeacherLeaveController::class, 'destroyAttachment'])->middleware('force-password-change')->name('teacher-leaves.attachments.destroy');
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
$adminTeacherMiddleware = ['auth', 'force-password-change', 'role:admin,teacher'];
if (!app()->environment('testing')) {
    array_unshift($adminTeacherMiddleware, 'throttle:30,1');
}
Route::middleware($adminTeacherMiddleware)->group(function () {
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
$adminCustomerMiddleware = ['auth', 'force-password-change', 'role:admin,student,guardian'];
if (!app()->environment('testing')) {
    array_unshift($adminCustomerMiddleware, 'throttle:30,1');
}
Route::middleware($adminCustomerMiddleware)->group(function () {
    Route::post('students/{student}/leaves', [StudentLeaveController::class, 'store'])->name('students.leaves.store');
    Route::get('makeup-requests-check-conflict', [MakeupRequestController::class, 'checkConflict'])->name('makeup-requests.check-conflict');

    Route::post('teaching-reports/{teachingReport}/homework-submissions', [HomeworkSubmissionController::class, 'store'])->name('homework-submissions.store');
});

// นักเรียน/ผู้ปกครอง: หน้าแจ้งลาเรียนของตัวเอง (เมนูแยกต่างหาก)
$customerMiddleware = ['auth', 'force-password-change', 'role:student,guardian'];
if (!app()->environment('testing')) {
    array_unshift($customerMiddleware, 'throttle:30,1');
}
Route::middleware($customerMiddleware)->group(function () {
    Route::get('my-courses', [MyCoursesController::class, 'index'])->name('enrollments.my-index');
    Route::get('my-schedule', [MyScheduleController::class, 'index'])->name('schedules.my-index');

    Route::get('my-leaves', [MyLeavesController::class, 'index'])->name('leaves.index');
    Route::get('my-leaves/create', [MyLeavesController::class, 'create'])->name('leaves.create');

    Route::get('my-teaching-reports', [TeachingReportController::class, 'myIndex'])->name('teaching-reports.my-index');
    Route::get('my-evaluations', [CourseEvaluationController::class, 'myIndex'])->name('course-evaluations.my-index');

    Route::get('my-homework', [HomeworkSubmissionController::class, 'myIndex'])->name('homework-submissions.my-index');

    Route::get('my-run-throughs', [RunThroughController::class, 'myIndex'])->name('run-throughs.my-index');

    Route::get('store', [StorefrontController::class, 'index'])->name('store.index');
    Route::post('store/checkout', [StorefrontController::class, 'checkout'])->name('store.checkout');
    Route::get('store/orders/{storeSale}', [StorefrontController::class, 'show'])->name('store.show');
    Route::post('store/orders/{storeSale}/apply-discount', [StorefrontController::class, 'applyDiscount'])->name('store.apply-discount');
    Route::get('store/orders/{storeSale}/edit', [StorefrontController::class, 'edit'])->name('store.edit');
    Route::put('store/orders/{storeSale}', [StorefrontController::class, 'update'])->name('store.update');
    Route::patch('store/orders/{storeSale}/cancel', [StorefrontController::class, 'cancelByCustomer'])->name('store.cancel');
    Route::post('store/orders/{storeSale}/confirm-payment', [StorefrontController::class, 'confirmPayment'])->name('store.confirm-payment');
    Route::get('my-orders', [StorefrontController::class, 'myOrders'])->name('store.my-orders');

    Route::get('my-membership', [MembershipController::class, 'index'])->name('membership.my-index');
    Route::get('my-points', [MembershipController::class, 'points'])->name('membership.my-points');
});

// อาจารย์: หน้าแจ้งลาหยุดสอนของตัวเอง (เมนูแยกต่างหาก)
$teacherMiddleware = ['auth', 'force-password-change', 'role:teacher'];
if (!app()->environment('testing')) {
    array_unshift($teacherMiddleware, 'throttle:30,1');
}
Route::middleware($teacherMiddleware)->group(function () {
    Route::get('my-reschedule-requests', [RescheduleRequestController::class, 'myIndex'])->name('reschedule-requests.my-index');
    Route::get('my-teaching-schedule', [TeacherWorkspaceController::class, 'schedule'])->name('teacher.schedule');
    Route::get('my-teaching-tasks', [TeacherWorkspaceController::class, 'tasks'])->name('teacher.tasks');
    Route::get('my-students', [TeacherWorkspaceController::class, 'students'])->name('teacher.students');
    Route::get('my-students/{student}', [TeacherWorkspaceController::class, 'studentShow'])->name('teacher.students.show');
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
$adminMiddleware = ['auth', 'force-password-change', 'role:admin,staff'];
if (!app()->environment('testing')) {
    array_unshift($adminMiddleware, 'throttle:30,1');
}

Route::middleware($adminMiddleware)->group(function () {

    // ----- อาจารย์ -----
    Route::middleware('permission:teachers.manage')->group(function () {
        Route::resource('teachers', TeacherController::class);
        Route::post('teachers/{teacher}/rates', [TeacherRateController::class, 'store'])->name('teachers.rates.store');
        Route::delete('teachers/{teacher}/rates/{rate}', [TeacherRateController::class, 'destroy'])->name('teachers.rates.destroy');
        Route::post('teachers/{teacher}/transport-fee', [TeacherRateController::class, 'storeTransportFee'])->name('teachers.transport-fee.store');
        Route::put('teachers/{teacher}/availability', [TeacherAvailabilityController::class, 'update'])->name('teachers.availability.update');
        Route::post('teachers/{teacher}/sessions', [TeachingSessionController::class, 'store'])->name('teachers.sessions.store');
        Route::put('teachers/{teacher}/sessions/{session}', [TeachingSessionController::class, 'update'])->name('teachers.sessions.update');
        Route::delete('teachers/{teacher}/sessions/{session}', [TeachingSessionController::class, 'destroy'])->name('teachers.sessions.destroy');
        Route::post('teachers/{teacher}/create-account', [UserController::class, 'quickCreateForTeacher'])->name('teachers.create-account');

        // ----- อนุมัติคำขอลาหยุดสอนของอาจารย์ (แจ้งได้จากกลุ่ม admin+teacher ด้านบน แต่อนุมัติได้เฉพาะ admin/staff) -----
        Route::get('teacher-leaves', [TeacherLeaveController::class, 'index'])->name('teacher-leaves.index');
        Route::post('teacher-leaves/{teacherLeave}/approve', [TeacherLeaveController::class, 'approve'])->name('teacher-leaves.approve');
        Route::post('teacher-leaves/{teacherLeave}/reject', [TeacherLeaveController::class, 'reject'])->name('teacher-leaves.reject');
    });

    // ----- จัดการคอร์สเรียน (รวม instruments/levels/evaluation-categories ซึ่งเป็น master data ของคอร์ส) -----
    Route::middleware('permission:courses.manage')->group(function () {
        Route::post('instruments', [InstrumentController::class, 'store'])->name('instruments.store');

        Route::resource('courses', CourseController::class)->except(['show']);
        Route::patch('courses/{course}/toggle-status', [CourseController::class, 'toggleStatus'])->name('courses.toggle-status');

        Route::post('levels', [LevelController::class, 'store'])->name('levels.store');

        Route::get('evaluation-categories', [EvaluationCategoryController::class, 'index'])->name('evaluation-categories.index');
        Route::post('evaluation-categories', [EvaluationCategoryController::class, 'store'])->name('evaluation-categories.store');
        Route::patch('evaluation-categories/{evaluationCategory}/toggle-active', [EvaluationCategoryController::class, 'toggleActive'])->name('evaluation-categories.toggle-active');
        Route::delete('evaluation-categories/{evaluationCategory}', [EvaluationCategoryController::class, 'destroy'])->name('evaluation-categories.destroy');
    });

    // ----- Promotion / Coupon -----
    Route::middleware('permission:promotions.manage')->group(function () {
        Route::get('promotions', [PromotionController::class, 'index'])->name('promotions.index');
        Route::get('promotions/create', [PromotionController::class, 'create'])->name('promotions.create');
        Route::post('promotions', [PromotionController::class, 'store'])->name('promotions.store');
        Route::get('promotions/{promotion}/edit', [PromotionController::class, 'edit'])->name('promotions.edit');
        Route::put('promotions/{promotion}', [PromotionController::class, 'update'])->name('promotions.update');
        Route::patch('promotions/{promotion}/toggle-status', [PromotionController::class, 'toggleStatus'])->name('promotions.toggle-status');
        Route::delete('promotions/{promotion}', [PromotionController::class, 'destroy'])->name('promotions.destroy');
    });

    // ----- ระดับสมาชิก -----
    Route::middleware('permission:membership.manage')->group(function () {
        Route::get('membership-tiers', [MembershipTierController::class, 'index'])->name('membership-tiers.index');
        Route::get('membership-tiers/create', [MembershipTierController::class, 'create'])->name('membership-tiers.create');
        Route::post('membership-tiers', [MembershipTierController::class, 'store'])->name('membership-tiers.store');
        Route::get('membership-tiers/{membershipTier}/edit', [MembershipTierController::class, 'edit'])->name('membership-tiers.edit');
        Route::put('membership-tiers/{membershipTier}', [MembershipTierController::class, 'update'])->name('membership-tiers.update');
        Route::patch('membership-tiers/{membershipTier}/toggle-active', [MembershipTierController::class, 'toggleActive'])->name('membership-tiers.toggle-active');
        Route::delete('membership-tiers/{membershipTier}', [MembershipTierController::class, 'destroy'])->name('membership-tiers.destroy');
    });

    // ----- จัดการข้อมูลนักเรียน -----
    Route::middleware('permission:students.manage')->group(function () {
        Route::resource('students', StudentController::class);
        Route::post('students/{student}/enrollments', [StudentEnrollmentController::class, 'store'])->name('students.enrollments.store');
        Route::patch('students/{student}/enrollments/{enrollment}/status', [StudentEnrollmentController::class, 'updateStatus'])->name('students.enrollments.status');
        Route::post('students/{student}/enrollments/{enrollment}/extend', [StudentEnrollmentController::class, 'extend'])->name('students.enrollments.extend');
        Route::post('students/{student}/payments', [StudentFinanceController::class, 'storePayment'])->name('students.payments.store');
        Route::post('students/{student}/credits', [StudentFinanceController::class, 'storeCredit'])->name('students.credits.store');
        Route::post('students/{student}/points', [StudentFinanceController::class, 'storePointAdjustment'])->name('students.points.store');
        Route::post('students/{student}/membership/recalculate', [StudentMembershipController::class, 'recalculate'])->name('students.membership.recalculate');
        Route::post('students/{student}/skill-levels', [StudentAcademicController::class, 'storeSkillLevel'])->name('students.skill-levels.store');
        Route::post('students/{student}/exam-results', [StudentAcademicController::class, 'storeExamResult'])->name('students.exam-results.store');
        Route::post('students/{student}/create-account', [UserController::class, 'quickCreateForStudent'])->name('students.create-account');
        Route::get('students-search', StudentSearchController::class)->name('students.search');
    });

    // ----- ลาเรียนของนักเรียน -----
    Route::middleware('permission:student_leaves.manage')->group(function () {
        Route::patch('students/{student}/leaves/{leave}/makeup', [StudentLeaveController::class, 'updateMakeup'])->name('students.leaves.makeup');
        Route::post('students/{student}/leaves/{leave}/approve', [StudentLeaveController::class, 'approve'])->name('students.leaves.approve');
        Route::post('students/{student}/leaves/{leave}/reject', [StudentLeaveController::class, 'reject'])->name('students.leaves.reject');
    });

    // ----- ผู้ปกครอง -----
    Route::middleware('permission:guardians.manage')->group(function () {
        Route::get('guardians', [GuardianController::class, 'index'])->name('guardians.index');
        Route::get('guardians/search', [GuardianController::class, 'search'])->name('guardians.search');
        Route::post('guardians', [GuardianController::class, 'store'])->name('guardians.store');
        Route::put('guardians/{guardian}', [GuardianController::class, 'update'])->name('guardians.update');
        Route::delete('guardians/{guardian}', [GuardianController::class, 'destroy'])->name('guardians.destroy');
        Route::post('guardians/{guardian}/create-account', [UserController::class, 'quickCreateForGuardian'])->name('guardians.create-account');
        Route::post('students/{student}/guardians', [StudentGuardianController::class, 'store'])->name('students.guardians.store');
        Route::delete('students/{student}/guardians/{guardian}', [StudentGuardianController::class, 'destroy'])->name('students.guardians.destroy');
    });

    // ----- จัดการห้องเรียน -----
    Route::middleware('permission:rooms.manage')->group(function () {
        Route::resource('rooms', RoomController::class);
        Route::patch('rooms/{room}/maintenance', [RoomController::class, 'toggleMaintenance'])->name('rooms.maintenance');
        Route::get('rooms-availability-check', [RoomController::class, 'availabilityCheck'])->name('rooms.availability-check');
        Route::post('rooms/{room}/equipment', [RoomEquipmentController::class, 'store'])->name('rooms.equipment.store');
        Route::delete('rooms/{room}/equipment/{equipmentType}', [RoomEquipmentController::class, 'destroy'])->name('rooms.equipment.destroy');
        Route::post('equipment-types', [EquipmentTypeController::class, 'store'])->name('equipment-types.store');
        Route::post('rooms/{room}/bookings', [RoomBookingController::class, 'store'])->name('rooms.bookings.store');
        Route::patch('rooms/{room}/bookings/{booking}/cancel', [RoomBookingController::class, 'cancel'])->name('rooms.bookings.cancel');
        Route::get('rooms-schedule', [RoomScheduleController::class, 'index'])->name('rooms.schedule');
    });

    // ----- ระบบขายคอร์สเรียน -----
    Route::middleware('permission:sales.manage')->group(function () {
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
    });

    // ----- เปลี่ยนคอร์ส -----
    Route::middleware('permission:course_transfers.manage')->group(function () {
        Route::get('course-transfers', [CourseTransferController::class, 'index'])->name('course-transfers.index');
        Route::get('course-transfers/create', [CourseTransferController::class, 'create'])->name('course-transfers.create');
        Route::post('course-transfers', [CourseTransferController::class, 'store'])->name('course-transfers.store');
        Route::get('course-transfers/{courseTransfer}', [CourseTransferController::class, 'show'])->name('course-transfers.show');
        Route::post('course-transfers/{courseTransfer}/confirm-payment', [CourseTransferController::class, 'confirmPayment'])->name('course-transfers.confirm-payment');
        Route::patch('course-transfers/{courseTransfer}/cancel', [CourseTransferController::class, 'cancel'])->name('course-transfers.cancel');
    });

    // ----- ตารางเรียน -----
    Route::middleware('permission:schedules.manage')->group(function () {
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
    });

    // ----- จัดการผู้ใช้งานระบบ (ล็อก admin เท่านั้นเสมอ) -----
    Route::middleware('permission:users.manage')->group(function () {
        Route::resource('users', UserController::class)->except(['show', 'edit', 'update']);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    });

    // ----- ระบบเรียนชดเชย / ระบบสลับคลาส -----
    Route::middleware('permission:makeup_reschedule.manage')->group(function () {
        Route::get('makeup-requests', [MakeupRequestController::class, 'index'])->name('makeup-requests.index');
        Route::post('makeup-requests/{makeupRequest}/approve-admin', [MakeupRequestController::class, 'approveByAdmin'])->name('makeup-requests.approve-admin');

        Route::get('reschedule-requests', [RescheduleRequestController::class, 'index'])->name('reschedule-requests.index');
        Route::post('reschedule-requests/{rescheduleRequest}/approve', [RescheduleRequestController::class, 'approve'])->name('reschedule-requests.approve');
        Route::post('reschedule-requests/{rescheduleRequest}/reject', [RescheduleRequestController::class, 'reject'])->name('reschedule-requests.reject');
    });

    // ----- เงินเดือนอาจารย์ -----
    Route::middleware('permission:payroll.manage')->group(function () {
        Route::get('payroll', [PayrollController::class, 'index'])->name('payroll.index');
        Route::get('payroll/{teacher}/generate', [PayrollController::class, 'show'])->name('payroll.show');
        Route::post('payroll/{payrollRun}/adjust', [PayrollController::class, 'adjust'])->name('payroll.adjust');
        Route::post('payroll/{payrollRun}/confirm', [PayrollController::class, 'confirm'])->name('payroll.confirm');
        Route::post('payroll/{payrollRun}/mark-paid', [PayrollController::class, 'markPaid'])->name('payroll.mark-paid');
        Route::get('payroll/{payrollRun}/export-pdf', [PayrollController::class, 'exportPdf'])->name('payroll.export-pdf');
        Route::get('payroll/{payrollRun}/export-excel', [PayrollController::class, 'exportExcel'])->name('payroll.export-excel');
        Route::post('payroll/{payrollRun}/recalculate', [PayrollController::class, 'recalculate'])->name('payroll.recalculate');
    });

    // ----- ค่ารถอาจารย์ -----
    Route::middleware('permission:transport_fees.manage')->group(function () {
        Route::get('transport-fees', [TransportFeeController::class, 'index'])->name('transport-fees.index');
        Route::get('transport-fees/{teacher}', [TransportFeeController::class, 'show'])->name('transport-fees.show');
        Route::post('transport-fees/{teacher}/compensations', [TransportFeeController::class, 'storeCompensation'])->name('transport-fees.compensations.store');
        Route::delete('transport-compensations/{transportCompensation}', [TransportFeeController::class, 'destroyCompensation'])->name('transport-fees.compensations.destroy');
    });

    // ----- การเงิน: รายรับ-รายจ่าย -----
    Route::middleware('permission:finance.manage')->group(function () {
        Route::get('finance', [FinanceController::class, 'dashboard'])->name('finance.dashboard');
        Route::get('finance/report', [FinanceController::class, 'report'])->name('finance.report');
        Route::get('finance/report/export', [FinanceController::class, 'exportCsv'])->name('finance.report.export');

        Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
        Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
        Route::get('expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
        Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
        Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    });

    // ----- รายงาน -----
    Route::middleware('permission:reports.view')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

        Route::get('reports/students', [ReportController::class, 'students'])->name('reports.students');
        Route::get('reports/students/export-excel', [ReportController::class, 'exportStudentsExcel'])->name('reports.students.export-excel');
        Route::get('reports/students/export-pdf', [ReportController::class, 'exportStudentsPdf'])->name('reports.students.export-pdf');

        Route::get('reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('reports/revenue/export-excel', [ReportController::class, 'exportRevenueExcel'])->name('reports.revenue.export-excel');
        Route::get('reports/revenue/export-pdf', [ReportController::class, 'exportRevenuePdf'])->name('reports.revenue.export-pdf');

        Route::get('reports/teacher-performance', [ReportController::class, 'teacherPerformance'])->name('reports.teacher-performance');
        Route::get('reports/teacher-performance/export-excel', [ReportController::class, 'exportTeacherPerformanceExcel'])->name('reports.teacher-performance.export-excel');
        Route::get('reports/teacher-performance/export-pdf', [ReportController::class, 'exportTeacherPerformancePdf'])->name('reports.teacher-performance.export-pdf');
    });

    // ----- Music Store: จัดการสินค้า + สต็อก -----
    Route::middleware('permission:products.manage')->group(function () {
        Route::get('product-categories', [ProductCategoryController::class, 'index'])->name('product-categories.index');
        Route::post('product-categories', [ProductCategoryController::class, 'store'])->name('product-categories.store');
        Route::patch('product-categories/{productCategory}/toggle-active', [ProductCategoryController::class, 'toggleActive'])->name('product-categories.toggle-active');
        Route::delete('product-categories/{productCategory}', [ProductCategoryController::class, 'destroy'])->name('product-categories.destroy');

        Route::resource('products', ProductController::class)->except(['show']);
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

        Route::get('stock', [StockController::class, 'index'])->name('stock.index');
        Route::post('products/{product}/stock/adjust', [StockController::class, 'adjust'])->name('products.stock.adjust');
    });

    // ----- Music Store: ขายสินค้า -----
    Route::middleware('permission:store_sales.manage')->group(function () {
        Route::get('store-sales', [StoreSaleController::class, 'index'])->name('store-sales.index');
        Route::get('store-sales/create', [StoreSaleController::class, 'create'])->name('store-sales.create');
        Route::post('store-sales', [StoreSaleController::class, 'store'])->name('store-sales.store');
        Route::get('store-sales/{storeSale}', [StoreSaleController::class, 'show'])->name('store-sales.show');
        Route::patch('store-sales/{storeSale}/cancel', [StoreSaleController::class, 'cancel'])->name('store-sales.cancel');
        Route::patch('store-sales/{storeSale}/delivery-status', [StoreSaleController::class, 'updateDeliveryStatus'])->name('store-sales.delivery-status');
    });

    // ----- สิทธิ์ผู้ใช้งาน (ล็อก admin เท่านั้นเสมอ) -----
    Route::middleware('permission:role_permissions.manage')->group(function () {
        Route::get('role-permissions', [RolePermissionController::class, 'index'])->name('role-permissions.index');
        Route::post('role-permissions', [RolePermissionController::class, 'update'])->name('role-permissions.update');
    });

    // ----- ประวัติการใช้งาน (Audit Log) -----
    Route::middleware('permission:audit_logs.view')->group(function () {
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });
});
