<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';
    
    public function rules()
    {
        $id = ($this->id != null && strlen($this->id) > 0 && is_numeric($this->id)) ? $this->id : 0;
    
        return [
            'name' => 'required|string|max:100',
            'slug'  => 'max:500|unique:states,slug,'.$id.',id' 
        ];
    }
    
    /**
     * Relaciones
     */
    
    #has many
    public function cities()
    {
        return $this->hasMany('App\Models\City','state_id','id');
    }
    /*
    
    #belongs to
    public function model_singular()
    {
        return $this->belongsTo('App\Models\Model','model_id','id');
    }
    
    #has one
    public function model_singular()
    {
        return $this->hasOne('App\Models\Model','state_id','id');
    }*/
    
    /**
     * Atributos virtuales
     */
    
    /*public function getAttributeNameAttribute()
    {
        return $this->field1.' '.$this->field2;
    }*/
}

