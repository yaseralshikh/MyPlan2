<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'need_care',
        'level_id',
        'office_id',
        'status',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event ::class);
    }

    public function need_care(): string
    {
        return $this->need_care ? __('site.need_care') : '';
    }

    public function status(): string
    {
        return $this->status ? __('site.active') : __('site.inActive');
    }

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";

        $query->where(function($query) use ($term){
            $query->where('name', 'like' , $term)
                ->orWhere(function ($qu) use ($term) {
                    $qu->whereHas('level', function ($q) use ($term) {
                        $q->where('name', 'like', $term);
                });
            });
        });
    }
}
