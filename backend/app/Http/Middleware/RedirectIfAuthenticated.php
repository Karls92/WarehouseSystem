<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use URL;

class RedirectIfAuthenticated
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;
    
    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->check())
        {
            if (strpos(URL::previous(), '/admin/') === false)
            {
                return redirect(route('dashboard'));
            }
            else
            {
                return redirect(URL::previous());
            }
        }
        
        if ($this->auth->viaRemember())
        {
            return redirect(route('dashboard'));
        }
        
        return $next($request);
    }
}
