<?php

use Illuminate\Database\Seeder;

class OutOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $out_orders = DB::table('orders')->where('type', '=', 'out')->get();
        
        if(!$out_orders)
        {
            $first_names = array(
                'Miguel',
                'José',
                'María',
                'Patricia',
                'Andrea',
                'Andreina',
                'Jesmary',
                'Wilmer',
                'Jhonatan',
                'Yakarina',
                'Pedro',
                'Manuel',
                'Yuki',
                'Mariangela',
                'Aquiles',
                'Luisa',
                'Johana',
                'Kerlys',
                'Rosmar',
                'Sergio',
                'Roberto',
                'Xavier',
                'Andres',
                'Javier',
            );
            
            $last_names = array(
                'Contreras',
                'Campos',
                'Rivera',
                'Resplandor',
                'Luzardo',
                'Lisboa',
                'Canales',
                'Rodriguez',
                'Bravo',
                'Maestre',
                'Rivas',
                'Ramirez',
                'Marcano',
                'Casadiego',
                'Ruiz',
                'Monserrat',
                'Villazmil',
                'Astudillo',
                'Carvajal',
                'Ramos',
                'Villanueva',
                'Timarai'
            );
            
            $clients = DB::table('orders')->distinct()->selectRaw('client_id as id')->where('type', '=', 'entry')->where('is_processed', '=', 'Y')->get();
            
            $false_orders = array();
            
            for($i = 1; $i <= 300; $i++)
            {
                array_push($false_orders, array(
                    'client_id'    => $clients[mt_rand(0,count($clients)-1)]->id,
                    'code'         => 'S0'.substr('0000'.$i, -5),
                    'received_by'  => $first_names[mt_rand(0, count($first_names) - 1)].' '.$last_names[mt_rand(0, count($last_names) - 1)],
                    'delivered_by' => $first_names[mt_rand(0, count($first_names) - 1)].' '.$last_names[mt_rand(0, count($last_names) - 1)],
                    'type'         => 'out',
                    'description'  => 'Description of the out order S0'.substr('0000'.$i, -5),
                    'date'         => date('Y-m-d H:i:s', mt_rand(reverse_date('-19 days '.rand(12, 23).':'.rand(10, 59).':'.rand(10, 59)), reverse_date('-10 days '.rand(12, 23).':'.rand(10, 59).':'.rand(10, 59)))),
                    'is_processed' => (mt_rand(0, 5) == 0) ? 'Y' : 'N',
                    'slug'         => slug('S0'.substr('0000'.$i, -5)),
                    'created_at'   => date('Y-m-d H:i:s', time()),
                    'updated_at'   => date('Y-m-d H:i:s', time()),
                ));
            }
            
            DB::table('orders')->insert($false_orders);
        }
    }
}