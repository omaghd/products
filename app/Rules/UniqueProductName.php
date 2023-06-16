<?php

namespace App\Rules;

use App\Repositories\Product\IProductRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueProductName implements ValidationRule
{
    public function __construct(protected ?int $id = null)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $productRepository = app(IProductRepository::class);

        $product = $productRepository->findByName($value);

        if ($product && $product->id !== $this->id) {
            $fail("The :attribute has already been taken.");
        }
    }
}
