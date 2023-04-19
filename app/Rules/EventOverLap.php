<?php

namespace App\Rules;

use Closure;
use App\Models\Event;
use Illuminate\Contracts\Validation\ValidationRule;

class EventOverLap implements ValidationRule
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
        $event = Event::where('task_id', $value)->where('start', $this->start)
            ->whereHas('task', function ($q) {$q->whereNotIn('name',['إجازة','برنامج تدريبي','يوم مكتبي','مكلف بمهمة']);})
            ->count() <= auth()->user()->office->allowed_overlap ;

        if (!$event) {

            $fail('تم حجز الزيارة في هذا الموعد لنفس المدرسة من قبل مشرف أخر.');
            
        }
    }
}
