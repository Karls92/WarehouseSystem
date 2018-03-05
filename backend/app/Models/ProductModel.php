<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = 'models';
    
    public function rules()
    {
        $id = ($this->id != null && strlen($this->id) > 0 && is_numeric($this->id)) ? $this->id : 0;
    
        return [
            'name' => 'required|string|max:100',
            'brand_id' => 'required|integer|exists:brands,id',
            'slug'  => 'max:500|unique:models,slug,'.$id.',id' 
        ];
    }
    
    /**
     * Relaciones
     */
    #belongs to
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand','brand_id','id');
    }
    
    #has many
    public function products()
    {
        return $this->hasMany('App\Models\Product','model_id','id');
    }
}

