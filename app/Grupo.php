<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = [
       'nome', 'user_id'
    ];

     public function user(){

    	 return $this->hasOne('App\User', 'id', 'user_id');
    }

       public function usergrupos(){

    	 return $this->hasMany('App\Usergrupo', 'grupo_id', 'id');
    }
}
