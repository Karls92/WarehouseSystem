<?php

use Illuminate\Database\Seeder;

class DevolutionOrderProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    
    public function run()
    {
        $order_products = DB::table('order_product')->join('orders','orders.id','=','order_product.order_id')->where('orders.type', '=', 'devolution')->get();
        
        if (!$order_products)
        {
            
            
            for($i = 1; $i <= 600; $i++)
            {
                $false_order_products = array();
                
                $data = array(
                    'order_id' => mt_rand(751,855),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                );
    
                $order = DB::table('orders')->where('type', '=', 'devolution')->where('id', '=', $data['order_id'])->first();
                
                $products = DB::table('order_product')
                    ->distinct()
                    ->select('product_id as id','quantity')
                    ->where('order_id', '=', $order->out_order_id)
                    ->where('quantity', '>', 0)
                    ->get();
                
                if(count($products) > 0)
                {
                    $product = $products[mt_rand(0,count($products)-1)];
                    $data['product_id'] = $product->id;
                    
                    $qty_used = DB::table('order_product')->selectRaw('COALESCE(SUM('.getDBConfig().'order_product.quantity),0) as qty')->join('orders','orders.id','=','order_product.order_id')->where('orders.type', '=', 'devolution')->where('orders.client_id','=',$order->client_id)->where('order_product.product_id','=',$data['product_id'])->first();
                    $qty_disp = intval($product->quantity)-intval($qty_used->qty);
                    
                    if($qty_disp > 0)
                    {
                        
                        $data['quantity'] = mt_rand(1,$qty_disp);
                        
                        if($data['quantity'] > 0 && !DB::table('order_product')->join('orders','orders.id','=','order_product.order_id')->where('orders.type', '=', 'devolution')->where('order_product.order_id', '=',$data['order_id'])->where('order_product.product_id', '=',$data['product_id'])->exists())
                        {
                            array_push($false_order_products,$data);
        
                            DB::table('order_product')->insert($false_order_products);
                        }
                    }
                }
            }
        }
    }
}