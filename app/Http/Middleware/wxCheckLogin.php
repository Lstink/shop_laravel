<?php

namespace App\Http\Middleware;

use Closure;

class wxCheckLogin
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
        if (empty(session('wxUserInfo'))) {
            return redirect('/admin/login') -> with(['msg'=>'请登录后查看']);
        }
        return $next($request);
    }
}
