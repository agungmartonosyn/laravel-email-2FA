<?php

namespace App\Http\Middleware;

use Closure;

class TwoFactor
{

    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if(auth()->check() && $user->two_factor_token)
        {
            if($user->two_factor_expiry->lt(now()))
            {
                $user->resetTwoFactorCode();
                auth()->logout();

                return redirect()->route('login')->withMessage('The two factor code has expired. Please login again.');
            }

            if(!$request->is('verify*'))
            {
                return redirect()->route('verify.index');
            }
        }

        return $next($request);
    }
}