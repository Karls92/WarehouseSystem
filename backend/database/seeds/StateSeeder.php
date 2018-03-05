<?php

use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = DB::table('states')->get();
        
        if (!$states)
        {
            $false_states = array(
                array(
                    'name' => 'Monagas',
                    'slug' => slug('Monagas'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ),
                array(
                    'name' => 'Caracas',
                    'slug' => slug('Caracas'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ),
                array(
                    'name' => 'Miranda',
                    'slug' => slug('Miranda'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                   'updated_at' => date('Y-m-d H:i:s', time()),
                ),
                array(
                    'name' => 'Miranda',
                    'slug' => slug('Miranda'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ),
                array(
                    'name' => 'Zulia',
                    'slug' => slug('Zulia'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ),
            );
            
            DB::table('states')->insert($false_states);
        }
    }
}