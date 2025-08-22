<?php

namespace App\Domains\Employee\Http\Requests;

use App\Domains\Employee\Model\Employee;
use App\Domains\Phone\Enums\PhoneType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('store', Employee::class);
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'phones' => ['required', 'array', 'min:1'],
            'phones.*.number' => ['required', 'string', 'max:255'],
            'phones.*.type' => ['required', 'string', new Enum(PhoneType::class)],
            'phones.*.whatsapp' => ['required', 'boolean'],
        ];
    }
}
