<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Role;

class RoleRequest extends FormRequest
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

            'name'  => 'required'
        ];

    }
   public function messages()
    {
        return [
            'required' => "ERRO: Falta preencher :attribute"
        ];
    }
    
    public function persist(){
   
       Role::create([
            'name'         => request()->name,
            'description'  => request()->description
        ]);
    }
}
