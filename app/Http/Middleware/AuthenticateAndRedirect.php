<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateAndRedirect extends \Illuminate\Auth\Middleware\Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        try {
            $this->authenticate($guards);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            $request->session()->flash('redirect', $request->getRequestUri());
            return redirect(route('login'));
        }

        return $next($request);
    }
}
