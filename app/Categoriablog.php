<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

class Categoriablog extends Model
{
    protected $fillable = [
        'titulo', 'descricao'
    ];


    public static function getCatBlog(){

       $categoria = Categoriablog::
                 where('titulo', '!=', '')
                 ->orderBy('titulo','asc')->get();


       $selcat = $categoria->pluck('titulo','id');

       
       $selcat->prepend('-- Escolha uma opção -- ',0);

        return $selcat;
    }

    public static function getListsort(){
       $cat = Categoriablog::get();

       $cat2 = array();

        if(count($cat) > 0){
                foreach ($cat as $key => $variable) {
                        $artcount = Article::where('activo','=','1')->where('category','=',$variable['id'])->count();
                        $cat2[] = array('titulo' => $variable['titulo'], 'qtd' => $artcount, 'id' => $variable['id']);
                }
        }
        $sorted = array_values(Arr::sort($cat2, function ($value) {
            return $value['qtd'];
        }));
       $sorted = array_reverse($sorted, true);
       $sorted = array_slice($sorted, 0, 5, true);

        return $sorted;
    }
    
}
