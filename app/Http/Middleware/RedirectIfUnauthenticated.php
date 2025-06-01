<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfUnauthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (!Auth::guard($guard)->check()) {
                $ssoLoginUrl = config('sso.login_url');
                $redirectUrl = $ssoLoginUrl . '?site='.config('app.name').'&redirect=' . urlencode(route('auth'));

                return redirect()->away($redirectUrl);
            }
        }

        return $next($request);
    }
}
