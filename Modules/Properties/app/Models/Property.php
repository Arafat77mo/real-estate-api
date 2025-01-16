<?php

namespace Modules\Properties\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Property extends Model implements HasMedia
{
    use HasFactory, Searchable,HasTranslations, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'description', 'location', 'price', 'status', 'user_id'];

    /**
     * The attributes that can be translated.
     */
    public array $translatable = ['name', 'description'];


    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id'); // Property belongs to a user
    }
    /**
     * Register media collections for the property.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('property_images')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
          ; // Max size 2MB
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
        ];
    }
}
