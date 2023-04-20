<?php

namespace App\Rules;

use Closure;
use App\Models\Week;
use App\Models\Semester;
use Illuminate\Contracts\Validation\ValidationRule;

class DateOutService implements ValidationRule
{
    public $start;
    public $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $semester_Id = Semester::where('start', '<=', $this->start)->where('end', '>=', $this->end)->pluck('id')->first();
        $week_Id = Week::where('start', '<=', $this->start)->where('end', '>=', $this->end)->pluck('id')->first();

        if (!$semester_Id || !$week_Id) {

            $fail('اليوم المحدد غير مطابق لتقويم الفصل الدراسي');

        }

    }
}
