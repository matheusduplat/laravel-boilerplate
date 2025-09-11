<?php

namespace App\Domains\AccreditedNetworks\Http\Requests;

use App\Domains\AccreditedNetworks\Enums\TypeServiceAccreditedNetworks;
use App\Domains\AccreditedNetworks\Model\AccreditedNetworks;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;

class AccreditedNetworksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('indexAndWithPagination', AccreditedNetworks::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer'],
            'page' => ['nullable', 'integer'],
            'name' => ['nullable', 'string', 'max:255'],
            'with_trashed' => ['required', 'boolean'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            "type_service" => ['nullable', 'string', new Enum(TypeServiceAccreditedNetworks::class)],
        ];
    }
}
