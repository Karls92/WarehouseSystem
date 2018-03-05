<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller
{
    private $icon;
    private $module;
    private $per_page;
    
    public function __construct()
    {
        $this->icon     = 'fa fa-user';
        $this->module   = 'users';
        $this->per_page = 100;
    }
    
    /**
     * Gestionar Usuarios
     */
    public function index()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Users',
                'url'  => route('users.index'),
            ),
            array(
                'name' => 'Lists',
            ),
        );
        
        $page_data['page_title']    = 'List of Users';
        $page_data['active_module'] = $this->module;
        $page_data['per_page']      = $this->per_page;
        $page_data['users']         = User::where('id', '!=', \Auth::user()->id)
                                          ->limit($this->per_page)
                                          ->orderBy('id', 'desc')
                                          ->get();
        $page_data['users_qty']     = User::where('id', '!=', \Auth::user()->id)->count();
        
        return view('backend.user.index', $page_data);
    }
    
    public function add()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Users',
                'url'  => route('users.index'),
            ),
            array(
                'name' => 'Add',
            ),
        );
        
        $page_data['page_title']    = 'Add Users';
        $page_data['active_module'] = $this->module;
        $page_data['form_type']     = 'success';
        
        return view('backend.user.add', $page_data);
    }
    
    public function store(Request $request)
    {
        $user = new User();
        
        #son valores que podrían cambiar con el respectivo formato, y ademas son únicos.
        $request->merge(
            [
                'username' => preg_replace('/[^a-z0-9_-]/', '', strtolower($request->username)),
                'email' => strtolower(trim($request->email)),
                'phone' => str_replace('_','',$request->phone),
            ]);
        
        $this->validate($request, $user->rules());
        
        $user->username   = $request->username;
        $user->first_name = camel_case_text(only_text($request->first_name), 3);
        $user->last_name  = camel_case_text(only_text($request->last_name), 3);
        $user->phone      = $request->phone;
        $user->email      = $request->email;
        $user->type       = $request->type;
        $user->level      = $request->level;
        
        if (secureSave($user))
        {
            $information_saved = true;
            
            if (!is_null($request->file('image')))
            {
                if (permitted_extension($request->file('image')->getClientOriginalExtension(), ['png', 'jpg', 'jpeg']))
                {
                    $new_image = $user->username.'.'.strtolower($request->file('image')->getClientOriginalExtension());
                    
                    if (resize_image($request->file('image')->getRealPath(), 'img/users', $new_image, 215, 215))
                    {
                        $user->image = $new_image;
                        
                        if (secureSave($user))
                        {
                            $file_saved = true;
                        }
                        else
                        {
                            delete_file('img/users', $new_image);
                            
                            flash('¡It has saved the information successfuly, pero ha ocurrido un error subiendo la imagen!', 'warning');
                            
                            $file_saved    = false;
                            $flash_message = true;
                        }
                    }
                }
                else
                {
                    flash('¡It has saved the information successfuly, pero la imagen no tiene una extensión permitida!', 'warning');
                    
                    $file_saved    = false;
                    $flash_message = true;
                }
            }
        }
        else
        {
            $information_saved = false;
            $flash_message     = true;
            
            flash('¡Ha ocurrido un problema guardando la información!', 'danger');
        }
        
        if (isset($information_saved) && $information_saved || isset($file_saved) && $file_saved)
        {
            $data = array(
                'activity' => 'ha agregado al usuario <a href="'.route('profile.details', ['username' => $user->username]).'">'.$user->full_name.'</a>',
                'icon'     => $this->icon.' bg-green',
            );
            
            add_recent_activity($data);
            
            if (!isset($flash_message) || isset($file_saved) && $file_saved)
            {
                flash('¡It has saved the information successfuly!', 'success');
            }
        }
        
        return redirect(route('users.index'));
    }
    
    public function edit($username = null)
    {
        $page_data['user'] = User::where('username', $username)->first();
        
        if (!is_null($page_data['user']))
        {
            $page_data['breadcrumb'] = array(
                array(
                    'name' => 'Users',
                    'url'  => route('users.index'),
                ),
                array(
                    'name' => $page_data['user']->full_name,
                    'url'  => route('profile.details', ['username' => $page_data['user']->username]),
                ),
                array(
                    'name' => 'Edit',
                ),
            );
            
            $page_data['page_title']    = 'Edit User';
            $page_data['active_module'] = $this->module;
            $page_data['form_type']     = 'primary';
            
            return view('backend.user.edit', $page_data);
        }
        else
        {
            flash('¡No existe el usuario a editar!', 'danger');
            
            return redirect(route('users.index'));
        }
    }
    
    public function update(Request $request, $username = null)
    {
        $user = User::where('username', $username)->first();
        
        if (!is_null($user))
        {
            $old_values = $user->attributesToArray();
            $new_values = $request->except('_token');
            
            if (count(array_diff_assoc($new_values, $old_values)) > 0 && (is_null($request->file('image')) || count(array_diff_assoc($new_values, $old_values)) > 1))
            {
                #son valores que podrían cambiar con el respectivo formato, y ademas son únicos.
                $request->merge(
                    [
                        'username' => preg_replace('/[^a-z0-9_-]/', '', strtolower($request->username)),
                        'email' => strtolower(trim($request->email)),
                        'phone' => str_replace('_','',$request->phone),
                    ]);
                
                $this->validate($request, $user->rules());
                
                $user->username   = $request->username;
                $user->first_name = camel_case_text(only_text($request->first_name), 3);
                $user->last_name  = camel_case_text(only_text($request->last_name), 3);
                $user->phone      = $request->phone;
                $user->email      = $request->email;
                $user->type       = $request->type;
                $user->level      = $request->level;
                
                if (secureSave($user))
                {
                    $information_saved = true;
                }
                else
                {
                    flash('¡Ha ocurrido un problema guardando la información!', 'danger');
                    
                    $information_saved = false;
                    $flash_message     = true;
                }
            }
            else
            {
                flash('¡No se produjo ningún cambio!', 'info');
                
                $flash_message = true;
            }
            
            if (!is_null($request->file('image')) && (isset($information_saved) && $information_saved || !isset($information_saved)))
            {
                if (permitted_extension($request->file('image')->getClientOriginalExtension(), ['png', 'jpg', 'jpeg']))
                {
                    $new_image = $user->username.'.'.strtolower($request->file('image')->getClientOriginalExtension());
                    $old_image = $user->image;
                    
                    if (resize_image($request->file('image')->getRealPath(), 'img/users', $new_image, 215, 215))
                    {
                        if ($new_image != $old_image)
                        {
                            $user->image = $new_image;
                            
                            if (secureSave($user))
                            {
                                delete_file('img/users', $old_image);
                                
                                $file_saved = true;
                            }
                            else
                            {
                                delete_file('img/users', $new_image);
                                
                                if (isset($information_saved) && $information_saved)
                                {
                                    flash('¡Se ha guardado la información correctamente, pero ha ocurrido un error subiendo la imagen!', 'warning');
                                }
                                else
                                {
                                    flash('¡Ha ocurrido un error subiendo la imagen!', 'warning');
                                }
                                
                                $file_saved    = false;
                                $flash_message = true;
                            }
                        }
                        else
                        {
                            $file_saved = true;
                        }
                    }
                }
                else
                {
                    if (isset($information_saved) && $information_saved)
                    {
                        flash('¡Se ha guardado la información correctamente, pero la imagen no tiene una extensión permitida!', 'warning');
                    }
                    else
                    {
                        flash('¡La imagen no tiene una extensión permitida!', 'danger');
                    }
                    
                    $file_saved    = false;
                    $flash_message = true;
                }
            }
            
            if (isset($information_saved) && $information_saved || isset($file_saved) && $file_saved)
            {
                $data = array(
                    'activity' => 'ha editado al usuario <a href="'.route('profile.details', ['username' => $user->username]).'">'.$user->full_name.'</a>',
                    'icon'     => $this->icon.' bg-blue',
                );
                
                add_recent_activity($data);
                
                if (!isset($flash_message) || isset($file_saved) && $file_saved)
                {
                    flash('¡Se ha guardado la información correctamente!', 'success');
                }
            }
        }
        else
        {
            flash('¡No existe el usuario a editar!', 'danger');
        }
        
        return redirect(route('users.index'));
    }
    
    public function delete($id = null)
    {
        $user = User::find($id);
        
        if (!is_null($user))
        {
            if (secureDelete($user))
            {
                $data = array(
                    'activity' => 'ha eliminado al usuario <a href="#">'.$user->full_name.'</a>',
                    'icon'     => $this->icon.' bg-red',
                );
                
                add_recent_activity($data);
                delete_file('img/users', $user->image);
                
                flash('¡El Usuario '.$user->full_name.' ha sido exitosamente eliminado!', 'success');
            }
            else
            {
                flash('¡Ha ocurrido un error desconocido eliminando al usuario '.$user->full_name.'!', 'danger');
            }
        }
        else
        {
            flash('¡El usuario a eliminar no ha sido encontrado!', 'danger');
        }
        
        return redirect(route('users.index'));
    }
    
    /**
     * Editar Perfil de Usuario
     */
    public function profile()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Profile',
                'url'  => route('profile.edit'),
            ),
            array(
                'name' => 'Edit',
            ),
        );
        
        $page_data['page_title']              = 'Edit Profile';
        $page_data['form_type']               = 'primary';
        $page_data['user']                    = User::find(\Auth::user()->id);
        $page_data['personalize_form_script'] = true;
        
        return view('backend.user.profile', $page_data);
    }
    
    public function update_profile(Request $request)
    {
        $user = User::where('id', \Auth::user()->id)->first();
        
        $old_values = $user->attributesToArray();
        $new_values = $request->except('_token');
        
        if (isset($request->password) && strlen($request->password) > 0 && isset($request->repeat_password) && strlen($request->repeat_password) > 0 && isset($request->current_password) && strlen($request->current_password) > 0)
        {
            if ($request->password != $request->repeat_password)
            {
                $change_password  = false;
                $process_continue = false;
                
                flash('¡The passwords are not the same. Check it out!', 'danger');
            }
            else
            {
                if (\Hash::check($request->current_password, \Auth::user()->password))
                {
                    if ($request->current_password != $request->password)
                    {
                        $change_password  = true;
                        $process_continue = true;
                    }
                    else
                    {
                        unset($new_values['password']);
                        unset($new_values['repeat_password']);
                        unset($new_values['current_password']);
                        
                        $change_password  = false;
                        $process_continue = true;
                    }
                }
                else
                {
                    $change_password  = false;
                    $process_continue = false;
                    
                    flash('¡The actual password is incorrect!', 'danger');
                }
            }
        }
        else
        {
            unset($new_values['password']);
            unset($new_values['repeat_password']);
            unset($new_values['current_password']);
            
            $change_password  = false;
            $process_continue = true;
        }
        
        if ($process_continue)
        {
            if (count(array_diff_assoc($new_values, $old_values)) > 0 && (is_null($request->file('image')) || count(array_diff_assoc($new_values, $old_values)) > 1))
            {
                #son valores que podrían cambiar con el respectivo formato, y ademas son únicos.
                $request->merge(
                    [
                        'username' => preg_replace('/[^a-z0-9_-]/', '', strtolower($request->username)),
                        'email' => strtolower(trim($request->email)),
                        'phone' => str_replace('_','',$request->phone),
                    ]);
                
                $this->validate($request, $user->rules());
                
                $user->username   = $request->username;
                $user->first_name = camel_case_text(only_text($request->first_name), 3);
                $user->last_name  = camel_case_text(only_text($request->last_name), 3);
                $user->phone      = $request->phone;
                $user->email      = $request->email;
                
                if ($change_password)
                {
                    $user->password = bcrypt($request->password);
                }
                
                if (secureSave($user))
                {
                    $information_saved = true;
                    
                    if ($change_password)
                    {
                        \Auth::user()->password = $user->password;
                    }
                }
                else
                {
                    flash('¡Ha ocurrido un problema guardando la información!', 'danger');
                    
                    $information_saved = false;
                    $flash_message     = true;
                }
            }
            else
            {
                flash('¡No se produjo ningún cambio!', 'info');
                
                $flash_message = true;
            }
            
            if ($process_continue && !is_null($request->file('image')) && (isset($information_saved) && $information_saved || !isset($information_saved)))
            {
                if (permitted_extension($request->file('image')->getClientOriginalExtension(), ['png', 'jpg', 'jpeg']))
                {
                    $new_image = $user->username.'.'.strtolower($request->file('image')->getClientOriginalExtension());
                    $old_image = $user->image;
                    
                    if (resize_image($request->file('image')->getRealPath(), 'img/users', $new_image, 215, 215))
                    {
                        if ($new_image != $old_image)
                        {
                            $user->image = $new_image;
                            
                            if (secureSave($user))
                            {
                                delete_file('img/users', $old_image);
                                
                                $file_saved = true;
                            }
                            else
                            {
                                delete_file('img/users', $new_image);
                                
                                if (isset($information_saved) && $information_saved)
                                {
                                    flash('¡Se ha guardado la información correctamente, pero ha ocurrido un error subiendo la imagen!', 'warning');
                                }
                                else
                                {
                                    flash('¡Ha ocurrido un error subiendo la imagen!', 'warning');
                                }
                                
                                $file_saved    = false;
                                $flash_message = true;
                            }
                        }
                        else
                        {
                            $file_saved = true;
                        }
                    }
                }
                else
                {
                    if (isset($information_saved) && $information_saved)
                    {
                        flash('¡Se ha guardado la información correctamente, pero la imagen no tiene una extensión permitida!', 'warning');
                    }
                    else
                    {
                        flash('¡La imagen no tiene una extensión permitida!', 'danger');
                    }
                    
                    $file_saved    = false;
                    $flash_message = true;
                }
            }
            
            if (isset($information_saved) && $information_saved || isset($file_saved) && $file_saved)
            {
                $data = array(
                    'activity' => 'You have edited your <a href="#">Profile</a>',
                    'icon'     => $this->icon.' bg-blue',
                );
                
                add_recent_activity($data);
                
                if (!isset($flash_message) || isset($file_saved) && $file_saved)
                {
                    flash('¡It has saved the information successfuly!', 'success');
                }
            }
        }
        
        return redirect(route('profile.edit'));
    }
    
    /**
     * Detalles de Usuario
     */
    public function details($username = null)
    {
        $page_data['user'] = User::where('username', $username)->first();
        
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Profile',
                'url'  => route('profile.details', ['username' => strtolower($username)]),
            ),
            array(
                'name' => ($page_data['user']) ? $page_data['user']->full_name : 'Not found',
            ),
        );
        
        $page_data['page_title'] = 'Details of Profile';
        
        return view('backend.user.details', $page_data);
    }
}
