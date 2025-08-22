<?php

namespace App\Domains\Customer\Http\Requests;

use App\Domains\Customer\Model\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('indexAndWithPagination', Customer::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["nullable", "string", "max:255"],
            "email" => ["nullable", "string",  "max:255"],
            "cpf" => ["nullable", "string"],
            "status" => ["nullable"],
            'per_page' => ['nullable', 'integer'],
            'page' => ['nullable', 'integer'],
            "with_trashed" => ["required", "boolean"],
        ];
    }
}
