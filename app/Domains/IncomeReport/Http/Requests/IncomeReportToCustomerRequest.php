<?php

namespace App\Domains\IncomeReport\Http\Requests;

use App\Domains\IncomeReport\Model\IncomeReport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class IncomeReportToCustomerRequest extends FormRequest
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
        return Gate::allows('toCustomer', IncomeReport::class);
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
        ];
    }
}
