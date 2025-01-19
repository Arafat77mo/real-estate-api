<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', // Add this line
        'message',
        'type',
        'read_at',
    ];
}
