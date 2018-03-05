<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class SiteConfigComposer{

    public function compose(View $view)
    {
        $page_data = site_config();
        $view->with($page_data);
    }
}