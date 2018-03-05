<?php

use Illuminate\Database\Seeder;

class SiteConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $site_config = DB::table('site_config')->get();
    
        if (!$site_config)
        {
            DB::table('site_config')->insert([
                [
                  'id' => 1,
                  'page_description'     => 'Web Page Description',
                  'map_height' => '200px',
                  'map_zoom'      => '6',
                  'map_color'  => '#0066B8',
                  'map_latitude'  => '9.732993098502856',
                  'map_longitude'  => '-63.19726198911667',
                  'social_facebook'  => 'Link to Facebook',
                  'social_twitter'  => 'Link to Twitter',
                  'social_instagram'  => 'Link to Instagram',
                  'social_youtube'  => 'Link to Youtube',
                  'social_google_plus'  => 'Link to Google Plus',
                  'social_mercado_libre'  => 'Link to Mercado Libre',
                  'api_id_google_analytics'  => 'APP ID Google Analytics',
                  'api_id_facebook'  => 'APP ID Facebook',
                  'email'  => 'contacto@pagina.com.ve',
                  'password_email'  => '123456',
                  'smtp_host'  => 'host.caracashosting50.com',
                  'smtp_port'  => '465',
                  'created_at' => date('Y-m-d H:i:s', time()),
                  'updated_at' => date('Y-m-d H:i:s', time()),
                ],
            ]);
        }
    }
}
