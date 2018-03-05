<?php

use Illuminate\Database\Seeder;

class DevolutionOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entry_orders = DB::table('orders')->where('type','=','devolution')->get();
        
        if (!$entry_orders)
        {
            $first_names = array(
                'Miguel', 'José', 'María', 'Patricia', 'Andrea', 'Andreina', 'Jesmary', 'Wilmer', 'Jhonatan', 'Yakarina',
                'Pedro', 'Manuel', 'Yuki', 'Mariangela', 'Aquiles', 'Luisa', 'Johana', 'Kerlys', 'Rosmar', 'Sergio',
                'Roberto', 'Xavier', 'Andres', 'Javier'
            );
            
            $last_names = array(
                'Contreras', 'Campos', 'Rivera', 'Resplandor', 'Luzardo', 'Lisboa', 'Canales', 'Rodriguez', 'Bravo',
                'Maestre','Rivas', 'Ramirez', 'Marcano', 'Casadiego', 'Ruiz', 'Monserrat', 'Villazmil', 'Astudillo',
                'Carvajal', 'Ramos', 'Villanueva'
            );
            
            $false_orders = array();
    
            $orders = DB::table('orders')->distinct()->select('id','client_id')->where('type', '=', 'out')->where('is_processed', '=', 'Y')->get();
            
            for($i = 1; $i <= 105; $i++)
            {
                $order = $orders[mt_rand(0,count($orders)-1)];
                array_push($false_orders,array(
                    'out_order_id' => $order->id,
                    'client_id' => $order->client_id,
                    'code' => 'D0'.substr('0000'.$i,-5),
                    'received_by' => $first_names[mt_rand(0,count($first_names)-1)].' '.$last_names[mt_rand(0,count($last_names)-1)],
                    'delivered_by' => $first_names[mt_rand(0,count($first_names)-1)].' '.$last_names[mt_rand(0,count($last_names)-1)],
                    'type' => 'devolution',
                    'description' => 'Description of the devolution order D0'.substr('0000'.$i,-5),
                    'date' => date('Y-m-d H:i:s', mt_rand(reverse_date('-9 days '.rand(12,23).':'.rand(10,59).':'.rand(10,59)),reverse_date(date('Y-m-d H:i:s')))),
                    'is_processed' => (mt_rand(0,5) == 0) ? 'Y' : 'N',
                    'slug' => slug('D0'.substr('0000'.$i,-5)),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ));
            }
            
            DB::table('orders')->insert($false_orders);
        }
    }
}