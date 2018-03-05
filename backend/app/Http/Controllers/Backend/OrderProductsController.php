<?php

namespace App\Http\Controllers\Backend;


use App\Models\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderProductsController extends Controller
{
    private $order;
    private $icon;
    private $module;
    private $per_page;
    private $variable;
    private $type;
    
    public function __construct()
    {
        $this->module   = 'main_operations';
        $this->icon     = site_config()['lateral_elements'][$this->module]['details']['icon'];
        $this->per_page = 100;
    }
    
    /**
     * Gestionar Productos de la Orden
     */
    
    #listar productos de la orden
    public function index($order_type = null, $order_code = null, $id = null)
    {
        $this->getEnv($order_type, $order_code);
    
        if(is_null($this->order))
        {
            flash('¡The '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' does not exist in the system!', 'danger');
        
            return redirect(route($this->variable.'.index'));
        }

        $page_data['breadcrumb'] = array(
            array(
                'name' => camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.plural')),
                'url'  => route($this->variable.'.index'),
            ),
            array(
                'name' => $this->order->code,
                'url'  => ($this->order->is_processed != 'Y') ? route($this->variable.'.search', ['slug' => $this->order->slug]) : '#',
            ),
            array(
                'name' => 'Products',
                'url'  => route('order_product.index', ['order_code' => $order_code, 'order_type' => $order_type]),
            ),
            array(
                'name' => 'List',
            ),
        );
        
        $page_data['page_title']       = 'Products List of the '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' '.$this->order->code;
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = $this->variable.'s';
        $page_data['per_page']         = $this->per_page;
        $page_data['order']            = $this->order;
        $page_data['order_type']       = $order_type;
        $page_data['order_code']       = $order_code;
        
        if(is_null($id))
        {
            switch($this->type)
            {
                case 'entry' :
                    
                    $page_data['order_products'] = $this->order->products;
                    
                    break;
                case 'out' :
                    
                    $db_prefix = getDBConfig();
                    
                    $sum_entries    = $this->getSUMOrders('entry', $this->order->client_id, $db_prefix);
                    $sum_outs       = $this->getSUMOrders('out', $this->order->client_id, $db_prefix);
                    $sum_devolution = $this->getSUMOrders('devolution', $this->order->client_id, $db_prefix);
                    
                    $page_data['order_products'] = $this->order->products()
                                                               ->selectRaw($db_prefix.'products.*, (('.getRealQuery($sum_entries).') + ('.getRealQuery($sum_devolution).') - ('.getRealQuery($sum_outs).')) as qty_disp')
                                                               ->get();
                    break;
                case 'devolution':
                    
                    $db_prefix = getDBConfig();
                    
                    #suma del producto en devolucion
                    $qty_product_sum_devolutions = \DB::table('order_product as op')
                                                      ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                                                      ->join('orders as o', 'o.id', '=', 'op.order_id')
                                                      ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'order_product.product_id')
                                                      ->where('o.type', '=', 'devolution')
                                                      ->where('o.is_processed', '=', 'Y')
                                                      ->whereRaw($db_prefix.'o.client_id = '.$this->order->client_id)
                                                      ->whereRaw($db_prefix.'o.out_order_id = '.$this->order->out_order_id);
                    
                    #producto de salida
                    $qty_product_out = \DB::table('order_product as op')
                                          ->selectRaw($db_prefix.'op.quantity')
                                          ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'order_product.product_id')
                                          ->whereRaw($db_prefix.'op.order_id = '.$this->order->out_order_id);
                    
                    $page_data['order_products'] = $this->order->products()
                                                               ->selectRaw($db_prefix.'products.*, (('.getRealQuery($qty_product_out).') - ('.getRealQuery($qty_product_sum_devolutions).')) as qty_disp')
                                                               ->get();
                    break;
            }
            
            $page_data['order_products_qty'] = count($page_data['order_products']);
        }
        else
        {
            switch($this->type)
            {
                case 'entry' :
                    
                    $page_data['order_products'] = $this->order->products()->where('order_product.id', '=', $id)->get();
                    
                    break;
                case 'out' :
                    
                    $db_prefix = getDBConfig();
                    
                    $sum_entries    = $this->getSUMOrders('entry', $this->order->client_id, $db_prefix);
                    $sum_outs       = $this->getSUMOrders('out', $this->order->client_id, $db_prefix);
                    $sum_devolution = $this->getSUMOrders('devolution', $this->order->client_id, $db_prefix);
                    
                    $page_data['order_products'] = $this->order->products()
                                                               ->selectRaw($db_prefix.'products.*, (('.getRealQuery($sum_entries).') + ('.getRealQuery($sum_devolution).') - ('.getRealQuery($sum_outs).')) as qty_disp')
                                                               ->where('order_product.id', '=', $id)->get();
                    break;
                case 'devolution':
                    
                    $db_prefix = getDBConfig();
                    
                    #suma del producto en devolucion
                    $qty_product_sum_devolutions = \DB::table('order_product as op')
                                                      ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                                                      ->join('orders as o', 'o.id', '=', 'op.order_id')
                                                      ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'order_product.product_id')
                                                      ->where('o.type', '=', 'devolution')
                                                      ->where('o.is_processed', '=', 'Y')
                                                      ->whereRaw($db_prefix.'o.client_id = '.$this->order->client_id)
                                                      ->whereRaw($db_prefix.'o.out_order_id = '.$db_prefix.'orders.out_order_id');
                    
                    #producto de salida
                    $qty_product_out = \DB::table('order_product as op')
                                          ->selectRaw($db_prefix.'op.quantity')
                                          ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'order_product.product_id')
                                          ->whereRaw($db_prefix.'op.order_id = '.$db_prefix.'orders.out_order_id');
                    
                    $page_data['order_products'] = $this->order->products()
                                                               ->join('orders', 'orders.id', '=', 'order_product.order_id')
                                                               ->selectRaw($db_prefix.'products.*, (('.getRealQuery($qty_product_out).') - ('.getRealQuery($qty_product_sum_devolutions).')) as qty_disp')
                                                               ->where('order_product.id', '=', $id)->get();
                    break;
            }
            
            if(count($page_data['order_products']) == 0)
            {
                flash('¡El Producto de la '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' ha buscar no existe!', 'danger');
                
                return redirect(route('order_product.index', [
                    'order_code' => $order_code,
                    'order_type' => $order_type,
                ]));
            }
            
            $page_data['order_products_qty'] = 1;
        }
        
        return view('backend.'.$this->type.'_order_product.index', $page_data);
    }
    
    #agregar producto de la orden
    public function add($order_type = null, $order_code = null)
    {
        $this->getEnv($order_type, $order_code);
    
        if(is_null($this->order))
        {
            flash('¡La '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' no existe en el sistema!', 'danger');
        
            return redirect(route($this->variable.'.index'));
        }
        
        if($this->order->is_processed != 'Y')
        {
            $page_data['breadcrumb'] = array(
                array(
                    'name' => camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.plural')),
                    'url'  => route($this->variable.'.index'),
                ),
                array(
                    'name' => $this->order->code,
                    'url'  => route($this->variable.'.search', ['slug' => $this->order->slug]),
                ),
                array(
                    'name' => 'Products',
                    'url'  => route('order_product.index', ['order_code' => $order_code, 'order_type' => $order_type]),
                ),
                array(
                    'name' => 'Add',
                ),
            );
            
            $page_data['page_title']       = 'Add Product of the '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' '.$this->order->code;
            $page_data['active_module']    = $this->module;
            $page_data['active_submodule'] = $this->variable.'s';
            $page_data['form_type']        = 'success';
            $page_data['order']            = $this->order;
            $page_data['order_type']       = $order_type;
            $page_data['order_code']       = $order_code;
            
            switch($this->type)
            {
                case 'entry' :
                    
                    $page_data['products'] = Product::whereNotIn('id', array_column($page_data['order']->products->toArray(), 'id'))
                                                    ->orderBy('created_at', 'desc')
                                                    ->get();
                    
                    break;
                case 'out' :
                    
                    $db_prefix = getDBConfig();
                    
                    $sum_entries    = $this->getSUMOrders('entry', $this->order->client_id, $db_prefix);
                    $sum_outs       = $this->getSUMOrders('out', $this->order->client_id, $db_prefix);
                    $sum_devolution = $this->getSUMOrders('devolution', $this->order->client_id, $db_prefix);
                    
                    $page_data['products'] = Product::selectRaw($db_prefix.'products.*, (('.getRealQuery($sum_entries).') + ('.getRealQuery($sum_devolution).') - ('.getRealQuery($sum_outs).')) as qty_disp')
                                                    ->whereNotIn('id', array_column($page_data['order']->products->toArray(), 'id'))
                                                    ->whereRaw('(('.getRealQuery($sum_entries).') + ('.getRealQuery($sum_devolution).') - ('.getRealQuery($sum_outs).')) > 0')
                                                    ->orderBy('created_at', 'desc')
                                                    ->get();
                    
                    break;
                case 'devolution':
                    
                    $db_prefix = getDBConfig();
                    
                    #suma del producto en devolucion
                    $qty_product_sum_devolutions = \DB::table('order_product as op')
                                                      ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                                                      ->join('orders as o', 'o.id', '=', 'op.order_id')
                                                      ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'products.id')
                                                      ->where('o.type', '=', 'devolution')
                                                      ->where('o.is_processed', '=', 'Y')
                                                      ->whereRaw($db_prefix.'o.client_id = '.$this->order->client_id)
                                                      ->whereRaw($db_prefix.'o.out_order_id = '.$this->order->out_order_id);
                    
                    #producto de salida
                    $qty_product_out = \DB::table('order_product as op')
                                          ->selectRaw($db_prefix.'op.quantity')
                                          ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'products.id')
                                          ->whereRaw($db_prefix.'op.order_id = '.$this->order->out_order_id);
                    
                    $page_data['products'] = Product::selectRaw($db_prefix.'products.*, (('.getRealQuery($qty_product_out).') - ('.getRealQuery($qty_product_sum_devolutions).')) as qty_disp')
                                                    ->whereNotIn('id', array_column($page_data['order']->products->toArray(), 'id'))
                                                    ->whereRaw('(('.getRealQuery($qty_product_out).') - ('.getRealQuery($qty_product_sum_devolutions).')) > 0')
                                                    ->orderBy('created_at', 'desc')
                                                    ->get();
                    break;
            }
            
            
            return view('backend.'.$this->type.'_order_product.add', $page_data);
        }
        else
        {
            flash('¡La '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' ya se encuentra procesada!', 'danger');
            
            return redirect(route('order_product.index', [
                'order_code' => $order_code,
                'order_type' => $order_type,
            ]));
        }
    }
    
    #guardar producto de la orden
    public function store(Request $request, $order_type = null, $order_code = null)
    {
        $this->getEnv($order_type, $order_code);
    
        if(is_null($this->order))
        {
            flash('¡La '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' no existe en el sistema!', 'danger');
        
            return redirect(route($this->variable.'.index'));
        }
        
        $this->validate($request, [
            'quantity'   => 'required|integer',
            'product_id' => 'required|integer|exists:products,id|not_in:'.implode(',', array_column($this->order->products->toArray(), 'id')),
        ]);
        
        switch($this->type)
        {
            case 'entry' :
                
                //nothing
                break;
            case 'out' :
                
                $db_prefix = getDBConfig();
                
                $sum_entries    = $this->getSUMOrders('entry', $this->order->client_id, $db_prefix);
                $sum_outs       = $this->getSUMOrders('out', $this->order->client_id, $db_prefix);
                $sum_devolution = $this->getSUMOrders('devolution', $this->order->client_id, $db_prefix);
                
                if(!Product::where('products.id', '=', $request->product_id)
                           ->whereRaw('(('.getRealQuery($sum_entries).') + ('.getRealQuery($sum_devolution).') - ('.getRealQuery($sum_outs).') - '.$request->quantity.') >= 0')
                           ->exists()
                )
                {
                    flash('¡EL cliente no posee en el inventario el producto con la cantidad que escogiste!', 'danger');
                    
                    return redirect(route('order_product.index', [
                        'order_code' => $order_code,
                        'order_type' => $order_type,
                    ]));
                }
                
                break;
            case 'devolution':
                
                $db_prefix = getDBConfig();
                
                #suma del producto en devolucion
                $qty_product_sum_devolutions = \DB::table('order_product as op')
                                                  ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0) as sum_devolutions')
                                                  ->join('orders as o', 'o.id', '=', 'op.order_id')
                                                  ->whereRaw($db_prefix.'op.product_id = '.$request->product_id)
                                                  ->where('o.type', '=', 'devolution')
                                                  ->where('o.is_processed', '=', 'Y')
                                                  ->whereRaw($db_prefix.'o.client_id = '.$this->order->client_id)
                                                  ->whereRaw($db_prefix.'o.out_order_id = '.$this->order->out_order_id)
                                                  ->first()->sum_devolutions;
                
                #producto de salida
                $qty_product_out = \DB::table('order_product as op')
                                      ->selectRaw($db_prefix.'op.quantity as sum_out')
                                      ->whereRaw($db_prefix.'op.product_id = '.$request->product_id)
                                      ->whereRaw($db_prefix.'op.order_id = '.$this->order->out_order_id)
                                      ->first()->sum_out;
                
                if($qty_product_out - $qty_product_sum_devolutions - $request->quantity < 0)
                {
                    flash('¡El producto en dicha orden de salida, no posee la cantidad que mencionas para que sea devuelto!', 'danger');
                    
                    return redirect(route('order_product.index', [
                        'order_code' => $order_code,
                        'order_type' => $order_type,
                    ]));
                }
                break;
        }
        
        try
        {
            $this->order->products()->attach($request->product_id, ['quantity' => $request->quantity]);
            
            $product = $this->order->products()->where('order_product.product_id', '=', $request->product_id)->first();
            
            $data = array(
                'activity' => 'You have added the product <a href="'.route('order_product.search', [
                        'order_type' => $order_type,
                        'order_code' => $order_code,
                        'id'         => $product->pivot->id,
                    ]).'">'.$product->name.'</a> a la '.trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular').' <a href="'.route('order_product.index', [
                        'order_type' => $order_type,
                        'order_code' => $order_code,
                    ]).'">'.$this->order->code.'</a>',
                'icon'     => $this->icon.' bg-green',
            );
            
            add_recent_activity($data);
            
            flash('¡It has saved the information successfuly!', 'success');
        }
        catch(\Exception $e)
        {
            flash('¡Ha ocurrido un problema guardando la información!', 'danger');
        }
        
        return redirect(route('order_product.index', [
            'order_code' => $order_code,
            'order_type' => $order_type,
        ]));
    }
    
    #editar producto de la orden
    public function edit($order_type = null, $order_code = null, $id = null)
    {
        $this->getEnv($order_type, $order_code);
    
        if(is_null($this->order))
        {
            flash('¡La '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' no existe en el sistema!', 'danger');
        
            return redirect(route($this->variable.'.index'));
        }
        
        if($this->order->is_processed != 'Y')
        {
            switch($this->type)
            {
                case 'entry' :
                    
                    $page_data['order_product'] = $this->order->products()
                                                              ->where('order_product.id', '=', $id)->first();
                    break;
                case 'out' :
                    
                    $db_prefix = getDBConfig();
                    
                    $sum_entries    = $this->getSUMOrders('entry', $this->order->client_id, $db_prefix);
                    $sum_outs       = $this->getSUMOrders('out', $this->order->client_id, $db_prefix);
                    $sum_devolution = $this->getSUMOrders('devolution', $this->order->client_id, $db_prefix);
                    
                    $page_data['order_product'] = $this->order->products()
                                                              ->selectRaw($db_prefix.'products.*, (('.getRealQuery($sum_entries).') + ('.getRealQuery($sum_devolution).') - ('.getRealQuery($sum_outs).')) as qty_disp')
                                                              ->where('order_product.id', '=', $id)->first();
                    
                    break;
                case 'devolution':
                    
                    $db_prefix = getDBConfig();
                    
                    #suma del producto en devolucion
                    $qty_product_sum_devolutions = \DB::table('order_product as op')
                                                      ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0) as sum_devolutions')
                                                      ->join('orders as o', 'o.id', '=', 'op.order_id')
                                                      ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'products.id')
                                                      ->where('o.type', '=', 'devolution')
                                                      ->where('o.is_processed', '=', 'Y')
                                                      ->whereRaw($db_prefix.'o.client_id = '.$this->order->client_id)
                                                      ->whereRaw($db_prefix.'o.out_order_id = '.$this->order->out_order_id);
                    
                    #producto de salida
                    $qty_product_out = \DB::table('order_product as op')
                                          ->selectRaw($db_prefix.'op.quantity as sum_out')
                                          ->whereRaw($db_prefix.'op.order_id = '.$this->order->out_order_id)
                                          ->whereRaw($db_prefix.'products.id = '.$db_prefix.'order_product.product_id');

                    $page_data['order_product'] = $this->order->products()
                                                              ->selectRaw($db_prefix.'products.*, (('.getRealQuery($qty_product_out).') - ('.getRealQuery($qty_product_sum_devolutions).') ) as qty_disp')
                                                              ->where('order_product.id', '=', $id)->first();

                    break;
            }
            
            if(!is_null($page_data['order_product']))
            {
                $page_data['breadcrumb'] = array(
                    array(
                        'name' => camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.plural')),
                        'url'  => route($this->variable.'.index'),
                    ),
                    array(
                        'name' => $this->order->code,
                        'url'  => route($this->variable.'.search', ['slug' => $this->order->slug]),
                    ),
                    array(
                        'name' => 'Products',
                        'url'  => route('order_product.index', [
                            'order_code' => $order_code,
                            'order_type' => $order_type,
                        ]),
                    ),
                    array(
                        'name' => $page_data['order_product']->name,
                        'url'  => route('product.search', ['slug' => $page_data['order_product']->slug]),
                    ),
                    array(
                        'name' => 'Edit',
                    ),
                );
                
                $page_data['page_title']       = 'Edit Product of the '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' '.$this->order->code;
                $page_data['active_module']    = $this->module;
                $page_data['active_submodule'] = $this->variable.'s';
                $page_data['form_type']        = 'primary';
                $page_data['order']            = $this->order;
                $page_data['order_type']       = $order_type;
                $page_data['order_code']       = $order_code;
                
                /*$page_data['products']         = Product::whereNotIn('id',array_filter(array_column($page_data['order']->products->toArray(), 'id'), function ($value) use($page_data) { return ($value != $page_data['order_product']->id);}) )
                                                        ->orderBy('created_at', 'desc')
                                                        ->get();*/
                
                return view('backend.'.$this->type.'_order_product.edit', $page_data);
            }
            else
            {
                flash('¡No existe el producto de la '.trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular').' a editar!', 'danger');
                
                return redirect(route('order_product.index', [
                    'order_code' => $order_code,
                    'order_type' => $order_type,
                ]));
            }
        }
        else
        {
            flash('¡La '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' ya se encuentra procesada!', 'danger');
            
            return redirect(route('order_product.index', [
                'order_code' => $order_code,
                'order_type' => $order_type,
            ]));
        }
    }
    
    #actualizar producto de la orden
    public function update(Request $request, $order_type = null, $order_code = null, $id = null)
    {
        $this->getEnv($order_type, $order_code);
    
        if(is_null($this->order))
        {
            flash('¡La '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' no existe en el sistema!', 'danger');
        
            return redirect(route($this->variable.'.index'));
        }
    
        switch($this->type)
        {
            case 'entry' :
    
                $order_product = $this->order->products()
                                                          ->where('order_product.id', '=', $id)->first();
                break;
            case 'out' :
    
                $db_prefix = getDBConfig();
    
                $sum_entries    = $this->getSUMOrders('entry', $this->order->client_id, $db_prefix);
                $sum_outs       = $this->getSUMOrders('out', $this->order->client_id, $db_prefix);
                $sum_devolution = $this->getSUMOrders('devolution', $this->order->client_id, $db_prefix);
    
                $order_product = $this->order->products()
                                             ->selectRaw($db_prefix.'products.*, (('.getRealQuery($sum_entries).') + ('.getRealQuery($sum_devolution).') - ('.getRealQuery($sum_outs).')) as qty_disp')
                                             ->where('order_product.id', '=', $id)->first();
            
                break;
            case 'devolution':
            
                $db_prefix = getDBConfig();
            
                #suma del producto en devolucion
                $qty_product_sum_devolutions = \DB::table('order_product as op')
                                                  ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0) as sum_devolutions')
                                                  ->join('orders as o', 'o.id', '=', 'op.order_id')
                                                  ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'products.id')
                                                  ->where('o.type', '=', 'devolution')
                                                  ->where('o.is_processed', '=', 'Y')
                                                  ->whereRaw($db_prefix.'o.client_id = '.$this->order->client_id)
                                                  ->whereRaw($db_prefix.'o.out_order_id = '.$this->order->out_order_id);
            
                #producto de salida
                $qty_product_out = \DB::table('order_product as op')
                                      ->selectRaw($db_prefix.'op.quantity as sum_out')
                                      ->whereRaw($db_prefix.'op.order_id = '.$this->order->out_order_id)
                                      ->whereRaw($db_prefix.'products.id = '.$db_prefix.'order_product.product_id');
    
                $order_product = $this->order->products()
                                                          ->selectRaw($db_prefix.'products.*, (('.getRealQuery($qty_product_out).') - ('.getRealQuery($qty_product_sum_devolutions).') ) as qty_disp')
                                                          ->where('order_product.id', '=', $id)->first();
            
                break;
        }
        
        if(!is_null($order_product))
        {
            $old_values = $order_product->pivot->attributesToArray();
            $new_values = $request->except('_token');
            
            if(count(array_diff_assoc($new_values, $old_values)) > 0)
            {
                $this->validate($request, [
                    'quantity' => 'required|integer',
                ]);
                
                switch($this->type)
                {
                    case 'entry' :
                        
                        //nothing
                        break;
                    case 'out' :
                        
                        if($request->quantity > $order_product->qty_disp)
                        {
                            flash('¡EL cliente no posee en el inventario el producto con la cantidad que escogiste!', 'danger');
                            
                            return redirect(route('order_product.index', [
                                'order_code' => $order_code,
                                'order_type' => $order_type,
                            ]));
                        }
                        
                        break;
                    case 'devolution':
    
                        if($request->quantity > $order_product->qty_disp)
                        {
                            flash('¡El producto no posee en la orden de salida la cantidad que escogiste!', 'danger');
        
                            return redirect(route('order_product.index', [
                                'order_code' => $order_code,
                                'order_type' => $order_type,
                            ]));
                        }
                        break;
                }
                
                $order_product->pivot->quantity = $request->quantity;
                
                if(secureSave($order_product->pivot))
                {
                    $data = array(
                        'activity' => 'you have edited the product <a href="'.route('order_product.search', [
                                'order_type' => $order_type,
                                'order_code' => $order_code,
                                'id'         => $order_product->pivot->id,
                            ]).'">'.$order_product->name.'</a> of the '.trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular').' <a href="'.route('order_product.index', [
                                'order_type' => $order_type,
                                'order_code' => $order_code,
                            ]).'">'.$this->order->code.'</a>',
                        'icon'     => $this->icon.' bg-green',
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
                flash('¡There is not any changes!', 'info');
            }
        }
        else
        {
            flash('¡No existe el producto de la '.trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular').' a editar!', 'danger');
        }
        
        return redirect(route('order_product.index', [
            'order_code' => $order_code,
            'order_type' => $order_type,
        ]));
    }
    
    #eliminar producto de la orden
    public function delete($order_type = null, $order_code = null, $id = null)
    {
        $this->getEnv($order_type, $order_code);
    
        if(is_null($this->order))
        {
            flash('¡La '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' no existe en el sistema!', 'danger');
        
            return redirect(route($this->variable.'.index'));
        }
        
        if($this->order->is_processed != 'Y')
        {
            $product = $this->order->products()->where('order_product.id', '=', $id)->first();
            
            if(!is_null($product))
            {
                try
                {
                    $this->order->products()->detach([$product->id]);
                    
                    $data = array(
                        'activity' => 'you have deleted the product<a href="#">'.$product->name.'</a> of the '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' <a href="'.route($this->variable.'.search', ['slug' => $this->order->slug]).'">'.$this->order->code.'</a>',
                        'icon'     => $this->icon.' bg-red',
                    );
                    
                    add_recent_activity($data);
                    
                    flash('¡The product from the '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' '.$product->name.' has been erased successfuly!', 'success');
                }
                catch(\Exception $e)
                {
                    flash('¡Ha ocurrido un error desconocido eliminando el producto de la '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' '.$product->name.'!', 'danger');
                }
            }
            else
            {
                flash('¡El Producto de la Orden a eliminar no se encuentra en el sistema!', 'danger');
            }
        }
        else
        {
            flash('¡La '.camel_case_text(trans('messages.full.'.str_replace('_', ' ', $this->variable).'.singular')).' ya se encuentra procesada!', 'danger');
        }
        
        return redirect(route('order_product.index', [
            'order_code' => $order_code,
            'order_type' => $order_type,
        ]));
    }
    
    public function getEnv($order_type, $order_code)
    {
        switch($order_type)
        {
            case 'ordenes-de-entrada' :
                
                $this->type     = 'entry';
                $this->variable = 'entry_order';
                break;
            case 'ordenes-de-salida' :
                
                $this->type     = 'out';
                $this->variable = 'out_order';
                break;
            case 'ordenes-de-devolucion':
                
                $this->type     = 'devolution';
                $this->variable = 'devolution_order';
                break;
            default:
                abort(404);
                break;
        }
        
        $this->order = Order::where('type', '=', $this->type)->where('code', '=', $order_code)->first();
    }
    
    public function getSUMOrders($type, $client_id = null, $db_prefix = '')
    {
        return \DB::table(\DB::raw($db_prefix.'orders as '.$db_prefix.'o'))
                  ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                  ->join('order_product as op', 'o.id', '=', 'op.order_id')
                  ->where('o.type', '=', $type)
                  ->where('o.is_processed', '=', 'Y')
                  ->where('o.client_id', '=', $client_id)
                  ->whereRaw($db_prefix.'products.id = '.$db_prefix.'op.product_id');
    }
}