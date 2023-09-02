<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MobileNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove any non-numeric characters from the input value.
        $numericValue = preg_replace('/[^0-9]/', '', $value);

        // Validate that the numeric value is 10 digits long.

        if (strlen($numericValue) !== 10 || !ctype_digit($numericValue)) {

            $fail(':attribute يجب أن يكون رقم هاتف مكونًا من 10 أرقام.');

        }
    }
}
