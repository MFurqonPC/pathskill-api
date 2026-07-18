<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MiniProjectController extends Controller
{
    /**
     * GET /assignments/{assignment}/mini-project
     * Ambil brief mini project milik sebuah assignment. Ini tahap terakhir
     * sebelum Pengumpulan — tidak ada submission tersendiri di sini, upload
     * hasil kerja tetap lewat endpoint /assignments/{assignment}/submit.
     */
    public function show(Request $request, Assignment $assignment): JsonResponse
    {
        $project = $assignment->miniProject;

        if (! $project) {
            return response()->json(['message' => 'Mini project belum tersedia untuk assignment ini.'], 404);
        }

        return response()->json([
            'id' => $project->id,
            'assignment_id' => $project->assignment_id,
            'title' => $project->title,
            'brief' => $project->brief,
            'objectives' => $project->objectives ?? [],
            'acceptance_criteria' => $project->acceptance_criteria ?? [],
            'deliverables' => $project->deliverables ?? [],
        ]);
    }
}
