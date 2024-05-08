<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
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
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/create', [UserController::class, 'create'])->name('user_create');
    Route::post('/users/create', [UserController::class, 'store'])->name('post_user');

    Route::get('/role', [RoleController::class, 'index'])->name('roles');
    Route::get('/role/create', [RoleController::class, 'create'])->name('role_create');
    Route::post('/role/create', [RoleController::class, 'store'])->name('role_store');

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

});


Route::get('/', [HomeController::class, 'dashboard'])->name('home');
Route::get('/admin', [HomeController::class, 'index'])->name('admin');
Route::post('logout', [HomeController::class, 'logout'])->name('logoutUser');

Route::get('/attendance', [AttendanceController::class, 'AttendanceShow'])->name('attendance');
Route::post('/checkin', [AttendanceController::class, 'checkInuser'])->name('checkIn');
Route::post('/checkOut', [AttendanceController::class, 'checkOutUser'])->name('checkOut');
