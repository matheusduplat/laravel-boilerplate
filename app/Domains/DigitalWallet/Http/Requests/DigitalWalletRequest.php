<?php

namespace App\Domains\DigitalWallet\Http\Requests;

use App\Domains\DigitalWallet\Model\DigitalWallet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DigitalWalletRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('indexAndWithPagination', DigitalWallet::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "per_page" => ["nullable", "integer"],
            "page" => ["nullable", "integer"],
            "customer_id" => ["nullable", "exists:customers,id"],
            "number" => ["nullable", "string"],
        ];
    }
}
