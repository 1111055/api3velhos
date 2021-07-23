<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usergrupo extends Model
{
     protected $fillable = [
        'user_id', 'grupo_id'
    ];

    public function user(){

    	 return $this->hasMany('App\User', 'id', 'user_id');
    }

    public function grupo(){

    	 return $this->hasOne('App\Grupo', 'id', 'grupo_id');
    }
}
