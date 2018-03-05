<?php

namespace App\Http\Controllers\Backend;

use App\Models\Client;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Order;
use JasperPHP\JasperPHP as Jasper;

class DevolutionOrdersController extends Controller
{
    private $icon;
    private $module;
    private $per_page;
    
    public function __construct()
    {
        $this->module   = 'main_operations';
        $this->icon     = site_config()['lateral_elements'][$this->module]['details']['icon'];
        $this->per_page = 100;
    }
    
    /**
     * Gestionar Órdenes
     */
    
    #listar órdenes
    public function index($slug = null)
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Devolutions Orders',
                'url'  => route('devolution_order.index'),
            ),
            array(
                'name' => 'Lists',
            ),
        );
        
        $page_data['page_title']       = 'Lists of Devolution Orders';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'devolution_orders';
        $page_data['per_page']         = $this->per_page;
        
        $db_prefix = getDBConfig();
        
        #suma del producto en devolucion
        $qty_product_sum_devolutions = \DB::table('order_product as op')
                                          ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                                          ->join('orders as o', 'o.id', '=', 'op.order_id')
                                          ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'opc.product_id')
                                          ->where('o.type', '=', 'devolution')
                                          ->where('o.is_processed', '=', 'Y')
                                          ->whereRaw($db_prefix.'o.client_id = '.$db_prefix.'orders.client_id')
                                          ->whereRaw($db_prefix.'o.out_order_id = '.$db_prefix.'orders.out_order_id');
        
        #suma de todas las devoluciones
        $qty_sum_devolutions = \DB::table('order_product as op')
                                  ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                                  ->join('orders as o', 'o.id', '=', 'op.order_id')
                                  ->where('o.type', '=', 'devolution')
                                  ->where('o.is_processed', '=', 'Y')
                                  ->whereRaw($db_prefix.'o.client_id = '.$db_prefix.'orders.client_id')
                                  ->whereRaw($db_prefix.'o.out_order_id = '.$db_prefix.'orders.out_order_id');
        
        #producto de salida
        $qty_product_out = \DB::table('order_product as op')
                              ->selectRaw($db_prefix.'op.quantity')
                              ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'opc.product_id')
                              ->whereRaw($db_prefix.'op.order_id = '.$db_prefix.'orders.out_order_id');
        
        #producto de salida
        $qty_out = \DB::table('order_product as op')
                      ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                      ->whereRaw($db_prefix.'op.order_id = '.$db_prefix.'orders.out_order_id');
        
        #contador
        $count_op = \DB::table('order_product as opc')
                       ->selectRaw('count(*)')
                       ->whereRaw($db_prefix.'opc.order_id = '.$db_prefix.'orders.id')
                       ->whereRaw('( ('.getRealQuery($qty_product_out).') - ('.$db_prefix.'opc.quantity) - ('.getRealQuery($qty_product_sum_devolutions).') ) < 0');
        
        if(is_null($slug))
        {
            $page_data['devolution_orders'] = Order::selectRaw($db_prefix.'orders.*,'.$db_prefix.'order_out.slug as slug_out,'.$db_prefix.'order_out.code as code_out, ('.getRealQuery($count_op).') as broken_products,(('.getRealQuery($qty_out).') - ('.getRealQuery($qty_sum_devolutions).')) as qty_disp ')
                                                   ->join('orders as order_out', 'order_out.id', '=', 'orders.out_order_id')
                                                   ->where('orders.type', '=', 'devolution')
                                                   ->limit($this->per_page)
                                                   ->orderBy('orders.date', 'desc')
                                                   ->orderBy('orders.is_processed', 'desc')
                                                   ->orderBy('orders.code', 'desc')
                                                   ->orderBy('orders.client_id', 'asc')
                                                   ->get();
            
            $page_data['devolution_orders_qty'] = Order::where('type', '=', 'devolution')->count();
        }
        else
        {
            $page_data['devolution_orders'] = Order::selectRaw($db_prefix.'orders.*,'.$db_prefix.'order_out.slug as slug_out,'.$db_prefix.'order_out.code as code_out, ('.getRealQuery($count_op).') as broken_products,(('.getRealQuery($qty_out).') - ('.getRealQuery($qty_sum_devolutions).')) as qty_disp ')
                                                   ->join('orders as order_out', 'order_out.id', '=', 'orders.out_order_id')
                                                   ->where('orders.type', '=', 'devolution')
                                                   ->where('orders.slug', '=', $slug)
                                                   ->get();
            
            if(count($page_data['devolution_orders']) == 0)
            {
                flash('¡The devolution order you are searching does not exist!', 'danger');
                
                return redirect(route('devolution_order.index'));
            }
            
            $page_data['devolution_orders_qty'] = 1;
        }
        
        return view('backend.devolution_order.index', $page_data);
    }
    
    #ajax para cargar más órdenes
    public function ajax_orders(Request $request)
    {
        $data = array();
        
        $db_prefix = getDBConfig();
        
        #suma del producto en devolucion
        $qty_product_sum_devolutions = \DB::table('order_product as op')
                                          ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                                          ->join('orders as o', 'o.id', '=', 'op.order_id')
                                          ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'opc.product_id')
                                          ->where('o.type', '=', 'devolution')
                                          ->where('o.is_processed', '=', 'Y')
                                          ->whereRaw($db_prefix.'o.client_id = '.$db_prefix.'orders.client_id')
                                          ->whereRaw($db_prefix.'o.out_order_id = '.$db_prefix.'orders.out_order_id');
        
        #suma de todas las devoluciones
        $qty_sum_devolutions = \DB::table('order_product as op')
                                  ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                                  ->join('orders as o', 'o.id', '=', 'op.order_id')
                                  ->where('o.type', '=', 'devolution')
                                  ->where('o.is_processed', '=', 'Y')
                                  ->whereRaw($db_prefix.'o.client_id = '.$db_prefix.'orders.client_id')
                                  ->whereRaw($db_prefix.'o.out_order_id = '.$db_prefix.'orders.out_order_id');
        
        #producto de salida
        $qty_product_out = \DB::table('order_product as op')
                              ->selectRaw($db_prefix.'op.quantity')
                              ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'opc.product_id')
                              ->whereRaw($db_prefix.'op.order_id = '.$db_prefix.'orders.out_order_id');
        
        #producto de salida
        $qty_out = \DB::table('order_product as op')
                      ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                      ->whereRaw($db_prefix.'op.order_id = '.$db_prefix.'orders.out_order_id');
        
        #contador
        $count_op = \DB::table('order_product as opc')
                       ->selectRaw('count(*)')
                       ->whereRaw($db_prefix.'opc.order_id = '.$db_prefix.'orders.id')
                       ->whereRaw('( ('.getRealQuery($qty_product_out).') - ('.$db_prefix.'opc.quantity) - ('.getRealQuery($qty_product_sum_devolutions).') ) < 0');
        
        $devolution_orders = Order::selectRaw($db_prefix.'orders.*,'.$db_prefix.'order_out.slug as slug_out,'.$db_prefix.'order_out.code as code_out, ('.getRealQuery($count_op).') as broken_products,(('.getRealQuery($qty_out).') - ('.getRealQuery($qty_sum_devolutions).')) as qty_disp ')
                                  ->join('orders as order_out', 'order_out.id', '=', 'orders.out_order_id')
                                  ->where('orders.type', '=', 'devolution')
                                  ->offset($request->offset)
                                  ->limit($this->per_page)
                                  ->orderBy('orders.date', 'desc')
                                  ->orderBy('orders.is_processed', 'desc')
                                  ->orderBy('orders.code', 'desc')
                                  ->orderBy('orders.client_id', 'asc')
                                  ->get();
        
        foreach($devolution_orders as $order)
        {
            $products_qty = count($order->products);
            $description   = (strlen($order->description) > 0) ? $order->description : '<em class="text-muted">Without descriptions</em>';
            $products      = ($products_qty > 0) ? $products_qty : '<em class="text-muted">Without Products</em>';
            $details_error = '';
            $action_button = '';
            
            if($order->is_processed == 'Y')
            {
                $action_button = '<a href="'.route('order_product.index', [
                        'order_type' => 'ordenes-de-devolucion',
                        'order_code' => strtolower($order->code),
                    ]).'" class="btn btn-default" title="Check Products"><span class=" glyphicon glyphicon-folder-open" aria-hidden="true"></span></a>
                                            <a href="'.route('devolution_order.report',['order_code' => $order->code]).'" class="btn btn-info" title="Get Report"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>';
            }
            else
            {
                if($order->broken_products > 0)
                {
                    $details_error .= '<b class="broken_order"> The out order does not have products that can be returned into the warehouse</b><br>';
                }
    
                if($order->broken_products > 0)
                {
                    $details_error .= '<b class="broken_order"> One of the products of this order has an incorrect quantity</b><br>';
                }
    
                if($order->qty_disp <= 0)
                {
                    $action_button .= '<a href="#" id="delete_'.$order->id.'" class="btn btn-danger confirmation_delete_modal" title="Delete this order"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
                }
                else
                {
                    $action_button .= '<a href="'.route('order_product.index',['order_type' => 'ordenes-de-devolucion', 'order_code' => strtolower($order->code)]).'" class="btn btn-default" title="Manage Products"><span class=" glyphicon glyphicon-folder-open" aria-hidden="true"></span></a>';
        
                    if($order->broken_products > 0 || $products_qty == 0)
                    {
                        $action_button .= '
                        <button type="button" class="btn btn-warning" disabled><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                        ';
                    }
                    else
                    {
                        $action_button .= '
                        <a href="#" id="process_'.$order->id.'" class="btn btn-warning confirmation_process_modal" title="Proccess Order"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
                        ';
                    }
        
                    $action_button .= '
                    <a href="'.route('devolution_order.update',['slug' => $order->slug]).'" class="btn btn-primary" title="Edit this order"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                    <a href="#" id="delete_'.$order->id.'" class="btn btn-danger confirmation_delete_modal" title="Delete this order"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                    ';
                }
            }
            
            array_push($data, array(
                $order->code.'<br><a href="'.route('client.search', ['slug' => $order->client->slug]).'"><b>'.$order->client->name.'</b></a>',
                $details_error.
                '<b>Out order: <a href="'.route('out_order.search', ['slug' => $order->slug_out]).'" >'.$order->code_out.'</a></b><br>
                <b>Products :</b> '.$products.'<br>
                <b>Received by: </b>'.$order->received_by.'<br>
                <b>Delivered by: </b>'.$order->delivered_by.'<br>
                <b>Date: </b>'.custom_date_format($order->date).'<br>
                <b>Description: </b>'.$description,
                $action_button,
            ));
        }
        
        die(json_encode($data));
    }
    
    #agregar órden
    public function add()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Devolution order',
                'url'  => route('devolution_order.index'),
            ),
            array(
                'name' => 'Add',
            ),
        );
        
        $page_data['page_title']       = 'Add Devolution order';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'devolution_orders';
        $page_data['form_type']        = 'success';
        
        $db_prefix = getDBConfig();
        
        #suma del producto en devolucion
        $qty_product_sum_devolutions = \DB::table('order_product as op')
                                          ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                                          ->join('orders as o', 'o.id', '=', 'op.order_id')
                                          ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'opc.product_id')
                                          ->where('o.type', '=', 'devolution')
                                          ->where('o.is_processed', '=', 'Y')
                                          ->whereRaw($db_prefix.'o.client_id = '.$db_prefix.'orders.client_id')
                                          ->whereRaw($db_prefix.'o.out_order_id = '.$db_prefix.'orders.id');
        
        $where = '(('.$db_prefix.'opc.quantity) - ('.getRealQuery($qty_product_sum_devolutions).'))';

        $page_data['clients'] = Client::whereIn('id', function ($query) use ($where)
        {
            $query->distinct()
                  ->select('orders.client_id')
                  ->from('orders')
                  ->join('order_product as opc', 'opc.order_id', '=', 'orders.id')
                  ->where('orders.type', '=', 'out')
                  ->where('orders.is_processed', '=', 'Y')
                  ->whereRaw($where.' > 0 ');
        })
                                      ->orderBy('created_at', 'desc')->get();

        return view('backend.devolution_order.add', $page_data);
    }
    
    #guardar órden
    public function store(Request $request)
    {
        $order = new Order();
        
        #son valores que podrían cambiar con el respectivo formato, y ademas son únicos.
        $request->merge(
            [
                'date' => date('Y-m-d H:i:s', reverse_date($request->date.' '.date('H:i:s'), false, '/')),
                'type' => 'devolution',
            ]);
        
        $this->validate($request, $order->rules());
        
        $db_prefix = getDBConfig();
        
        #suma del producto en devolucion
        $qty_product_sum_devolutions = \DB::table('order_product as op')
                                          ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                                          ->join('orders as o', 'o.id', '=', 'op.order_id')
                                          ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'opc.product_id')
                                          ->where('o.type', '=', 'devolution')
                                          ->where('o.is_processed', '=', 'Y')
                                          ->whereRaw($db_prefix.'o.client_id = '.$db_prefix.'orders.client_id')
                                          ->whereRaw($db_prefix.'o.out_order_id = '.$request->out_order_id);
        
        $where = '(('.$db_prefix.'opc.quantity) - ('.getRealQuery($qty_product_sum_devolutions).'))';
        
        if(
        Order::join('order_product as opc', 'opc.order_id', '=', 'orders.id')
             ->where('client_id', '=', $request->client_id)
             ->where('orders.id', '=', $request->out_order_id)
             ->where('orders.type', '=', 'out')
             ->where('orders.is_processed', '=', 'Y')
             ->whereRaw($where.' > 0 ')
             ->exists()
        )
        {
            $order->out_order_id = $request->out_order_id;
            $order->client_id    = $request->client_id;
            $order->code         = 'D0'.substr('0000'.(intval(Order::selectRaw('(max(right(code,5))) as last_code')
                                                                   ->where('type', '=', 'devolution')
                                                                   ->first()->last_code) + 1), -5);
            $order->received_by  = camel_case_text(only_text($request->received_by), 3);
            $order->delivered_by = camel_case_text(only_text($request->delivered_by), 3);
            $order->type         = 'devolution';
            $order->is_processed = 'N';
            $order->date         = $request->date;
            $order->description  = $request->description;
            $order->slug         = slug($order->code);
            
            if(secureSave($order))
            {
                $data = array(
                    'activity' => 'You have added the devolution order <a href="'.route('devolution_order.search', ['slug' => $order->slug]).'">'.$order->code.'</a> of the client <a href="'.route('client.search', ['slug' => $order->client->slug]).'">'.$order->client->name.'</a>',
                    'icon'     => $this->icon.' bg-green',
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
            flash('¡EL cliente u órden que escogiste no tiene productos que puedan ser devueltos!', 'danger');
        }
        
        return redirect(route('devolution_order.index'));
    }
    
    #editar órden
    public function edit($slug = null)
    {
        $page_data['devolution_order'] = Order::where('type', '=', 'devolution')->where('slug', '=', $slug)->first();
        
        if(!is_null($page_data['devolution_order']))
        {
            if($page_data['devolution_order']->is_processed != 'Y')
            {
                $db_prefix = getDBConfig();
                
                #suma de todas las devoluciones
                $qty_sum_devolutions = \DB::table('order_product as op')
                                          ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                                          ->join('orders as o', 'o.id', '=', 'op.order_id')
                                          ->where('o.type', '=', 'devolution')
                                          ->where('o.is_processed', '=', 'Y')
                                          ->whereRaw($db_prefix.'o.client_id = '.$db_prefix.'orders.client_id')
                                          ->whereRaw($db_prefix.'o.out_order_id = '.$db_prefix.'orders.out_order_id');
    
                #suma del producto en devolucion
                $qty_product_sum_devolutions = \DB::table('order_product as op')
                                                  ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                                                  ->join('orders as o', 'o.id', '=', 'op.order_id')
                                                  ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'opc.product_id')
                                                  ->where('o.type', '=', 'devolution')
                                                  ->where('o.is_processed', '=', 'Y')
                                                  ->whereRaw($db_prefix.'o.client_id = '.$db_prefix.'orders.client_id')
                                                  ->whereRaw($db_prefix.'o.out_order_id = '.$db_prefix.'orders.out_order_id');
                
                #producto de salida
                $qty_out = \DB::table('order_product as op')
                              ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                              ->whereRaw($db_prefix.'op.order_id = '.$db_prefix.'orders.out_order_id');
    
                $page_data['out_orders'] = Order::distinct()
                                                ->select('orders.id', 'orders.code')
                                                ->join('order_product as opc', 'orders.id', '=', 'opc.order_id')
                                                ->where('orders.client_id', '=', $page_data['devolution_order']->client_id)
                                                ->where('orders.type', '=', 'out')
                                                ->where('orders.is_processed', '=', 'Y')
                                                ->whereRaw('(('.$db_prefix.'opc.quantity) - ('.getRealQuery($qty_product_sum_devolutions).')) > 0')
                                                ->get();
    
                if(count($page_data['out_orders']) == 0)
                {
                    flash('¡The out order does not have products that can be returned into the warehouse!', 'danger');
        
                    return redirect(route('devolution_order.index'));
                }
    
                $where = '(('.$db_prefix.'opc.quantity) - ('.getRealQuery($qty_product_sum_devolutions).'))';
                
                $page_data['breadcrumb'] = array(
                    array(
                        'name' => 'Devolution order',
                        'url'  => route('devolution_order.index'),
                    ),
                    array(
                        'name' => $page_data['devolution_order']->code,
                        'url'  => route('devolution_order.search', ['slug' => $page_data['devolution_order']->slug]),
                    ),
                    array(
                        'name' => 'Edit',
                    ),
                );
                
                $page_data['page_title']       = 'Edit Devolution order ';
                $page_data['active_module']    = $this->module;
                $page_data['active_submodule'] = 'devolution_orders';
                $page_data['form_type']        = 'primary';
                
                $page_data['clients'] = Client::whereIn('id', function ($query) use ($where)
                {
                    $query->distinct()
                          ->select('orders.client_id')
                          ->from('orders')
                          ->join('order_product as opc', 'opc.order_id', '=', 'orders.id')
                          ->where('orders.type', '=', 'out')
                          ->where('orders.is_processed', '=', 'Y')
                          ->whereRaw($where.' > 0 ');
                })
                                              ->orderBy('created_at', 'desc')->get();
                
                return view('backend.devolution_order.edit', $page_data);
            }
            else
            {
                flash('¡The devolution order '.$page_data['devolution_order']->code.' is proccessed and can not be edited!', 'danger');
                
                return redirect(route('devolution_order.index'));
            }
        }
        else
        {
            flash('¡The devolution order you want to edit does not exist!', 'danger');
            
            return redirect(route('devolution_order.index'));
        }
    }
    
    #actualizar órden
    public function update(Request $request, $slug = null)
    {
        $order = Order::where('type', '=', 'devolution')->where('slug', '=', $slug)->first();
        
        if(!is_null($order))
        {
            $old_values = $order->attributesToArray();
            $new_values = $request->except('_token');
            
            if(count(array_diff_assoc($new_values, $old_values)) > 0)
            {
                #son valores que podrían cambiar con el respectivo formato, y ademas son únicos.
                $request->merge(
                    [
                        'date' => date('Y-m-d H:i:s', reverse_date($request->date.' '.date('H:i:s'), false, '/')),
                        'type' => 'devolution',
                    ]);
                
                $this->validate($request, $order->rules());
    
                $db_prefix = getDBConfig();
    
                #suma del producto en devolucion
                $qty_product_sum_devolutions = \DB::table('order_product as op')
                                                  ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                                                  ->join('orders as o', 'o.id', '=', 'op.order_id')
                                                  ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'opc.product_id')
                                                  ->where('o.type', '=', 'devolution')
                                                  ->where('o.is_processed', '=', 'Y')
                                                  ->whereRaw($db_prefix.'o.client_id = '.$db_prefix.'orders.client_id')
                                                  ->whereRaw($db_prefix.'o.out_order_id = '.$order->out_order_id);
    
                $where = '(('.$db_prefix.'opc.quantity) - ('.getRealQuery($qty_product_sum_devolutions).'))';
    
                if(
                Order::join('order_product as opc', 'opc.order_id', '=', 'orders.id')
                     ->where('client_id', '=', $order->client_id)
                     ->where('orders.id', '=', $order->out_order_id)
                     ->where('orders.type', '=', 'out')
                     ->where('orders.is_processed', '=', 'Y')
                     ->whereRaw($where.' > 0 ')
                     ->exists()
                )
                {
                    
                    $order->received_by  = camel_case_text(only_text($request->received_by), 3);
                    $order->delivered_by = camel_case_text(only_text($request->delivered_by), 3);
                    $order->date         = $request->date;
                    $order->description  = $request->description;
    
                    if(secureSave($order))
                    {
                        $data = array(
                            'activity' => 'You have edited the devolution order <a href="'.route('devolution_order.search', ['slug' => $order->slug]).'">'.$order->code.'</a>',
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
                    flash('¡EL cliente u órden que escogiste no tiene productos que puedan ser devueltos!', 'danger');
                }
            }
            else
            {
                flash('¡There is not any changes!', 'info');
            }
        }
        else
        {
            flash('¡The devolution order you want to edit does not exist!', 'danger');
        }
        
        return redirect(route('devolution_order.index'));
    }
    
    #eliminar órden
    public function delete($id = null)
    {
        $order = Order::find($id);
        
        if(!is_null($order))
        {
            if($order->is_processed != 'Y')
            {
                if(secureDelete($order))
                {
                    $data = array(
                        'activity' => 'You have delete the devolution order <a href="#">'.$order->code.'</a> of the client <a href="'.route('client.search', ['slug' => $order->client->slug]).'">'.$order->client->name.'</a>',
                        'icon'     => $this->icon.' bg-red',
                    );
                    
                    add_recent_activity($data);
                    
                    flash('¡The Devolution order '.$order->code.' has been erased successfuly!', 'success');
                }
                else
                {
                    flash('¡It has happened an unknown error deleting the devolution order '.$order->code.'!', 'danger');
                }
            }
            else
            {
                flash('¡The devolution order '.$order->code.' is proccessed and it can not be deleted!', 'danger');
            }
        }
        else
        {
            flash('¡The Devolution order you want to delete does not exist in the system!', 'danger');
        }
        
        return redirect(route('devolution_order.index'));
    }
    
    public function process($id = null)
    {
        $order = Order::where('type', '=', 'devolution')->where('id', '=', $id)->first();
        
        if(!is_null($order))
        {
            if($order->is_processed != 'Y')
            {
                if($order->products->count() > 0)
                {
                    $db_prefix = getDBConfig();
    
                    #suma del producto en devolucion
                    $qty_product_sum_devolutions = \DB::table('order_product as op')
                                                      ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                                                      ->join('orders as o', 'o.id', '=', 'op.order_id')
                                                      ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'opc.product_id')
                                                      ->where('o.type', '=', 'devolution')
                                                      ->where('o.is_processed', '=', 'Y')
                                                      ->whereRaw($db_prefix.'o.client_id = '.$order->client_id)
                                                      ->whereRaw($db_prefix.'o.out_order_id = '.$order->out_order_id);
    
                    #producto de salida
                    $qty_product_out = \DB::table('order_product as op')
                                          ->selectRaw($db_prefix.'op.quantity')
                                          ->whereRaw($db_prefix.'op.product_id = '.$db_prefix.'opc.product_id')
                                          ->whereRaw($db_prefix.'op.order_id = '.$order->out_order_id);

                    if(\DB::table('order_product as opc')
                          ->whereRaw($db_prefix.'opc.order_id = '.$order->id)
                          ->whereRaw('( ('.getRealQuery($qty_product_out).') - ('.$db_prefix.'opc.quantity) - ('.getRealQuery($qty_product_sum_devolutions).') ) < 0')->count() == 0)
                    {
                        $order->is_processed = 'Y';
    
                        if(secureSave($order))
                        {
                            $data = array(
                                'activity' => 'You have proccessed the devolution order <a href="'.route('devolution_order.search', ['slug' => $order->slug]).'">'.$order->code.'</a> of the client <a href="'.route('client.search', ['slug' => $order->client->slug]).'">'.$order->client->name.'</a>',
                                'icon'     => $this->icon.' bg-yellow',
                            );
        
                            add_recent_activity($data);
                            
                            $this->generate_order($order->code);
        
                            flash('¡The Devolution order '.$order->code.' has been proccessed successfuly!', 'success');
                        }
                        else
                        {
                            flash('¡Ha ocurrido un error desconocido eliminando la órden de devolución '.$order->code.'!', 'danger');
                        }
                    }
                    else
                    {
                        flash('¡EL cliente u órden que escogiste no tiene productos que puedan ser devueltos!', 'danger');
                    }
                }
                else
                {
                    flash('¡La órden de devolución '.$order->code.' no tiene productos y no se puede procesar!', 'danger');
                }
            }
            else
            {
                flash('¡La órden de devolución '.$order->code.' ya se encuentra procesada!', 'info');
            }
        }
        else
        {
            flash('¡La Órden de Devolución a procesar no se encuentra en el sistema!', 'danger');
        }
        
        return redirect(route('devolution_order.index'));
    }
    
    public function getCountOrderProducts($type, $db_prefix = '')
    {
        return \DB::table(\DB::raw($db_prefix.'orders as '.$db_prefix.'o'))
                  ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                  ->join('order_product as op', 'o.id', '=', 'op.order_id')
                  ->where('o.type', '=', $type)
                  ->where('o.is_processed', '=', 'Y')
                  ->whereRaw($db_prefix.'opc.product_id = '.$db_prefix.'op.product_id');
    }
    
    public function report($order_code)
    {
        if(file_exists(assets_path('orders/devolution/'.$order_code.'.pdf')) && is_file(assets_path('orders/devolution/'.$order_code.'.pdf')))
        {
            return response()->download(assets_path('orders/devolution/'.$order_code.'.pdf'));
        }
        else
        {
            if($this->generate_order($order_code))
            {
                return response()->download(assets_path('orders/devolution/'.$order_code.'.pdf'));
            }
            else
            {
                flash('¡The Devolution order could not be gotten!', 'danger');
                
                return redirect(route('devolution_order.index'));
            }
        }
    }
    
    public function generate_order($order_code)
    {
        $jasper = new Jasper;
        
        $parameters = array(
            'order_type' => "'devolution'",
            'current_date' => "'".date('d/m/Y')."'",
            'current_hour' => "'".date('h:i A')."'",
            'order_code' => "'".$order_code."'",
        );
    
        $db_connection = array(
            'driver' => getDBConfig('driver'),
            'username' => getDBConfig('username'),
            'password' => getDBConfig('password'),
            'host' => getDBConfig('host'),
            'port' => getDBConfig('port'),
            'database' => getDBConfig('database'),
            'jdbc_driver' => 'com.mysql.jdbc.Driver',
            'jdbc_url' => 'jdbc:mysql://localhost:3306/nitcelis',
            'jdbc_dir' => base_path('vendor/cossou/jasperphp/src/JasperStarter/jdbc')
        );
        
        $jasper->process(
            base_path('resources/assets/reports/devolution_order.jasper'),
            assets_path('orders/devolution/'.$order_code),
            array('pdf'),
            $parameters,
            $db_connection,
            false
        )->execute();
        
        for($i = 0;$i < 5 ;$i++)
        {
            if(file_exists(assets_path('orders/devolution/'.$order_code.'.pdf')) && is_file(assets_path('orders/devolution/'.$order_code.'.pdf')))
            {
                return true;
                break;
            }
            else
            {
                sleep(1);
            }
        }
        
        return false;
    }
}