<?php

namespace Modules\Properties\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Modules\Properties\app\Models\Scopes\ActiveAndNotOwnerScope;
use Modules\Properties\app\Models\Scopes\OwnerScope;
use Modules\Properties\app\Models\Scopes\SearchScope;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Property extends Model implements HasMedia
{
    use HasFactory, Searchable,HasTranslations, InteractsWithMedia,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'location',
        'price',
        'status',
        'user_id' ,
        'latitude',  // عمود خطوط العرض
        'longitude', // عمود خطوط الطول
        'rooms',
        'bathrooms',
        'living_room_size',
        'additional_features',
        'type',
        'created_at',
        'updated_at'
];

    /**
     * The attributes that can be translated.
     */
    public array $translatable = ['name', 'description'];

    protected static function booted()
    {
        // Always apply the 'latest' global scope
        static::addGlobalScope('latest', function ($query) {
            $query->latest();
        });

        // If the user is not 'owner', apply 'active_and_not_owner' scope
        if (auth()->check() && auth()->user()->type !== 'owner') {
            static::addGlobalScope('active_and_not_owner', new ActiveAndNotOwnerScope());
        }

    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id'); // Property belongs to a user
    }

    public function scopeForOwner($query, $ownerId)
    {
        return $query->where('user_id', $ownerId);
    }
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
    /**
     * Scope for filtering properties by location.
     */
    public function scopeLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    /**
     * Get the URL of the cover image.
     */
    public function getCoverImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('property_images') ?: asset('images/default.png');
    }

    public function getPropertyImagesUrlsAttribute()
    {
        return $this->getMedia('property_images')->map(fn($mediaItem) => $mediaItem->getUrl());
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'location' => $this->location,
            'price' => $this->price,
            'rooms' => $this->rooms,
            'bathrooms' => $this->bathrooms,
            'living_room_size' => $this->living_room_size,
            'type' => $this->type,
        ];
    }



}
