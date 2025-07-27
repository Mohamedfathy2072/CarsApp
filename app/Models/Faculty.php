<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = ['name', 'university_id'];

    public function university()
    {
        return $this->belongsTo(University::class);
    }
}
