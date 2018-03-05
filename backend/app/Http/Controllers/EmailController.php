<?php

namespace App\Http\Controllers\Backend;

use App\Models\ProductModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Brand;

class EmailController extends Controller
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
     * Gestionar Marcas
     */
    
    #listar marcas
    public function index($slug = null)
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Marcas',
                'url'  => route('brand.index'),
            ),
            array(
                'name' => 'Listado',
            ),
        );
        
        $page_data['page_title']       = 'Listado de Marcas';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'brands';
        $page_data['per_page']         = $this->per_page;
        
        if(is_null($slug))
        {
            $page_data['brands'] = Brand::where('id', '!=', 1)//opcion: sin especificar
                                        ->limit($this->per_page)
                                        ->orderBy('created_at', 'desc')
                                        ->get();
            
            $page_data['brands_qty'] = Brand::where('id', '!=', 1)->count();
        }
        else
        {
            $page_data['brands'] = Brand::where('id', '!=', 1)//opcion: sin especificar
                                        ->where('slug', '=', $slug)
                                        ->get();
            
            if(count($page_data['brands']) == 0)
            {
                flash('¡La Marca ha buscar no existe!', 'danger');
                
                return redirect(route('brand.index'));
            }
            
            $page_data['brands_qty'] = 1;
        }
        
        return view('backend.brand.index', $page_data);
    }
    
    #agregar marca
    public function add()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Marcas',
                'url'  => route('brand.index'),
            ),
            array(
                'name' => 'Agregar',
            ),
        );
        
        $page_data['page_title']       = 'Agregar Marca';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'brands';
        $page_data['form_type']        = 'success';
        
        return view('backend.newbrand.addbrand', $page_data);
    }
    
    #guardar marca
    public function store(Request $request)
    {
        $brand = new Brand();
        
        $this->validate($request, $brand->rules());
        
        $brand->name = strtoupper(trim($request->name));
        
        #creacion del slug
        $slug  = slug($brand->name);
        $count = 2;
        
        while(Brand::where('slug', '=', $slug)->count() > 0)
        {
            $slug = slug($brand->name.' '.$count);
            $count++;
        }
        
        $brand->slug = $slug;
        
        if(secureSave($brand))
        {
            $data = array(
                'activity' => 'ha agregado la marca <a href="'.route('brand.update', ['slug' => $brand->slug]).'">'.$brand->name.'</a>',
                'icon'     => $this->icon.' bg-green',
            );
            
            add_recent_activity($data);
            
            return redirect(route('product.add'));

        }
        else
        {
            flash('¡Ha ocurrido un problema guardando la información!', 'danger');
        }
        
    }
    
    #actualizar marca
    public function update(Request $request, $slug = null)
    {
        $brand = Brand::where('id', '!=', 1)->where('slug', '=', $slug)->first();
        
        if(!is_null($brand))
        {
            $old_values = $brand->attributesToArray();
            $new_values = $request->except('_token');
            
            if(count(array_diff_assoc($new_values, $old_values)) > 0)
            {
                $this->validate($request, $brand->rules());
                
                $brand->name = strtoupper(trim($request->name));
                
                #creacion del slug
                $slug  = slug($brand->name);
                $count = 2;
                
                while(Brand::where('slug', '=', $slug)->where('id', '!=', $brand->id)->count() > 0)
                {
                    $slug = slug($brand->name.' '.$count);
                    $count++;
                }
                
                $brand->slug = $slug;
                
                if(secureSave($brand))
                {
                    $data = array(
                        'activity' => 'ha editado la marca <a href="'.route('brand.update', ['slug' => $brand->slug]).'">'.$brand->name.'</a>',
                        'icon'     => $this->icon.' bg-blue',
                    );
                    
                    add_recent_activity($data);
                    
                    flash('¡Se ha guardado la información correctamente!', 'success');
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
        else
        {
            flash('¡No existe la marca a editar!', 'danger');
        }
        
        return redirect(route('brand.index'));
    }
    
    
    /*
     * AJAX
     */
    
    public function ajax_models(Request $request)
    {
        if(isset($request->brand_id) && !is_null($request->brand_id) && is_numeric($request->brand_id))
        {
            $models = ProductModel::selectRaw('id,name')->where('brand_id','=',$request->brand_id)->orWhere('id','=',1)->get();
            
            if(count($models) > 0)
            {
                die(json_encode($models));
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