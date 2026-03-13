<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand',
        'component_type',
        'socket_type',
        'chipset',
        'memory_type',
        'form_factor',
        'tdp_watts',
        'name',
        'slug',
        'description',
        'specifications',
        'price',
        'stock',
        'image',
        'images',
        'is_trending',
        'view_count',
        'sold_count',
    ];

    protected function casts(): array
    {
        return [
            'specifications' => 'array',
            'images' => 'array',
            'price' => 'decimal:2',
            'is_trending' => 'boolean',
        ];
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the cart items for the product.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the wishlists for the product.
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the average rating.
     */
    public function getAverageRatingAttribute(): ?float
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? round($avg, 1) : null;
    }

    /**
     * Check if the current user has wishlisted this product.
     */
    public function getIsWishlistedAttribute(): bool
    {
        if (!Auth::check()) return false;
        return $this->wishlists()->where('user_id', Auth::id())->exists();
    }

    /**
     * Scope: trending products.
     */
    public function scopeTrending($query)
    {
        return $query->where('is_trending', true)->orWhere('view_count', '>=', 100);
    }

    /**
     * Scope: best sellers.
     */
    public function scopeBestSellers($query)
    {
        return $query->orderByDesc('sold_count');
    }

    /**
     * Scope to filter products that are compatible with a given reference product (e.g. CPU)
     */
    public function scopeCompatibleWith($query, Product $reference)
    {
        if ($reference->component_type === 'cpu') {
            return $query->where('socket_type', $reference->socket_type);
        }
        
        if ($reference->component_type === 'motherboard') {
            // Motherboard dictates both CPU socket and RAM type
            return $query->where(function($q) use ($reference) {
                $q->where('socket_type', $reference->socket_type)
                  ->orWhere('memory_type', $reference->memory_type);
            });
        }

        return $query;
    }
}
