<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Only run this if a user is actually logged in
        if ($user) {

            // 2. If they don't have a current_company_id set, they shouldn't be in the dashboard
            if (!$user->current_company_id) {
                return redirect()->route('login')->with('error', 'Please select a workspace.');
            }

            // 3. Security Check: Does the user actually belong to the company they claim to be in?
            $exists = $user->companies()
                ->where('company_id', $user->current_company_id)
                ->exists();

            if (!$exists) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Unauthorized workspace access.');
            }
        }
        return $next($request);
    }
}
