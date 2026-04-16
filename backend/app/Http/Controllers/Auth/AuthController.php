<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\WelcomeEmail;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function __construct(protected AuthService $authService) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        $user = $result['user'];

        Mail::to($user->email)->queue(new WelcomeEmail($user));

        Auth::login($user);

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Account created successfully',
            'user' => $user
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        Auth::login($result['user']);

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login successful',
            'user' => $result['user']
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function switchWorkspace(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);

       /** @var \App\Models\User $user */
        $user = Auth::user();


        // Problem: What if I try to switch to a company ID I don't belong to?
        $belongsToCompany = $user->companies()->where('company_id', $request->company_id)->exists();

        if (!$belongsToCompany) {
            return response()->json(['message' => 'You do not have access to this workspace.'], 403);
        }

        // Update the "Current" context
        $user->update([
            'current_company_id' => $request->company_id
        ]);

        // Clear the Redis cache for this user's roles
        // Why? Because their role in Company A might be 'Owner', 
        // but in Company B they might only be a 'Member'.
        $user->forgetCachedPermissions();

        return response()->json([
            'message' => 'Workspace switched successfully',
            'current_company_id' => $user->current_company_id,
            'user' => $user->load('currentCompany') // Give Vue.js the new company details
        ]);
    }
}
