<?php

use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLeaveController;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\OrganizationFileController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Employee\AttendanceController;
use App\Http\Controllers\Employee\CompanyFileController;
use App\Http\Controllers\Employee\EmpDashboardController;
use App\Http\Controllers\Employee\LeaveController;
use App\Http\Controllers\Employee\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'processLogin'])->name('login.submit');
});

Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Profile
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');

    // Department Management
    Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::put('departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::patch('departments/{department}/toggle-status', [DepartmentController::class, 'toggleStatus'])->name('departments.toggle-status');

    // Designation Management
    Route::get('designations', [DesignationController::class, 'index'])->name('designations.index');
    Route::post('designations', [DesignationController::class, 'store'])->name('designations.store');
    Route::put('designations/{designation}', [DesignationController::class, 'update'])->name('designations.update');
    Route::patch('designations/{designation}/toggle-status', [DesignationController::class, 'toggleStatus'])->name('designations.toggle-status');

    // Shift Management
    Route::resource('shifts', ShiftController::class)->except(['create', 'show', 'edit']);

    // Holiday Management
    Route::resource('holidays', HolidayController::class)->except(['create', 'show', 'edit']);
    Route::patch('holidays/{holiday}/toggle-status', [HolidayController::class, 'toggleStatus'])->name('holidays.toggle-status');

    // Employee Management
    Route::resource('employees', EmployeeController::class);

    // Attendance Management
    Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/employees/{employee}/attendance', [EmployeeController::class, 'attendance'])->name('employees.attendance');
    Route::post('/employees/{employee}/attendance', [AdminAttendanceController::class, 'store'])->name('employees.attendance.store');
    Route::put('/attendance/{attendance}/update-time', [AdminAttendanceController::class, 'updateTime'])->name('attendance.update-time');

    // Asset Management
    Route::resource('assets', AssetController::class)->except(['create', 'show', 'edit']);

    // Employee 360° Quick Actions
    Route::post('employees/{employee}/assign-asset', [EmployeeController::class, 'assignAsset'])->name('employees.assets.assign');
    Route::post('employees/{employee}/return-asset/{asset}', [EmployeeController::class, 'returnAsset'])->name('employees.assets.return');

    // Employee 360° Documents & Timeline
    Route::post('employees/{employee}/documents', [EmployeeController::class, 'storeDocument'])->name('employees.documents.store');
    Route::delete('employees/documents/{document}', [EmployeeController::class, 'destroyDocument'])->name('employees.documents.destroy');
    Route::post('employees/{employee}/timeline', [EmployeeController::class, 'storeTimeline'])->name('employees.timeline.store');
    Route::delete('employees/timeline/{timeline}', [EmployeeController::class, 'destroyTimeline'])->name('employees.timeline.destroy');

    Route::get('/leaves/manage', [AdminLeaveController::class, 'manageLeaves'])->name('leaves.manage');
    Route::get('/leaves/requests', [AdminLeaveController::class, 'leaveRequests'])->name('leaves.requests');

    // Actions
    Route::post('/leaves/types', [AdminLeaveController::class, 'storeType'])->name('leaves.types.store');
    Route::post('/leaves/balances', [AdminLeaveController::class, 'updateBalance'])->name('leaves.balances.update');
    Route::put('/leaves/requests/{leaveRequest}', [AdminLeaveController::class, 'updateRequestStatus'])->name('leaves.requests.update');

    Route::get('/organization-files', [OrganizationFileController::class, 'index'])->name('organization.files');
    Route::post('/organization-files', [OrganizationFileController::class, 'store'])->name('organization.files.store');
    Route::put('/organization-files/{organizationFile}', [OrganizationFileController::class, 'update'])->name('organization.files.update');
    Route::delete('/organization-files/{organizationFile}', [OrganizationFileController::class, 'destroy'])->name('organization.files.destroy');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginFormEmp'])->name('login');
    Route::post('/login', [AuthController::class, 'loginEmp'])->name('login.submit');
});

// 3. Employee Protected Routes
Route::middleware('auth')->prefix('employee')->name('employee.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logoutEmp'])->name('logout');

    // Dashboard & Attendance Actions
    Route::get('/dashboard', [EmpDashboardController::class, 'index'])->name('dashboard');
    Route::post('/check-in', [EmpDashboardController::class, 'checkIn'])->name('check-in');
    Route::post('/check-out', [EmpDashboardController::class, 'checkOut'])->name('check-out');
    Route::post('/start-break', [EmpDashboardController::class, 'startBreak'])->name('start-break');
    Route::post('/end-break', [EmpDashboardController::class, 'endBreak'])->name('end-break');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Attendance History
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
    Route::get('/attendance/calendar', [AttendanceController::class, 'calendar'])->name('attendance.calendar');

    // Leave Application
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.apply');

    Route::get('/company-files', [CompanyFileController::class, 'index'])->name('company-files');
});
