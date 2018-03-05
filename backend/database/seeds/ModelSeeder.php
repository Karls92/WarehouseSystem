<?php

use Illuminate\Database\Seeder;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $models = DB::table('models')->get();
        
        if (!$models)
        {
            $false_models = array(
                array(
                    'name' => 'WITHOUT ESPECIFICATION',
                    'brand_id' => 1,
                    'slug' => slug('WITHOUT ESPECIFICATION'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                )
            );
            
            DB::table('models')->insert($false_models);
        }
    }
}