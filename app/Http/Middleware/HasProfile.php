<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasProfile
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (is_null($user)) {
            return redirect('/login');
        }
        if (is_null($user['name']) || is_null($user['avatar']) || str_contains($user['avatar'], 'avatar-0')) {
            return redirect('/on-boarding-profile');
        }
        return $next($request);
    }
}
