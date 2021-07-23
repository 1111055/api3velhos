<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Setting extends Model
{
     protected $fillable = [
        'nome', 'contacto1', 'contacto2','rua', 'localidade', 'cidade', 'codigopostal', 'email1', 'email2','latitude','longitude','website'
    ];
    

    public static function getSettings()
    {

        $valty = DB::table('settings')
            ->get();

        return $valty;
    }

    public static function getAllSettings(){

       return Setting::find(1);
     }
}
