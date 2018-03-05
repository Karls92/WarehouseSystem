<?php

use Illuminate\Database\Seeder;

class UnitOfMeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units_of_measure = DB::table('units_of_measure')->get();
        
        if (!$units_of_measure)
        {
            DB::table('units_of_measure')->insert([
                [
                  'name' => 'WITHOUT ESPECIFICATION',
                  'slug' => slug('WITHOUT ESPECIFICATION'),
                  'created_at' => date('Y-m-d H:i:s', time()),
                  'updated_at' => date('Y-m-d H:i:s', time()),
                ],
                [
                    'name' => 'UNIDAD',
                    'slug' => slug('UNIDAD'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ],
                [
                    'name' => 'CAJA',
                    'slug' => slug('CAJA'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ],
                [
                    'name' => 'BULTO',
                    'slug' => slug('BULTO'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ],
                [
                    'name' => 'BARRIL',
                    'slug' => slug('BARRIL'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ],
            ]);
        }
    }
}