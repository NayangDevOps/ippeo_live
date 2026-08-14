<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return redirect()->route('admin.login')->with('error', 'Please sign in to continue.');
        }

        return $next($request);
    }
}
