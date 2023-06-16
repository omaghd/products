<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ImageSizeRule implements ValidationRule
{
    protected int $maxSize = 2 * 1024 * 1024; // 2MB

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value instanceof UploadedFile) {
            if ($value->getSize() > $this->maxSize) {
                $fail("The :attribute must be less than 2MB.");
            }
        } else {
            if (filesize($value) > $this->maxSize) {
                $fail("The :attribute must be less than 2MB.");
            }
        }
    }
}
