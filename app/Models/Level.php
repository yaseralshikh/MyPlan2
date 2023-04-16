<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task ::class);
    }

    public function status(): string
    {
        return $this->status ? __('site.active') : __('site.inActive');
    }
}
