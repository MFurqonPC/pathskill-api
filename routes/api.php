<?php

use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CareerController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\LearningPathController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\AssignmentReviewController;
use App\Http\Controllers\Api\CodingExerciseController;
use App\Http\Controllers\Api\MiniProjectController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\SelfAssessmentController;
use App\Http\Controllers\Api\SkillAssessmentController;
use App\Http\Controllers\Api\SkillMapController;
use Illuminate\Support\Facades\Route;

// ==== PUBLIC ROUTES ====
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
// TANPA middleware auth:sanctum dengan sengaja — endpoint ini justru dipakai
// SETELAH access token expired, diautentikasi lewat refresh_token cookie,
// bukan Bearer token.
Route::post('/refresh', [AuthController::class, 'refresh']);
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

    // Self-Assessment Step 2 (Experience Checklist + Scenario Confidence + Short Verification Task)
    Route::get('/careers/{career}/self-assessment', [SelfAssessmentController::class, 'step2Content']);
    Route::post('/self-assessment/checklist', [SelfAssessmentController::class, 'saveChecklist']);
    Route::post('/self-assessment/confidence', [SelfAssessmentController::class, 'saveConfidence']);

    // Self-Assessment Step 3 (Skill Verification Quiz)
    Route::get('/careers/{career}/verification-quiz', [SelfAssessmentController::class, 'quizQuestions']);
    Route::post('/verification-quiz/{question}/answer', [SelfAssessmentController::class, 'answerQuizQuestion']);
    Route::get('/careers/{career}/verification-quiz/result', [SelfAssessmentController::class, 'quizResult']);
    Route::post('/careers/{career}/verification-quiz/log-tab-switch', [SelfAssessmentController::class, 'logTabSwitch']);

    // Skill Map (radar chart + gap analysis)
    Route::get('/skill-map', [SkillMapController::class, 'index']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::middleware('plan:pro')->group(function () {
        // Assignment & Learning Path
        Route::get('/assignments/{assignment}', [AssignmentController::class, 'show']);
        Route::post('/assignments/{assignment}/submit', [ProgressController::class, 'submitAssignment']);
        Route::get('/assignments/{assignment}/quiz', [QuizController::class, 'show']);
        Route::post('/quiz-questions/{question}/answer', [QuizController::class, 'answer']);
        Route::get('/assignments/{assignment}/coding-exercise', [CodingExerciseController::class, 'show']);
        Route::post('/coding-exercises/{codingExercise}/submit', [CodingExerciseController::class, 'submit']);
        Route::get('/assignments/{assignment}/mini-project', [MiniProjectController::class, 'show']);
        Route::post('/lessons/{lesson}/complete', [ProgressController::class, 'completeLesson']);

        Route::post('/learning-path/generate', [LearningPathController::class, 'recommend']);
        Route::get('/learning-path', [LearningPathController::class, 'index']);
        Route::get('/learning-path/{module}', [LearningPathController::class, 'show']);
    });

    // AssignmentReviewController dipakai mentor untuk mereview tugas siswa,
    // bukan siswa mengerjakan tugas — tidak relevan dengan plan siswa,
    // jadi sengaja tetap di luar grup plan:pro di atas.
    Route::get('/assignments/{assignment}/review', [AssignmentReviewController::class, 'show']);
    Route::post('/assignments/{assignment}/review', [AssignmentReviewController::class, 'store'])
        ->middleware('role:mentor');

Route::middleware('role:admin')->prefix('admin')->group(function () {
    Route::get('/users/search', [AdminUserController::class, 'search']);
    Route::post('/users/{user}/activate-plan', [AdminUserController::class, 'activatePlan']);
    });
});