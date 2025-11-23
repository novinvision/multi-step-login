<?php

namespace NovinVision\MultiStepLogin\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $field = 'mobile'): Response
    {
        if(empty($request->user()->{$field."_verified_at"})) {
            return redirect()->route("verify", [
                'field' => $field,
                'redirect' => urlencode($request->fullUrl()),
            ]);
        }

        return $next($request);
    }
}
