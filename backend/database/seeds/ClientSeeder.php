<?php

use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = DB::table('clients')->get();
        
        if (!$clients)
        {
            $false_clients = array();
            
            $code = array(
                '0414',
                '0424',
                '0416',
                '0426',
                '0412',
                '0291',
                '0212',
            );
            
            for($i = 1; $i <= 150; $i++)
            {               
                array_push($false_clients,array(
                    'client_code' => '#'.mt_rand(1,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9),
                    'name' => 'Cliente #'.$i,
                    'document' => mt_rand(1,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9),
                    'address' => 'Dirección del cliente #'.$i,
                    'city_id' => mt_rand(1,2),
                    'phone_1' => '('.$code[mt_rand(0,count($code)-1)].') '.mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).'-'.mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9),
                    'phone_2' => '('.$code[mt_rand(0,count($code)-1)].') '.mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).'-'.mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9),
                    'email' => 'cliente.'.$i.'@gmail.com',
                    'description' => 'Descripción del cliente#'.$i,
                    'slug' => slug('Cliente #'.$i),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ));
            }
            
            DB::table('clients')->insert($false_clients);
        }
    }
}