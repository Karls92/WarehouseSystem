<?php

namespace App\Http\Controllers\Backend;

use App\Models\Client;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Order;
use JasperPHP\JasperPHP as Jasper;

class EntryOrdersController extends Controller
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
        $count = 0;
        for($i = 0;$i < 600;$i++)
        {
            
        }
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Entry Orders',
                'url'  => route('entry_order.index'),
            ),
            array(
                'name' => 'List',
            ),
        );
        
        $page_data['page_title']       = 'List of Entries';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'entry_orders';
        $page_data['per_page']         = $this->per_page;
        
        if(is_null($slug))
        {
            $page_data['entry_orders'] = Order::where('type', '=', 'entry')
                                              ->limit($this->per_page)
                                              ->orderBy('date', 'desc')
                                              ->orderBy('is_processed', 'desc')
                                              ->orderBy('code', 'desc')
                                              ->orderBy('client_id', 'asc')
                                              ->get();
            
            $page_data['entry_orders_qty'] = Order::where('type', '=', 'entry')->count();
        }
        else
        {
            $page_data['entry_orders'] = Order::where('type', '=', 'entry')
                                              ->where('slug', '=', $slug)
                                              ->get();
            
            if(count($page_data['entry_orders']) == 0)
            {
                flash('¡La Órden de Entrada ha buscar no existe!', 'danger');
                
                return redirect(route('entry_order.index'));
            }
            
            $page_data['entry_orders_qty'] = 1;
        }
        
        return view('backend.entry_order.index', $page_data);
    }
    
    #ajax para cargar más órdenes
    public function ajax_orders(Request $request)
    {
        $data = array();
        
        $entry_orders = Order::where('type', '=', 'entry')
                             ->offset($request->offset)
                             ->limit($this->per_page)
                             ->orderBy('date', 'desc')
                             ->orderBy('is_processed', 'desc')
                             ->orderBy('code', 'desc')
                             ->orderBy('client_id', 'asc')
                             ->get();
        
        foreach($entry_orders as $order)
        {
            $products_qty = count($order->products);
            $description = (strlen($order->description) > 0) ? $order->description : '<em class="text-muted">Without description</em>';
            $products    = ($products_qty > 0) ? $products_qty : '<em class="text-muted">Without Products</em>';
            $action_button = '';
            
            if($order->is_processed == 'Y')
            {
                $action_button = '
                <a href="'.route('order_product.index', [
                        'order_type' => 'ordenes-de-entrada',
                        'order_code' => strtolower($order->code),
                    ]).'" class="btn btn-default" title="Check Products"><span class=" glyphicon glyphicon-folder-open" aria-hidden="true"></span></a>
                <a href="'.route('entry_order.report',['order_code' => $order->code]).'" class="btn btn-info" title="Get Report"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>
                ';
            }
            else
            {
                $action_button .= '
                <a href="'.route('order_product.index', [
                        'order_type' => 'ordenes-de-entrada',
                        'order_code' => strtolower($order->code),
                    ]).'" class="btn btn-default" title="Manege Products"><span class=" glyphicon glyphicon-folder-open" aria-hidden="true"></span></a>
                ';
    
                if($products_qty == 0)
                {
                    $action_button .= '<button type="button" class="btn btn-warning" disabled><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>';
                }
                else
                {
                    $action_button .= '<a href="#" id="process_'.$order->id.'" class="btn btn-warning confirmation_process_modal" title="Proccess Order"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>';
                }
    
                $action_button .= '
                <a href="'.route('entry_order.update', ['slug' => $order->slug]).'" class="btn btn-primary" title="Edit this order"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                <a href="#" id="delete_'.$order->id.'" class="btn btn-danger confirmation_delete_modal" title="Delete this order"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                ';
            }
            
            array_push($data, array(
                $order->code.'<br><a href="'.route('client.search', ['slug' => $order->client->slug]).'"><b>'.$order->client->name.'</b></a>',
                '<b>Products :</b> '.$products.'<br>
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
                'name' => 'Entry orders',
                'url'  => route('entry_order.index'),
            ),
            array(
                'name' => 'Add',
            ),
        );
        
        $page_data['page_title']       = 'Add Entry orders';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'entry_orders';
        $page_data['form_type']        = 'success';
        $page_data['clients']          = Client::all();
        
        return view('backend.entry_order.add', $page_data);
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
        
        $order->client_id    = $request->client_id;
        $order->code         = 'E0'.substr('0000'.(intval(Order::selectRaw('(max(right(code,5))) as last_code')
                                                               ->where('type', '=', 'entry')
                                                               ->first()->last_code) + 1), -5);
        $order->received_by  = camel_case_text(only_text($request->received_by), 3);
        $order->delivered_by = camel_case_text(only_text($request->delivered_by), 3);
        $order->type         = 'entry';
        $order->is_processed = 'N';
        $order->date         = $request->date;
        $order->description  = $request->description;
        $order->slug         = slug($order->code);
        
        if(secureSave($order))
        {
            $data = array(
                'activity' => 'You have added the entry order <a href="'.route('entry_order.search', ['slug' => $order->slug]).'">'.$order->code.'</a> of the client <a href="'.route('client.search', ['slug' => $order->client->slug]).'">'.$order->client->name.'</a>',
                'icon'     => $this->icon.' bg-green',
            );
            
            add_recent_activity($data);
            
            flash('¡It has saved the information successfuly!', 'success');
        }
        else
        {
            flash('¡It has saved the information successfuly, but It has happened an unknown error!', 'warning');
        }
        
        return redirect(route('entry_order.index'));
    }
    
    #editar órden
    public function edit($slug = null)
    {
        $page_data['entry_order'] = Order::where('type', '=', 'entry')->where('slug', '=', $slug)->first();
        
        if(!is_null($page_data['entry_order']))
        {
            if($page_data['entry_order']->is_processed != 'Y')
            {
                $page_data['breadcrumb'] = array(
                    array(
                        'name' => 'Entry orders',
                        'url'  => route('entry_order.index'),
                    ),
                    array(
                        'name' => $page_data['entry_order']->code,
                        'url'  => route('entry_order.search', ['slug' => $page_data['entry_order']->slug]),
                    ),
                    array(
                        'name' => 'Edit',
                    ),
                );
                
                $page_data['page_title']       = 'Edit Entry order';
                $page_data['active_module']    = $this->module;
                $page_data['active_submodule'] = 'entry_orders';
                $page_data['form_type']        = 'primary';
                $page_data['clients']          = Client::all();
                
                return view('backend.entry_order.edit', $page_data);
            }
            else
            {
                flash('¡La órden de entrada '.$page_data['entry_order']->code.' está procesada y no puede editarse!', 'danger');
                
                return redirect(route('entry_order.index'));
            }
        }
        else
        {
            flash('¡No existe la órden de entrada a editar!', 'danger');
            
            return redirect(route('entry_order.index'));
        }
    }
    
    #actualizar órden
    public function update(Request $request, $slug = null)
    {
        $order = Order::where('type', '=', 'entry')->where('slug', '=', $slug)->first();
        
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
                
                $order->client_id    = $request->client_id;
                $order->received_by  = camel_case_text(only_text($request->received_by), 3);
                $order->delivered_by = camel_case_text(only_text($request->delivered_by), 3);
                $order->date         = $request->date;
                $order->description  = $request->description;
                
                if(secureSave($order))
                {
                    $data = array(
                        'activity' => 'You have edited the entry order <a href="'.route('entry_order.search', ['slug' => $order->slug]).'">'.$order->code.'</a>',
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
        
        return redirect(route('entry_order.index'));
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
                        'activity' => 'You have delete the entry order <a href="#">'.$order->code.'</a> del cliente <a href="'.route('client.search', ['slug' => $order->client->slug]).'">'.$order->client->name.'</a>',
                        'icon'     => $this->icon.' bg-red',
                    );
                    
                    add_recent_activity($data);
                    
                    flash('¡The entry order '.$order->code.' has been erased successfuly!', 'success');
                }
                else
                {
                    flash('¡Ha ocurrido un error desconocido eliminando la órden de entrada '.$order->code.'!', 'danger');
                }
            }
            else
            {
                flash('¡La órden de entrada '.$order->code.' está procesada y no puede eliminarse!', 'danger');
            }
        }
        else
        {
            flash('¡La Órden de Entrada a eliminar no se encuentra en el sistema!', 'danger');
        }
        
        return redirect(route('entry_order.index'));
    }
    
    public function process($id = null)
    {
        $order = Order::where('type', '=', 'entry')->where('id', '=', $id)->first();
        
        if(!is_null($order))
        {
            if($order->is_processed != 'Y')
            {
                if($order->products->count() > 0)
                {
                    $order->is_processed = 'Y';
                    
                    if(secureSave($order))
                    {
                        $data = array(
                            'activity' => 'you have proccessed the entry order <a href="'.route('entry_order.search', ['slug' => $order->slug]).'">'.$order->code.'</a> of the client <a href="'.route('client.search', ['slug' => $order->client->slug]).'">'.$order->client->name.'</a>',
                            'icon'     => $this->icon.' bg-yellow',
                        );
                        
                        add_recent_activity($data);
                        
                        $this->generate_order($order->code);
                        
                        flash('¡The Entry order '.$order->code.' It has been proccessed successfuly!', 'success');
                    }
                    else
                    {
                        flash('¡Ha ocurrido un error desconocido eliminando la órden de entrada '.$order->code.'!', 'danger');
                    }
                }
                else
                {
                    flash('¡La órden de entrada '.$order->code.' no tiene productos y no se puede procesar!', 'danger');
                }
            }
            else
            {
                flash('¡La órden de entrada '.$order->code.' ya se encuentra procesada!', 'info');
            }
        }
        else
        {
            flash('¡La Órden de Entrada a procesar no se encuentra en el sistema!', 'danger');
        }
        
        return redirect(route('entry_order.index'));
    }
    
    public function report($order_code)
    {
        if(file_exists(assets_path('orders/entry/'.$order_code.'.pdf')) && is_file(assets_path('orders/entry/'.$order_code.'.pdf')))
        {
            return response()->download(assets_path('orders/entry/'.$order_code.'.pdf'));
        }
        else
        {
            if($this->generate_order($order_code))
            {
                return response()->download(assets_path('orders/entry/'.$order_code.'.pdf'));
            }
            else
            {
                flash('¡The entry order could not be gotten!', 'danger');
                
                return redirect(route('entry_order.index'));
            }
        }
    }
    
    public function generate_order($order_code)
    {
        $jasper = new Jasper;
    
        $parameters = array(
            'order_type' => "'entry'",
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
            base_path('resources/assets/reports/entry_order.jasper'),
            assets_path('orders/entry/'.$order_code),
            array('pdf'),
            $parameters,
            $db_connection,
            false
        )->execute();
    
        for($i = 0;$i < 5 ;$i++)
        {
            if(file_exists(assets_path('orders/entry/'.$order_code.'.pdf')) && is_file(assets_path('orders/entry/'.$order_code.'.pdf')))
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