<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Laracasts\Flash\Flash;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;
    
    /**
     * Create a new middleware instance.
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
        $current_url = \URL::current();
        
        if(strpos($current_url, '/admin/') !== false)
        {
            \Cookie::queue('previous_admin_url',$current_url,5);
        }
        
        if ($this->auth->guest() && !$this->auth->viaRemember())
        {
            if ($request->ajax())
            {
                return response('Unauthorized.', 401);
            }
            else
            {
                flash('¡You must log in!', 'danger');
                
                return redirect(route('login'));
            }
        }
        
        return $next($request);
    }
}
