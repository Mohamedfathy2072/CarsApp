<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $fillable = [
        'hotline_number',
        'branch_number',
        'whatsapp_number',
    ];
}
