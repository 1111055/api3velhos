<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Pagina;

class PaginaRequest extends FormRequest
{
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


            'nome'  => 'required',


        ];

    }
   public function messages()
    {
        return [
            'required' => "ERRO: Falta preencher :attribute"
        ];
    }
    
    public function persist(){
   
       Pagina::create([
            'nome'         => request()->nome,
            'titulo'         => request()->titulo,
            'subtitulo'   => request()->subtitulo,
            'descricao'        => request()->descricao,
            'titulo1'        => request()->titulo1,
            'descricao1'        => request()->descricao1,
            'titulo2'        => request()->titulo2,
            'descricao2'        => request()->descricao2,
            'link'        => request()->link,
            'link1'        => request()->link1,
            'pathimg'        => request()->pathimg,
            'pathimg1'        => request()->pathimg1,
        ]);
    }
}
