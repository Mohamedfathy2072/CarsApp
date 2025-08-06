<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    /** @use HasFactory<\Database\Factories\QuizFactory> */
    use HasFactory;

    protected $fillable = ['question', 'attribute', 'options', 'type', 'is_required'];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

}
