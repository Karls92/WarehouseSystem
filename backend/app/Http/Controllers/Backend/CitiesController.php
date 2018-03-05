<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\City;

class CitiesController extends Controller
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
     * Gestionar Ciudades
     */
    
    #listar ciudades
    public function index($slug = null)
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Cities',
                'url'  => route('city.index'),
            ),
            array(
                'name' => 'Lists',
            ),
        );
        
        $page_data['page_title']    = 'Lists of Cities';
        $page_data['active_module'] = $this->module;
        $page_data['active_submodule'] = 'cities';
        $page_data['per_page']      = $this->per_page;
        
        if(is_null($slug))
        {
            $page_data['cities'] = City::limit($this->per_page)
                                       ->orderBy('created_at', 'desc')
                                       ->get();
            
            $page_data['cities_qty'] = City::count();
        }
        else
        {
            $page_data['cities'] = City::where('slug', '=', $slug)
                                       ->get();
            
            if(count($page_data['cities']) == 0)
            {
                flash('¡The City you are searching does not exist!', 'danger');
                
                return redirect(route('city.index'));
            }
            
            $page_data['cities_qty'] = 1;
        }
        
        return view('backend.city.index', $page_data);
    }
    
    #agregar ciudad
    public function add()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Cities',
                'url'  => route('city.index'),
            ),
            array(
                'name' => 'Add',
            ),
        );
        
        $page_data['page_title']    = 'Add new City';
        $page_data['active_module'] = $this->module;
        $page_data['active_submodule'] = 'cities';
        $page_data['form_type']     = 'success';
        $page_data['states'] = State::all();
        
        return view('backend.city.add', $page_data);
    }
    
    #guardar ciudad
    public function store(Request $request)
    {
        $city = new City();
        
        #valores que podrían cambiar con el respectivo formato, y ademas son únicos.
        
        $this->validate($request, $city->rules());
        
        $city->name  = camel_case_text(only_text($request->name));
        $city->state_id = $request->state_id;
        
        #creacion del slug
        $slug  = slug($city->name);
        $count = 2;
        
        while(City::where('slug', '=', $slug)->count() > 0)
        {
            $slug = slug($city->name.' '.$count);
            $count++;
        }
        
        $city->slug = $slug;
        
        if(secureSave($city))
        {
            $data = array(
                'activity' => 'You have added the city <a href="'.route('city.update', ['slug' => $city->slug]).'">'.$city->name.'</a>',
                'icon'     => $this->icon.' bg-green',
            );
            
            add_recent_activity($data);
            
            flash('¡It has saved the information successfuly!', 'success');
        }
        else
        {
            flash('¡It has happened an error saving the information!', 'danger');
        }
        
        return redirect(route('city.index'));
    }
    
    #editar ciudad
    public function edit($slug = null)
    {
        $page_data['city'] = City::where('slug', '=', $slug)->first();
        
        if(!is_null($page_data['city']))
        {
            $page_data['breadcrumb'] = array(
                array(
                    'name' => 'Cities',
                    'url'  => route('city.index'),
                ),
                array(
                    'name' => $page_data['city']->name,
                    'url'  => route('city.update', ['slug' => $page_data['city']->slug]),
                ),
                array(
                    'name' => 'Edit',
                ),
            );
            
            $page_data['page_title']    = 'Edit City';
            $page_data['active_module'] = $this->module;
            $page_data['active_submodule'] = 'cities';
            $page_data['form_type']     = 'primary';
            $page_data['states'] = State::all();
            
            return view('backend.city.edit', $page_data);
        }
        else
        {
            flash('¡The city you want to edit does not exist!', 'danger');
            
            return redirect(route('city.index'));
        }
    }
    
    #actualizar ciudad
    public function update(Request $request, $slug = null)
    {
        $city = City::where('slug', '=', $slug)->first();
        
        if(!is_null($city))
        {
            $old_values = $city->attributesToArray();
            $new_values = $request->except('_token');
            
            if(count(array_diff_assoc($new_values, $old_values)) > 0)
            {
                #son valores que podrían cambiar con el respectivo formato, y ademas son únicos.
                
                $this->validate($request, $city->rules());
                
                $city->name  = camel_case_text(only_text($request->name));
                $city->state_id = $request->state_id;
                
                #creacion del slug
                $slug  = slug($city->name);
                $count = 2;
                
                while(City::where('slug', '=', $slug)->where('id', '!=', $city->id)->count() > 0)
                {
                    $slug = slug($city->name.' '.$count);
                    $count++;
                }
                
                $city->slug = $slug;
                
                if(secureSave($city))
                {
                    $data = array(
                        'activity' => 'You have edited the city <a href="'.route('city.update', ['slug' => $city->slug]).'">'.$city->name.'</a>',
                        'icon'     => $this->icon.' bg-blue',
                    );
                    
                    add_recent_activity($data);
                    
                    flash('¡It has saved the information successfuly!', 'success');
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
        }
        else
        {
            flash('¡The city you want to edit does not exist!', 'danger');
        }
        
        return redirect(route('city.index'));
    }
    
    #eliminar ciudad
    public function delete($id = null)
    {
        $city = City::find($id);
        
        if(!is_null($city))
        {
            if(secureDelete($city))
            {
                $data = array(
                    'activity' => 'You have delete the city <a href="#">'.$city->name.'</a>',
                    'icon'     => $this->icon.' bg-red',
                );
                
                add_recent_activity($data);
                
                flash('¡The city '.$city->name.'has been erased successfuly!', 'success');
            }
            else
            {
                flash('¡It has happened an unknown error deleting the city '.$city->name.'!', 'danger');
            }
        }
        else
        {
            flash('¡The city you want to delete does not exist in the system!', 'danger');
        }
        
        return redirect(route('city.index'));
    }
}