<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CareerController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\LearningPathController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\CodingExerciseController;
use App\Http\Controllers\Api\MiniProjectController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\SkillAssessmentController;
use App\Http\Controllers\Api\SkillMapController;
use Illuminate\Support\Facades\Route;

// ==== PUBLIC ROUTES ====
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/contact', [ContactMessageController::class, 'store']);

// ==== PROTECTED ROUTES (butuh Bearer token dari Sanctum) ====
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Careers
    Route::get('/careers', [CareerController::class, 'index']);
    Route::post('/careers/{career}/select', [CareerController::class, 'select']);
    Route::get('/careers/{career}/skills', [CareerController::class, 'skills']);

    // Skill Assessment
    Route::post('/skill-assessments', [SkillAssessmentController::class, 'store']);
    Route::get('/skill-assessments/{careerId}', [SkillAssessmentController::class, 'show']);

    // Skill Map (radar chart + gap analysis)
    Route::get('/skill-map', [SkillMapController::class, 'index']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Learning Path (AI-generated via Groq)
    Route::post('/learning-path/generate', [LearningPathController::class, 'generate']);
    Route::get('/learning-path', [LearningPathController::class, 'index']);
    Route::get('/learning-path/{module}', [LearningPathController::class, 'show']);

    // Progress
    Route::post('/lessons/{lesson}/complete', [ProgressController::class, 'completeLesson']);
    // route baru
    Route::get('/assignments/{assignment}', [AssignmentController::class, 'show']);
    // route lama tetap
    Route::post('/assignments/{assignment}/submit', [ProgressController::class, 'submitAssignment']);

    Route::get('/assignments/{assignment}/quiz', [QuizController::class, 'show']);
    Route::post('/quiz-questions/{question}/answer', [QuizController::class, 'answer']);

    Route::get('/assignments/{assignment}/coding-exercise', [CodingExerciseController::class, 'show']);
    Route::post('/coding-exercises/{codingExercise}/submit', [CodingExerciseController::class, 'submit']);

    Route::get('/assignments/{assignment}/mini-project', [MiniProjectController::class, 'show']);
});