<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\SubmissionController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ForumController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\ExportController; // <-- Tambahkan ini

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rute Publik
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// --- RUTE BARU UNTUK EKSPOR PDF ---
// Rute ini tidak memerlukan token Sanctum karena diakses langsung dari browser
Route::get('/export/grades/{kelas}', [ExportController::class, 'exportGradesPdf'])->name('export.grades.pdf');


// Rute yang Membutuhkan Autentikasi (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);

    Route::apiResource('materials', MaterialController::class);
    Route::apiResource('assignments', AssignmentController::class);
    
    Route::post('assignments/{assignment}/submissions', [SubmissionController::class, 'store']);
    Route::put('submissions/{submission}', [SubmissionController::class, 'update']);
    
    Route::apiResource('forums', ForumController::class)->except(['update', 'destroy']);
    Route::post('forums/{forum}/comments', [CommentController::class, 'store']);
    
    Route::get('/grades', [GradeController::class, 'index']);
});

