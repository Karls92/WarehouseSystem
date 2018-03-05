<?php

use Illuminate\Database\Seeder;

class PanelConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $panel_config = DB::table('panel_config')->get();
        
        if (!$panel_config)
        {
            DB::table('panel_config')->insert([
                [
                  'user_id'     => '1',
                  'theme_color' => 'skin-black',
                  'screen'      => 'N',
                  'breadcrumb'  => 'Y',
                  'box_design'  => 'N',
                  'created_at' => date('Y-m-d H:i:s', time()),
                  'updated_at' => date('Y-m-d H:i:s', time()),
                ],
                [
                    'user_id'     => '2',
                    'theme_color' => 'skin-black',
                    'screen'      => 'N',
                    'breadcrumb'  => 'Y',
                    'box_design'  => 'N',
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ],
                [
                    'user_id'     => '3',
                    'theme_color' => 'skin-black',
                    'screen'      => 'N',
                    'breadcrumb'  => 'Y',
                    'box_design'  => 'N',
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ],
                [
                    'user_id'     => '4',
                    'theme_color' => 'skin-black',
                    'screen'      => 'N',
                    'breadcrumb'  => 'Y',
                    'box_design'  => 'N',
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ],
                [
                    'user_id'     => '5',
                    'theme_color' => 'skin-black',
                    'screen'      => 'N',
                    'breadcrumb'  => 'Y',
                    'box_design'  => 'N',
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ],
                [
                    'user_id'     => '6',
                    'theme_color' => 'skin-black',
                    'screen'      => 'N',
                    'breadcrumb'  => 'Y',
                    'box_design'  => 'N',
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ],
            ]);
        }
    }
}
