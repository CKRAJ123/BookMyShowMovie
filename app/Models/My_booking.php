<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class My_booking extends Model
{
    protected $fillable = [
        
        'theater_id',
        'seatbooked_id',
        //'status',
    ];
}
