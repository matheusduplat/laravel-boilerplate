<?php

namespace App\Domains\DigitalWallet\Http\Requests;

use App\Domains\DigitalWallet\Model\DigitalWallet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreDigitalWalletRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('store', DigitalWallet::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'plan' => 'required',
            'number' => 'required',
            'date_issue' => 'required|date_format:Y-m-d',
            'validity' => 'nullable',
        ];
    }
}
