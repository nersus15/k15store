<?php

namespace App\Http\Middleware;

use Closure;

class isAdmin
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
        if(empty($_SESSION['userdata']) || $_SESSION['userdata']['role'] != 'admin')
            return redirect('/');      
        else
            return $next($request);
    }
}
