<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpRequest extends Model
{
    protected $fillable = ['email', 'mobile_number', 'type', 'subject','sub_type','description'];

}
