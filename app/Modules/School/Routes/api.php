<?php

use Illuminate\Support\Facades\Route;
use App\Modules\School\Controllers\AuthController;
use App\Modules\School\Controllers\StudentController;
use App\Modules\School\Controllers\TeacherController;
use App\Modules\School\Controllers\AdminController;

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/upload-profile-image', [AuthController::class, 'uploadProfileImage']);

// Student Routes
Route::prefix('students')->group(function () {
    Route::get('/getStudentFromCreatingData', [StudentController::class, 'getStudentFromCreatingData']);
    Route::post('/register', [StudentController::class, 'saveStudent']);
    Route::get('/feachStudentAdminScreenData', [StudentController::class, 'feachStudentAdminScreenData']);
    Route::get('/{studentId}', [StudentController::class, 'getStudentById']);
    Route::post('/update/{studentId}', [StudentController::class, 'updateStudent']);
    Route::get('/getUserFeesHistoryData/{studentId}', [StudentController::class, 'getUserFeesHistoryData']);
    Route::post('/updateFees/{studentFeeId}', [StudentController::class, 'updateFees']);
    Route::post('/collectFees', [StudentController::class, 'collectFees']);
    Route::delete('/deleteFees/{studentFeeId}', [StudentController::class, 'deleteFees']);
});

Route::get('/student-dashboard-details', [StudentController::class, 'getStudentDashboardDetails']);
Route::get('/student/{studentId}', [StudentController::class, 'getStudentById']);

// Teacher Routes
Route::prefix('teachers')->group(function () {
    Route::post('/register', [TeacherController::class, 'saveTeacher']);
    Route::get('/all', [TeacherController::class, 'getAllTeachers']);
    Route::get('/{teacherId}', [TeacherController::class, 'getTeacherById']);
    Route::post('/update/{teacherId}', [TeacherController::class, 'updateTeacher']);
    Route::delete('/delete/{teacherId}', [TeacherController::class, 'deleteTeacher']);
});

Route::get('/getAllTeachers', [TeacherController::class, 'getAllTeachers']);
Route::get('/teacher/{teacherId}', [TeacherController::class, 'getTeacherById']);

// Teacher Attendance Routes
Route::prefix('teacher-attendance')->group(function () {
    Route::post('/mark', [TeacherController::class, 'markAttendance']);
    Route::get('/today/{userId}', [TeacherController::class, 'getTeacherAttendanceByDate']);
    Route::get('/history/{userId}', [TeacherController::class, 'getTeacherAttendanceHistory']);
});

// Teacher Leave Routes
Route::prefix('teacher-leaves')->group(function () {
    Route::post('/apply', [TeacherController::class, 'applyLeave']);
    Route::get('/history/{userId}', [TeacherController::class, 'getTeacherHistory']);
});

// Admin - School Holidays Routes
Route::prefix('school-holidays')->group(function () {
    Route::post('/', [AdminController::class, 'createSchoolHoliday']);
    Route::get('/', [AdminController::class, 'getSchoolHolidays']);
    Route::put('/{holidayId}', [AdminController::class, 'updateSchoolHoliday']);
    Route::delete('/{holidayId}', [AdminController::class, 'deleteSchoolHoliday']);
});
