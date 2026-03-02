<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function __construct(protected AuthService $authService){}

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        Auth::login($result['user']);

        return response()->json([
            'message' => 'Account created successfully',
            'user' => $result['user']
        ], 201);
    }

    public function login(LoginRequest $request)
    {
       $result = $this->authService->login($request->validated());

       Auth::login($result['user']);

        return response()->json([
            'message' => 'Login successful',
            'user' => $result['user']
        ]);
    }
}
