<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'note',
        'start',
        'end',
        'overlap',
        'display',
        'user_id',
        'week_id',
        'semester_id',
        'office_id',
        'color',
        'status',
        'task_done',
    ];

    public function status(): string
    {
        return $this->status ? __('site.permission') : __('site.unPermission');
    }

    public function overlap(): string
    {
        return $this->overlap ? __('site.active') : __('site.inActive');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function week(): BelongsTo
    {
        return $this->belongsTo(Week::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";

        $query->where(function($query) use ($term){
            $query->whereHas('task', function ($q) use ($term) {
                    $q->where('name', 'like', $term);
                })
                ->orWhere(function ($qu) use ($term) {
                    $qu->whereHas('user', function ($q) use ($term) {
                        $q->where('name', 'like', $term);
                });
            });
        });
    }
}
