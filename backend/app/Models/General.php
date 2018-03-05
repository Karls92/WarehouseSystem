<?php
/**
 * Created by mcontreras
 * Date: 16-Sep-16
 * Time: 11:25 AM
 */

namespace App\Models;

use DB;

class General
{
    public function registers_list($table , $where = null, $limit = null, $quantity = null, $order = null)
    {
        $query = DB::table($table);

        if(!is_null($limit))
        {
            if(is_null($quantity))
            {
                $query = $query->skip($limit)->take(100);
            }
            else
            {
                $query = $query->skip($limit)->take($quantity);
            }
        }

        if(!is_null($where))
        {
            foreach($where as $condition)
            {
                $condition_parts = explode(' ',$condition);
                $query = $query->where($condition_parts[0],$condition_parts[1],$condition_parts[2]);
            }
        }

        if(!is_null($order))
        {
            $order_parts = explode(' ',$order);
            
            $field = $order_parts[0];
            $method = $order_parts[1];

            $query = $query->orderBy($field,$method);
        }
        else
        {
            $query = $query->orderBy('id','desc');
        }

        return $query->get();
    }

    public function quantity($table , $where = null)
    {
        $query = DB::table($table);

        if(!is_null($where))
        {
            foreach($where as $condition)
            {
                $condition_parts = explode(' ',$condition);
                $query = $query->where($condition_parts[0],$condition_parts[1],$condition_parts[2]);
            }
        }

        return $query->count();
    }
}