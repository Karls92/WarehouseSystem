<?php

namespace App\Http\Controllers\Backend;

use App\Models\Brand;
use App\Models\Classification;
use App\Models\ProductModel;
use App\Models\UnitOfMeasure;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller
{
    private $icon;
    private $module;
    private $per_page;
    
    public function __construct()
    {
        $this->module   = 'products';
        $this->icon     = site_config()['lateral_elements'][$this->module]['details']['icon'];
        $this->per_page = 100;
    }
    
    /**
     * Gestionar Productos
     */
    
    #listar productos
    public function index($slug = null)
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Products',
                'url'  => route('product.index'),
            ),
            array(
                'name' => 'Lists',
            ),
        );
        
        $page_data['page_title']       = 'List of Products';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'products';
        $page_data['per_page']         = $this->per_page;
        
        if(is_null($slug))
        {
            $page_data['products'] = Product::limit($this->per_page)
                                            ->orderBy('created_at', 'desc')
                                            ->get();
            
            $page_data['products_qty'] = Product::count();
        }
        else
        {
            $page_data['products'] = Product::where('slug', '=', $slug)
                                            ->get();
            
            if(count($page_data['products']) == 0)
            {
                flash('¡El Producto ha buscar no existe!', 'danger');
                
                return redirect(route('product.index'));
            }
            
            $page_data['products_qty'] = 1;
        }
        
        return view('backend.product.index', $page_data);
    }
    
    #ajax para cargar más productos
    public function ajax_products(Request $request)
    {
        $data = array();
        
        $products = Product::offset($request->offset)->limit($this->per_page)->orderBy('created_at', 'desc')->get();
        
        foreach($products as $product)
        {
            $uom = ($product->uom_id > 1) ? '<a href="'.route('unit_of_measure.search',['slug' => $product->unit_of_measure->slug]).'"><b>'.$product->unit_of_measure->name.'</b></a>' : '<em class="text-muted">'.$product->unit_of_measure->name.'</em>';
            $brand = ($product->brand_id > 1) ? '<a href="'.route('brand.search',['slug' => $product->brand->slug]).'"><b>'.$product->brand->name.'</b></a>' : '<em class="text-muted">'.$product->brand->name.'</em>';
            $model = ($product->model_id > 1) ? '<a href="'.route('model.search',['slug' => $product->model->slug]).'"><b>'.$product->model->name.'</b></a>' : '<em class="text-muted">'.$product->model->name.'</em>';
            $observation = (strlen($product->observation) > 0) ? $product->observation : '<em class="text-muted">Without observation</em>';
            
            array_push($data,array(
                $product->name.'<br>'.$product->product_code,
                '<b>Clasification: <a href="'.route('classification.search',['slug' => $product->classification->slug]).'">'.$product->classification->name.'</a></b><br>
                <b>Unit of measure: </b>'.$uom.'<br>
                <b>Brand: </b>'.$brand.'<br>
                <b>Model: </b>'.$model.'<br>
                <b>Description: </b>'.$product->description.'<br>
                <b>Observations: </b>'.$observation,
                '<a href="'.route('product.update',['slug' => $product->slug]).'" class="btn btn-primary" title="Edit this product"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                <a href="#" id="delete_'.$product->id.'" class="btn btn-danger confirmation_delete_modal" title="Delete this product"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>'
            ));
        }
        
        die(json_encode($data));
    }
    
    #agregar producto
    public function add()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Products',
                'url'  => route('product.index'),
            ),
            array(
                'name' => 'Add',
            ),
        );
        
        $page_data['page_title']       = 'Add Product';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'products';
        $page_data['form_type']        = 'success';
        $page_data['brands'] = Brand::all();
        $page_data['models'] = ProductModel::where('id','=',1)->get();
        $page_data['classifications'] = Classification::all();
        $page_data['units_of_measure'] = UnitOfMeasure::all();
        
        return view('backend.product.add', $page_data);
    }
    
    #guardar producto
    public function store(Request $request)
    {
        $product = new Product();
    
        #son valores que podrían cambiar con el respectivo formato, y ademas son únicos.
        $request->merge(
            [
                'product_code' => strtoupper(str_replace(' ', '', $request->product_code)),
            ]);
        
        $this->validate($request, $product->rules());
        
        $product->product_code = $request->product_code;
        $product->name  = remove_unnecessary_spaces($request->name);
        $product->brand_id = $request->brand_id;
        $product->model_id = $request->model_id;
        $product->classification_id = $request->classification_id;
        $product->uom_id = $request->uom_id;
        $product->description = $request->description;
        $product->observation = $request->observation;
        
        #creacion del slug
        $slug  = slug($product->name);
        $count = 2;
        
        while(Product::where('slug', '=', $slug)->count() > 0)
        {
            $slug = slug($product->name.' '.$count);
            $count++;
        }
        
        $product->slug = $slug;
        
        if(secureSave($product))
        {
            $data = array(
                'activity' => 'you have added the product <a href="'.route('product.search', ['slug' => $product->slug]).'">'.$product->name.'</a>',
                'icon'     => $this->icon.' bg-green',
            );
            
            add_recent_activity($data);
            
            flash('¡It has saved the information successfuly!', 'success');
        }
        else
        {
            flash('¡Ha ocurrido un problema guardando la información!', 'danger');
        }
        
        return redirect(route('product.index'));
    }
    
    #editar producto
    public function edit($slug = null)
    {
        $page_data['product'] = Product::where('slug', '=', $slug)->first();
        
        if(!is_null($page_data['product']))
        {
            $page_data['breadcrumb'] = array(
                array(
                    'name' => 'Products',
                    'url'  => route('product.index'),
                ),
                array(
                    'name' => $page_data['product']->name,
                    'url'  => route('product.search', ['slug' => $page_data['product']->slug]),
                ),
                array(
                    'name' => 'Edit',
                ),
            );
            
            $page_data['page_title']       = 'Edit Product';
            $page_data['active_module']    = $this->module;
            $page_data['active_submodule'] = 'products';
            $page_data['form_type']        = 'primary';
            $page_data['brands'] = Brand::all();
            $page_data['models'] = ProductModel::where('brand_id','=',$page_data['product']->brand_id)->orWhere('id','=',1)->get();
            $page_data['classifications'] = Classification::all();
            $page_data['units_of_measure'] = UnitOfMeasure::all();
            
            return view('backend.product.edit', $page_data);
        }
        else
        {
            flash('¡No existe el producto a editar!', 'danger');
            
            return redirect(route('product.index'));
        }
    }
    
    #actualizar producto
    public function update(Request $request, $slug = null)
    {
        $product = Product::where('slug', '=', $slug)->first();
        
        if(!is_null($product))
        {
            $old_values = $product->attributesToArray();
            $new_values = $request->except('_token');
            
            if(count(array_diff_assoc($new_values, $old_values)) > 0)
            {
                #son valores que podrían cambiar con el respectivo formato, y ademas son únicos.
                $request->merge(
                    [
                        'product_code' => strtoupper(str_replace(' ', '', $request->product_code)),
                    ]);
                
                $this->validate($request, $product->rules());
    
                $product->product_code = $request->product_code;
                $product->name  = remove_unnecessary_spaces($request->name);
                $product->brand_id = $request->brand_id;
                $product->model_id = $request->model_id;
                $product->classification_id = $request->classification_id;
                $product->uom_id = $request->uom_id;
                $product->description = $request->description;
                $product->observation = $request->observation;
                
                #creacion del slug
                $slug  = slug($product->name);
                $count = 2;
                
                while(Product::where('slug', '=', $slug)->where('id', '!=', $product->id)->count() > 0)
                {
                    $slug = slug($product->name.' '.$count);
                    $count++;
                }
                
                $product->slug = $slug;
                
                if(secureSave($product))
                {
                    $data = array(
                        'activity' => 'you have edited the product <a href="'.route('product.search', ['slug' => $product->slug]).'">'.$product->name.'</a>',
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
            flash('¡No existe el producto a editar!', 'danger');
        }
        
        return redirect(route('product.index'));
    }
    
    #eliminar producto
    public function delete($id = null)
    {
        $product = Product::find($id);
        
        if(!is_null($product))
        {
            if(secureDelete($product))
            {
                $data = array(
                    'activity' => 'you have deleted the product <a href="#">'.$product->name.'</a>',
                    'icon'     => $this->icon.' bg-red',
                );
                
                add_recent_activity($data);
                
                flash('¡The Product '.$product->name.' has been erased successfuly!', 'success');
            }
            else
            {
                flash('¡Ha ocurrido un error desconocido eliminando el producto '.$product->name.'!', 'danger');
            }
        }
        else
        {
            flash('¡El Producto a eliminar no se encuentra en el sistema!', 'danger');
        }
        
        return redirect(route('product.index'));
    }
}