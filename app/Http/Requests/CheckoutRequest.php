<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_name'        => 'required|string|max:255',
            'shipping_address'     => 'required|string|max:1000',
            'shipping_city'        => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_phone'       => 'required|string|max:30',
            'payment_method'       => 'required|in:bank_transfer,credit_card,e_wallet',
            'notes'                => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_name.required'    => 'Please enter your full name.',
            'shipping_address.required' => 'Please enter your shipping address.',
            'shipping_city.required'    => 'Please enter your city.',
            'shipping_phone.required'   => 'Please enter your phone number.',
            'payment_method.required'   => 'Please select a payment method.',
        ];
    }
}
