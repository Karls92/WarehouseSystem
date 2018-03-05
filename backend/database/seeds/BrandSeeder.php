<?php

use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = DB::table('brands')->get();
        
        if (!$brands)
        {
            $false_brands = array(
                array(
                    'name' => 'WITHOUT SPECIFICATION',
                    'slug' => slug('WITHOUT SPECIFICATION'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                )
            );
            
            DB::table('brands')->insert($false_brands);
        }
    }
}