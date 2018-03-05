<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    
    /**
     * Reglas
     */
    
    public function rules()
    {
        $id = ($this->id != null && strlen($this->id) > 0 && is_numeric($this->id)) ? $this->id : 0;
        
        return [
            'username' => 'required|max:30|unique:users,username,'.$id.',id',// de esta forma se especifica que sea distinto el id a el id de ese usuario.. si la clave primaria se llama distinto, entonces como 4to parametro pasar el nombre de la clave primaria
            'first_name' => 'required|string|max:25',
            'last_name' => 'required|string|max:25',
            'email' => 'required|max:50|unique:users,email,'.$id.',id',
            'type' => 'in:member,admin',
            'level' => 'numeric|between:0,3',
            'phone' => 'required|string|size:15',
            'password' => 'string|min:6|max:60'
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name','last_name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    /**
     * Atributos virtuales
     */
    
    public function getFullNameAttribute()// se puede llamar con getFullNameAttribute, o full_name porque cada camello lo separa por _, o tambien sirve si se escribe pegado
    {
        return $this->first_name.' '.$this->last_name;
    }
    
    /**
     * Funciones Especiales
     */
    
    public function admin()
    {
        return $this->type === 'admin';
    }
    
    /**
     * Relaciones
     */
    
    public function panel_config()
    {
        return $this->hasOne('App\Models\PanelConfig','user_id','id');
    }
}
