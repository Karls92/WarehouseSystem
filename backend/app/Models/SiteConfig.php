<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteConfig extends Model
{
    protected $table = 'site_config';
    
    public function rules()
    {
        return [
            'page_description' => 'string|max:300',
            'map_height' => 'string|max:10',
            'map_zoom' => 'string|max:10',
            'map_color' => 'string|max:10',
            'map_latitude' => 'string|max:100',
            'map_longitude' => 'string|max:100',
            'social_facebook' => 'string|max:500',
            'social_twitter' => 'string|max:500',
            'social_instagram' => 'string|max:500',
            'social_youtube' => 'string|max:500',
            'social_google_plus' => 'string|max:500',
            'social_mercado_libre' => 'string|max:500',
            'api_id_google_analytics' => 'string|max:100',
            'api_id_facebook' => 'string|max:100',
            'email' => 'email|max:100',
            'password_email' => 'string|min:6|max:100',
            'smtp_host' => 'string|max:100',
            'smtp_port' => 'string|max:10',
        ];
    }
}
