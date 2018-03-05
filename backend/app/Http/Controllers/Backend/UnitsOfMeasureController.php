<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UnitOfMeasure;

class UnitsOfMeasureController extends Controller
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
     * Gestionar Unidades de Medida
     */
    
    #listar unidades de medida
    public function index($slug = null)
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Unit of Measure',
                'url'  => route('unit_of_measure.index'),
            ),
            array(
                'name' => 'Lists',
            ),
        );
        
        $page_data['page_title']       = 'Lists of Units of Measure';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'units_of_measure';
        $page_data['per_page']         = $this->per_page;
        
        if(is_null($slug))
        {
            $page_data['units_of_measure']     = UnitOfMeasure::where('id', '!=', 1)->limit($this->per_page)
                                                              ->orderBy('created_at', 'desc')
                                                              ->get();
            $page_data['units_of_measure_qty'] = UnitOfMeasure::count();
        }
        else
        {
            $page_data['units_of_measure'] = UnitOfMeasure::where('id', '!=', 1)//opcion: sin especificar
                                                          ->where('slug', '=', $slug)
                                                          ->get();
            
            if(count($page_data['units_of_measure']) == 0)
            {
                flash('¡La Unidad de Medida ha buscar no existe!', 'danger');
                
                return redirect(route('unit_of_measure.index'));
            }
            
            $page_data['units_of_measure_qty'] = 1;
        }
        
        return view('backend.unit_of_measure.index', $page_data);
    }
    
    #agregar unidad de medida
    public function add()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Units of Measure',
                'url'  => route('unit_of_measure.index'),
            ),
            array(
                'name' => 'Add',
            ),
        );
        
        $page_data['page_title']       = 'Add Unit of Measure';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'units_of_measure';
        $page_data['form_type']        = 'success';
        
        return view('backend.unit_of_measure.add', $page_data);
    }
    
    #guardar unidad de medida
    public function store(Request $request)
    {
        $unit_of_measure = new UnitOfMeasure();
        
        $this->validate($request, $unit_of_measure->rules());
        
        $unit_of_measure->name = strtoupper(trim($request->name));
        
        #creacion del slug
        $slug  = slug($unit_of_measure->name);
        $count = 2;
        
        while(UnitOfMeasure::where('slug', '=', $slug)->count() > 0)
        {
            $slug = slug($unit_of_measure->name.' '.$count);
            $count++;
        }
        
        $unit_of_measure->slug = $slug;
        
        if(secureSave($unit_of_measure))
        {
            $data = array(
                'activity' => 'You have added the unit of measure <a href="'.route('unit_of_measure.update', ['slug' => $unit_of_measure->slug]).'">'.$unit_of_measure->name.'</a>',
                'icon'     => $this->icon.' bg-green',
            );
            
            add_recent_activity($data);
            
            flash('¡It has saved the information successfuly!', 'success');
        }
        else
        {
            flash('¡Ha ocurrido un problema guardando la información!', 'danger');
        }
        
        return redirect(route('unit_of_measure.index'));
    }
    
    #editar unidad de medida
    public function edit($slug = null)
    {
        $page_data['unit_of_measure'] = UnitOfMeasure::where('id', '!=', 1)->where('slug', '=', $slug)->first();
        
        if(!is_null($page_data['unit_of_measure']))
        {
            $page_data['breadcrumb'] = array(
                array(
                    'name' => 'Units of Measure',
                    'url'  => route('unit_of_measure.index'),
                ),
                array(
                    'name' => $page_data['unit_of_measure']->name,
                    'url'  => route('unit_of_measure.update', ['slug' => $page_data['unit_of_measure']->slug]),
                ),
                array(
                    'name' => 'Edit',
                ),
            );
            
            $page_data['page_title']       = 'Edit Unit of Measure';
            $page_data['active_module']    = $this->module;
            $page_data['active_submodule'] = 'units_of_measure';
            $page_data['form_type']        = 'primary';
            
            return view('backend.unit_of_measure.edit', $page_data);
        }
        else
        {
            flash('¡No existe la unidad de medida a editar!', 'danger');
            
            return redirect(route('unit_of_measure.index'));
        }
    }
    
    #actualizar unidad de medida
    public function update(Request $request, $slug = null)
    {
        $unit_of_measure = UnitOfMeasure::where('id', '!=', 1)->where('slug', '=', $slug)->first();
        
        if(!is_null($unit_of_measure))
        {
            $old_values = $unit_of_measure->attributesToArray();
            $new_values = $request->except('_token');
            
            if(count(array_diff_assoc($new_values, $old_values)) > 0)
            {
                $this->validate($request, $unit_of_measure->rules());
                
                $unit_of_measure->name = strtoupper(trim($request->name));
                
                #creacion del slug
                $slug  = slug($unit_of_measure->name);
                $count = 2;
                
                while(UnitOfMeasure::where('slug', '=', $slug)->where('id', '!=', $unit_of_measure->id)->count() > 0)
                {
                    $slug = slug($unit_of_measure->name.' '.$count);
                    $count++;
                }
                
                $unit_of_measure->slug = $slug;
                
                if(secureSave($unit_of_measure))
                {
                    $data = array(
                        'activity' => 'You have edited the unit of measure <a href="'.route('unit_of_measure.update', ['slug' => $unit_of_measure->slug]).'">'.$unit_of_measure->name.'</a>',
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
            flash('¡No existe la unidad de medida a editar!', 'danger');
        }
        
        return redirect(route('unit_of_measure.index'));
    }
    
    #eliminar unidad de medida
    public function delete($id = null)
    {
        if($id != 1)
        {
            $unit_of_measure = UnitOfMeasure::find($id);
            
            if(!is_null($unit_of_measure))
            {
                if(secureDelete($unit_of_measure))
                {
                    $data = array(
                        'activity' => 'You have deleted the unit of measure <a href="#">'.$unit_of_measure->name.'</a>',
                        'icon'     => $this->icon.' bg-red',
                    );
                    
                    add_recent_activity($data);
                    
                    flash('¡The unit of measure '.$unit_of_measure->name.' has been erased successfuly!', 'success');
                }
                else
                {
                    flash('¡Ha ocurrido un error desconocido eliminando la unidad de medida '.$unit_of_measure->name.'!', 'danger');
                }
            }
            else
            {
                flash('¡La Unidad de Medida a eliminar no se encuentra en el sistema!', 'danger');
            }
        }
        else
        {
            flash('¡La Unidad de Medida a eliminar no se encuentra en el sistema!', 'danger');
        }
        
        return redirect(route('unit_of_measure.index'));
    }
}