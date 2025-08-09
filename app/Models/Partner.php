<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
protected $fillable = ['title', 'description', 'image', 'link', 'is_active'];

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }
}
