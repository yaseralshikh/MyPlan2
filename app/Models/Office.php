<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'director',
        'director_signature_path',
        'assistant_signature_path',
        'assistant2_signature_path',
        'status',
    ];

    public function status(): string
    {
        return $this->status ? __('site.active') : __('site.inActive');
    }

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";

        $query->where(function($query) use ($term){
            $query->where('name', 'like' , $term)->orWhere('director', 'like' , $term);
        });
    }

    protected $appends = [
        'director_url',
        'assistant_url',
        'assistant2_url',
    ];
}
