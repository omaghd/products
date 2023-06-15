<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255|unique:products,name,' . $this->product,
            'description'  => 'required|string',
            'price'        => 'required|numeric|min:0',
            'image'        => 'nullable|image|max:2048',
            'categories.*' => 'nullable|integer|exists:categories,id',
        ];
    }
}
