<?php

namespace App\Domains\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateProfileCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('updatePerfil', $this->customer);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'name_social' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('customers')->ignore($this->customer)],
            'cpf' => ['required', 'string', 'min:11', Rule::unique('customers')->ignore($this->customer)],
            'birth_date' => ['required', 'date_format:Y-m-d',],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'first_access' => ['nullable', 'boolean'],
        ];
    }
}
