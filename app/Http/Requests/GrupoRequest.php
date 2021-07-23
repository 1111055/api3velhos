<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Grupo;

class GrupoRequest extends FormRequest
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
              'nome' => 'required|unique:grupos|max:255',
        ];
    }
    public function messages()
    {
        return [
            "unique" => "Error ja existe esse grupo!",
        ];
    }
     public function persist(){
   
       Grupo::create([
            'nome'         => request()->nome,
            'user_id'      => request()->user_id,

        ]);
    }
}
