<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;

class UserRequest extends FormRequest
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

            'name'  => 'required',
            'email' => 'required|unique:users'
        ];

    }
   public function messages()
   {
        return [
            'required' => "ERRO: Falta preencher :attribute",
            'unique'   => "ERRO: Já existe campo :attribute"
        ];
   }
    
    public function persist(){

       User::create([
            'name'        => request()->name,
            'email'       => request()->email,
            'password'    => bcrypt(request()->password)
            
        ]);
    }
}
