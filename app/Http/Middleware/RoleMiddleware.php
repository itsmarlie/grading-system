<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        // Not logged in at all
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Logged in but wrong role
        if (!in_array($user->role, $roles)) {
            // Log the unauthorized attempt
            \Log::warning('Unauthorized access attempt', [
                'user_id' => $user->id,
                'role'    => $user->role,
                'url'     => $request->fullUrl(),
                'ip'      => $request->ip(),
            ]);

            // Redirect to their correct dashboard instead of exposing a 403
            return match($user->role) {
                'student' => redirect()->route('student.dashboard')
                                ->with('error', 'You do not have access to that page.'),
                'teacher' => redirect()->route('dashboard')
                                ->with('error', 'You do not have access to that page.'),
                default   => redirect()->route('dashboard')
                                ->with('error', 'You do not have access to that page.'),
            };
        }

        return $next($request);
    }
}