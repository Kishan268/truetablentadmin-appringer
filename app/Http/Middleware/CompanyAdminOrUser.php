<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CompanyAdminOrUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()->roles[0]['name'] == config('access.users.company_admin_role') || Auth::user()->roles[0]['name'] == config('access.users.company_user_role')) {
            return $next($request);
        }
        return redirect()->route('frontend.index');
    }
}
