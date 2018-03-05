<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\RecentActivity;

class RecentActivitiesController extends Controller
{
    private $per_page;
    private $module;
    
    public function __construct()
    {
        $this->per_page = 100;
        $this->module = 'recent_activities';
    }
    
    public function index()
    {
        $page_data['breadcrumb'] = array(
            array(
                'name' => 'Recent Activities',
                'url'  => route('recent_activities'),
            ),
            array(
                'name' => 'Lists',
            ),
        );
    
        $page_data['page_title'] = 'Recents Activities';
        $page_data['per_page'] = $this->per_page;
        $page_data['active_module'] = $this->module;
        $page_data['recent_activities'] = RecentActivity::limit($this->per_page)->orderBy('date','DESC')->get();

        return view('backend.recent_activity.index', $page_data);
    }

    public function ajax_recent_activities(Request $request)
    {
        $data = array();
    
        $recent_activities = RecentActivity::offset($request->offset)->limit($this->per_page)->orderBy('date','DESC')->get();
    
        foreach($recent_activities as $activity)
        {
            array_push($data,array(
                'user' => $activity->user,
                'activity' => $activity->activity,
                'icon' => $activity->icon,
                'date' => $activity->date,
                'formatted_date' => custom_date_format($activity->date),
                'hour' => date('h:i a',reverse_date($activity->date))
            ));
        }
        
        die(json_encode($data));
    }

}
