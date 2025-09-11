<?php

namespace App\Domains\Copaticipation\Http\Requests;

use App\Domains\Copaticipation\Model\Copaticipation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CopaticipationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('indexAndWithPagination', Copaticipation::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "base_month" => ["nullable", "string"],
            "base_year" => ["nullable", "string"],
            "per_page" => ["nullable", "integer"],
            "page" => ["nullable", "integer"],
            "customer_id" => ["nullable", "exists:customers,id"],
        ];
    }
}
