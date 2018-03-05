<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Classification;

class ClassificationsController extends Controller
{
    private $icon;
    private $module;
    private $per_page;
    
    public function __construct()
    {
        $this->module   = 'products_configuration';
        $this->icon     = site_config()['lateral_elements'][$this->module]['details']['icon'];
        $this->per_page = 100;
    }
    
    /**
     * Gestionar Clasificaciones
     */
    
    #listar clasificaciones
    public function index($slug = null)
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Classifications',
                'url'  => route('classification.index'),
            ),
            array(
                'name' => 'Lists',
            ),
        );
        
        $page_data['page_title']          = 'Lists of Classifications';
        $page_data['active_module']       = $this->module;
        $page_data['active_submodule']    = 'classifications';
        $page_data['per_page']            = $this->per_page;
    
        if(is_null($slug))
        {
            $page_data['classifications']     = Classification::limit($this->per_page)
                                                              ->orderBy('created_at', 'desc')
                                                              ->get();
            $page_data['classifications_qty'] = Classification::count();
        }
        else
        {
            $page_data['classifications'] = Classification::where('slug', '=', $slug)
                                                          ->get();
        
            if(count($page_data['classifications']) == 0)
            {
                flash('¡The classification you are searching does not exist!', 'danger');
            
                return redirect(route('classification.index'));
            }
        
            $page_data['classifications_qty'] = 1;
        }
        
        return view('backend.classification.index', $page_data);
    }
    
    #agregar clasificación
    public function add()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Classifications',
                'url'  => route('classification.index'),
            ),
            array(
                'name' => 'Add',
            ),
        );
        
        $page_data['page_title']       = 'Add new Classifications';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'classifications';
        $page_data['form_type']        = 'success';
        
        return view('backend.classification.add', $page_data);
    }
    
    #guardar clasificación
    public function store(Request $request)
    {
        $classification = new Classification();
        
        $this->validate($request, $classification->rules());
        
        $classification->name = strtoupper(trim($request->name));
        
        #creacion del slug
        $slug  = slug($classification->name);
        $count = 2;
        
        while (Classification::where('slug', '=', $slug)->count() > 0)
        {
            $slug = slug($classification->name.' '.$count);
            $count++;
        }
        
        $classification->slug = $slug;
        
        if (secureSave($classification))
        {
            $data = array(
                'activity' => 'You have added the classification <a href="'.route('classification.update', ['slug' => $classification->slug]).'">'.$classification->name.'</a>',
                'icon'     => $this->icon.' bg-green',
            );
            
            add_recent_activity($data);
            
            flash('¡It has saved the information successfuly!', 'success');
        }
        else
        {
            flash('¡It has happened an error saving the information!', 'danger');
        }
        
        return redirect(route('classification.index'));
    }
    
    #editar clasificación
    public function edit($slug = null)
    {
        $page_data['classification'] = Classification::where('slug', '=', $slug)->first();
        
        if (!is_null($page_data['classification']))
        {
            $page_data['breadcrumb'] = array(
                array(
                    'name' => 'Classifications',
                    'url'  => route('classification.index'),
                ),
                array(
                    'name' => $page_data['classification']->name,
                    'url'  => route('classification.update', ['slug' => $page_data['classification']->slug]),
                ),
                array(
                    'name' => 'Edit',
                ),
            );
            
            $page_data['page_title']       = 'Edit Classifications';
            $page_data['active_module']    = $this->module;
            $page_data['active_submodule'] = 'classifications';
            $page_data['form_type']        = 'primary';
            
            return view('backend.classification.edit', $page_data);
        }
        else
        {
            flash('¡The classification you want to edit does not exist!', 'danger');
            
            return redirect(route('classification.index'));
        }
    }
    
    #actualizar clasificación
    public function update(Request $request, $slug = null)
    {
        $classification = Classification::where('slug', '=', $slug)->first();
        
        if (!is_null($classification))
        {
            $old_values = $classification->attributesToArray();
            $new_values = $request->except('_token');
            
            if (count(array_diff_assoc($new_values, $old_values)) > 0)
            {
                $this->validate($request, $classification->rules());
                
                $classification->name = strtoupper(trim($request->name));
                
                #creacion del slug
                $slug  = slug($classification->name);
                $count = 2;
                
                while (Classification::where('slug', '=', $slug)->where('id', '!=', $classification->id)->count() > 0)
                {
                    $slug = slug($classification->name.' '.$count);
                    $count++;
                }
                
                $classification->slug = $slug;
                
                if (secureSave($classification))
                {
                    $data = array(
                        'activity' => 'You have edited the classification <a href="'.route('classification.update', ['slug' => $classification->slug]).'">'.$classification->name.'</a>',
                        'icon'     => $this->icon.' bg-blue',
                    );
                    
                    add_recent_activity($data);
                    
                    flash('¡ It has saved the information successfuly!', 'success');
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
            flash('¡The classification you want to edit does not exist!', 'danger');
        }
        
        return redirect(route('classification.index'));
    }
    
    #eliminar clasificación
    public function delete($id = null)
    {
        $classification = Classification::find($id);
        
        if (!is_null($classification))
        {
            if (secureDelete($classification))
            {
                $data = array(
                    'activity' => 'You have delete the classification <a href="#">'.$classification->name.'</a>',
                    'icon'     => $this->icon.' bg-red',
                );
                
                add_recent_activity($data);
                
                flash('¡The Classification '.$classification->name.' has been erased successfuly!', 'success');
            }
            else
            {
                flash('¡It has happened an unknown error deleting the classification '.$classification->name.'!', 'danger');
            }
        }
        else
        {
            flash('¡The Classification you want to delete does not exist in the system!', 'danger');
        }
        
        return redirect(route('classification.index'));
    }
}