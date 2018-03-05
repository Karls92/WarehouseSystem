<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Models\PanelConfig;

class PanelConfigComposer{

    public function compose(View $view)
    {
        try
        {
            $page_data['panel_config'] = PanelConfig::where('user_id', \Auth::user()->id)->first();
            $view->with($page_data);
        }
        catch(\Exception $e)
        {
            
        }
    }
}