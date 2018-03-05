<?php

namespace App\Http\Controllers\Backend;

use App\Models\ProductModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Brand;

class BrandsController extends Controller
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
                'name' => 'Brands',
                'url'  => route('brand.index'),
            ),
            array(
                'name' => 'Lists',
            ),
        );
        
        $page_data['page_title']       = 'Lists Of Brands';
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
            $page_data['brands'] = Brand::where('id', '!=', 1)//option: sin especificar
                                        ->where('slug', '=', $slug)
                                        ->get();
            
            if(count($page_data['brands']) == 0)
            {
                flash('¡The Brand you are searching does not exist!', 'danger');
                
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
                'name' => 'Brands',
                'url'  => route('brand.index'),
            ),
            array(
                'name' => 'Add',
            ),
        );
        
        $page_data['page_title']       = 'Add brand';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'brands';
        $page_data['form_type']        = 'success';
        
        return view('backend.brand.add', $page_data);
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
                'activity' => 'You have added the brand <a href="'.route('brand.update', ['slug' => $brand->slug]).'">'.$brand->name.'</a>',
                'icon'     => $this->icon.' bg-green',
            );
            
            add_recent_activity($data);
            
            flash('¡It has saved the information successfuly!', 'success');
        }
        else
        {
            flash('¡It has happened an error saving the information!', 'danger');
        }
        
        return redirect(route('brand.index'));
    }
    
    #editar marca
    public function edit($slug = null)
    {
        $page_data['brand'] = Brand::where('id', '!=', 1)->where('slug', '=', $slug)->first();
        
        if(!is_null($page_data['brand']))
        {
            $page_data['breadcrumb'] = array(
                array(
                    'name' => 'Brands',
                    'url'  => route('brand.index'),
                ),
                array(
                    'name' => $page_data['brand']->name,
                    'url'  => route('brand.update', ['slug' => $page_data['brand']->slug]),
                ),
                array(
                    'name' => 'Edit',
                ),
            );
            
            $page_data['page_title']       = 'Edit Brand';
            $page_data['active_module']    = $this->module;
            $page_data['active_submodule'] = 'brands';
            $page_data['form_type']        = 'primary';
            
            return view('backend.brand.edit', $page_data);
        }
        else
        {
            flash('¡The brand you want to edit does not exist!', 'danger');
            
            return redirect(route('brand.index'));
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
                        'activity' => 'You have edited the brand <a href="'.route('brand.update', ['slug' => $brand->slug]).'">'.$brand->name.'</a>',
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
                flash('¡There is not any error!', 'info');
            }
        }
        else
        {
            flash('¡The brand you want to edit does not exist!', 'danger');
        }
        
        return redirect(route('brand.index'));
    }
    
    #eliminar marca
    public function delete($id = null)
    {
        if($id != 1)
        {
            $brand = Brand::find($id);
            
            if(!is_null($brand))
            {
                if(secureDelete($brand))
                {
                    $data = array(
                        'activity' => 'You have delete the brand <a href="#">'.$brand->name.'</a>',
                        'icon'     => $this->icon.' bg-red',
                    );
                    
                    add_recent_activity($data);
                    
                    flash('¡The Brand '.$brand->name.' has been erased successfuly!', 'success');
                }
                else
                {
                    flash('¡It has happened an unknown error deleting the brand '.$brand->name.'!', 'danger');
                }
            }
            else
            {
                flash('¡The brand you want to delete does not exist in the system!', 'danger');
            }
        }
        else
        {
            flash('¡The brand you want to delete does not exist in the system!', 'danger');
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