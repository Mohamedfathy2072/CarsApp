<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarImage extends Model
{
    use HasFactory;

    protected $fillable = ['car_id', 'image_path', 'is_360'];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

}
