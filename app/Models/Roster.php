<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{

    protected $fillable = [
        'date',
        'day',
        'week',
        'shift_type',
        'staff_name',
    ];
}
