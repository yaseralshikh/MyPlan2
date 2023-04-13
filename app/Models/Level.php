<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    public function status(): string
    {
        return $this->status ? __('site.active') : __('site.inActive');
    }
}
