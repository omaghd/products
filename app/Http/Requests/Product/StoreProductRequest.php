<?php

namespace App\Http\Requests\Product;

use App\Rules\ImageExtensionRule;
use App\Rules\ImageSizeRule;
use App\Rules\UniqueProductName;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255', new UniqueProductName()],
            'description'  => 'required|string',
            'price'        => 'required|numeric|min:0',
            'image'        => ['nullable', new ImageExtensionRule(), new ImageSizeRule()],
            'categories'   => 'required|array|min:1',
            'categories.*' => 'required|integer|exists:categories,id',
        ];
    }
}
