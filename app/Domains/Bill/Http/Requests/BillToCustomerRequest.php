<?php

namespace App\Domains\Bill\Http\Requests;

use App\Domains\Bill\Model\Bill;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BillToCustomerRequest extends FormRequest
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
        return Gate::allows('toCustomer', Bill::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'base_year' => 'nullable',
            'base_month' => 'nullable',
        ];
    }
}
