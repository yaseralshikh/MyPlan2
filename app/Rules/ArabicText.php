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
        $words = preg_split('/\s+/', $value);
        $wordCount = count($words);
        $textValue = preg_match('/\p{Arabic}/u', $value);

        if (!$textValue or $wordCount < 4) {

            $fail('يجب كتابة الاسم رباعي كما يجب الكتابة بالأحرف العربية فقط');

        }
    }
}
