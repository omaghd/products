<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ImageExtensionRule implements ValidationRule
{
    protected array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value instanceof UploadedFile) {
            $extension = strtolower($value->getClientOriginalExtension());
        } else {
            $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));
        }

        if (!in_array($extension, $this->allowedExtensions)) {
            $extensions = implode(', ', $this->allowedExtensions);
            $fail("The $attribute must have a valid image file extension ($extensions).");
        }
    }
}
