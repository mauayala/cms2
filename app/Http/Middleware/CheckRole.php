<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if(\Auth::check()){
            if (in_array(\Auth::user()->role, $roles))
                return $next($request);
            elseif($request->path() == 'dashboard')
                return redirect('/dashboard/users');
            else
                return redirect('404');
        } else {
            return redirect('/');
        }
    }
}
