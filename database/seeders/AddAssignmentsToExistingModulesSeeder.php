<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\LearningModule;
use Illuminate\Database\Seeder;

class AddAssignmentsToExistingModulesSeeder extends Seeder
{
    /**
     * Aman dijalankan kapan saja, berkali-kali, tanpa reset database:
     * - Tidak menyentuh modul/assignment yang sudah ada (Full Stack Developer).
     * - Pakai updateOrCreate berdasarkan (learning_module_id, title), jadi
     *   re-run tidak membuat assignment duplikat.
     * - Kalau career atau modul tidak ditemukan (misal LearningPathSeeder
     *   belum dijalankan untuk career itu), entri itu di-skip dengan
     *   warning, tidak menghentikan seluruh proses.
     *
     * Jalankan:
     *   php artisan db:seed --class=AddAssignmentsToExistingModulesSeeder
     */
    private array $data = [
        'Backend Developer' => [
            'Node.js Fundamentals' => [
                'Static File Server with Node.js',
                'Async Task Queue Simulator',
            ],
            'Membangun REST API' => [
                'Task Manager REST API',
                'Book Catalog API',
                'API Structure Refactor',
            ],
            'Database SQL & NoSQL' => [
                'Library Database Schema Design',
                'Blog CRUD with MySQL',
                'Product Catalog with MongoDB',
            ],
            'Authentication & Security' => [
                'JWT Authentication System',
                'Secure Login API',
                'Rate-Limited Login Endpoint',
            ],
            'Git & Collaboration Workflow' => [
                'Team Branching Simulation',
                'Pull Request Practice Repo',
            ],
            'Testing & Debugging Backend' => [
                'Unit Test Suite for Utility Functions',
                'API Integration Test with Supertest',
            ],
            'Server Architecture & Performance' => [
                'Refactor to Layered Architecture',
                'Redis Caching Layer',
                'Health Check & Monitoring Endpoint',
            ],
        ],

        'Data Analyst' => [
            'Python untuk Data Analysis' => [
                'Sales Data Exploration with Pandas',
                'Customer Data Summary Report',
            ],
            'SQL untuk Data Analyst' => [
                'Sales Database Query Challenge',
                'Multi-table Join Report',
            ],
            'Data Cleaning & Preparation' => [
                'Messy Dataset Cleanup',
                'Customer Data Standardization',
            ],
            'Data Visualization' => [
                'Sales Performance Dashboard',
                'Chart Type Comparison Report',
            ],
            'Statistik Dasar untuk Analisis' => [
                'Statistical Summary Report',
                'Correlation Analysis Project',
            ],
            'R untuk Data Analysis' => [
                'R Data Manipulation with dplyr',
                'Visualization with ggplot2',
            ],
            'Komunikasi Insight Data' => [
                'Data Story Presentation',
                'Executive Summary Report',
            ],
        ],

        'DevOps Engineer' => [
            'Linux Fundamentals' => [
                'Linux Server Setup Exercise',
                'Automated Backup Shell Script',
            ],
            'Docker & Containerization' => [
                'Dockerize a Node.js App',
                'Multi-Container App with Docker Compose',
            ],
            'CI/CD Pipeline' => [
                'GitHub Actions CI Pipeline',
                'Automated Deployment Workflow',
            ],
            'Kubernetes & Orchestration' => [
                'Deploy App to Kubernetes Cluster',
                'Autoscaling Configuration Exercise',
            ],
            'Monitoring & Logging' => [
                'Prometheus Monitoring Setup',
                'Centralized Logging with ELK Stack',
            ],
            'Git & Infrastructure Workflow' => [
                'Terraform Infrastructure Setup',
                'GitOps Deployment with ArgoCD',
            ],
        ],

        'UI/UX Designer' => [
            'Design Thinking & User Research' => [
                'User Research & Persona Creation',
                'Customer Journey Map Project',
            ],
            'Wireframing & Prototyping' => [
                'Low-Fidelity Wireframe Set',
                'Interactive Prototype in Figma',
            ],
            'Visual Design & Tipografi' => [
                'Mobile App Visual Design',
                'Design System Style Guide',
            ],
            'HTML & CSS untuk Designer' => [
                'Static Page from Figma Design',
                'Responsive Landing Page Handoff',
            ],
            'Usability Testing' => [
                'Usability Testing Session Report',
                'Design Iteration Based on Feedback',
            ],
            'Design Handoff & Kolaborasi dengan Developer' => [
                'Design Handoff Documentation',
                'Design QA Checklist Exercise',
            ],
        ],
    ];

    public function run(): void
    {
        $totalCreated = 0;
        $skippedCareers = [];
        $skippedModules = [];

        foreach ($this->data as $careerName => $modules) {
            $career = Career::where('name', $careerName)->first();

            if (! $career) {
                $skippedCareers[] = $careerName;
                continue;
            }

            foreach ($modules as $moduleTitle => $assignmentTitles) {
                $module = LearningModule::where('career_id', $career->id)
                    ->where('title', $moduleTitle)
                    ->first();

                if (! $module) {
                    $skippedModules[] = "{$careerName} > {$moduleTitle}";
                    continue;
                }

                foreach ($assignmentTitles as $index => $title) {
                    $fullTitle = 'Assignment ' . ($index + 1) . ": {$title}";

                    // updateOrCreate berdasarkan (learning_module_id, title)
                    // supaya seeder ini idempotent — aman dijalankan ulang
                    // tanpa membuat assignment duplikat.
                    $module->assignments()->updateOrCreate(
                        ['title' => $fullTitle],
                        [
                            'description' => 'Complete a practical ' . strtolower($moduleTitle) . ' project',
                            'due_date' => now()->addWeeks($module->order * 2 + $index + 1),
                            'order' => $index + 1,
                        ]
                    );
                    $totalCreated++;
                }

                // Sinkronkan total_assignments di modul (dipakai Dashboard
                // & Module Detail), karena sebelumnya modul ini punya 0
                // assignment (array kosong di LearningPathSeeder).
                $module->update([
                    'total_assignments' => count($assignmentTitles),
                ]);
            }
        }

        $this->command?->info("AddAssignmentsToExistingModulesSeeder: {$totalCreated} assignment berhasil dibuat/diperbarui.");

        if (! empty($skippedCareers)) {
            $this->command?->warn(
                'Career tidak ditemukan (jalankan CareerSeeder dulu): ' . implode(', ', $skippedCareers)
            );
        }

        if (! empty($skippedModules)) {
            $this->command?->warn(
                'Modul tidak ditemukan (cek ejaan title atau jalankan LearningPathSeeder dulu): '
                . implode('; ', $skippedModules)
            );
        }
    }
}