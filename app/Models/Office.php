<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'assistant3_signature_path',
        'education_id',
        'office_type',
        'gender',
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

    public function education(): BelongsTo
    {
        return $this->belongsTo(Education::class);
    }

    public function allowed_create_plans(): string
    {
        return $this->allowed_create_plans ? __('site.open') : __('site.close');
    }

    public function office_type(): string
    {
        return $this->office_type ? __('site.officeType') : __('site.managementType');
    }

    public function gender(): string
    {
        return $this->gender ? __('site.male') : __('site.female');
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
        'assistant3_url',
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

    public function getAssistant3UrlAttribute()
    {
        if ($this->assistant3_signature_path && Storage::disk('signature_photos')->exists($this->assistant3_signature_path)) {
            return Storage::disk('signature_photos')->url($this->assistant3_signature_path);
        }

        return asset('backend/img/noimage.png');
    }
}
