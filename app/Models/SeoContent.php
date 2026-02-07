<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SeoContents
 *
 * @property int $id
 * @property int $category_id
 * @property int $sub_category_id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class SeoContent extends Model
{
    use HasFactory;

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'seo_contents';

    /**
     * Primary key.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Mass assignable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'page_slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'created_at',
        'updated_at'
    ];

    /**
     * Attribute casting.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    

    /**
     * Add or update SEO content for a given page type and data.
     *
     * @param string $pageType
     * @param array $data
     * @return self
     */
    public static function addOrUpdateSeoContent(array $data): self
    {
        $seoContent = self::firstOrNew(['id' => $data['id'] ?? null]);
        $seoContent->page_slug = $data['page_slug'] ?? null;
        $seoContent->meta_title = $data['meta_title'] ?? null;
        $seoContent->meta_description = $data['meta_description'] ?? null;
        $seoContent->meta_keywords = $data['meta_keywords'] ?? null;

        if (!$seoContent->exists) {
            $seoContent->created_at =  now();
        }
        $seoContent->updated_at = now();
        $seoContent->save();
        return $seoContent;
    }
}