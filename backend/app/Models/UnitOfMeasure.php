<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model
{
    protected $table = 'units_of_measure';
    
    public function rules()
    {
        $id = ($this->id != null && strlen($this->id) > 0 && is_numeric($this->id)) ? $this->id : 0;
    
        return [
            'name' => 'required|string|max:100',
            'slug'  => 'max:500|unique:units_of_measure,slug,'.$id.',id' 
        ];
    }
    
    /**
     * Relaciones
     */
    
    #has many
    public function products()
    {
        return $this->hasMany('App\Models\Product','uom_id','id');
    }
}

