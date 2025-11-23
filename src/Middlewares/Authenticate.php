<?php

namespace NovinVision\MultiStepLogin\Middlewares;

use Illuminate\Auth\AuthenticationException;
use Inertia\Inertia;

class Authenticate extends \Illuminate\Auth\Middleware\Authenticate
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        try {
            parent::handle($request, $next, $guards);
        } catch (AuthenticationException $exception) {
            if ($request->isXmlHttpRequest() && $request->hasHeader('x-inertia')) {
                return Inertia::location($this->redirectTo($request));
            }

            throw $exception;
        }

        return $next($request);
    }

    protected function redirectTo(\Illuminate\Http\Request $request)
    {
        return $request->expectsJson() ? null : route('login', [
            'next' => urlencode($request->url())
        ]);
    }

}
