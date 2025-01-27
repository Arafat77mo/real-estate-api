<?php

namespace Modules\Favorite\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Properties\App\Models\Property;

// use Modules\Favorite\Database\Factories\FavoriteFactory;

class Favorite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id','property_id', 'id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Property belongs to a user
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id'); // Property belongs to a user
    }
}
