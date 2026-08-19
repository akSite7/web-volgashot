<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->approved_at) {
            Auth::logout();

            return redirect()->route('login')->with('status', 'Ваш аккаунт ещё не подтверждён администратором.');
        }

        return $next($request);
    }
}
