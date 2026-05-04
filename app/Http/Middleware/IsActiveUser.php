<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class IsActiveUser
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (is_null($user)) {
            return redirect('/login');
        }
        if ($user['status'] === User::STATUS_INACTIVE) {
            auth()->logout();
            return redirect('/login')->withErrors('Account is not active. Please contact your administrator');
        }
        return $next($request);
    }
}
