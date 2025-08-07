<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarExteriorCondition extends Model
{
    protected $fillable = ['car_id', 'part_name', 'note', 'image_path'];

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
