<?php

namespace CafeHistoire\CafeBlog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory, HasSlug;

    protected $table = 'cafe_blog_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'cover_image',
        'meta_title',
        'meta_description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Predefined coffee blog categories.
     */
    public const DEFAULT_CATEGORIES = [
        ['name' => 'Histoire du Café',         'color' => '#6B3A2A', 'icon' => '📜'],
        ['name' => 'Origines & Terroirs',       'color' => '#2D5A27', 'icon' => '🌍'],
        ['name' => 'Méthodes de Torréfaction',  'color' => '#8B4513', 'icon' => '🔥'],
        ['name' => 'Art du Barista',            'color' => '#4A2C2A', 'icon' => '☕'],
        ['name' => 'Cafés Légendaires',         'color' => '#7B6B4A', 'icon' => '🏛️'],
        ['name' => 'Science & Chimie du Café',  'color' => '#2C5F6E', 'icon' => '🧪'],
        ['name' => 'Café & Littérature',        'color' => '#5C4A7A', 'icon' => '📚'],
        ['name' => 'Recettes & Préparations',   'color' => '#A0522D', 'icon' => '🍵'],
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

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    public function publishedPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id')->published();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
