<?php

namespace App\Domains\Copaticipation\Http\Requests;

use App\Domains\Copaticipation\Model\Copaticipation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreCopaticipationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('store', Copaticipation::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'base64' => 'required',
            'base_month' => 'required',
            'base_year' => 'required',
            'customer_id' => 'required|exists:customers,id',
        ];
    }
}
