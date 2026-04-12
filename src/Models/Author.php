<?php

namespace CafeHistoire\CafeBlog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Author extends Model
{
    use HasFactory, HasSlug;

    protected $table = 'cafe_blog_authors';

    protected $fillable = [
        'name',
        'slug',
        'email',
        'bio',
        'avatar',
        'website',
        'twitter',
        'instagram',
        'coffee_preference',
        'origin_specialty',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = ['email'];

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
        return $this->hasMany(Post::class, 'author_id');
    }

    public function publishedPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id')->published();
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        // Gravatar fallback
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email ?? ''))) . '?s=200&d=mp';
    }
}
