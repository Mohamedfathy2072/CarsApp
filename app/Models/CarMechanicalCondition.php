<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarMechanicalCondition extends Model
{
    protected $fillable = ['car_id', 'part_name', 'note', 'image_path'];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}

