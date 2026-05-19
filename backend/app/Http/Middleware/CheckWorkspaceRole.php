<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckWorkspaceRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user || !$user->current_company_id) {
            return response()->json([
                'message' => 'No active workspace selected.'
            ], 403);
        }

        $activeCompany = $user->companies()->firstWhere('id', $user->current_company_id);

        if (!$activeCompany) {
            return response()->json([
                'message' => 'You do not belong to this workspace.'
            ], 403);
        }

        $currentRoleName = strtolower($activeCompany->pivot->role?->name ?? '');

        if ($currentRoleName !== strtolower($role)) {
            return response()->json([
                'message' => 'Unauthorized. This action requires the ' . ucfirst($role) . ' role.'
            ], 403);
        }

        return $next($request);
    }
}
