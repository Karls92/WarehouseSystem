<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    
    public function rules()
    {
        $id = ($this->id != null && strlen($this->id) > 0 && is_numeric($this->id)) ? $this->id : 0;
        
        return [
            'brand_id'          => 'required|integer|exists:brands,id',
            'model_id'          => 'required|integer|exists:models,id',
            'classification_id' => 'required|integer|exists:classifications,id',
            'uom_id'            => 'required|integer|exists:units_of_measure,id',
            'product_code'      => 'required|string|max:45|unique:products,product_code,'.$id.',id',
            'name'              => 'required|string|max:100',
            'description'       => 'required|string|max:500',
            'observation'       => 'string|max:500',
            'slug'              => 'max:500|unique:products,slug,'.$id.',id',
        ];
    }
    
    /**
     * Relaciones
     */
    
    #belongs to
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id', 'id');
    }
    
    public function model()
    {
        return $this->belongsTo('App\Models\ProductModel', 'model_id', 'id');
    }
    
    public function classification()
    {
        return $this->belongsTo('App\Models\Classification', 'classification_id', 'id');
    }
    
    public function unit_of_measure()
    {
        return $this->belongsTo('App\Models\UnitOfMeasure', 'uom_id', 'id');
    }
    
    #many yo many
    public function orders()
    {
        return $this->belongsToMany('App\Models\Order', 'order_product', 'product_id', 'order_id')->withPivot('quantity','id')->withTimestamps();
    }
    
    /*#has many
    public function model_plural()
    {
        return $this->hasMany('App\Models\Model','product_id','id');
    }
    
    #has one
    public function model_singular()
    {
        return $this->hasOne('App\Models\Model','product_id','id');
    }*/
    
    /**
     * Atributos virtuales
     */
    
    /*public function getAttributeNameAttribute()
    {
        return $this->field1.' '.$this->field2;
    }*/
}

