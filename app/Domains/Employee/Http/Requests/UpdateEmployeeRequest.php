<?php

namespace App\Domains\Employee\Http\Requests;

use App\Domains\Employee\Model\Employee;
use App\Domains\Phone\Enums\PhoneType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->employee);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->employee->user->id;
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'role' => ['required', 'exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'phones' => ['required', 'array', 'min:1'],
            'phones.*.id' => ['nullable ',  'exists:phones,id'],
            'phones.*.number' => ['required', 'string', 'max:255'],
            'phones.*.type' => ['required', 'string', new Enum(PhoneType::class)],
            'phones.*.whatsapp' => ['required', 'boolean'],
            'delete_phones' => ['nullable', 'array'],
            'delete_phones.*' => ['required',  'exists:phones,id'],
        ];
    }
}
