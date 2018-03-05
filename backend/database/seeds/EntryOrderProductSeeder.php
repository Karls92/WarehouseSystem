<?php

use Illuminate\Database\Seeder;

class EntryOrderProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order_products = DB::table('order_product')->join('orders','orders.id','=','order_product.order_id')->where('orders.type', '=', 'entry')->get();
        
        if (!$order_products)
        {
            for($i = 1; $i <= 600; $i++)
            {
                $false_order_products = array();
                
                $data = array(
                    'order_id' => mt_rand(1,450),
                    'product_id' => mt_rand(1,250),
                    'quantity' => mt_rand(1,10),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                );
                
                if(!DB::table('order_product')->join('orders','orders.id','=','order_product.order_id')->where('orders.type', '=', 'entry')->where('order_product.order_id', '=',$data['order_id'])->where('order_product.product_id', '=',$data['product_id'])->exists())
                {
                    array_push($false_order_products,$data);
    
                    DB::table('order_product')->insert($false_order_products);
                }
            }
        }
    }
}