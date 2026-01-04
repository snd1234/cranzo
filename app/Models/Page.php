<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Page extends Model
{
    use HasFactory, SoftDeletes;

    // Status constants
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_ARCHIVED = 'archived';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'page_category_id','meta_title','meta_description', 'meta_keywords', 'description', 'body', 'status', 'published_at', 'created_by',  'updated_by' , 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime',
        'meta' => 'array',
        'is_featured' => 'boolean',
    ];

    /**
     * Use slug for route model binding.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Boot the model and ensure slug generation.
     */
    protected static function booted()
    {
        static::creating(function (Content $model) {
            if (empty($model->slug) && ! empty($model->title)) {
                $model->slug = static::generateUniqueSlug($model->title);
            }
        });

        static::updating(function (Content $model) {
            // Regenerate slug if title changed and slug is empty or equals old slug
            if ($model->isDirty('title') && (empty($model->slug) || $model->getOriginal('title') === $model->slug)) {
                $model->slug = static::generateUniqueSlug($model->title, $model->id);
            }
        });
    }

    /**
     * Generate a unique slug for the given title.
     *
     * @param string $title
     * @param int|null $ignoreId
     * @return string
     */
    protected static function generateUniqueSlug(string $title, int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'content';
        $slug = $base;
        $i = 1;

        while (static::query()
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    /**
     * Relationships
     */
    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'author_id');
    }

    public function categories()
    {
        return $this->belongsToMany(\App\Models\Category::class, 'content_category', 'content_id', 'category_id');
    }

    public function tags()
    {
        return $this->morphToMany(\App\Models\Tag::class, 'taggable');
    }

    public function comments()
    {
        return $this->morphMany(\App\Models\Comment::class, 'commentable');
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED)
                     ->where('published_at', '>', now());
    }

    /**
     * Helpers
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED
            && $this->published_at !== null
            && $this->published_at->lte(now());
    }
}