<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_path',
    ];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
    public function financingRequests()
    {
        return $this->hasMany(FinancingRequest::class);
    }
    public function getImagePathAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

}

