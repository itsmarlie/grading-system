<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        if (
            auth()->check() &&
            auth()->user()->force_password_change &&
            !$request->routeIs('profile.edit', 'profile.password', 'logout')
        ) {
            return redirect()->route('profile.edit')
                ->with('warning', 'You must change your temporary password before continuing.');
        }

        return $next($request);
    }
}