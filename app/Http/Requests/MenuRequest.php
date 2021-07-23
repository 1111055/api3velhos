<?php

namespace App\Http\Requests;
use App\Menu;
use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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


            'menu'  => 'required',
            'ordem'  => 'required',

        ];

    }
   public function messages()
    {
        return [
            'required' => "ERRO: Falta preencher :attribute"
        ];
    }
    
    public function persist(){
   
       Menu::create([
            'menu'         => request()->menu,
            'link'         => request()->link,
            'submenu'      => request()->submenu,
            'descricao'    => request()->descricao,
            'ordem'        => request()->ordem,
        ]);
    }
}
