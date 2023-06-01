<?php

namespace App\Rules;

use App\Models\Task;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoteRequired implements ValidationRule
{
    public $note;

    public function __construct($note)
    {
        $this->note = $note;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $task = Task::findOrFail($value);

        if ($task->name ==  'مكلف بمهمة' && empty($this->note)) {

            $fail('كتابة الملاحظة مطلوبة في حالة اختيار مكلف بمهمة');

        }
    }
}
