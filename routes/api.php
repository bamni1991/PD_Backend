<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\student\StudentController;


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
