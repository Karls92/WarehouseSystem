<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Client;
use JasperPHP\JasperPHP as Jasper;

class OutOrdersController extends Controller
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
                'name' => 'Out Orders',
                'url'  => route('out_order.index'),
            ),
            array(
                'name' => 'Lists',
            ),
        );
        
        $page_data['page_title']       = 'List of Out orders';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'out_orders';
        $page_data['per_page']         = $this->per_page;
        
        $db_prefix = getDBConfig();
        
        $sum_entries    = $this->getSUMOrdersInSelect('entry', 'fromSubQuery', $db_prefix);
        $sum_outs       = $this->getSUMOrdersInSelect('out', 'fromSubQuery', $db_prefix);
        $sum_devolution = $this->getSUMOrdersInSelect('devolution', 'fromSubQuery', $db_prefix);
        
        $count_entries    = $this->getCountOrderProducts('entry', $db_prefix);
        $count_outs       = $this->getCountOrderProducts('out', $db_prefix);
        $count_devolutions = $this->getCountOrderProducts('devolution', $db_prefix);
        
        $count_op = \DB::table('order_product as opc')
                       ->selectRaw('count(*)')
                       ->whereRaw($db_prefix.'opc.order_id = '.$db_prefix.'orders.id')
                       ->whereRaw('(('.getRealQuery($count_entries).') + ('.getRealQuery($count_devolutions).') - ('.getRealQuery($count_outs).') - '.$db_prefix.'opc.quantity) < 0');

        if(is_null($slug))
        {
            $page_data['out_orders'] = Order::selectRaw($db_prefix.'orders.*, (('.getRealQuery($sum_entries).') + ('.getRealQuery($sum_devolution).') - ('.getRealQuery($sum_outs).')) as qty_disp, ('.getRealQuery($count_op).') as broken_products')
                                            ->where('type', '=', 'out')
                                            ->limit($this->per_page)
                                            ->orderBy('date', 'desc')
                                            ->orderBy('is_processed', 'desc')
                                            ->orderBy('code', 'desc')
                                            ->orderBy('client_id', 'asc')
                                            ->get();

            $page_data['out_orders_qty'] = Order::where('type', '=', 'out')->count();
        }
        else
        {
            $page_data['out_orders'] = Order::selectRaw($db_prefix.'orders.*, (('.getRealQuery($sum_entries).') + ('.getRealQuery($sum_devolution).') - ('.getRealQuery($sum_outs).')) as qty_disp, ('.getRealQuery($count_op).') as broken_products')
                                            ->where('type', '=', 'out')
                                            ->where('slug', '=', $slug)
                                            ->get();
            
            if(count($page_data['out_orders']) == 0)
            {
                flash('¡La Órden de Salida ha buscar no existe!', 'danger');
                
                return redirect(route('out_order.index'));
            }
            
            $page_data['out_orders_qty'] = 1;
        }
        
        return view('backend.out_order.index', $page_data);
    }
    
    #ajax para cargar más órdenes
    public function ajax_orders(Request $request)
    {
        $data = array();
        
        $db_prefix = getDBConfig();
        
        $sum_entries    = $this->getSUMOrdersInSelect('entry', 'fromSubQuery', $db_prefix);
        $sum_outs       = $this->getSUMOrdersInSelect('out', 'fromSubQuery', $db_prefix);
        $sum_devolution = $this->getSUMOrdersInSelect('devolution', 'fromSubQuery', $db_prefix);
    
        $count_entries    = $this->getCountOrderProducts('entry', $db_prefix);
        $count_outs       = $this->getCountOrderProducts('out', $db_prefix);
        $count_devolutions = $this->getCountOrderProducts('devolution', $db_prefix);
    
        $count_op = \DB::table('order_product as opc')
                       ->selectRaw('count(*)')
                       ->whereRaw($db_prefix.'opc.order_id = '.$db_prefix.'orders.id')
                       ->whereRaw('(('.getRealQuery($count_entries).') + ('.getRealQuery($count_devolutions).') - ('.getRealQuery($count_outs).') - '.$db_prefix.'opc.quantity) < 0');
        
        $out_orders = Order::selectRaw($db_prefix.'orders.*, (('.getRealQuery($sum_entries).') + ('.getRealQuery($sum_devolution).') - ('.getRealQuery($sum_outs).')) as qty_disp, ('.getRealQuery($count_op).') as broken_products')
                           ->where('type', '=', 'out')
                           ->offset($request->offset)
                           ->limit($this->per_page)
                           ->orderBy('date', 'desc')
                           ->orderBy('is_processed', 'desc')
                           ->orderBy('code', 'desc')
                           ->orderBy('client_id', 'asc')
                           ->get();
        
        foreach($out_orders as $order)
        {
            $products_qty = count($order->products);
            $description = (strlen($order->description) > 0) ? $order->description : '<em class="text-muted">Without description</em>';
            $products    = ($products_qty > 0) ? $products_qty : '<em class="text-muted">Without Products</em>';
            $details_error = '';
            $action_button = '';
           
            if($order->is_processed == 'Y')
            {
                $action_button = '<a href="'.route('order_product.index', [
                        'order_type' => 'ordenes-de-salida',
                        'order_code' => strtolower($order->code),
                    ]).'" class="btn btn-default" title="Check Products"><span class=" glyphicon glyphicon-folder-open" aria-hidden="true"></span></a>
                <a href="'.route('out_order.report',['order_code' => $order->code]).'" class="btn btn-info" title="Get Report"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>';
            }
            else
            {
                if($order->qty_disp <= 0)
                {
                    $details_error .= '<b class="broken_order"> El cliente no tiene productos en el inventario</b><br>';
                }
    
                if($order->broken_products > 0)
                {
                    $details_error .= '<b class="broken_order"> Uno de los productos de ésta orden, tiene una cantidad incorrecta</b><br>';
                }
    
    
                if($order->qty_disp <= 0)
                {
                    $action_button .= '<a href="#" id="delete_'.$order->id.'" class="btn btn-danger confirmation_delete_modal" title="Delete this order"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
                }
                else
                {
                    $action_button .= '<a href="'.route('order_product.index',['order_type' => 'ordenes-de-salida', 'order_code' => strtolower($order->code)]).'" class="btn btn-default" title="Manege Products"><span class=" glyphicon glyphicon-folder-open" aria-hidden="true"></span></a>';
        
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
                    <a href="'.route('out_order.update',['slug' => $order->slug]).'" class="btn btn-primary" title="Edit this order"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                    <a href="#" id="delete_'.$order->id.'" class="btn btn-danger confirmation_delete_modal" title="Delete this order"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                    ';
                }
            }
            
            array_push($data, array(
                $order->code.'<br><a href="'.route('client.search', ['slug' => $order->client->slug]).'"><b>'.$order->client->name.'</b></a>',
                $details_error.
                '<b>Products :</b> '.$products.'<br>
                <b>Delivered by: </b>'.$order->delivered_by.'<br>
                <b>Received by: </b>'.$order->received_by.'<br>
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
                'name' => 'Out order',
                'url'  => route('out_order.index'),
            ),
            array(
                'name' => 'Add',
            ),
        );
        
        $page_data['page_title']       = 'Add Out order';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'out_orders';
        $page_data['form_type']        = 'success';
        
        $db_prefix = getDBConfig();
        
        $sum_entries    = $this->getSUMOrders('entry', 'fromSubQuery', $db_prefix);
        $sum_outs       = $this->getSUMOrders('out', 'fromSubQuery', $db_prefix);
        $sum_devolution = $this->getSUMOrders('devolution', 'fromSubQuery', $db_prefix);
        
        $page_data['clients'] = Client::distinct()->select('clients.id', 'clients.name')
                                      ->whereRaw('(('.$sum_entries->toSql().') + ('.$sum_devolution->toSql().') - ('.$sum_outs->toSql().')) > 0')
                                      ->mergeBindings($sum_entries->getQuery())
                                      ->mergeBindings($sum_devolution->getQuery())
                                      ->mergeBindings($sum_outs->getQuery())
                                      ->get();
        
        return view('backend.out_order.add', $page_data);
    }
    
    #guardar órden
    public function store(Request $request)
    {
        $order = new Order();
        
        #son valores que podrían cambiar con el respectivo formato, y ademas son únicos.
        $request->merge(
            [
                'date' => date('Y-m-d H:i:s', reverse_date($request->date.' '.date('H:i:s'), false, '/')),
            ]);
        
        $this->validate($request, $order->rules());
        
        $db_prefix = getDBConfig();
        
        $sum_entries    = $this->getSUMOrders('entry', 'fromSubQuery', $db_prefix);
        $sum_outs       = $this->getSUMOrders('out', 'fromSubQuery', $db_prefix);
        $sum_devolution = $this->getSUMOrders('devolution', 'fromSubQuery', $db_prefix);
        
        if(Client::where('clients.id', '=', $request->client_id)
                 ->whereRaw('(('.$sum_entries->toSql().') + ('.$sum_devolution->toSql().') - ('.$sum_outs->toSql().')) > 0')
                 ->mergeBindings($sum_entries->getQuery())
                 ->mergeBindings($sum_devolution->getQuery())
                 ->mergeBindings($sum_outs->getQuery())
                 ->exists()
        )
        {
            $order->client_id    = $request->client_id;
            $order->code         = 'S0'.substr('0000'.(intval(Order::selectRaw('(max(right(code,5))) as last_code')
                                                                   ->where('type', '=', 'out')
                                                                   ->first()->last_code) + 1), -5);
            $order->received_by  = camel_case_text(only_text($request->received_by), 3);
            $order->delivered_by = camel_case_text(only_text($request->delivered_by), 3);
            $order->type         = 'out';
            $order->is_processed = 'N';
            $order->date         = $request->date;
            $order->description  = $request->description;
            $order->slug         = slug($order->code);
            
            if(secureSave($order))
            {
                $data = array(
                    'activity' => 'you have added the out order <a href="'.route('out_order.search', ['slug' => $order->slug]).'">'.$order->code.'</a> of the client <a href="'.route('client.search', ['slug' => $order->client->slug]).'">'.$order->client->name.'</a>',
                    'icon'     => $this->icon.' bg-green',
                );
                
                add_recent_activity($data);
                
                flash('¡It has saved the information successfuly!', 'success');
            }
            else
            {
                flash('¡It has saved the information successfuly, pero ha ocurrido un error desconocido!', 'warning');
            }
        }
        else
        {
            flash('¡EL cliente que escogiste no tiene productos dentro del inventario!', 'danger');
        }
        
        return redirect(route('out_order.index'));
    }
    
    #editar órden
    public function edit($slug = null)
    {
        $page_data['out_order'] = Order::where('type', '=', 'out')->where('slug', '=', $slug)->first();
        
        if(!is_null($page_data['out_order']))
        {
            if($page_data['out_order']->is_processed != 'Y')
            {
                $db_prefix = getDBConfig();
                
                $sum_entries    = $this->getSUMOrders('entry', 'fromSubQuery', $db_prefix);
                $sum_outs       = $this->getSUMOrders('out', 'fromSubQuery', $db_prefix);
                $sum_devolution = $this->getSUMOrders('devolution', 'fromSubQuery', $db_prefix);
                
                if(Client::where('clients.id', '=', $page_data['out_order']->client_id)
                         ->whereRaw('(('.$sum_entries->toSql().') + ('.$sum_devolution->toSql().') - ('.$sum_outs->toSql().')) > 0')
                         ->mergeBindings($sum_entries->getQuery())
                         ->mergeBindings($sum_devolution->getQuery())
                         ->mergeBindings($sum_outs->getQuery())
                         ->exists()
                )
                {
                    $page_data['breadcrumb'] = array(
                        array(
                            'name' => 'Out orders',
                            'url'  => route('out_order.index'),
                        ),
                        array(
                            'name' => $page_data['out_order']->code,
                            'url'  => route('out_order.search', ['slug' => $page_data['out_order']->slug]),
                        ),
                        array(
                            'name' => 'Edit',
                        ),
                    );
                    
                    $page_data['page_title']       = 'Edit Out orders';
                    $page_data['active_module']    = $this->module;
                    $page_data['active_submodule'] = 'out_orders';
                    $page_data['form_type']        = 'primary';
                    
                    $db_prefix = getDBConfig();
                    
                    $sum_entries    = $this->getSUMOrders('entry', 'fromSubQuery', $db_prefix);
                    $sum_outs       = $this->getSUMOrders('out', 'fromSubQuery', $db_prefix);
                    $sum_devolution = $this->getSUMOrders('devolution', 'fromSubQuery', $db_prefix);
                    
                    $page_data['clients'] = Client::distinct()->select('clients.id', 'clients.name')
                                                  ->whereRaw('(('.$sum_entries->toSql().') + ('.$sum_devolution->toSql().') - ('.$sum_outs->toSql().')) > 0')
                                                  ->mergeBindings($sum_entries->getQuery())
                                                  ->mergeBindings($sum_devolution->getQuery())
                                                  ->mergeBindings($sum_outs->getQuery())
                                                  ->get();
                    
                    /*->orWhere('clients.id', '=', $page_data['out_order']->client_id)*/
                    
                    return view('backend.out_order.edit', $page_data);
                }
                else
                {
                    flash('¡Ya el cliente '.$page_data['out_order']->client->name.' no tiene productos dentro del inventario!', 'danger');
                    
                    return redirect(route('out_order.index'));
                }
            }
            else
            {
                flash('¡La órden de salida '.$page_data['out_order']->code.' está procesada y no puede editarse!', 'danger');
                
                return redirect(route('out_order.index'));
            }
        }
        else
        {
            flash('¡No existe la órden de salida a editar!', 'danger');
            
            return redirect(route('out_order.index'));
        }
    }
    
    #actualizar órden
    public function update(Request $request, $slug = null)
    {
        $order = Order::where('type', '=', 'out')->where('slug', '=', $slug)->first();
        
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
                    ]);
                
                $this->validate($request, $order->rules());
                
                $order->received_by  = camel_case_text(only_text($request->received_by), 3);
                $order->delivered_by = camel_case_text(only_text($request->delivered_by), 3);
                $order->date         = $request->date;
                $order->description  = $request->description;
                
                if(secureSave($order))
                {
                    $data = array(
                        'activity' => 'you have edited the out order <a href="'.route('out_order.search', ['slug' => $order->slug]).'">'.$order->code.'</a>',
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
                flash('¡There is not any changes!', 'info');
            }
        }
        else
        {
            flash('¡No existe la órden a editar!', 'danger');
        }
        
        return redirect(route('out_order.index'));
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
                        'activity' => 'you have deleted the out order <a href="#">'.$order->code.'</a> of the client <a href="'.route('client.search', ['slug' => $order->client->slug]).'">'.$order->client->name.'</a>',
                        'icon'     => $this->icon.' bg-red',
                    );
                    
                    add_recent_activity($data);
                    
                    flash('¡The out order '.$order->code.' has been erased successfuly!', 'success');
                }
                else
                {
                    flash('¡Ha ocurrido un error desconocido eliminando la órden de salida '.$order->code.'!', 'danger');
                }
            }
            else
            {
                flash('¡La órden de salida '.$order->code.' está procesada y no puede eliminarse!', 'danger');
            }
        }
        else
        {
            flash('¡La Órden de Salida a eliminar no se encuentra en el sistema!', 'danger');
        }
        
        return redirect(route('out_order.index'));
    }
    
    public function process($id = null)
    {
        $order = Order::where('type', '=', 'out')->where('id', '=', $id)->first();
        
        if(!is_null($order))
        {
            if($order->is_processed != 'Y')
            {
                if($order->products->count() >= 0)
                {
                    $db_prefix = getDBConfig();
    
                    $sum_entries    = $this->getSUMOrders('entry', 'fromSubQuery', $db_prefix);
                    $sum_outs       = $this->getSUMOrders('out', 'fromSubQuery', $db_prefix);
                    $sum_devolution = $this->getSUMOrders('devolution', 'fromSubQuery', $db_prefix);
                    
                    if(Client::where('clients.id', '=', $order->client_id)
                             ->whereRaw('(('.$sum_entries->toSql().') + ('.$sum_devolution->toSql().') - ('.$sum_outs->toSql().')) > 0')
                             ->mergeBindings($sum_entries->getQuery())
                             ->mergeBindings($sum_devolution->getQuery())
                             ->mergeBindings($sum_outs->getQuery())
                             ->exists()
                    )
                    {
                        $count_entries    = $this->getCountOrderProducts('entry', $db_prefix);
                        $count_outs       = $this->getCountOrderProducts('out', $db_prefix);
                        $count_devolutions = $this->getCountOrderProducts('devolution', $db_prefix);
    
                        if(!\DB::table('orders')
                            ->join('order_product as opc', 'orders.id', '=', 'opc.order_id')
                                       ->whereRaw($db_prefix.'opc.order_id = '.$order->id)
                                       ->whereRaw('(('.getRealQuery($count_entries).') + ('.getRealQuery($count_devolutions).') - ('.getRealQuery($count_outs).') - '.$db_prefix.'opc.quantity) < 0')->exists())
                        {
                            $order->is_processed = 'Y';
    
                            if(secureSave($order))
                            {
                                $data = array(
                                    'activity' => 'you have proccessed the out order <a href="'.route('out_order.search', ['slug' => $order->slug]).'">'.$order->code.'</a> of the client <a href="'.route('client.search', ['slug' => $order->client->slug]).'">'.$order->client->name.'</a>',
                                    'icon'     => $this->icon.' bg-yellow',
                                );
        
                                add_recent_activity($data);
                                
                                $this->generate_order($order->code);
        
                                flash('¡The out order '.$order->code.' has been proccessed successfuly!', 'success');
                            }
                            else
                            {
                                flash('¡Ha ocurrido un error desconocido eliminando la órden de salida '.$order->code.'!', 'danger');
                            }
                        }
                        else
                        {
                            flash('¡Uno de los productos de la orden '.$order->code.', tiene una cantidad incorrecta!', 'danger');
                        }
                    }
                    else
                    {
                        flash('¡EL cliente no tiene productos dentro del inventario y no se puede procesar!', 'danger');
                    }
                }
                else
                {
                    flash('¡La órden de entrada '.$order->code.' no tiene productos y no se puede procesar!', 'danger');
                }
            }
            else
            {
                flash('¡La órden de salida '.$order->code.' ya se encuentra procesada!', 'info');
            }
        }
        else
        {
            flash('¡La Órden de Salida a procesar no se encuentra en el sistema!', 'danger');
        }
        
        return redirect(route('out_order.index'));
    }
    
    public function getSUMOrders($type, $client_id = 'fromSubQuery', $db_prefix = '')
    {
        $builder = Order::selectRaw('COALESCE(SUM('.$db_prefix.'order_product.quantity),0)')
                        ->join('order_product', 'orders.id', '=', 'order_product.order_id')
                        ->where('orders.type', '=', $type)
                        ->where('orders.is_processed', '=', 'Y');
        
        if(!is_null($client_id) && is_numeric($client_id))
        {
            $builder = $builder->where('orders.client_id', '=', $client_id);
        }
        elseif($client_id == 'fromSubQuery')
        {
            $builder = $builder->whereRaw($db_prefix.'orders.client_id = '.$db_prefix.'clients.id');
        }
        
        return $builder;
    }
    
    public function getSUMOrdersInSelect($type, $client_id = 'fromSubQuery', $db_prefix = '')
    {
        $builder = \DB::table(\DB::raw($db_prefix.'orders '.$db_prefix.'o'))
                      ->selectRaw('COALESCE(SUM('.$db_prefix.'order_product.quantity),0)')
                      ->join('order_product', 'o.id', '=', 'order_product.order_id')
                      ->where('o.type', '=', $type)
                      ->where('o.is_processed', '=', 'Y');
        
        if(!is_null($client_id) && is_numeric($client_id))
        {
            $builder = $builder->where('orders.client_id', '=', $client_id);
        }
        elseif($client_id == 'fromSubQuery')
        {
            $builder = $builder->whereRaw($db_prefix.'orders.client_id = '.$db_prefix.'o.client_id');
        }
        
        return $builder;
    }
    
    public function getCountOrderProducts($type, $db_prefix = '')
    {
        return \DB::table(\DB::raw($db_prefix.'orders as '.$db_prefix.'o'))
                  ->selectRaw('COALESCE(SUM('.$db_prefix.'op.quantity),0)')
                  ->join('order_product as op', 'o.id', '=', 'op.order_id')
                  ->where('o.type', '=', $type)
                  ->where('o.is_processed', '=', 'Y')
            ->whereRaw($db_prefix.'orders.client_id = '.$db_prefix.'o.client_id')
                  ->whereRaw($db_prefix.'opc.product_id = '.$db_prefix.'op.product_id');
    }
    
    public function report($order_code)
    {
        if(file_exists(assets_path('orders/out/'.$order_code.'.pdf')) && is_file(assets_path('orders/out/'.$order_code.'.pdf')))
        {
            return response()->download(assets_path('orders/out/'.$order_code.'.pdf'));
        }
        else
        {
            if($this->generate_order($order_code))
            {
                return response()->download(assets_path('orders/out/'.$order_code.'.pdf'));
            }
            else
            {
                flash('¡The out order could not be gotten!', 'danger');
                
                return redirect(route('out_order.index'));
            }
        }
    }
    
    public function generate_order($order_code)
    {
        $jasper = new Jasper;
        
        $parameters = array(
            'order_type' => "'out'",
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
            base_path('resources/assets/reports/out_order.jasper'),
            assets_path('orders/out/'.$order_code),
            array('pdf'),
            $parameters,
            $db_connection,
            false
        )->execute();
        
        for($i = 0;$i < 5 ;$i++)
        {
            if(file_exists(assets_path('orders/out/'.$order_code.'.pdf')) && is_file(assets_path('orders/out/'.$order_code.'.pdf')))
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