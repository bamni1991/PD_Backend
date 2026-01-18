<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\student\StudentController;
use App\Http\Controllers\teacher\TeacherController;
use App\Http\Controllers\AdminController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/upload-profile-image', [AuthController::class, 'uploadProfileImage']);
Route::get('/getStudentFromCreatingData', [StudentController::class, 'getStudentFromCreatingData']);

Route::post('/students/register', [StudentController::class, 'saveStudent']);

Route::get('/feachStudentAdminScreenData', [StudentController::class, 'feachStudentAdminScreenData']);


Route::get('/student/{studentId}', [StudentController::class, 'getStudentById']);

Route::post('/students/update/{studentId}', [StudentController::class, 'updateStudent']);

Route::get('/students/getUserFeesHistoryData/{studentId}', [StudentController::class, 'getUserFeesHistoryData']);


Route::post('/students/updateFees/{studentFeeId}', [StudentController::class, 'updateFees']);

Route::post('/students/collectFees', [StudentController::class, 'collectFees']);
Route::delete('/students/deleteFees/{studentFeeId}', [StudentController::class, 'deleteFees']);
Route::get('/student-dashboard-details', [StudentController::class, 'getStudentDashboardDetails']);




// Teacher Routes
Route::post('/teachers/register', [TeacherController::class, 'saveTeacher']);
Route::get('/getAllTeachers', [TeacherController::class, 'getAllTeachers']);
Route::get('/teacher/{teacherId}', [TeacherController::class, 'getTeacherById']);

Route::post('/teachers/update/{teacherId}', [TeacherController::class, 'updateTeacher']);

Route::delete('/teachers/delete/{teacherId}', [TeacherController::class, 'deleteTeacher']);

Route::post('/teacher-attendance/mark', [TeacherController::class, 'markAttendance']);
Route::get('/teacher-attendance/today/{userId}', [TeacherController::class, 'getTeacherAttendanceByDate']);
Route::get('/teacher-attendance/history/{userId}', [TeacherController::class, 'getTeacherAttendanceHistory']);


// Admin



Route::post('/school-holidays', [AdminController::class, 'createSchoolHoliday']);

Route::get('/school-holidays', [AdminController::class, 'getSchoolHolidays']);



Route::put('/school-holidays/{holidayId}', [AdminController::class, 'updateSchoolHoliday']);


Route::delete('/school-holidays/{holidayId}', [AdminController::class, 'deleteSchoolHoliday']);
