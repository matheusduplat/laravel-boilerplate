<?php

namespace App\Domains\Auth\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ForgotPasswordRequest extends FormRequest
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
            'email' => ['required', Rule::exists('users')]
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'E-mail não informado',
            'email.exists' => 'E-mail não cadastrado ou invalido',
        ];
    }
}
