<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\PanelConfig;
use App\Models\SiteConfig;

class ConfigurationsController extends Controller
{
    private $module;
    private $icon;
    
    public function __construct()
    {
        $this->module = 'site_config';
        $this->icon   = 'fa fa-tachometer bg-blue';
    }
    
    /**
     * Logo
     */
    public function logo()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Settings',
                'url'  => route('settings.logo'),
            ),
            array(
                'name' => 'Logo',
            ),
        );
        
        $page_data['page_title']       = 'Logo Settings';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'logo';
        $page_data['form_type']        = 'primary';
        
        return view('backend.configuration.logo', $page_data);
    }
    
    public function update_logo(Request $request)
    {
        if (!is_null($request->file('image')))
        {
            if(permitted_extension($request->file('image')->getClientOriginalExtension(),['png']))
            {
                if(resize_image($request->file('image')->getRealPath(),'img','logo.'.strtolower($request->file('image')->getClientOriginalExtension()),300))
                {
                    $data = array(
                        'activity' => 'you have edited the <a href="'.route('settings.logo').'">Logo</a> of the Website',
                        'icon'     => $this->icon,
                    );
        
                    add_recent_activity($data);
        
                    flash('¡The image has been saved successfuly, You must wait a few hours to check the changes!', 'success');
                }
            }
            else
            {
                flash('¡The image does not have an allowed format!', 'danger');
            }
        }
        else
        {
            flash('¡There is not any image saved!', 'info');
        }
        
        return redirect(\URL::previous());
    }
    
    /**
     * Panel de Administración
     */
    public function administrative_panel()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Settings',
                'url'  => route('settings.panel'),
            ),
            array(
                'name' => 'Admin Panel',
            ),
        );
        
        $page_data['page_title']       = 'Administrative Panel Settings';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'administrative_panel';
        $page_data['form_type']        = 'primary';
        
        return view('backend.configuration.administrative_panel', $page_data);
    }
    
    public function update_administrative_panel(Request $request)
    {
        $panel_config = PanelConfig::where('user_id', \Auth::user()->id)->first();
        
        $old_values = $panel_config->attributesToArray();
        $new_values = $request->except('_token');
        
        if (count(array_diff_assoc($new_values, $old_values)) > 0)
        {
            $this->validate($request, $panel_config->rules());
            
            $panel_config->screen      = $request->screen;
            $panel_config->breadcrumb  = $request->breadcrumb;
            $panel_config->box_design  = $request->box_design;
            $panel_config->theme_color = $request->theme_color;
            
            if (secureSave($panel_config))
            {
                flash('¡Panel Setting has been saved successfuly!', 'success');
            }
            else
            {
                flash('¡It has happened an error saving the information!', 'danger');
            }
        }
        else
        {
            flash('¡There is not any changes!', 'info');
        }
        
        return redirect(\URL::previous());
    }
    
    /**
     * Redes Sociales
     */
    public function social_networks()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Setting',
                'url'  => route('settings.social_networks'),
            ),
            array(
                'name' => 'Social network',
            ),
        );
        
        $page_data['page_title']       = 'Social network setting';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'social_networks';
        $page_data['form_type']        = 'primary';
        $page_data['social_network']   = SiteConfig::find(1);
        
        return view('backend.configuration.social_networks', $page_data);
    }
    
    public function update_social_networks(Request $request)
    {
        $social_networks = SiteConfig::where('id', \Auth::user()->id)->first();
        
        $old_values = $social_networks->attributesToArray();
        $new_values = $request->except('_token');
        
        if (count(array_diff_assoc($new_values, $old_values)) > 0)
        {
            $this->validate($request, $social_networks->rules());
            
            $social_networks->social_facebook = $request->social_facebook;
            $social_networks->social_twitter = $request->social_twitter;
            $social_networks->social_instagram = $request->social_instagram;
            $social_networks->social_youtube = $request->social_youtube;
            $social_networks->social_google_plus = $request->social_google_plus;
            $social_networks->social_mercado_libre = $request->social_mercado_libre;
            
            if (secureSave($social_networks))
            {
                $data = array(
                    'activity' => 'ha editado la configuración de las <a href="'.route('settings.social_networks').'">Redes Sociales</a>',
                    'icon'     => $this->icon,
                );
                
                add_recent_activity($data);
                
                flash('¡La configuración de las redes sociales ha sido exitosamente guardada!', 'success');
            }
            else
            {
                flash('¡Ha ocurrido un problema guardando la información!', 'danger');
            }
        }
        else
        {
            flash('¡No se produjo ningún cambio!', 'info');
        }
        
        return redirect(\URL::previous());
    }
    
    /**
     * Contacto
     */
    public function contact()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Setting',
                'url'  => route('settings.contact'),
            ),
            array(
                'name' => 'Contact',
            ),
        );
        
        $page_data['page_title']       = 'Contact setting';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'contact';
        $page_data['form_type']        = 'primary';
        $page_data['personalize_form_script'] = true;
        $page_data['contact']   = SiteConfig::find(1);
        
        return view('backend.configuration.contact', $page_data);
    }
    
    public function update_contact(Request $request)
    {
        $contact = SiteConfig::where('id', \Auth::user()->id)->first();
        
        $old_values = $contact->attributesToArray();
        $new_values = $request->except('_token');
    
        if(isset($request->password_email) && strlen($request->password_email) > 0 && isset($request->repeat_password) && strlen($request->repeat_password) > 0 && isset($request->current_password) && strlen($request->current_password) > 0 && isset($request->user_password) && strlen($request->user_password) > 0)
        {
            if($request->password_email != $request->repeat_password)
            {
                $change_password = false;
                $process_continue = false;
            
                flash('¡Las contraseñas no coinciden!', 'danger');
            }
            else
            {
                if($request->current_password != $contact->password_email)
                {
                    $change_password = false;
                    $process_continue = false;
    
                    flash('¡Las contraseña actual es incorrecta!', 'danger');
                }
                else
                {
                    if(\Hash::check($request->user_password,\Auth::user()->password))
                    {
                        if($request->current_password != $request->password_email)
                        {
                            $change_password = true;
                            $process_continue = true;
                        }
                        else
                        {
                            unset($new_values['password_email']);
                            unset($new_values['repeat_password']);
                            unset($new_values['current_password']);
                            unset($new_values['user_password']);
            
                            $change_password = false;
                            $process_continue = true;
                        }
                    }
                    else
                    {
                        $change_password = false;
                        $process_continue = false;
        
                        flash('¡Tu contraseña es incorrecta!', 'danger');
                    }
                }
            }
        }
        else
        {
            unset($new_values['password_email']);
            unset($new_values['repeat_password']);
            unset($new_values['current_password']);
            unset($new_values['user_password']);
        
            $change_password = false;
            $process_continue = true;
        }
    
        if($process_continue)
        {
            if (count(array_diff_assoc($new_values, $old_values)) > 0)
            {
                $this->validate($request, $contact->rules());
        
                $contact->smtp_host = $request->smtp_host;
                $contact->smtp_port = $request->smtp_port;
                $contact->email = $request->email;
    
                if($change_password)
                {
                    $contact->password_email = $request->password_email;
                }
        
                if (secureSave($contact))
                {
                    $data = array(
                        'activity' => 'ha editado la configuración del <a href="'.route('settings.contact').'">Contacto</a> de la Página Web',
                        'icon'     => $this->icon,
                    );
            
                    add_recent_activity($data);
            
                    flash('¡La configuración del contacto de la página web ha sido exitosamente guardada!', 'success');
                }
                else
                {
                    flash('¡Ha ocurrido un problema guardando la información!', 'danger');
                }
            }
            else
            {
                flash('¡No se produjo ningún cambio!', 'info');
            }
        }
        
        return redirect(\URL::previous());
    }
}
