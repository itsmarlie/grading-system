<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizeInput
{
    // Fields that should NEVER be stripped (passwords, tokens)
    protected array $exempt = [
        'password',
        'password_confirmation',
        'current_password',
        'temp_password',
        '_token',
    ];

    public function handle(Request $request, Closure $next): mixed
    {
        $input = $request->all();
        array_walk_recursive($input, function (&$value, $key) {
            if (!in_array($key, $this->exempt) && is_string($value)) {
                // Strip tags and encode special HTML characters
                $value = htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
            }
        });
        $request->merge($input);

        return $next($request);
    }
}