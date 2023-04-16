<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start',
        'end',
        'school_year',
        'active',
        'status',
    ];

    public function weeks(): HasMany
    {
        return $this->hasMany(Week ::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event ::class);
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
                ->orWhere('school_year', 'like' , $term);
        });
    }
}
