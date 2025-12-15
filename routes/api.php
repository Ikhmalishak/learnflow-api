<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonCompletionController;
use App\Http\Controllers\LessonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');

Route::apiResource('courses', CourseController::class)->middleware('auth:sanctum');
Route::apiResource('lessons', LessonController::class)->middleware('auth:sanctum');
Route::apiResource('enrollments', EnrollmentController::class)->middleware('auth:sanctum');
Route::apiResource('lesson-completions',LessonCompletionController::class)->middleware('auth:sanctum');