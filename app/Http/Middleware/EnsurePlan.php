<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlan
{
    public function handle(Request $request, Closure $next, string $requiredPlan): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasPlanAtLeast($requiredPlan)) {
            abort(403, "Fitur ini butuh paket {$requiredPlan} atau lebih tinggi. Upgrade dulu di halaman Layanan.");
        }

        return $next($request);
    }
}