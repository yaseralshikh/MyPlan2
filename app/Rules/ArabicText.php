<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ArabicText implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $textValue = preg_match('/\p{Arabic}/u', $value);

        if (!$textValue) {

            $fail('يسمح بكتابة الحروف العربية فقط');

        }
    }
}
