<?php

namespace App\Domains\Bill\Http\Requests;

use App\Domains\Bill\Enums\BillStatus;
use App\Domains\Bill\Model\Bill;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;

class StoreBillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('store', Bill::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "base64" => "required",
            'title_id' => 'required',
            'base_year' => 'required',
            'base_month' => 'required',
            'issue_date' => 'required',
            'due_date' => 'required',
            'low_date' => 'nullable',
            'value' => 'required|integer',
            'status' => ['required', new Enum(BillStatus::class)],
            'customer_id' => 'required|exists:customers,id',
        ];
    }
}
