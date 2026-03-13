<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxStock = 99;

        if ($this->product_id) {
            $product = Product::find($this->product_id);
            if ($product) {
                $maxStock = $product->stock;
            }
        }

        return [
            'product_id' => 'required|exists:products,id',
            'quantity'    => "required|integer|min:1|max:{$maxStock}",
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.max' => 'The requested quantity exceeds available stock.',
        ];
    }
}
