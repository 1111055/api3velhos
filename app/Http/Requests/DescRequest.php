<?php

namespace App\Http\Requests;

use App\Desc;
use Illuminate\Foundation\Http\FormRequest;

class DescRequest extends FormRequest
{
    /**
   /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [


        ];

    }
   public function messages()
    {
        return [
            
        ];
    }
    
    public function persist(){
   
       Desc::create([
            'page_id'      => request()->idpage,
            'link'         => request()->link,
            'titulo'       => request()->titulo,
            'descricao'    => request()->descricaodesc,
            'path'         => request()->path,
            'ordem'        => request()->ordem,
            'activo'       => 1,

        ]);
    }
}
