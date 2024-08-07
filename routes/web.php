<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\BankDetailController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DocumentUserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobPortalControllers\CandidateController;
use App\Http\Controllers\JobPortalControllers\InterviewerRemarkController;
use App\Http\Controllers\JobPortalControllers\JobController;
use App\Http\Controllers\JobPortalControllers\JobOfferController;
use App\Http\Controllers\JobPortalControllers\ScheduleInterviewController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TimeLogController;
use App\Models\JobModels\ScheduleInterview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Auth::routes(['register' => true]);

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::fallback(function () {
    return redirect('/login');
});

Route::get('/optimize-clear', function() {
    Artisan::call('optimize:clear');
    return response()->json(['message' => 'Cache cleared successfully']);
});

Route::middleware(['auth', 'role:0,1,2', 'check.user.status'])->group(function () {

    // Job Portals Routses
    Route::prefix('portal')->group(function () {

        Route::resource('jobs', JobController::class)->except(['show']);

        Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');
        Route::get('/candidates/data', [CandidateController::class, 'getData'])->name('candidates.data');
        Route::get('/candidates/{candidate}', [CandidateController::class, 'show'])->name('candidates.show');
        Route::post('/candidates/status/{candidate}', [CandidateController::class, 'candidateStatus'])->name('candidates.status');
        Route::delete('/candidates/{candidate}', [CandidateController::class, 'destroy'])->name('candidates.destroy');

        Route::resource('schedule-interviews', ScheduleInterviewController::class)->except(['edit', 'update', 'destroy']);
        Route::get('/schedule-interviews/{schedule_interview}/remarks', [ScheduleInterviewController::class, 'interviewerRemarks'])->name('schedule-interviews.remarks');

        Route::resource('interviewer-remarks', InterviewerRemarkController::class);

        Route::resource('job-offers', JobOfferController::class);
        Route::post('/job-offers/send-email/{job_offer}', [JobOfferController::class, 'sendEmail'])->name('job-offers.send-email');

        Route::patch('/interviewer-remark/selected/{id}', [InterviewerRemarkController::class, 'selectedCandidateRemarks'])->name('selected.remarks');
        Route::patch('/interviewer-remark/rejected/{id}', [InterviewerRemarkController::class, 'rejectedCandidateRemarks'])->name('rejected.remarks');
        Route::patch('/interviewer-remark/status/{interviewer_remark}', [InterviewerRemarkController::class, 'candidateStatus'])->name('interviewer.status');
    });


    Route::get('/dashboard/notification', [HomeController::class, 'notification'])->name('dashboard.notification');


    // Route::get('/admin', [HomeController::class, 'dashboard'])->name('home');

    // Route::get('/users', [UserController::class, 'index'])->name('users');
    // Route::get('/users/create', [UserController::class, 'create'])->name('user_create');
    // Route::post('/users/create', [UserController::class, 'store'])->name('post_user');
    // Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('user_edit');
    // Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');

    Route::resource('users', UserController::class)->except(['show']);

    Route::put('/user-status/{id}', [UserController::class, 'updateStatus'])->name('users.status');

    Route::get('/attendance/log', [AttendanceController::class, 'attendanceLog'])->name('attendance.log');
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::get('/attendance/{id}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/attendance/update/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::post('/attendance/create', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/report', [AttendanceController::class, 'filterAttendanceReport'])->name('attendance.report');
    Route::get('/attendance/report/download', [AttendanceController::class, 'downloadAttendanceReport'])->name('attendance.report.download');
    
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::get('/roles/data/set', [RoleController::class, 'roleFetchData'])->name('roles.data');

    Route::resource('department', DepartmentController::class)->except(['show']);
    Route::get('department/data', [DepartmentController::class, 'getData'])->name('department.data');
    
    Route::resource('designation', DesignationController::class)->except(['show']);
    Route::get('designation/data', [DesignationController::class, 'getData'])->name('designation.data');
    Route::put('/designation-status/{id}', [DesignationController::class, 'updateStatus'])->name('update.status');

    Route::resource('awards', AwardController::class)->except(['show']);

    Route::resource('holidays', HolidayController::class)->except(['index', 'show']);
    Route::get('/holidays/data/set', [HolidayController::class, 'getData'])->name('holidays.data');
    Route::put('/holidays-status/{holiday}', [HolidayController::class, 'updateStatus'])->name('holidays.status');

    Route::resource('notices', NoticeController::class)->except(['index', 'show']);
    Route::put('/notices/notices-status/{id}', [NoticeController::class, 'updateStatus'])->name('notices.status');
    Route::get('/notices/data/set', [NoticeController::class, 'getData'])->name('notices.data');

    Route::resource('documents', DocumentUserController::class)->except(['show']);
    Route::post('/documents/upload', [DocumentUserController::class, 'upload'])->name('documents.upload');


    Route::resource('employees', EmployeeController::class)->parameters([
        'employees' => 'id'
    ])->names([
        'create' => 'employees.create',
        'store' => 'employees.store',
        'edit' => 'employees.edit',
        'update' => 'employees.update',
        'destroy' => 'employees.destroy',
    ])->except([
        'index',
        'show',
    ]);

    Route::put('/employees-status/{id}', [EmployeeController::class, 'updateStatus'])->name('employees.status');
    Route::get('/get-designations/{departmentId}', [EmployeeController::class, 'getDesignations']);

    Route::resource('leave-types', LeaveTypeController::class);
    Route::put('/leave-types/status/{leave_type}', [LeaveTypeController::class, 'updateStatus'])->name('updateLeave.status');
    Route::get('/leave-types/data/set', [LeaveTypeController::class, 'fetchData'])->name('leave-types.data');

    Route::resource('shifts', ShiftController::class);
    Route::get('/shifts/data/get', [ShiftController::class, 'getShiftData'])->name('shifts.data');


    Route::put('/leave-applications/{leave_application}', [LeaveApplicationController::class, 'update'])->name('leave-applications.update');
    Route::get('/leave-applications/{leave_application}/edit', [LeaveApplicationController::class, 'edit'])->name('leave-applications.edit');
    Route::delete('/leave-applications/{leave_application}', [LeaveApplicationController::class, 'destroy'])->name('leave-applications.destroy');
    Route::post('/leave-applications/{leave_application}', [LeaveApplicationController::class, 'updateStatus'])->name('leave-applications.status');

    Route::post('/complaints/{complaint}', [ComplaintController::class, 'updateStatus'])->name('complaints.status');

    Route::resource('bank-details', BankDetailController::class)->except(['show']);
    Route::put('bank-details/status/{bank_detail}', [BankDetailController::class, 'bankStatus'])->name('bank-details.status');
});

Route::middleware(['auth', 'check.user.status'])->group(function () {
    Route::put('/employees/image/set', [EmployeeController::class, 'updateImage'])->name('employees.image');
    Route::resource('time-logs', TimeLogController::class)->except(['edit', 'update', 'show', 'store', 'create']);
    Route::post('/start-time', [TimeLogController::class, 'startTime'])->name('start.time');
    Route::put('/end-time/{time_log}', [TimeLogController::class, 'endTimeTracker'])->name('end.time');
    Route::get('/timer-data', [TimeLogController::class, 'getTimerData'])->name('get.timer.data');

    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('home');
    Route::resource('complaints', ComplaintController::class);
    Route::post('logout', [HomeController::class, 'logout'])->name('logoutUser');
    Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays.index');

    Route::get('/attendance', [AttendanceController::class, 'AttendanceShow'])->name('attendance');
    Route::post('/checkin', [AttendanceController::class, 'checkInuser'])->name('checkIn');
    Route::get('/check-in-status', [AttendanceController::class, 'hasCheckedIn']);
    Route::patch('/checkOut', [AttendanceController::class, 'checkOutUser'])->name('checkOut');
    Route::get('download-pdf', [AttendanceController::class, 'downloadPdf'])->name('download-pdf');
    Route::get('/attendance/filter', [AttendanceController::class, 'AttendanceWithFilter'])->name('attendance.filter');
    Route::get('/attendance/daily-report', [AttendanceController::class, 'dailyReport'])->name('daily.report');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.view');
    Route::get('/employees/data', [EmployeeController::class, 'getData'])->name('employees.data');
    Route::get('/employees/profile/{id}', [EmployeeController::class, 'employeeProfile'])->name('employees.profile');
    Route::put('/employees/change-password/{user}', [EmployeeController::class, 'changePassword'])->name('employees.changePassword');
    Route::post('/leave-applications', [LeaveApplicationController::class, 'store'])->name('leave-applications.store');
    Route::get('/leave-applications/create', [LeaveApplicationController::class, 'create'])->name('leave-applications.create');
    Route::get('leave-applications', [LeaveApplicationController::class, 'index'])->name('leave-applications.index');
    Route::get('leave-policy', [PolicyController::class, 'index'])->name('policy.index');
    Route::get('notices', [NoticeController::class, 'index'])->name('notices.index');
    Route::get('notices/{notice}', [NoticeController::class, 'show'])->name('notices.show');
});

Route::prefix('candidates/portal/apply')->group(function () {
    Route::post('/check-email-phone', [CandidateController::class, 'checkEmailPhone'])->name('check.email.phone');
    Route::post('/form-submit', [CandidateController::class, 'store'])->name('candidates.store');
    Route::get('/form', [CandidateController::class, 'create'])->name('candidates.create');
    Route::get('/jobs', [JobController::class, 'showJobsCandidate'])->name('jobs.data');
});
