<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\General;

class DashboardController extends Controller
{
    /**
     * Muesta el resumen de la página.
     */
    public function index(Request $request)
    {
        #Definicion de rutas de navegacion para el breadcrumb
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Summary',
            ),
        );
        
        $page_data['page_title'] = 'Summary';
        $page_data['General_model'] = new General();
        
        return view('backend.dashboard.main', $page_data);
    }
}
