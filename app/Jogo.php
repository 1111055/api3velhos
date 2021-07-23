<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jogo extends Model
{
    
     protected $fillable = [
        'eq1', 'eq2','data_encontro', 'situacao', 'resultado', 'cancelado', 'hora'
    ];
}
