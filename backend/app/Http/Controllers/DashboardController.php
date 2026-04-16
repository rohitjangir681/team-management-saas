<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $companyId = $user->current_company_id;

        // Fetch the tally from Redis (0.0001 seconds)
        // If the key doesn't exist yet, we default to 0
        $projectCount = Redis::get("company:{$companyId}:project_count") ?? 0;

        return response()->json([
            'status' => [
                'total_projects' => (int) $projectCount,
            ],
            'user' => [
                'name' => '$user->name',
                'company_id' => $companyId,
            ]
        ]);
    }
}
