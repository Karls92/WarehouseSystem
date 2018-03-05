<?php

use Illuminate\Database\Seeder;

class RecentActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $recent_activities = DB::table('recent_activities')->get();
    
        if(!$recent_activities)
        {
            $maker = (strlen(site_config()['false_maker']) > 0) ? site_config()['false_maker'] : site_config()['maker'];
            
            DB::table('recent_activities')->insert([
                [
                   'id'       => 1,
                   'user'     => '<a href="#">'.$maker.'</a>',
                   'activity' => 'You have created the base of your <a href="#">Web Page</a>',
                   'icon'     => 'fa fa-gift bg-maroon',
                   'date'     => date('Y-m-d H:i:s', time()),
                ],
            ]);
        }
    }
}
