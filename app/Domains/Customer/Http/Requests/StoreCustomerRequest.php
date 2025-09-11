<?php

namespace App\Domains\Customer\Http\Requests;

use App\Domains\Customer\Model\Customer;
use App\Domains\Phone\Enums\PhoneType;
use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\Role\Model\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;

class StoreCustomerRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $role = Role::where('name', RoleDefaults::CLIENT)->first();
        $this->merge([
            'role' => $role->id
        ]);
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('store', Customer::class);
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'cpf' => ['required', 'string', 'min:11', 'unique:customers'],
            'phones' => ['required', 'array', 'min:1'],
            'phones.*.number' => ['required', 'string', 'max:255'],
            'phones.*.type' => ['required', 'string', new Enum(PhoneType::class)],
            'phones.*.whatsapp' => ['required', 'boolean'],
            'birth_date' => ['required', 'date_format:Y-m-d',],
            'role' => ['required', 'integer', 'exists:roles,id'],
            'address' => ['nullable', 'array'],
            'address.zip_code' => ['nullable', 'string', 'max:9'],
            'address.state' => ['nullable', 'string', 'max:255'],
            'address.city' => ['nullable', 'string', 'max:255'],
            'address.neighborhood' => ['nullable', 'string', 'max:255'],
            'address.street' => ['nullable', 'string', 'max:255'],
            'address.number' => ['nullable', 'string', 'max:255'],
            'address.complement' => ['nullable', 'string', 'max:255'],
        ];
    }
}
