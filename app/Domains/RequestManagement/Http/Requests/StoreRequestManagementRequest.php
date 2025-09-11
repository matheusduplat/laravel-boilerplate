<?php

namespace App\Domains\RequestManagement\Http\Requests;

use App\Domains\RequestManagement\Enums\StatusProcedureRequestManagement;
use App\Domains\RequestManagement\Enums\StatusRequestManagement;
use App\Domains\RequestManagement\Enums\TypeRequestManagement;

use App\Domains\RequestManagement\Model\RequestManagement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreRequestManagementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('store', RequestManagement::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', new Enum(TypeRequestManagement::class)],
            'note_customer' => ['required', 'string', 'max:255'],
            'location_performing_procedure' => ['nullable', 'string'],
            'attachment' => ['file', 'max:2048', Rule::requiredIf($this->type != TypeRequestManagement::CONSULTATION->name)],
            'code_guide' => ['nullable', 'string'],
            'customer_id' => ['required', 'exists:customers,id'],
            'status' => ['nullable', new Enum(StatusRequestManagement::class)],
            'status_procedure' => ['nullable', new Enum(StatusProcedureRequestManagement::class)],
            // 'date_close' => ['nullable', 'date_format:Y-m-d'],
            'responsible' => ['nullable', 'array'],
            'responsible.*' => ['required', 'exists:employees,id'],
        ];
    }
}
