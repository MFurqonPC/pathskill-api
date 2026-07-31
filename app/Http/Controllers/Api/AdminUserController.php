<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    // GET /admin/users/search?q=budi
    public function search(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if ($q === '') {
            return response()->json([]);
        }

        $users = User::where('email', 'like', "%{$q}%")
            ->orWhere('name', 'like', "%{$q}%")
            ->select('id', 'name', 'email', 'plan', 'plan_expires_at')
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    // POST /admin/users/{user}/activate-plan
    public function activatePlan(Request $request, User $user)
    {
        $validated = $request->validate([
            'plan' => 'required|in:free,pro,career_mentor',
            'months' => 'required_unless:plan,free|integer|min:1|max:24',
        ]);

        $user->update([
            'plan' => $validated['plan'],
            'plan_expires_at' => $validated['plan'] === 'free'
                ? null
                : now()->addMonths((int) ($validated['months'] ?? 1)),
        ]);

        return response()->json([
            'message' => "Plan {$user->email} berhasil diaktivasi.",
            'user' => $user->only(['id', 'name', 'email', 'plan', 'plan_expires_at']),
        ]);
    }
}