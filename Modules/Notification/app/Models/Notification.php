<?php

namespace Modules\Notification\app\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', // Add this line
        'message',
        'type',
        'read_at',
    ];
    /**
     * Scope a query to only include notifications for a specific user.
     *
     * @param Builder $query
     * @param int $userId
     * @return Builder
     */
    protected static function booted()
    {
        static::addGlobalScope('user_id', function (Builder $builder) {
            $builder->where('user_id', auth()->id());
        });
    }
}
