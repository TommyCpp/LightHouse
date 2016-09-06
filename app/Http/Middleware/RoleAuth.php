<?php

namespace App\Http\Middleware;

use Closure;

class RoleAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        if ($request->user()->hasRole('ADMIN')) {
            return $next($request);
        }
        $roles = explode('|',$roles);
        $validate = false;
        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {
                $validate = true;
            }
        }
        if($validate)
            return $next($request);
        else
            return redirect()->back();
    }
}
