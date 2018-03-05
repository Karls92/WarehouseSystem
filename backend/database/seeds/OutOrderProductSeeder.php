<?php

use Illuminate\Database\Seeder;

class OutOrderProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order_products = DB::table('order_product')
                            ->join('orders', 'orders.id', '=', 'order_product.order_id')
                            ->where('orders.type', '=', 'out')
                            ->get();
        
        if(!$order_products)
        {
            for($i = 1; $i <= 600; $i++)
            {
                $false_order_products = array();
                
                $data = array(
                    'order_id'   => mt_rand(451, 750),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                );
                
                $client = DB::table('orders')
                            ->selectRaw('client_id as id')
                            ->where('id', '=', $data['order_id'])
                            ->first();
                
                $products = DB::table('order_product')
                              ->distinct()
                              ->selectRaw('product_id as id')
                              ->join('orders', 'orders.id', '=', 'order_product.order_id')
                              ->where('orders.type', '=', 'entry')
                              ->where('orders.is_processed', '=', 'Y')
                              ->where('orders.client_id', '=', $client->id)
                              ->get();
                
                if(count($products) > 0)
                {
                    $data['product_id'] = $products[mt_rand(0, count($products) - 1)]->id;
                    
                    /*$sum_entries = Order::selectRaw('COALESCE(SUM(nit_order_product.quantity),0)')
                                        ->join('order_product', 'orders.id', '=', 'order_product.order_id')
                                        ->where('orders.type', '=', 'entry')
                                        ->where('order_product.product_id', '=', $data['product_id']);
        
                    $sum_outs = Order::selectRaw('COALESCE(SUM(nit_order_product.quantity),0)')
                                     ->join('order_product', 'orders.id', '=', 'order_product.order_id')
                                     ->where('orders.type', '=', 'out')
                                     ->where('order_product.product_id', '=', $data['product_id']);
                    
                    
                    if(!DB::table('order_product')
                          ->join('orders', 'orders.id', '=', 'order_product.order_id')
                          ->where('orders.type', '=', 'entry')
                          ->where('order_product.product_id', '=', $data['product_id'])
                          ->whereRaw('(('.$sum_entries->toSql().') - ('.$sum_outs->toSql().')) > 0')
                          ->mergeBindings($sum_entries->getQuery())
                          ->mergeBindings($sum_outs->getQuery())
                          ->exists()
                    )
                    {
                        array_push($false_order_products, $data);
                    }*/
                    
                    $sum_entries = DB::table('orders')
                                     ->selectRaw('COALESCE(SUM(quantity),0) as qty')
                                     ->join('order_product', 'orders.id', '=', 'order_product.order_id')
                                     ->where('orders.type', '=', 'entry')
                                     ->where('orders.is_processed', '=', 'Y')
                                     ->where('order_product.product_id', '=', $data['product_id'])
                                     ->where('orders.client_id', '=', $client->id)
                                     ->first()->qty;
                    
                    $sum_outs = DB::table('orders')
                                  ->selectRaw('COALESCE(SUM(quantity),0)  as qty')
                                  ->join('order_product', 'orders.id', '=', 'order_product.order_id')
                                  ->where('orders.type', '=', 'out')
                                  ->where('orders.is_processed', '=', 'Y')
                                  ->where('order_product.product_id', '=', $data['product_id'])
                                  ->where('orders.client_id', '=', $client->id)
                                  ->first()->qty;
                    
                    if($sum_entries - $sum_outs > 0)
                    {
                        $data['quantity'] = mt_rand(1, $sum_entries - $sum_outs);
                        
                        array_push($false_order_products, $data);
                        
                        if(!DB::table('order_product')
                              ->join('orders', 'orders.id', '=', 'order_product.order_id')
                              ->where('orders.type', '=', 'out')
                              ->where('order_product.order_id', '=', $data['order_id'])
                              ->where('order_product.product_id', '=', $data['product_id'])
                              ->exists()
                        )
                        {
                            DB::table('order_product')->insert($false_order_products);
                        }
                    }
                }
            }
            
            $db_prefix = getDBConfig();
            
            $sum_entries    = $this->getSUMOrders('entry', 'fromSubQuery', $db_prefix);
            $sum_outs       = $this->getSUMOrders('out', 'fromSubQuery', $db_prefix);
            $sum_devolution = $this->getSUMOrders('devolution', 'fromSubQuery', $db_prefix);
            
            \App\Models\Client::whereRaw('(('.$sum_entries->toSql().') + ('.$sum_devolution->toSql().') - ('.$sum_outs->toSql().')) <= 0')
                              ->mergeBindings($sum_entries->getQuery())
                              ->mergeBindings($sum_devolution->getQuery())
                              ->mergeBindings($sum_outs->getQuery())
                              ->whereIn('clients.id', function ($query)
                              {
                                  $query->select('client_id')
                                        ->from('orders')
                                        ->where('type', '=', 'out')
                                        ->groupBy('client_id');
                              })->delete();
            
        }
    }
    
    public function getSUMOrders($type, $client_id = 'fromSubQuery', $db_prefix = '')
    {
        $builder = \App\Models\Order::selectRaw('COALESCE(SUM('.$db_prefix.'order_product.quantity),0)')
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
}