<?php

namespace Azuriom\Plugin\Wiki\Models;

use Azuriom\Models\Traits\Attachable;
use Azuriom\Models\Traits\HasTablePrefix;
use Azuriom\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $content
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Azuriom\Plugin\Wiki\Models\Category $category
 */
class Page extends Model
{
    use Attachable;
    use HasTablePrefix;
    use Searchable;

    /**
     * The table prefix associated with the model.
     */
    protected string $prefix = 'wiki_';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'slug', 'content', 'category_id',
    ];

    /**
     * The attributes that can be used for search.
     *
     * @var array<int, string>
     */
    protected array $searchable = [
        'title', 'content',
    ];

    /**
     * Get the category of this page.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
