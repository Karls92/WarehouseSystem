<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = DB::table('products')->get();
        
        if (!$products)
        {
            $false_products = array();
    
            $brands = \App\Models\Brand::all();
            
            for($i = 1; $i <= 250; $i++)
            {
                $brand_id = mt_rand(1,count($brands));
                
                if($brand_id == 1)
                {
                    $model_id = 1;
                }
                else
                {
                    $models = \App\Models\ProductModel::where('brand_id','=',$brand_id)->get();
                    
                    $model_id = mt_rand(1,count($models));
                }
                
                array_push($false_products,array(
                    'brand_id' => $brand_id,
                    'model_id' => $model_id,
                    'classification_id' => mt_rand(1,5),
                    'uom_id' => mt_rand(1,5),
                    'product_code' => '#'.mt_rand(1,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9),
                    'name' => 'Producto #'.$i,
                    'description' => 'Descripción del producto #'.$i,
                    'observation' => 'Observación del producto #'.$i,
                    'slug' => slug('Producto #'.$i),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ));
            }
            
            DB::table('products')->insert($false_products);
        }
    }
}