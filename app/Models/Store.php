<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Store extends Model
{
    use HasSlug, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'wallmart_key',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }    

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'owner_id');
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
