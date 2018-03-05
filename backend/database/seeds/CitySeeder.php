<?php

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = DB::table('cities')->get();
        
        if (!$cities)
        {
            $false_cities = array(
                array(
                    'state_id' => 1,
                    'name' => 'Maturín',
                    'slug' => slug('Maturín'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ),
                array(
                    'state_id' => 2,
                    'name' => 'Caracas',
                    'slug' => slug('Caracas'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ),
            );
            
            DB::table('cities')->insert($false_cities);
        }
    }
}