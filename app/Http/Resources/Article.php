<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class Article extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
           $createdAt = Carbon::parse($this->created_at);

           $link = "/articles/show/".$this->id;

        //   dd($link);

            return [

                'id'           => $this->id,
                'title'        => $this->title,
                'body'         => $this->body,
                'path'         => $this->path,
                'created_at'   => $createdAt->format('d M Y'),
                'category'     => $this->categoriablog != null ? $this->categoriablog->titulo : 0,
                'link'         =>  $link,
                'fonte'         =>  $this->fonte, 

            ];

    }


    public function with($request){
            return [

                   'version' => '1.0.0',
                   'author'  => '3Velhos'     
            ];

    }
}
