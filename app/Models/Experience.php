<?php

namespace App\Models;

use Database\Factories\ExperienceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    /** @use HasFactory<ExperienceFactory> */
    use HasFactory;

    protected $fillable = [
        'role',
        'company',
        'period',
        'description',
        'skills',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function getSkillsStringAttribute(): string
    {
        return is_array($this->skills) ? implode(', ', $this->skills) : '';
    }
}
