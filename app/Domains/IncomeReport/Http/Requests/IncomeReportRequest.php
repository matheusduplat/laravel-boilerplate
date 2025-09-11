<?php

namespace App\Domains\IncomeReport\Http\Requests;

use App\Domains\IncomeReport\Model\IncomeReport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class IncomeReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('indexAndWithPagination', IncomeReport::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'year' => ['nullable', 'integer'],
            'customer_id' => ['nullable', 'integer'],
            'per_page' => ['nullable', 'integer'],
            'page' => ['nullable', 'integer'],
        ];
    }
}
