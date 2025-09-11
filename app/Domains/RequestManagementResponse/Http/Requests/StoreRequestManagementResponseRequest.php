<?php

namespace App\Domains\RequestManagementResponse\Http\Requests;

use App\Domains\RequestManagementResponse\Model\RequestManagementResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreRequestManagementResponseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('store', RequestManagementResponse::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "hidden" => ['nullable', 'boolean'],
            "answer" => ['required', 'string'],
            "request_management_id" => ['required', 'exists:request_management,id'],
        ];
    }
}
