<?php

namespace App\Http\Middleware;

use Closure;

class notAdmin
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
        if(empty($_SESSION['userdata']) || $_SESSION['userdata']['role'] == 'admin')
            return redirect(url('/admin'));      
        else
            return $next($request);
    }
}
