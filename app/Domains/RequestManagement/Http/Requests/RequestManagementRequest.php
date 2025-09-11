<?php

namespace App\Domains\RequestManagement\Http\Requests;

use App\Domains\RequestManagement\Model\RequestManagement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;

class RequestManagementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('indexAndWithPagination', RequestManagement::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['nullable', 'string'],
            "code" => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'customer_id' => ['nullable', 'integer'],
            'with_trashed' => ['required', 'boolean'],
            'per_page' => ['nullable', 'integer'],
            'page' => ['nullable', 'integer'],
        ];
    }
}
