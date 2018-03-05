<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    
    public function rules()
    {
        $id = ($this->id != null && strlen($this->id) > 0 && is_numeric($this->id)) ? $this->id : 0;
    
        return [
            'name' => 'required|string|max:100',
            'state_id'     => 'required|integer|exists:states,id',
            'slug'  => 'max:500|unique:cities,slug,'.$id.',id' 
        ];
    }
    
    /**
     * Relaciones
     */
    
    #belongs to
    public function state()
    {
        return $this->belongsTo('App\Models\State','state_id','id');
    }
    
    #has many
    public function clients()
    {
        return $this->hasMany('App\Models\Client','city_id','id');
    }
    
    /*
    
    
    
    #has one
    public function model_singular()
    {
        return $this->hasOne('App\Models\Model','city_id','id');
    }*/
    
    /**
     * Atributos virtuales
     */
    
    /*public function getAttributeNameAttribute()
    {
        return $this->field1.' '.$this->field2;
    }*/
}

