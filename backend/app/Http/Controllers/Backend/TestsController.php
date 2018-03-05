<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use JasperPHP\JasperPHP as Jasper;

class TestsController extends Controller
{
    public function jasper()
    {
        $jasper = new Jasper;
        
        /*$jasper->compile(base_path('resources/assets/report/hello_world.jrxml'))->execute();*/
    
        /*$output = $jasper->list_parameters(
            base_path('resources/assets/reports/report.jasper')
        )->execute();
        
        dd($output);*/
    
        $jasper->process(
            base_path('resources/assets/reports/report.jasper'),
            assets_path('reports/prueba'),
            array("pdf"),
            array (
              "order_type" => "entry",
              "date_start" => "2017-03-20 00:00:00",
              "date_end" => "2017-03-20 23:59:59",
              "current_date" => "21/03/2017",
              "current_hour" => "02:38 PM",
              "client_id" => null,
              "title" => "ENTRY REPORTS",
                )
        )->execute();
        
        die(assets_path('reports/prueba'));
    }
    
    public function user($user_id = null)
    {
        dd($user_id);
    }
}
