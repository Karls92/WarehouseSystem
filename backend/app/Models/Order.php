<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    public function rules()
    {
        $id = ($this->id != null && strlen($this->id) > 0 && is_numeric($this->id)) ? $this->id : 0;
        
        return [
            'client_id'    => 'integer|exists:clients,id',
            'out_order_id' => 'integer|exists:orders,id,type,out',
            'code'         => 'string|max:45|unique:orders,code,'.$id.',id',
            'received_by'  => 'required|string|max:100',
            'delivered_by' => 'required|string|max:100',
            'type'         => 'in:entry,out,devolution',
            'is_processed' => 'in:Y,N',
            'date'         => 'required|date|max:20',
            'description'  => 'string|max:500',
            'slug'         => 'max:500|unique:orders,slug,'.$id.',id',
        ];
    }
    
    /**
     * Relaciones
     */
    
    #belongs to
    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id', 'id');
    }
    
    #many yo many
    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'order_product', 'order_id', 'product_id')
                    ->withPivot('quantity', 'id')
                    ->withTimestamps();
    }
    
    /*#has many
    public function model_plural()
    {
        return $this->hasMany('App\Models\Model','order_id','id');
    }
    
    #has one
    public function model_singular()
    {
        return $this->hasOne('App\Models\Model','order_id','id');
    }*/
    
    /**
     * Atributos virtuales
     */
    
    /*public function getAttributeNameAttribute()
    {
        return $this->field1.' '.$this->field2;
    }*/
}

