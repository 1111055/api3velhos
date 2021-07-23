<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aposta extends Model
{
     protected $fillable = [
        'user_id', 'jogo_id','aposta'
    ];

    public function user(){

    	 return $this->hasMany('App\User', 'id', 'user_id');
    }
    public function jogo(){

    	 return $this->hasMany('App\Jogo', 'id', 'jogo_id');
    }
}
