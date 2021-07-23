<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Aposta;

class ApostaRequest extends FormRequest
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
   
       Aposta::create([
            'user_id'    => request()->user_id,
            'jogo_id'    => request()->jogo_id,
            'aposta'     => request()->aposta
            
        ]);
    }
}
