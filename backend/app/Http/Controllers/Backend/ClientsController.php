<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\City;
use App\Models\Client;

class ClientsController extends Controller
{
    private $icon;
    private $module;
    private $per_page;
    
    public function __construct()
    {
        $this->module   = 'clients';
        $this->icon     = site_config()['lateral_elements'][$this->module]['details']['icon'];
        $this->per_page = 100;
    }
    
    /**
     * Gestionar Clientes
     */
    
    #listar clientes
    public function index($slug = null)
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Clients',
                'url'  => route('client.index'),
            ),
            array(
                'name' => 'Lists',
            ),
        );
        
        $page_data['page_title']    = 'Lists of Clients';
        $page_data['active_module'] = $this->module;
        $page_data['active_module'] = 'clients';
        $page_data['per_page']      = $this->per_page;
        
        if(is_null($slug))
        {
            $page_data['clients'] = Client::limit($this->per_page)
                                          ->orderBy('created_at', 'desc')
                                          ->get();
            
            $page_data['clients_qty'] = Client::count();
        }
        else
        {
            $page_data['clients'] = Client::where('slug', '=', $slug)
                                          ->get();
            
            if(count($page_data['clients']) == 0)
            {
                flash('¡El Cliente ha buscar no existe!', 'danger');
                
                return redirect(route('client.index'));
            }
            
            $page_data['clients_qty'] = 1;
        }
        
        return view('backend.client.index', $page_data);
    }
    
    #ajax para cargar más clientes
    public function ajax_clients(Request $request)
    {
        $data = array();
        
        $clients = Client::offset($request->offset)->limit($this->per_page)->orderBy('created_at', 'desc')->get();
        
        foreach($clients as $client)
        {
            $phone_1     = (strlen($client->phone_1) > 5) ? $client->phone_1 : '<em class="text-muted">Without telephone</em>';
            $phone_2     = (strlen($client->phone_2) > 5) ? $client->phone_2 : '<em class="text-muted">Without telephone</em>';
            $email       = (strlen($client->email) > 5) ? $client->email : '<em class="text-muted">Without email</em>';
            $description = (strlen($client->description) > 0) ? $client->description : '<em class="text-muted">Without observations</em>';
            
            array_push($data, array(
                $client->name.'<br>'.$client->document,
                '<b>City: <a href="'.route('city.search', ['slug' => $client->city->slug]).'">'.$client->city->name.'</a></b><br>
                <b>Code: </b>'.$client->client_code.'<br>
                <b>Telephone 1: </b>'.$phone_1.'<br>
                <b>Telephone 2: </b>'.$phone_2.'<br>
                <b>Email: </b>'.$email.'<br>
                <b>Observations: </b>'.$description,
                '<a href="'.route('client.update', ['slug' => $client->slug]).'" class="btn btn-primary" title="Edit this client"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                 <a href="#" id="delete_'.$client->id.'" class="btn btn-danger confirmation_delete_modal" title="Delete this client"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>',
            ));
        }
        
        die(json_encode($data));
    }
    
    #agregar cliente
    public function add()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Clients',
                'url'  => route('client.index'),
            ),
            array(
                'name' => 'Add',
            ),
        );
        
        $page_data['page_title']    = 'Add new Client';
        $page_data['active_module'] = $this->module;
        $page_data['active_module'] = 'clients';
        $page_data['form_type']     = 'success';
        $page_data['states']        = State::all();
        
        return view('backend.client.add', $page_data);
    }
    
    #guardar cliente
    public function store(Request $request)
    {
        $client = new Client();
        
        #son valores que podrían cambiar con el respectivo formato, y ademas son únicos.
        $request->merge(
            [
                'phone_1'     => str_replace('_', '', $request->phone_1),
                'phone_2'     => str_replace('_', '', $request->phone_2),
                'client_code' => strtoupper(str_replace(' ', '', $request->client_code)),
            ]);
        
        $this->validate($request, $client->rules());
        
        $client->client_code = $request->client_code;
        $client->name        = remove_unnecessary_spaces($request->name);
        $client->document    = strtoupper(str_replace(' ', '', $request->document));
        $client->address     = remove_unnecessary_spaces($request->address);
        $client->city_id     = $request->city_id;
        $client->phone_1     = $request->phone_1;
        $client->phone_2     = $request->phone_2;
        $client->email       = strtolower(trim($request->email));
        $client->description = remove_unnecessary_spaces($request->description);
        
        #creacion del slug
        $slug  = slug($client->name);
        $count = 2;
        
        while(Client::where('slug', '=', $slug)->count() > 0)
        {
            $slug = slug($client->name.' '.$count);
            $count++;
        }
        
        $client->slug = $slug;
        
        if(secureSave($client))
        {
            $data = array(
                'activity' => 'You have added the client <a href="'.route('client.search', ['slug' => $client->slug]).'">'.$client->name.'</a>',
                'icon'     => $this->icon.' bg-green',
            );
            
            add_recent_activity($data);
            
            flash('¡It has saved the information successfuly!', 'success');
        }
        else
        {
            flash('¡It has happened an error saving the information!', 'danger');
        }
        
        return redirect(route('client.index'));
    }
    
    #editar cliente
    public function edit($slug = null)
    {
        $page_data['client'] = Client::where('slug', '=', $slug)->first();
        
        if(!is_null($page_data['client']))
        {
            $page_data['breadcrumb'] = array(
                array(
                    'name' => 'Clients',
                    'url'  => route('client.index'),
                ),
                array(
                    'name' => $page_data['client']->name,
                    'url'  => route('client.search', ['slug' => $page_data['client']->slug]),
                ),
                array(
                    'name' => 'Edit',
                ),
            );
            
            $page_data['page_title']    = 'Edit Client';
            $page_data['active_module'] = $this->module;
            $page_data['active_module'] = 'clients';
            $page_data['form_type']     = 'primary';
            $page_data['states']        = State::all();
            $page_data['cities']        = City::where('state_id', '=', $page_data['client']->city->state_id)->get();
            
            return view('backend.client.edit', $page_data);
        }
        else
        {
            flash('¡The client you want to edit does not exist!', 'danger');
            
            return redirect(route('client.index'));
        }
    }
    
    #actualizar cliente
    public function update(Request $request, $slug = null)
    {
        $client = Client::where('slug', '=', $slug)->first();
        
        if(!is_null($client))
        {
            $old_values = $client->attributesToArray();
            $new_values = $request->except('_token');
            
            if(count(array_diff_assoc($new_values, $old_values)) > 0)
            {
                #son valores que podrían cambiar con el respectivo formato, y ademas son únicos.
                $request->merge(
                    [
                        'phone_1'     => str_replace('_', '', $request->phone_1),
                        'phone_2'     => str_replace('_', '', $request->phone_2),
                        'client_code' => strtoupper(str_replace(' ', '', $request->client_code)),
                    ]);
                
                $this->validate($request, $client->rules());
                
                $client->client_code = $request->client_code;
                $client->name        = remove_unnecessary_spaces($request->name);
                $client->document    = strtoupper(str_replace(' ', '', $request->document));
                $client->address     = remove_unnecessary_spaces($request->address);
                $client->city_id     = $request->city_id;
                $client->phone_1     = $request->phone_1;
                $client->phone_2     = $request->phone_2;
                $client->email       = strtolower(trim($request->email));
                $client->description = remove_unnecessary_spaces($request->description);
                
                #creacion del slug
                $slug  = slug($client->name);
                $count = 2;
                
                while(Client::where('slug', '=', $slug)->where('id', '!=', $client->id)->count() > 0)
                {
                    $slug = slug($client->name.' '.$count);
                    $count++;
                }
                
                $client->slug = $slug;
                
                if(secureSave($client))
                {
                    $data = array(
                        'activity' => 'You have edited the client <a href="'.route('client.search', ['slug' => $client->slug]).'">'.$client->name.'</a>',
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
                flash('¡There is not any changes!', 'info');
            }
        }
        else
        {
            flash('¡The client you want to edit does not exist!', 'danger');
        }
        
        return redirect(route('client.index'));
    }
    
    #eliminar cliente
    public function delete($id = null)
    {
        $client = Client::find($id);
        
        if(!is_null($client))
        {
            if(secureDelete($client))
            {
                $data = array(
                    'activity' => 'You have delete the client <a href="#">'.$client->name.'</a>',
                    'icon'     => $this->icon.' bg-red',
                );
                
                add_recent_activity($data);
                
                flash('¡The Client '.$client->name.' has been erased successfuly!', 'success');
            }
            else
            {
                flash('¡It has happened an unknown error deleting the client '.$client->name.'!', 'danger');
            }
        }
        else
        {
            flash('¡The Client you want to delete does not exist in the system!', 'danger');
        }
        
        return redirect(route('client.index'));
    }
    
    /*
     * AJAX
     */
    
    public function ajax_out_orders(Request $request)
    {
        if(isset($request->client_id) && !is_null($request->client_id) && is_numeric($request->client_id))
        {
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
            
            $orders = \App\Models\Order::distinct()
                                       ->select('orders.id','orders.code')
                                       ->join('order_product as opc', 'orders.id', '=', 'opc.order_id')
                                       ->where('orders.client_id', '=', $request->client_id)
                                       ->where('orders.type', '=', 'out')
                                       ->where('orders.is_processed', '=', 'Y')
                                       ->whereRaw('(('.$db_prefix.'opc.quantity) - ('.getRealQuery($qty_product_sum_devolutions).')) > 0')
                                       ->get();
            if(count($orders) > 0)
            {
                die(json_encode($orders));
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