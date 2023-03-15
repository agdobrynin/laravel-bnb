<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request): ?string
    {
        if ($request->routeIs('verification.verify')) {
            $params = [...$request->query(), ...$request->route()->parameters()];

            return '/login?verification.verify&'.http_build_query($params);
        }

        if (! $request->expectsJson()) {
            return route('login');
        }

        return null;
    }
}
