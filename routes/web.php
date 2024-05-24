<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => true]);

// Route::middleware('auth', 'role:3')->group(function () {

//     Route::get('/users', [UserController::class, 'index'])->name('users');
//     Route::get('/users/create', [UserController::class, 'create'])->name('user_create');
//     Route::post('/users/create', [UserController::class, 'store'])->name('post_user');

//     // Role Manager

//     Route::get('/role', [RoleController::class, 'index'])->name('roles');
//     Route::get('/role/create', [RoleController::class, 'create'])->name('role_create');
//     Route::post('/role/create', [RoleController::class, 'store'])->name('role_store');
// });

// Route::get('/', [HomeController::class, 'dashboard'])->name('home');
// Route::get('/admin', [HomeController::class, 'index'])->name('admin');
// Route::post('logout', [HomeController::class, 'logout'])->name('logoutUser');

// Route::get('/attendance', [AttendanceController::class, 'AttendanceShow'])->name('attendance');
// Route::post('/checkin', [AttendanceController::class, 'checkInuser'])->name('checkIn');
// Route::post('/checkOut', [AttendanceController::class, 'checkOutUser'])->name('checkOut');

// // users

// Route::get('/users', [UserController::class, 'index'])->name('users');
// Route::get('/users/create', [UserController::class, 'create'])->name('user_create');
// Route::post('/users/create', [UserController::class, 'store'])->name('post_user');

// // Role Manager

// Route::get('/role', [RoleController::class, 'index'])->name('roles');
// Route::get('/role/create', [RoleController::class, 'create'])->name('role_create');
// Route::post('/role/create', [RoleController::class, 'store'])->name('role_store');

// Route::get('/department', [DepartmentController::class, 'index'])->name('departmentView');
// Route::get('/department/create', [DepartmentController::class, 'create'])->name('departmentCreate');
// Route::post('/department/create', [DepartmentController::class, 'store'])->name('departmentStore');
// Route::get('/department/{id}/edit', [DepartmentController::class, 'edit'])->name('departmentEdit');
// Route::put('/department/{id}/update', [DepartmentController::class, 'update'])->name('departmentUpdate');
// Route::delete('/department/{id}', [DepartmentController::class, 'destroy'])->name('departmentDestroy');

Route::middleware(['auth', 'role:1,2'])->group(function () {
    // Route::get('/admin', [HomeController::class, 'dashboard'])->name('home');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/create', [UserController::class, 'create'])->name('user_create');
    Route::post('/users/create', [UserController::class, 'store'])->name('post_user');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('user_edit');
    Route::put('/user-status/{id}', [UserController::class, 'updateStatus'])->name('users.status');
    Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');

    Route::get('/role', [RoleController::class, 'index'])->name('roles');
    Route::get('/role/create', [RoleController::class, 'create'])->name('role_create');
    Route::post('/role/create', [RoleController::class, 'store'])->name('role_store');
    Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role_edit');
    Route::put('/role/{id}/update', [RoleController::class, 'update'])->name('role_update');
    Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role_destroy');
    Route::put('/role-status/{id}', [RoleController::class, 'updateStatus'])->name('role.status');
    Route::get('/attendance/log', [AttendanceController::class, 'attendanceLog'])->name('attendance.log');
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/create', [AttendanceController::class, 'store'])->name('attendance.store');

    // Department Routes
    Route::resource('department', DepartmentController::class)->parameters([
        'department' => 'id'
    ])->names([
        'index' => 'departmentView',
        'create' => 'departmentCreate',
        'store' => 'departmentStore',
        'edit' => 'departmentEdit',
        'update' => 'departmentUpdate',
        'destroy' => 'departmentDestroy',
    ])->except([
        'show'
    ]);
    Route::resource('designation', DesignationController::class)->parameters([
        'designation' => 'id'
    ])->names([
        'index' => 'designation.view',
        'create' => 'designation.create',
        'store' => 'designation.store',
        'edit' => 'designation.edit',
        'update' => 'designation.update',
        'destroy' => 'designation.destroy',
    ])->except([
        'show'
    ]);

    Route::put('/update-status/{id}', [DesignationController::class, 'updateStatus'])->name('update.status');
    Route::resource('employees', EmployeeController::class)->parameters([
        'employees' => 'id'
    ])->names([
        'create' => 'employees.create',
        'store' => 'employees.store',
        'edit' => 'employees.edit',
        'update' => 'employees.update',
    ])->except([
        'index',
        'show',
        'destroy'
    ]);

    Route::put('/employees-status/{id}', [EmployeeController::class, 'updateStatus'])->name('employees.status');
    Route::get('/get-designations/{departmentId}', [EmployeeController::class, 'getDesignations']);

    Route::resource('leave-types', LeaveTypeController::class)->parameters([
        'leave-types' => 'leaveType'
    ])->names([
        'index' => 'leave_types.index',
        'create' => 'leave_types.create',
        'edit' => 'leave_types.edit',
        'store' => 'leave_types.store',
        'update' => 'leave_types.update',
        'destroy' => 'leave_types.destroy',
    ])->except([
        'show',
    ]);
    Route::put('/leave-types/{leaveType}/status', [LeaveTypeController::class, 'updateStatus'])->name('updateLeave.status');

    Route::resource('leave-applications', LeaveApplicationController::class)
        ->parameters([
            'leave-applications' => 'id'
        ])->names([
            'edit' => 'leave_application.edit',
            'update' => 'leave_application.update',
            'destroy' => 'leave_application.destroy',
        ])->except([
            'show',
        ]);

    Route::resource('holidays', HolidayController::class)
        ->parameters([
            'holidays' => 'holiday'
        ])->names([
            'create' => 'holidays.create',
            'edit' => 'holidays.edit',
            'store' => 'holidays.store',
            'update' => 'holidays.update',
            'destroy' => 'holidays.destroy',
        ])->except([
            'show',
        ]);
        Route::put('/holidays-status/{holiday}', [HolidayController::class, 'updateStatus'])->name('holidays.status');
});

Route::middleware(['auth'])->group(function () {
    
    Route::get('/', [HomeController::class, 'dashboard'])->name('home');
    Route::post('logout', [HomeController::class, 'logout'])->name('logoutUser');

    Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays.index');
    Route::get('/attendance', [AttendanceController::class, 'AttendanceShow'])->name('attendance');
    Route::post('/checkin', [AttendanceController::class, 'checkInuser'])->name('checkIn');
    Route::post('/checkOut', [AttendanceController::class, 'checkOutUser'])->name('checkOut');
    Route::get('download-pdf', [AttendanceController::class, 'downloadPdf'])->name('download-pdf');
    // Route::get('/attendance/filter', [AttendanceController::class, 'AttendanceShow'])->name('attendance.filter');
    Route::get('/attendance/daily-report', [AttendanceController::class, 'dailyReport'])->name('daily.report');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.view');
    Route::post('/leave-applications/create', [LeaveApplicationController::class, 'store'])->name('leave_application.store');
    Route::get('/leave-applications/create', [LeaveApplicationController::class, 'create'])->name('leave_application.create');
    Route::get('leave-applications', [LeaveApplicationController::class, 'index'])->name('leave_application.index');
    Route::get('leave-policy', [PolicyController::class, 'index'])->name('policy.index');
});
 