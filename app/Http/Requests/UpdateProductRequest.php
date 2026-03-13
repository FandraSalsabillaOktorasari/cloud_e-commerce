<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Protected by admin middleware at route level
    }

    public function rules(): array
    {
        return [
            'category_id'    => 'required|exists:categories,id',
            'brand'          => 'required|string|max:100',
            'name'           => 'required|string|max:255',
            'description'    => 'required|string',
            'price'          => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images.*'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_trending'    => 'boolean',
            'specifications' => 'nullable|array',
            'specifications.Processor' => 'nullable|string|max:255',
            'specifications.GPU'       => 'nullable|string|max:255',
            'specifications.RAM'       => 'nullable|string|max:255',
            'specifications.Storage'   => 'nullable|string|max:255',
            'specifications.Display'   => 'nullable|string|max:255',
        ];
    }
}
