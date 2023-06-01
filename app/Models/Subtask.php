<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subtask extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'section',
        'edu_type',
        'office_id',
        'section_type_id',
        'position',
        'status',
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function section_type(): BelongsTo
    {
        return $this->belongsTo(SectionType::class);
    }

    public function status(): string
    {
        return $this->status ? __('site.active') : __('site.inActive');
    }

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";

        $query->where(function($query) use ($term){
            $query->where('name', 'like' , $term);
        });
    }
}
