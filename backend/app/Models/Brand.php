<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brands';
    
    public function rules()
    {
        $id = ($this->id != null && strlen($this->id) > 0 && is_numeric($this->id)) ? $this->id : 0;
    
        return [
            'name' => 'required|string|max:100',
            'slug'  => 'max:500|unique:brands,slug,'.$id.',id' 
        ];
    }
    
    /**
     * Relaciones
     */
    
    public function product_models()
    {
        return $this->hasMany('App\Models\ProductModel','brand_id','id');
    }
    
    public function products()
    {
        return $this->hasMany('App\Models\Product','brand_id','id');
    }
}

