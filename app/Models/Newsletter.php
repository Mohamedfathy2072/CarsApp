<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Newsletter extends Model
{
    protected $fillable = ['email'];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (self::where('email', $model->email)->exists()) {
                throw ValidationException::withMessages([
                    'email' => 'تم إدخال هذا الإيميل من قبل.'
                ]);
            }
        });
    }
}
