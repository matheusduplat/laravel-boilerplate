<?php

namespace App\Domains\Contact\Http\Requests;

use App\Domains\Contact\Enums\StatusContact;
use App\Domains\Contact\Model\Contact;
use App\Domains\Customer\Model\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreContactRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        if (Auth::check() && Auth::user() instanceof Customer) {
            $this->merge([
                'customer_id' => Auth::user()->id,
            ]);
        }
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "message" => ["required", "string"],
            "status" => ["nullable", "string", new Enum(StatusContact::class)],
            "customer_id" => ["nullable", "exists:customers,id"],
            "name" => ["string", Rule::requiredIf($this->customer_id == null)],
            "phone" => ["string", Rule::requiredIf($this->customer_id == null)],
            "email" => ["string", "email", Rule::requiredIf($this->customer_id == null)],
        ];
    }
}
