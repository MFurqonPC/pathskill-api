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
            SelfAssessmentContentSeeder::class,
            AssignmentDetailSeeder::class,
            QuizSeeder::class,
            CodingExerciseSeeder::class,
            MiniProjectSeeder::class,
        ]);
    }
}