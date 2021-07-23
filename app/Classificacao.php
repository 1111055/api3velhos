<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classificacao extends Model
{
     protected $fillable = [
        'user_id', 'pontos'
    ];

    public function utilizador(){

    	 return $this->hasMany('App\User', 'id', 'user_id');
    }
}
