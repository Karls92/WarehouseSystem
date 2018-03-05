<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
    
    public function rules()
    {
        $id = ($this->id != null && strlen($this->id) > 0 && is_numeric($this->id)) ? $this->id : 0;
        
        return [
            'client_code' => 'required|string|max:45|unique:clients,client_code,'.$id.',id',
            'name'        => 'required|string|max:255',
            'document'    => 'required|string|max:20',
            'address'     => 'required|string|max:500',
            'city_id'     => 'required|integer|exists:cities,id',
            'phone_1'     => 'string|size:15',
            'phone_2'     => 'string|size:15',
            'email'       => 'email|max:100',
            'description' => 'string|max:500',
            'slug'        => 'max:500|unique:clients,slug,'.$id.',id',
        ];
    }
    
    /**
     * Relaciones
     */
    
    #belongs to
    public function city()
    {
        return $this->belongsTo('App\Models\City','city_id','id');
    }
    
    #has many
    public function orders()
    {
        return $this->hasMany('App\Models\Order','client_id','id');
    }
    
    /*
    #has one
    public function model_singular()
    {
        return $this->hasOne('App\Models\Model','client_id','id');
    }*/
    
    /**
     * Atributos virtuales
     */
    
    /*public function getAttributeNameAttribute()
    {
        return $this->field1.' '.$this->field2;
    }*/
}

