<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSearch extends Model
{
    protected $fillable = [
        'user_id',
        'search_query',
        'filters',
    ];

    protected $casts = [
        'filters' => 'array', // لتحويل JSON إلى Array والعكس
    ];
}
