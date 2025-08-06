<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    protected $fillable = ['user_id', 'attribute', 'value'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
