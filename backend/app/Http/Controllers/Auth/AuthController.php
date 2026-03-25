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

    public function __construct(protected AuthService $authService){}

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

}
