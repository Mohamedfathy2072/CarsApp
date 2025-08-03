<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['title', 'description', 'image', 'link'];


    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image);
    }

}
