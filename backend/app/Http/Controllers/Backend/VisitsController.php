<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Visit;

class VisitsController extends Controller
{
    private $per_page;
    private $module;
    
    public function __construct()
    {
        $this->per_page = 100;
        $this->module   = 'visits';
    }
    
    public function index()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Visits',
                'url'  => route('visits.index'),
            ),
            array(
                'name' => 'Lists',
            ),
        );
        
        $page_data['page_title']       = 'Visits of the Site';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'visits';
        $page_data['per_page']         = $this->per_page;
        $page_data['visits']           = Visit::limit($this->per_page)->orderBy('date', 'DESC')->get();
        $page_data['visits_qty']       = Visit::count();
        
        return view('backend.visit.index', $page_data);
    }
    
    public function referrers()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Visits',
                'url'  => route('visits.index'),
            ),
            array(
                'name' => 'Referrers',
            ),
        );
        
        $page_data['page_title']       = 'Web page referrers';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'referrers';
        $page_data['per_page']         = $this->per_page;
        $page_data['referrers']        = Visit::selectRaw('referrer, referrer_link, count(*) as count')->where('referrer','!=','Directo')->groupBy('referrer','referrer_link')->orderBy('referrer','asc')->orderBy('count','desc')->get();
        $page_data['referrers_qty']    = count($page_data['referrers']);
        
        return view('backend.visit.referrers', $page_data);
    }
    
    public function ajax_visits(Request $request)
    {
        $data = array();
        
        $visits = Visit::offset($request->offset)->limit($this->per_page)->orderBy('date', 'DESC')->get();
        
        foreach($visits as $visit)
        {
            $referrer_parts = explode('://', $visit->referrer);
            
            if(count($referrer_parts) > 1)
            {
                $referrer = explode('/', $referrer_parts[1])[0];
            }
            else
            {
                $referrer = $visit->referrer;
            }
            
            $link = $visit->referrer;
            
            if($referrer == 'www.codigo42.com.ve' || $referrer == 'codigo42.com.ve')
            {
                $link     = 'http://www.codigo42.com.ve';
                $referrer = 'www.codigo42.com.ve';
            }
            
            array_push($data, array(
                custom_date_format($visit->date).'<br>'.date('h:i a', reverse_date($visit->date)),
                $visit->ip,
                $visit->country,
                ($referrer == 'Directo') ? $referrer : '<a href="'.$link.'" target="_blank"><b>'.$referrer.'</b></a>',
                $visit->browser,
                $visit->so,
            ));
        }
        
        die(json_encode($data));
    }
    
    public function summary()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Visits',
                'url'  => route('visits.summary'),
            ),
            array(
                'name' => 'Summary',
            ),
        );
        
        $page_data['page_title']       = 'Visits';
        $page_data['active_module']    = $this->module;
        $page_data['active_submodule'] = 'summary';
        $page_data['visits']           = array();
        $page_data['browsers']         = array();
        $page_data['sos']              = array();
        $page_data['referrers']        = array('data' => array(),'drilldown' => array());
        
        #visitas por los últimos 10 días
        for($i = 9; $i >= 0; $i--)
        {
            $start_day = date('Y-m-d 00:00:00', strtotime("-".$i." days"));
            $end_day  = date('Y-m-d 23:59:59', strtotime("-".$i." days"));
            
            $total = Visit::whereBetween('date', [$start_day, $end_day])->count();
            $unique = count(Visit::select('ip')
                                 ->whereBetween('date', [$start_day, $end_day])
                                 ->groupBy('ip')
                                 ->get());
            $new = count(Visit::select('ip')
                              ->whereBetween('date', [$start_day, $end_day])
                              ->whereNotIn('ip', function ($query) use ($start_day)
                              {
                                  $query->distinct()
                                        ->select('ip')
                                        ->from('visits')
                                        ->where('date', '<', $start_day);
                              })
                              ->groupBy('ip')
                              ->get());
            
            array_push($page_data['visits'], array(
                'date'   => custom_date_format($start_day),
                'total'  => $total,
                'unique' => $unique,
                'new'    => $new,
                'regulars'    => ($unique-$new),
            ));
        }
        
        $visits_qty = Visit::count();
        $browsers   = Visit::selectRaw('browser,count(*) as count')->groupBy('browser')->get();
        $random_key = mt_rand(0,count($browsers)-1);
        foreach($browsers as $key => $browser)
        {
            if($key == $random_key)
            {
                array_push($page_data['browsers'], array(
                    'name'     => $browser->browser,
                    'y'        => floatval($browser->count*100/$visits_qty),
                    'sliced'   => true,
                    'selected' => true,
                ));
            }
            else
            {
                array_push($page_data['browsers'], array(
                    'name' => $browser->browser,
                    'y'    => floatval($browser->count*100/$visits_qty),
                ));
            }
        }
        
        $sos     = Visit::selectRaw('so,count(*) as count')->groupBy('so')->get();
        $random_key = mt_rand(0,count($sos)-1);
        foreach($sos as $key => $so)
        {
            if($key == $random_key)
            {
                array_push($page_data['sos'], array(
                    'name'     => $so->so,
                    'y'        => floatval($browser->count*100/$visits_qty),
                    'sliced'   => true,
                    'selected' => true,
                ));
            }
            else
            {
                array_push($page_data['sos'], array(
                    'name' => $so->so,
                    'y'    => floatval($browser->count*100/$visits_qty),
                ));
            }
        }
    
        $referrers = Visit::selectRaw('referrer,count(*) as count')->groupBy('referrer')->orderBy('count','desc')->limit(30)->get();
        foreach($referrers as $key => $referrer)
        {
            array_push($page_data['referrers']['data'], array(
                'name' => $referrer->referrer,
                'y'    => floatval($referrer->count*100/$visits_qty),
                'title' => $referrer->referrer,
                'drilldown' => $referrer->referrer,
            ));
            
            $referrers_pages = Visit::selectRaw('referrer_link, count(*) as count')->where('referrer','=',$referrer->referrer)->groupBy('referrer_link')->orderBy('count','desc')->limit(30)->get();
            
            $count = 1;
            $qty = array_sum(array_column($referrers_pages->toArray(),'count'));
            $data = array();
            
            foreach($referrers_pages as $referrer_page)
            {
                array_push($data, array(
                    'name' => ''.$count,
                    'y'    => floatval($referrer_page->count*100/$qty),
                    'title' => $referrer_page->referrer_link,
                ));
    
                $count++;
            }
    
            array_push($page_data['referrers']['drilldown'], array(
                'name' => $referrer->referrer,
                'id'    => $referrer->referrer,
                'data' => $data,
            ));
        }
        
        return view('backend.visit.summary', $page_data);
    }
    
    
}
