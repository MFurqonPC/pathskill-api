<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CareerSeeder::class,
            LearningPathSeeder::class,
            AddAssignmentsToExistingModulesSeeder::class,
            SelfAssessmentContentSeeder::class,
            AssignmentDetailSeeder::class,
            QuizFullStackSeeder::class,
            QuizBackendSeeder::class,
            QuizDataAnalystSeeder::class,
            QuizDevOpsSeeder::class,
            QuizUIUXSeeder::class,
            CodingExerciseFullStackSeeder::class,
            CodingExerciseBackendSeeder::class,
            CodingExerciseDataAnalystSeeder::class,
            CodingExerciseDevOpsSeeder::class,
            CodingExerciseUIUXSeeder::class,
            MiniProjectFullStackSeeder::class,
            MiniProjectBackendSeeder::class,
            MiniProjectDataAnalystSeeder::class,
            MiniProjectDevOpsSeeder::class,
            MiniProjectUiUxSeeder::class,
        ]);
    }
}