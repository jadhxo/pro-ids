<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonProgressController;

/*
|--------------------------------------------------------------------------
| Health
|--------------------------------------------------------------------------
*/
Route::get('/health', fn () => ['status' => 'ok']);

/*
|--------------------------------------------------------------------------
| Auth (JWT)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

/*
|--------------------------------------------------------------------------
| Student
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'role:student'])->group(function () {
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{course}', [CourseController::class, 'show']);
    Route::get('/courses/{course}/lessons', [LessonController::class, 'index']);

    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll']);
    Route::get('/my-courses', [EnrollmentController::class, 'myCourses']);

    // Lesson progress
    Route::post('/lessons/{lesson}/complete', [LessonProgressController::class, 'complete']);
    Route::get('/courses/{course}/progress', [LessonProgressController::class, 'progress']);
});

/*
|--------------------------------------------------------------------------
| Instructor
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'role:instructor'])->group(function () {
    Route::post('/courses', [CourseController::class, 'store']);
    Route::get('/instructor/courses', [CourseController::class, 'myCourses']);
    Route::post('/courses/{course}/publish', [CourseController::class, 'publish']);
    Route::post('/courses/{course}/lessons', [LessonController::class, 'store']);
});