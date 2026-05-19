<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $activeCompany = $user->companies()->firstWhere('companies.id', $user->current_company_id);

        if (!$activeCompany) {
            return response()->json(['message' => 'Workspace not found.'], 404);
        }

        $teamMembers = $activeCompany->users()
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->withPivot(['role_id']) // Eager load the role from the custom pivot model
            ->get();

        return response()->json([
            'members' => $teamMembers
        ]);
    }
}
