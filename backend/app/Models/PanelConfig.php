<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanelConfig extends Model
{
    protected $table = 'panel_config';
    
    public function rules()
    {
        return [
            'screen' => 'required|string|size:1|in:Y,N',
            'breadcrumb' => 'required|string|size:1|in:Y,N',
            'box_design' => 'required|string|size:1|in:Y,N',
            'theme_color' => 'required|string|min:5|max:20'
        ];
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
