<?php

namespace App\Domains\Employee\Http\Requests;

use App\Domains\Employee\Enums\EmployeeStatus;
use App\Domains\Employee\Model\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  Gate::allows('indexAndWithPagination', Employee::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string',  'max:255'],
            'status' => ['nullable', 'string', new Enum(EmployeeStatus::class)],
            'per_page' => ['nullable', 'integer'],
            'page' => ['nullable', 'integer'],
            'with_trashed' => ['required', 'boolean'],
        ];
    }
}
