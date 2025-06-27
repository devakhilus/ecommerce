<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfVerifiedOrAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && ($user->is_admin || $user->hasVerifiedEmail())) {
            return $next($request);
        }

        return redirect()->route('verification.notice');
    }
}
