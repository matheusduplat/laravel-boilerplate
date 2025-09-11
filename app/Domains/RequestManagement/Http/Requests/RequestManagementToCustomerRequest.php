<?php

namespace App\Domains\RequestManagement\Http\Requests;

use App\Domains\RequestManagement\Model\RequestManagement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RequestManagementToCustomerRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'customer_id' => Auth::user()->id,
        ]);
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('toCustomer', RequestManagement::class);
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
            'status' => ['nullable', 'string'],
            'customer_id' => ['required', 'integer'],
            'with_trashed' => ['required', 'boolean'],
            'per_page' => ['nullable', 'integer'],
            'page' => ['nullable', 'integer'],
        ];
    }
}
