<?php

use Illuminate\Database\Seeder;

class VisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $visits = DB::table('visits')->get();
        
        if(!$visits)
        {
            $countries = array(
                'Venezuela', 'Colombia', 'Argentina', 'España', 'Ecuador', 'Mexico', 'EE.UU', 'Alemania', 'Japon'
            );
    
            $browsers = array(
                'Mozilla', 'Internet Explorer', 'Google Chrome', 'Opera', 'Safari'
            );
    
            $sos = array(
                'Windows 10', 'Windows 7', 'Linux', 'Windows XP', 'Android', 'IOs'
            );
    
            $referrers = array(
                'http://www.google.com','http://www.facebook.com','http://www.trafficmonsoon.com', 'Directo', 'http://www.prensa.com', 'http://www.periodico.com',
                'http://www.google.com/pagina_interna1','http://www.facebook.com/pagina_interna1','http://www.trafficmonsoon.com/pagina_interna1', 'http://www.prensa.com/pagina_interna1', 'http://www.periodico.com/pagina_interna1',
                'http://www.google.com/pagina_interna2','http://www.facebook.com/pagina_interna2','http://www.trafficmonsoon.com/pagina_interna2', 'http://www.prensa.com/pagina_interna2', 'http://www.periodico.com/pagina_interna2'
            );
    
            for($i = 1; $i <= 10; $i++)
            {
                array_push($referrers,'http://www.pagina_falsa'.$i.'.com');
                array_push($referrers,'http://www.pagina_falsa'.$i.'.com/pagina_interna1');
                array_push($referrers,'http://www.pagina_falsa'.$i.'.com/pagina_interna2');
                array_push($referrers,'http://www.pagina_falsa'.$i.'.com/pagina_interna3');
            }
    
            $ips = array();
            for($i = 0; $i < 45; $i++)
            {
                array_push($ips,rand(54,190).'.'.rand(78,220).'.'.rand(18,190).'.'.rand(35,255));
            }
    
            $false_visits = array();
            for($i = 0; $i < 225; $i++)
            {
                $referrer = $referrers[rand(0,count($referrers)-1)];
        
                array_push($false_visits,array(
                    'ip'       => $ips[rand(0,count($ips)-1)],
                    'country'  => $countries[rand(0,count($countries)-1)],
                    'browser'  => $browsers[rand(0,count($browsers)-1)],
                    'so'       => $sos[rand(0,count($sos)-1)],
                    'referrer' => ($referrer != 'Directo') ? explode('/',explode('://',$referrer)[1])[0] : 'Directo',
                    'referrer_link' => ($referrer != 'Directo') ? $referrer : '#',
                    'date'     => date('Y-m-d H:i:s', strtotime('-'.rand(0,9).' days'.' '.rand(12,23).':'.rand(10,59).':'.rand(10,59))),
                ));
            }
    
            DB::table('visits')->insert($false_visits);
        }
    }
}
