<?php

namespace App\Domains\AccreditedNetworks\Http\Requests;

use App\Domains\AccreditedNetworks\Enums\StatusAccreditedNetworks;
use App\Domains\AccreditedNetworks\Enums\TypeServiceAccreditedNetworks;
use App\Domains\Phone\Enums\PhoneType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;

class UpdateAccreditedNetworksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->accreditedNetworks);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "type_service" => ["required", "string", new Enum(TypeServiceAccreditedNetworks::class)],
            "specialties_served" => ["required", "array", "min:1"],
            "exams_attended" => ["required", "array", "min:1"],
            "plans_attended" => ["required", "array", "min:1"],
            'urgency_emergency' => ['required', 'boolean'],
            'status' => ['required', new Enum(StatusAccreditedNetworks::class)],
            'phones' => ['required', 'array', 'min:1'],
            'phones.*.id' => ['nullable', 'exists:phones,id'],
            'phones.*.number' => ['required', 'string', 'max:255'],
            'phones.*.type' => ['required', 'string', new Enum(PhoneType::class)],
            'phones.*.whatsapp' => ['required', 'boolean'],
            'address' => ['nullable', 'array'],
            'address.id' => ['nullable', 'exists:addresses,id'],
            'address.zip_code' => ['nullable', 'string', 'max:9'],
            'address.state' => ['nullable', 'string', 'max:255'],
            'address.city' => ['nullable', 'string', 'max:255'],
            'address.neighborhood' => ['nullable', 'string', 'max:255'],
            'address.street' => ['nullable', 'string', 'max:255'],
            'address.number' => ['nullable', 'string', 'max:255'],
            'address.complement' => ['nullable', 'string', 'max:255'],
            'delete_phones' => ['nullable', 'array'],
        ];
    }
}
