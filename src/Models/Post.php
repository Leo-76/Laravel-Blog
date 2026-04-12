<?php

namespace CafeHistoire\CafeBlog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

class Post extends Model
{
    use HasFactory, SoftDeletes, HasSlug, HasTags;

    protected $table = 'cafe_blog_posts';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'featured_image_caption',
        'author_id',
        'category_id',
        'status',
        'is_featured',
        'coffee_origin',
        'coffee_variety',
        'coffee_era',
        'brewing_method',
        'reading_time_minutes',
        'views_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
    ];

    protected $casts = [
        'is_featured'        => 'boolean',
        'published_at'       => 'datetime',
        'views_count'        => 'integer',
        'reading_time_minutes' => 'integer',
    ];

    /**
     * Coffee origins for filtering.
     */
    public const ORIGINS = [
        'ethiopie'   => 'Éthiopie',
        'yemen'      => 'Yémen',
        'bresil'     => 'Brésil',
        'colombie'   => 'Colombie',
        'vietnam'    => 'Vietnam',
        'indonesia'  => 'Indonésie',
        'guatemala'  => 'Guatemala',
        'kenya'      => 'Kenya',
        'costa_rica' => 'Costa Rica',
        'jamaique'   => 'Jamaïque',
        'mondial'    => 'Mondial',
    ];

    /**
     * Historical eras.
     */
    public const ERAS = [
        'antiquite'       => 'Antiquité (avant 600)',
        'moyen_age'       => 'Moyen Âge (600–1400)',
        'renaissance'     => 'Renaissance (1400–1700)',
        'moderne'         => 'Époque Moderne (1700–1900)',
        'contemporaine'   => 'Époque Contemporaine (1900+)',
    ];

    /**
     * Status values.
     */
    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_SCHEDULED = 'scheduled';

    /**
     * Get slug options.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * Get the route key name.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id')->where('status', 'approved');
    }

    public function relatedPosts(): BelongsToMany
    {
        return $this->belongsToMany(
            Post::class,
            'cafe_blog_related_posts',
            'post_id',
            'related_post_id'
        );
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                     ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByOrigin(Builder $query, string $origin): Builder
    {
        return $query->where('coffee_origin', $origin);
    }

    public function scopeByEra(Builder $query, string $era): Builder
    {
        return $query->where('coffee_era', $era);
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->orderByDesc('views_count');
    }

    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderByDesc('published_at');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getOriginLabelAttribute(): string
    {
        return self::ORIGINS[$this->coffee_origin] ?? ucfirst($this->coffee_origin ?? 'Inconnu');
    }

    public function getEraLabelAttribute(): string
    {
        return self::ERAS[$this->coffee_era] ?? ucfirst($this->coffee_era ?? 'Inconnue');
    }

    public function getFormattedReadingTimeAttribute(): string
    {
        $minutes = $this->reading_time_minutes ?? 0;
        return $minutes <= 1 ? '1 min de lecture' : "{$minutes} min de lecture";
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === self::STATUS_PUBLISHED
            && $this->published_at !== null
            && $this->published_at->isPast();
    }

    // ─── Methods ──────────────────────────────────────────────────────────────

    /**
     * Increment views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Calculate and set reading time based on content.
     */
    public function calculateReadingTime(): void
    {
        $wordCount = str_word_count(strip_tags($this->content ?? ''));
        $this->reading_time_minutes = (int) ceil($wordCount / 200);
    }

    /**
     * Check if the post is new (published within 7 days).
     */
    public function isNew(): bool
    {
        return $this->published_at?->isAfter(now()->subDays(7)) ?? false;
    }
}
