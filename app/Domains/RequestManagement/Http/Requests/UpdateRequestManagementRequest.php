<?php

namespace App\Domains\RequestManagement\Http\Requests;

use App\Domains\RequestManagement\Enums\StatusProcedureRequestManagement;
use App\Domains\RequestManagement\Enums\StatusRequestManagement;
use App\Domains\RequestManagement\Enums\TypeRequestManagement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateRequestManagementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->requestManagement);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if (gettype($this->attachment) == 'string') {
            return [
                'type' => ['required', new Enum(TypeRequestManagement::class)],
                'note_customer' => ['required', 'string', 'max:255'],
                'location_performing_procedure' => ['nullable', 'string'],
                'code_guide' => ['nullable', 'string'],
                'status' => ['nullable', new Enum(StatusRequestManagement::class)],
                'status_procedure' => ['nullable', new Enum(StatusProcedureRequestManagement::class)],
                // 'date_close' => ['nullable', 'date_format:Y-m-d'],
                // 'customer_id' => ['required', 'exists:customers,id'],
                "response" => ['nullable', 'array'],
                "response.hidden" => ['nullable', 'boolean'],
                "response.answer" => ['nullable', 'string'],
                "deleted_response" => ['nullable', 'array'],
                'responsible' => ['nullable', 'array'],
                'responsible.*' => ['required', 'exists:employees,id'],
            ];
        }

        return [
            'type' => ['required', new Enum(TypeRequestManagement::class)],
            'note_customer' => ['required', 'string', 'max:255'],
            'location_performing_procedure' => ['nullable', 'string'],
            'code_guide' => ['nullable', 'string'],
            // 'customer_id' => ['required', 'exists:customers,id'],
            'status' => ['nullable', new Enum(StatusRequestManagement::class)],
            'status_procedure' => ['nullable', new Enum(StatusProcedureRequestManagement::class)],
            // 'date_close' => ['nullable', 'date_format:Y-m-d'],
            'attachment' => [
                'file',
                'max:2048',
                Rule::excludeIf(gettype($this->attachment) == 'string'),
                Rule::requiredIf($this->type != TypeRequestManagement::CONSULTATION->name)
            ],
            "response" => ['nullable', 'array'],
            "response.hidden" => ['nullable', 'boolean'],
            "response.answer" => ['nullable', 'string'],
            "deleted_response" => ['nullable', 'array'],
            'responsible' => ['nullable', 'array'],
            'responsible.*' => ['required', 'exists:employees,id'],
        ];
    }
}
