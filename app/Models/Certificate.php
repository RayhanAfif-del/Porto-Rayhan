<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = ['title', 'issuer', 'issued_at', 'image', 'verification_url', 'sort_order'];

    protected function casts(): array
    {
        return ['issued_at' => 'date'];
    }

    public function getImageUrlAttribute(): string
    {
        return asset($this->image ?: 'images/certificate-placeholder.svg');
    }
}
