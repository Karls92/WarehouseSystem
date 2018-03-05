<?php

namespace App\Http\Controllers\Backend;

use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use JasperPHP\JasperPHP as Jasper;

class ReportsController extends Controller
{
    private $module;
    private $icon;
    
    public function __construct()
    {
        $this->module = 'reports';
        $this->icon   = 'fa fa-archive';
    }
    
    
    public function reports()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Reports',
                'url'  => route('report.reports'),
            ),
            array(
                'name' => 'General',
            ),
        );
        
        $page_data['page_title']       = 'Get General Reports';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'reports';
        $page_data['form_type']        = 'danger';
        $page_data['date']             = Order::selectRaw('COALESCE(min(date),\''.date('Y-m-d H:i:s').'\') as start, COALESCE(max(date),\''.date('Y-m-d H:i:s').'\') as end')
                                              ->first();
        $page_data['clients']          = Client::distinct()
                                               ->select('clients.id', 'clients.name')
                                               ->join('orders', 'orders.client_id', '=', 'clients.id')
                                               ->where('orders.type', '=', 'entry')
                                               ->where('orders.is_processed', '=', 'Y')
                                               ->get();
        
        return view('backend.report.reports', $page_data);
    }
    
    public function generate_report(Request $request)
    {
        try
        {
            $this->delete_old_reports();
            
            $request->merge(
                [
                    'start' => date('Y-m-d H:i:s', reverse_date($request->start.' 00:00:00', false, '/')),
                    'end'   => date('Y-m-d H:i:s', reverse_date($request->end.' 23:59:59', false, '/')),
                ]);
            
            $orders = Order::where('type', '=', $request->operation)
                           ->where('is_processed', '=', 'Y')
                           ->whereBetween('date', [
                               $request->start,
                               $request->end,
                           ]);
            
            if(isset($request->client_id) && !is_null($request->client_id) && is_numeric($request->client_id))
            {
                $orders = $orders->where('client_id', '=', $request->client_id);
    
                $client_id = $request->client_id;
            }
            else
            {
                $client_id = null;
            }
            
            if($orders->exists())
            {
                switch($request->operation)
                {
                    case 'entry':
                        $title = 'ENTRY REPORTS';
                        
                        break;
                    case 'out':
                        $title = 'OUTS REPORTS';
                        break;
                    case 'devolution':
                        $title = 'DEVOLUTIONS REPORTS';
                        break;
                    default:
                        abort(404);
                        break;
                }
                
                $file = strtolower(str_replace(' ','_',$title)).'_'.date('Ymd_His');
                
                $jasper = new Jasper;
                
                $parameters = array(
                    'order_type' => "'".$request->operation."'",
                    'date_start' => "'".$request->start."'",
                    'date_end' => "'".$request->end."'",
                    'current_date' => "'".date('d/m/Y')."'",
                    'current_hour' => "'".date('h:i A')."'",
                    'title' => "'".$title."'",
                );
                    
                if(!is_null($client_id))
                {
                    $parameters['client_id'] = $client_id;
                }
    
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
                    base_path('resources/assets/reports/report.jasper'),
                    assets_path('reports/'.$file),
                    array('pdf'),
                    $parameters,
                    $db_connection,
                    false
                )->execute();
                
                for($i = 0;$i < 5 ;$i++)
                {
                    if(file_exists(assets_path('reports/'.$file.'.pdf')) && is_file(assets_path('reports/'.$file.'.pdf')))
                    {
                        //return response()->download(assets_path('reports/'.$file.'.pdf'));
                        return redirect(public_path('assets/reports/'.$file.'.pdf'));
                        break;
                    }
                    else
                    {
                        sleep(1);
                    }
                }
            }
            else
            {
                flash('¡No existen reportes con los parametros ingresados!', 'info');
            }
    
            flash('¡The report could not be generated!', 'danger');
        }
        catch(\Exception $e)
        {
            flash('¡Ha ocurrido un error inesperado, vuelva a intentarlo!'.$e->getMessage(), 'danger');
        }
        
        return redirect(route('report.reports'));
    }
    
    public function orders()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Reports',
                'url'  => route('report.reports'),
            ),
            array(
                'name' => 'Get Order reports',
            ),
        );
        
        $page_data['page_title']       = 'Get Order Reports';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'orders';
        $page_data['form_type']        = 'danger';
        $page_data['date']             = Order::selectRaw('COALESCE(min(date),\''.date('Y-m-d H:i:s').'\') as start, COALESCE(max(date),\''.date('Y-m-d H:i:s').'\') as end')
                                              ->first();
        $page_data['clients']          = Client::distinct()
                                               ->select('clients.id', 'clients.name')
                                               ->join('orders', 'orders.client_id', '=', 'clients.id')
                                               ->where('orders.is_processed', '=', 'Y')
                                               ->where('orders.type', '=', 'entry')
                                               ->get();
        
        return view('backend.report.orders', $page_data);
    }
    
    public function generate_order(Request $request)
    {
        try
        {
            $this->delete_old_reports();
            
            switch($request->operation)
            {
                case 'entry':
                    $title = 'ENTRY';
            
                    break;
                case 'out':
                    $title = 'OUT';
                    break;
                case 'devolution':
                    $title = 'DEVOLUTIONS';
                    break;
                default:
                    abort(404);
                    break;
            }
    
            $file = strtolower(str_replace(' ','_',$title.' '.$request->order_code));
    
            $jasper = new Jasper;
    
            $parameters = array(
                'order_type' => "'".$request->operation."'",
                'current_date' => "'".date('d/m/Y')."'",
                'current_hour' => "'".date('h:i A')."'",
                'order_code' => "'".$request->order_code."'",
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
                base_path('resources/assets/reports/'.$request->operation.'_order.jasper'),
                assets_path('reports/'.$file),
                array('pdf'),
                $parameters,
                $db_connection,
                false
            )->execute();
            
            for($i = 0;$i < 5 ;$i++)
            {
                if(file_exists(assets_path('reports/'.$file.'.pdf')) && is_file(assets_path('reports/'.$file.'.pdf')))
                {
                    return redirect(public_path('assets/reports/'.$file.'.pdf'));
                    break;
                }
                else
                {
                    sleep(1);
                }
            }
    
            flash('¡The report could not be generated!', 'danger');
        }
        catch(\Exception $e)
        {
            flash('¡Ha ocurrido un error inesperado, vuelva a intentarlo!', 'danger');
        }
        
        return redirect(route('report.orders'));
    }
    
    public function inventory()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Reports',
                'url'  => route('report.reports'),
            ),
            array(
                'name' => 'Stock Reports',
            ),
        );
        
        $page_data['page_title']       = 'Stock Reports';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'inventory';
        $page_data['form_type']        = 'danger';
        $page_data['clients']          = Client::distinct()
                                               ->select('clients.id', 'clients.name')
                                               ->join('orders', 'orders.client_id', '=', 'clients.id')
                                               ->where('orders.is_processed', '=', 'Y')
                                               ->where('orders.type', '=', 'entry')
                                               ->get();
        
        return view('backend.report.inventory', $page_data);
    }
    
    public function generate_inventory(Request $request)
    {
        try
        {
            $this->delete_old_reports();
    
            $file = strtolower(str_replace(' ','_','STOCK TO '.date('Ymd_his')));
    
            $jasper = new Jasper;
    
            $parameters = array(
                'current_date' => "'".date('d/m/Y')."'",
                'current_hour' => "'".date('h:i A')."'",
            );
    
            if(isset($request->client_id) && !is_null($request->client_id) && is_numeric($request->client_id))
            {
                $parameters['client_id'] = $request->client_id;
            }
    
            if(isset($request->product_id) && !is_null($request->product_id) && is_numeric($request->product_id))
            {
                $parameters['product_id'] = $request->product_id;
            }
    
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
                base_path('resources/assets/reports/inventory.jasper'),
                assets_path('reports/'.$file),
                array('pdf'),
                $parameters,
                $db_connection,
                false
            )->execute();
    
            for($i = 0;$i < 5 ;$i++)
            {
                if(file_exists(assets_path('reports/'.$file.'.pdf')) && is_file(assets_path('reports/'.$file.'.pdf')))
                {
                    return redirect(public_path('assets/reports/'.$file.'.pdf'));
                    break;
                }
                else
                {
                    sleep(1);
                }
            }
    
            flash('¡The report could not be generated!', 'danger');
        }
        catch(\Exception $e)
        {
            flash('¡Ha ocurrido un error inesperado, vuelva a intentarlo!', 'danger');
        }
    
        return redirect(route('report.inventory'));
    }
    
    /*
     * AJAX
     */
    
    public function ajax_orders_client(Request $request)
    {
        try
        {
            $request->merge(
                [
                    'start' => date('Y-m-d H:i:s', reverse_date($request->date.' 00:00:00', false, '/')),
                    'end'   => date('Y-m-d H:i:s', reverse_date($request->date.' 23:59:59', false, '/')),
                ]);

            $orders = Order::select('id', 'code')
                           ->where('type', '=', $request->operation)
                           ->where('client_id', '=', $request->client_id)
                           ->whereBetween('date', [$request->start, $request->end])
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
        catch(\Exception $e)
        {
            die('ERROR');
        }
    }
    
    public function ajax_products_client(Request $request)
    {
        try
        {
            $products = Order::distinct()
                             ->select('products.id', 'products.name')
                             ->join('order_product', 'orders.id', '=', 'order_product.order_id')
                             ->join('products', 'products.id', '=', 'order_product.product_id')
                             ->where('orders.type', '=', 'entry')
                             ->where('orders.is_processed', '=', 'Y')
                             ->where('orders.client_id', '=', $request->client_id)
                             ->get();
            
            if(count($products) > 0)
            {
                die(json_encode($products));
            }
            else
            {
                die('ERROR');
            }
        }
        catch(\Exception $e)
        {
            die('ERROR');
        }
    }
    
    /*
     * funciones
     */
    
    public function delete_old_reports()
    {
        foreach(scandir(assets_path('reports')) as $key => $file)
        {
            if($file == '.' || $file == '..' || $file == 'index.html')
            {
                continue;
            }
            else
            {
                $current_time = time();
                $created_at = filectime(assets_path('reports/'.$file));
            
                $elapsed_days =intval(($current_time-$created_at)/(24*60*60));// one day old
            
                if($elapsed_days >= 0)
                {
                    delete_file('reports',$file);
                }
            }
        
            if(intval($key) >= 15)
            {
                break;
            }
        }
    }
}
