<?php

namespace App\Http\Controllers\Backend;

use App\Models\Brand;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ProductModel;

class ModelsController extends Controller
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
     * Gestionar Modelos
     */
    
    #listar modelos
    public function index($slug = null)
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Models',
                'url'  => route('model.index'),
            ),
            array(
                'name' => 'Lists',
            ),
        );
        
        $page_data['page_title']       = 'Lists of Models';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'models';
        $page_data['per_page']         = $this->per_page;
    
        if(is_null($slug))
        {
            $page_data['models']           = ProductModel::where('id', '!=', 1)//opcion: sin  especificar
                                                         ->limit($this->per_page)
                                                         ->orderBy('created_at', 'desc')
                                                         ->get();
    
            $page_data['models_qty'] = ProductModel::where('id', '!=', 1)->count();
        }
        else
        {
            $page_data['models'] = ProductModel::where('id', '!=', 1)//opcion: sin especificar
                                                          ->where('slug', '=', $slug)
                                                          ->get();
        
            if(count($page_data['models']) == 0)
            {
                flash('¡El modelo ha buscar no existe!', 'danger');
            
                return redirect(route('model.index'));
            }
        
            $page_data['models_qty'] = 1;
        }
        
        return view('backend.model.index', $page_data);
    }
    
    #agregar modelo
    public function add()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Models',
                'url'  => route('model.index'),
            ),
            array(
                'name' => 'Add',
            ),
        );
        
        $page_data['page_title']       = 'Add Model';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'models';
        $page_data['form_type']        = 'success';
        $page_data['brands'] = Brand::where('id','!=',1)->get();
        
        return view('backend.model.add', $page_data);
    }
    
    #guardar modelo
    public function store(Request $request)
    {
        $model = new ProductModel();
        
        $this->validate($request, $model->rules());
        
        $model->name  = strtoupper(trim($request->name));
        $model->brand_id = $request->brand_id;
        
        #creacion del slug
        $slug  = slug($model->name);
        $count = 2;
        
        while (ProductModel::where('slug', '=', $slug)->count() > 0)
        {
            $slug = slug($model->name.' '.$count);
            $count++;
        }
        
        $model->slug = $slug;
        
        if (secureSave($model))
        {
            $data = array(
                'activity' => 'you have added the model <a href="'.route('model.update', ['slug' => $model->slug]).'">'.$model->name.'</a>',
                'icon'     => $this->icon.' bg-green',
            );
            
            add_recent_activity($data);
            
            flash('¡It has saved the information successfuly!', 'success');
        }
        else
        {
            flash('¡Ha ocurrido un problema guardando la información!', 'danger');
        }
        
        return redirect(route('model.index'));
    }
    
    #editar modelo
    public function edit($slug = null)
    {
        $page_data['model'] = ProductModel::where('id', '!=', 1)->where('slug', '=', $slug)->first();
        
        if (!is_null($page_data['model']))
        {
            $page_data['breadcrumb'] = array(
                array(
                    'name' => 'Models',
                    'url'  => route('model.index'),
                ),
                array(
                    'name' => $page_data['model']->name,
                    'url'  => route('model.update', ['slug' => $page_data['model']->slug]),
                ),
                array(
                    'name' => 'Edit',
                ),
            );
            
            $page_data['page_title']       = 'Edit Model';
            $page_data['active_module']    = $this->module;
            $page_data['active_submodule'] = 'models';
            $page_data['form_type']        = 'primary';
            $page_data['brands'] = Brand::where('id','!=',1)->get();
            
            return view('backend.model.edit', $page_data);
        }
        else
        {
            flash('¡No existe el modelo a editar!', 'danger');
            
            return redirect(route('model.index'));
        }
    }
    
    #actualizar modelo
    public function update(Request $request, $slug = null)
    {
        $model = ProductModel::where('id', '!=', 1)->where('slug', '=', $slug)->first();
        
        if (!is_null($model))
        {
            $old_values = $model->attributesToArray();
            $new_values = $request->except('_token');
            
            if (count(array_diff_assoc($new_values, $old_values)) > 0)
            {
                #son valores que podrían cambiar con el respectivo formato, y ademas son únicos.
                
                $this->validate($request, $model->rules());
                
                $model->name  = strtoupper(trim($request->name));
                $model->brand_id = $request->brand_id;
                
                #creacion del slug
                $slug  = slug($model->name);
                $count = 2;
                
                while (ProductModel::where('slug', '=', $slug)->where('id', '!=', $model->id)->count() > 0)
                {
                    $slug = slug($model->name.' '.$count);
                    $count++;
                }
                
                $model->slug = $slug;
                
                if (secureSave($model))
                {
                    $data = array(
                        'activity' => 'you have edited the model <a href="'.route('model.update', ['slug' => $model->slug]).'">'.$model->name.'</a>',
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
                flash('¡There is any changes!', 'info');
            }
        }
        else
        {
            flash('¡No existe el modelo a editar!', 'danger');
        }
        
        return redirect(route('model.index'));
    }
    
    #eliminar modelo
    public function delete($id = null)
    {
        if ($id != 1)
        {
            $model = ProductModel::find($id);
            
            if (!is_null($model))
            {
                if (secureDelete($model))
                {
                    $data = array(
                        'activity' => 'you have deleted the model <a href="#">'.$model->name.'</a>',
                        'icon'     => $this->icon.' bg-red',
                    );
                    
                    add_recent_activity($data);
                    
                    flash('¡The Model '.$model->name.' has been erased successfuly!', 'success');
                }
                else
                {
                    flash('¡Ha ocurrido un error desconocido eliminando el modelo '.$model->name.'!', 'danger');
                }
            }
            else
            {
                flash('¡El Modelo a eliminar no se encuentra en el sistema!', 'danger');
            }
        }
        else
        {
            flash('¡El Modelo a eliminar no se encuentra en el sistema!', 'danger');
        }
        
        return redirect(route('model.index'));
    }
}