<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'size', 'short_description', 'description',
        'benefits', 'ingredients', 'how_to_use', 'price', 'mrp', 'discount', 'rating',
        'reviews_count', 'badge', 'cashback', 'is_best_seller', 'is_featured', 'is_new',
        'is_active', 'amazon_url', 'thumbnail', 'stock', 'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'mrp' => 'decimal:2',
        'rating' => 'decimal:1',
        'cashback' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(ProductVideo::class)->orderBy('sort_order');
    }

    public function primaryImage(): ?string
    {
        if ($this->thumbnail) {
            return $this->thumbnail;
        }
        $img = $this->images->first();
        return $img?->path;
    }

    public function getBenefitsListAttribute(): array
    {
        if (!$this->benefits) {
            return [];
        }
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $this->benefits))));
    }
}
