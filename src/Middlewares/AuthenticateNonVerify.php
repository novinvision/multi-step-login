<?php

namespace NovinVision\MultiStepLogin\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateNonVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $field = 'mobile'): Response
    {
        if(!empty($request->user()->{$field."_verified_at"})) {
            return redirect()->to(urldecode($request->get('redirect')) ?: back()->getTargetUrl());
        }

        return $next($request);
    }
}
