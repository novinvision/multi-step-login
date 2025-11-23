<?php

namespace NovinVision\MultiStepLogin\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class HandleNextUrl
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (($hasNextUrl = session()->get('next_url')) && $request->is($hasNextUrl)) {
            session()->forget('next_url');
        }

        $nextUrl = urldecode(Arr::first($request->only(['redirect', 'next'])) ?: '') ?: null;
        if ($nextUrl) {
            session()->put('next_url', $nextUrl);
        }

        return $next($request);
    }
}
