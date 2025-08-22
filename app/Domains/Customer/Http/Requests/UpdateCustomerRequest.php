<?php

namespace App\Domains\Customer\Http\Requests;

use App\Domains\Phone\Enums\PhoneType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->customer);
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
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('customers')->ignore($this->customer)],
            'cpf' => ['required', 'string', 'min:11', Rule::unique('customers')->ignore($this->customer)],
            'holder_id' => ['nullable', 'exists:customers,id'],
            'birth_date' => ['required', 'date_format:Y-m-d',],
            'first_access' => ['nullable', 'boolean'],
            'phones' => ['required', 'array', 'min:1'],
            'phones.*.id' => ['nullable', 'exists:phones,id'],
            'phones.*.number' => ['required', 'string', 'max:255'],
            'phones.*.type' => ['required', 'string', new Enum(PhoneType::class)],
            'phones.*.whatsapp' => ['required', 'boolean'],
            'address' => ['nullable', 'array'],
            'address.id' => ['nullable'],
            'address.zip_code' => ['nullable', 'string', 'max:9'],
            'address.state' => ['nullable', 'string', 'max:255'],
            'address.city' => ['nullable', 'string', 'max:255'],
            'address.neighborhood' => ['nullable', 'string', 'max:255'],
            'address.street' => ['nullable', 'string', 'max:255'],
            'address.number' => ['nullable', 'string', 'max:255'],
            'address.complement' => ['nullable', 'string', 'max:255'],
            'delete_phones' => ['nullable', 'array'],
        ];
    }
}
