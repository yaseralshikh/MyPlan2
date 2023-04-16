<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'director',
        'allowed_overlap',
        'allowed_create_plans',
        'director_signature_path',
        'assistant_signature_path',
        'assistant2_signature_path',
        'status',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User ::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task ::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event ::class);
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask ::class);
    }

    public function features(): HasMany
    {
        return $this->hasMany(Feature ::class);
    }

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

    public function getDirectorUrlAttribute()
    {
        if ($this->director_signature_path && Storage::disk('signature_photos')->exists($this->director_signature_path)) {
            return Storage::disk('signature_photos')->url($this->director_signature_path);
        }
        return asset('backend/img/noimage.png');
    }

    public function getAssistantUrlAttribute()
    {
        if ($this->assistant_signature_path && Storage::disk('signature_photos')->exists($this->assistant_signature_path)) {
            return Storage::disk('signature_photos')->url($this->assistant_signature_path);
        }

        return asset('backend/img/noimage.png');
    }

    public function getAssistant2UrlAttribute()
    {
        if ($this->assistant2_signature_path && Storage::disk('signature_photos')->exists($this->assistant2_signature_path)) {
            return Storage::disk('signature_photos')->url($this->assistant2_signature_path);
        }

        return asset('backend/img/noimage.png');
    }
}
