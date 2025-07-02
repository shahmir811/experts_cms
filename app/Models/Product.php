<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasSlug;
    
    protected $fillable = [
        'store_id',
        'title',
        'asin',
        'url_amazon',
        'image',
        'buy_box_percentage_amazon_30_days',
        'buy_box_eligible_offer_count_new_fba',
        'amazon_current_price',
        'amazon_stock',
        'list_price_current',
        'list_price_30_days_avg',
        'live_offers_fba',
        'live_offers_fbm',
        'categories_root',
        'categories_sub',
        'categories_tree',
        'launchpad',
        'manufacturer',
        'unit_count_value',
        'unit_count_type',
        'material',
        'item_type',
        'number_of_items',
        'video_count',
        'has_main_video',
        'main_videos',
        'additional_videos',
        'package_dimension_cm3',
        'package_weight_g',
        'package_quantity',
        'item_dimension_cm3',
        'item_length_cm',
        'item_width_cm',
        'item_height_cm',
        'item_weight_g',
        'batteries_included',
        'hazardous_materials',
        'description',
    ];

    protected $casts = [
        'has_main_video' => 'boolean',
        'batteries_included' => 'boolean',
        'amazon_current_price' => 'decimal:2',
        'list_price_current' => 'decimal:2',
        'list_price_30_days_avg' => 'decimal:2',
        'package_dimension_cm3' => 'decimal:2',
        'package_weight_g' => 'decimal:2',
        'item_dimension_cm3' => 'decimal:2',
        'item_length_cm' => 'decimal:2',
        'item_width_cm' => 'decimal:2',
        'item_height_cm' => 'decimal:2',
        'item_weight_g' => 'decimal:2',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function __toString()
    {
        return $this->title ?? 'Unnamed Product';
    }
}