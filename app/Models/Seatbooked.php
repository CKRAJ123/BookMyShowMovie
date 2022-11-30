<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seatbooked extends Model
{
    protected $fillable = [
        'user_id',
        'total_seat_selected',
        'total_movie_selected',
        //'status',
    ];
}
