<?php

use Illuminate\Database\Seeder;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classifications = DB::table('classifications')->get();
        
        if (!$classifications)
        {
            $false_classifications = array(
                array(
                    'name' => 'LIQUIDOS PELIGROSOS',
                    'slug' => slug('LIQUIDOS PELIGROSOS'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ),
                array(
                    'name' => 'GASES PELIGROSOS',
                    'slug' => slug('GASES PELIGROSOS'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ),
                array(
                    'name' => 'SOLIDOS PELIGROSOS',
                    'slug' => slug('SOLIDOS PELIGROSOS'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ),
                array(
                    'name' => 'MAQUINARIA Y EQUIPOS',
                    'slug' => slug('MAQUINARIA Y EQUIPOS'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ),
                array(
                    'name' => 'HERRAMIENTAS Y ACCESORIOS MARITIMOS',
                    'slug' => slug('HERRAMIENTAS Y ACCESORIOS MARITIMOS'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ),
            );
            
            DB::table('classifications')->insert($false_classifications);
        }
    }
}