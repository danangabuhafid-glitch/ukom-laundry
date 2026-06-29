<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $userLevel = Auth::user()->level->level_name;
        
        if (!in_array($userLevel, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
