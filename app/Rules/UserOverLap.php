<?php

namespace App\Rules;

use Closure;
use App\Models\Event;
use Illuminate\Contracts\Validation\ValidationRule;

class UserOverLap implements ValidationRule
{
    public $start;

    public function __construct($start)
    {
        $this->start = $start;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $event = Event::where('start', $this->start)->where('user_id', auth()->user()->id)->count() == 0;

        if (!$event) {

            $fail('لا يمكن ادخال اكثر من مهمة لنفس المستخدم بنفس اليوم.');
            
        }
    }
}
