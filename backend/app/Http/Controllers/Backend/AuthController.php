<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Auth;

class AuthController extends Controller
{
    use ThrottlesLogins; // para que la sesion permanezca

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);//solamente los invitados pueden acceder aqui, un admin no podra hacerlo ya que se redireccionara al incio, a menos que sea al logout
    }
    
    public function getLogin()
    {
        $page_data['page_title'] = 'Log in';
        
        return view('backend.auth.login',$page_data);
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|max:100',
            'password' => 'required|max:20'
        ]);
        
        $remember_user = true;
        
        if(Auth::attempt(['username' => $request->username, 'password' => $request->password],$remember_user))
        {
            $previous_url_cookie = \Request::cookie('previous_admin_url');
            
            if(!is_null($previous_url_cookie) && strpos($previous_url_cookie, '/admin/') !== false)
            {
                return redirect($previous_url_cookie);
            }
            else
            {
                return redirect(route('dashboard'));
            }
        }
        else
        {
            flash('¡Authentication Error!','warning');
            return redirect(route('login'));
        }
    }
    
    public function getLogout()
    {
        Auth::logout();
        flash('¡Log out!','info');
        return redirect(route('login'));
    }
}
