<?php

namespace App\Domains\Auth\Request;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ];
    }
    public function messages()
    {
        return [
            'token.required' => 'Token não informado',
            'email.required' => 'E-mail não informado',
            'password.required' => 'Senha não informado',
            'password.confirmed' => 'Senha diferente da senha de confirmação',
        ];
    }
}
