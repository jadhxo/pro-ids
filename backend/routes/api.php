<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MyLearningController;

/*
|--------------------------------------------------------------------------
| Health
|--------------------------------------------------------------------------
*/
Route::get('/health', fn () => ['status' => 'ok']);

/*
|--------------------------------------------------------------------------
| SAFE FRONTEND ROUTES (NO AUTH)
|--------------------------------------------------------------------------
*/
Route::get('/my-progress', [MyLearningController::class, 'index']);

Route::get('/courses', fn () => response()->json([]));

Route::get('/auth/me', fn () => response()->json(null));

/*
|--------------------------------------------------------------------------
| Auth (JWT – strict)
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
| Student (STRICT – LATER)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'role:student'])->group(function () {
    // real student routes (keep them)
});

/*
|--------------------------------------------------------------------------
| Instructor (STRICT – LATER)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'role:instructor'])->group(function () {
    // real instructor routes
});