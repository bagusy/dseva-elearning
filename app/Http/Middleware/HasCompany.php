<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasCompany
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (is_null($user)) {
            return redirect('/login');
        }
        if (is_null($user['company_id'])) {
            return redirect('/on-boarding-company');
        }
        return $next($request);
    }
}
