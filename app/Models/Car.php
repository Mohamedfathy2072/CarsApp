<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id', 'model', 'year', 'color', 'transmission', 'engine_cc',
        'body_type', 'km_driven', 'price', 'down_payment', 'license_validity', 'location'
    ];

    public function images()
    {
        return $this->hasMany(CarImage::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function favouritedByUsers()
    {
        return $this->belongsToMany(User::class, 'car_user_favourites')->withTimestamps();
    }

}

