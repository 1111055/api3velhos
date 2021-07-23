<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Article;

class Categoriablog extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $total = Article::where('category','=',$this->id)->count();

        return [

            'id'           => $this->id,
            'titulo'       => $this->titulo,
            'descricao'    => $this->descricao,
            'total'        => $total

        ];

    }

}
