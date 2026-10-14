<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public const CATEGORIES = [
        'web' => 'Web Development',
        'uiux' => 'UI/UX Design',
        'responsive' => 'Responsive Web',
        'mobile' => 'Mobile App',
        'opensource' => 'Open Source',
    ];

    protected $fillable = [
        'title', 'slug', 'description', 'image', 'category',
        'technologies', 'demo_url', 'repository_url', 'published', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'published' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true)->orderBy('sort_order')->orderBy('id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst($this->category);
    }

    public function getImageUrlAttribute(): string
    {
        return asset($this->image ?: 'images/project-placeholder.svg');
    }
}
