<?php

namespace App\Rules;

use Closure;
use App\Models\Event;
use Illuminate\Contracts\Validation\ValidationRule;

class UserOverLap implements ValidationRule
{
    public $start;
    public $user_id;

    public function __construct($start,$user_id)
    {
        $this->start = $start;
        $this->user_id = $user_id;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $event = Event::where('start', $this->start)->where('user_id', $this->user_id)->count() == 0;

        if (!$event) {

            $fail('لا يمكن ادخال اكثر من مهمة لنفس المستخدم بنفس اليوم');

        }
    }
}
