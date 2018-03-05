<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\City;

class StatesController extends Controller
{
    private $icon;
    private $module;
    private $per_page;
    
    public function __construct()
    {
        $this->module   = 'locations';
        $this->icon     = site_config()['lateral_elements'][$this->module]['details']['icon'];
        $this->per_page = 100;
    }
    
    /**
     * Gestionar Estados
     */
    
    #listar estados
    public function index($slug = null)
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'States',
                'url'  => route('state.index'),
            ),
            array(
                'name' => 'Lists',
            ),
        );
        
        $page_data['page_title']    = 'Lists of states';
        $page_data['active_module'] = $this->module;
        $page_data['active_submodule'] = 'states';
        $page_data['per_page']      = $this->per_page;
        
        if(is_null($slug))
        {
            $page_data['states'] = State::limit($this->per_page)
                                        ->orderBy('created_at', 'desc')
                                        ->get();
            
            $page_data['states_qty'] = State::count();
        }
        else
        {
            $page_data['states'] = State::where('slug', '=', $slug)
                                        ->get();
            
            if(count($page_data['states']) == 0)
            {
                flash('¡El Estado ha buscar no existe!', 'danger');
                
                return redirect(route('state.index'));
            }
            
            $page_data['states_qty'] = 1;
        }
        
        return view('backend.state.index', $page_data);
    }
    
    #agregar estado
    public function add()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'States',
                'url'  => route('state.index'),
            ),
            array(
                'name' => 'Add',
            ),
        );
        
        $page_data['page_title']    = 'Add new state';
        $page_data['active_module'] = $this->module;
        $page_data['active_submodule'] = 'states';
        $page_data['form_type']     = 'success';
        
        return view('backend.state.add', $page_data);
    }
    
    #guardar estado
    public function store(Request $request)
    {
        $state = new State();
        
        $this->validate($request, $state->rules());
        
        $state->name  = camel_case_text(only_text($request->name));
        
        #creacion del slug
        $slug  = slug($state->name);
        $count = 2;
        
        while(State::where('slug', '=', $slug)->count() > 0)
        {
            $slug = slug($state->name.' '.$count);
            $count++;
        }
        
        $state->slug = $slug;
        
        if(secureSave($state))
        {
            $data = array(
                'activity' => 'You have added the state <a href="'.route('state.update', ['slug' => $state->slug]).'">'.$state->name.'</a>',
                'icon'     => $this->icon.' bg-green',
            );
            
            add_recent_activity($data);
            
            flash('¡It has saved the information successfuly!', 'success');
        }
        else
        {
            flash('¡Ha ocurrido un problema guardando la información!', 'danger');
        }
        
        return redirect(route('state.index'));
    }
    
    #editar estado
    public function edit($slug = null)
    {
        $page_data['state'] = State::where('slug', '=', $slug)->first();
        
        if(!is_null($page_data['state']))
        {
            $page_data['breadcrumb'] = array(
                array(
                    'name' => 'States',
                    'url'  => route('state.index'),
                ),
                array(
                    'name' => $page_data['state']->name,
                    'url'  => route('state.update', ['slug' => $page_data['state']->slug]),
                ),
                array(
                    'name' => 'Edit',
                ),
            );
            
            $page_data['page_title']    = 'Edit States';
            $page_data['active_module'] = $this->module;
            $page_data['active_submodule'] = 'states';
            $page_data['form_type']     = 'primary';
            
            return view('backend.state.edit', $page_data);
        }
        else
        {
            flash('¡No existe el estado a editar!', 'danger');
            
            return redirect(route('state.index'));
        }
    }
    
    #actualizar estado
    public function update(Request $request, $slug = null)
    {
        $state = State::where('slug', '=', $slug)->first();
        
        if(!is_null($state))
        {
            $old_values = $state->attributesToArray();
            $new_values = $request->except('_token');
            
            if(count(array_diff_assoc($new_values, $old_values)) > 0)
            {
                $this->validate($request, $state->rules());
                
                $state->name  = camel_case_text(only_text($request->name));
                
                #creacion del slug
                $slug  = slug($state->name);
                $count = 2;
                
                while(State::where('slug', '=', $slug)->where('id', '!=', $state->id)->count() > 0)
                {
                    $slug = slug($state->name.' '.$count);
                    $count++;
                }
                
                $state->slug = $slug;
                
                if(secureSave($state))
                {
                    $data = array(
                        'activity' => 'you have edited the state <a href="'.route('state.update', ['slug' => $state->slug]).'">'.$state->name.'</a>',
                        'icon'     => $this->icon.' bg-blue',
                    );
                    
                    add_recent_activity($data);
                    
                    flash('¡It has saved the information successfuly!', 'success');
                }
                else
                {
                    flash('¡Ha ocurrido un problema guardando la información!', 'danger');
                }
            }
            else
            {
                flash('¡There is not any change!', 'info');
            }
        }
        else
        {
            flash('¡No existe el estado a editar!', 'danger');
        }
        
        return redirect(route('state.index'));
    }
    
    #eliminar estado
    public function delete($id = null)
    {
        $state = State::find($id);
        
        if(!is_null($state))
        {
            if(secureDelete($state))
            {
                $data = array(
                    'activity' => 'You have delete the state <a href="#">'.$state->name.'</a>',
                    'icon'     => $this->icon.' bg-red',
                );
                
                add_recent_activity($data);
                
                flash('¡The state '.$state->name.' has been erased successfuly!', 'success');
            }
            else
            {
                flash('¡Ha ocurrido un error desconocido eliminando el estado '.$state->name.'!', 'danger');
            }
        }
        else
        {
            flash('¡El Estado a eliminar no se encuentra en el sistema!', 'danger');
        }
        
        return redirect(route('state.index'));
    }
    
    /*
     * AJAX
     */
    
    public function ajax_cities(Request $request)
    {
        if(isset($request->state_id) && !is_null($request->state_id) && is_numeric($request->state_id))
        {
            $cities = City::selectRaw('id,name')->where('state_id','=',$request->state_id)->get();
            
            if(count($cities) > 0)
            {
                die(json_encode($cities));
            }
            else
            {
                die('ERROR');
            }
        }
        else
        {
            die('ERROR');
        }
    }
}