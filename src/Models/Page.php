<?php

namespace Azuriom\Plugin\Wiki\Models;

use Azuriom\Models\Traits\Attachable;
use Azuriom\Models\Traits\HasTablePrefix;
use Azuriom\Models\Traits\Searchable;
use DOMDocument;
use DOMXPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    /**
     * Extract a plain text preview from the page HTML content.
     */
    public function getPreviewTextAttribute(): string
    {
        return $this->extractPreviewText($this->content, 300);
    }

    private function extractPreviewText(?string $html, int $limit = 300): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        $dom = new DOMDocument();

        libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="utf-8" ?><div>'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        foreach ($xpath->query('//style | //script') as $node) {
            if ($node->parentNode !== null) {
                $node->parentNode->removeChild($node);
            }
        }

        $container = $dom->getElementsByTagName('div')->item(0);
        $text = $container?->textContent ?? $dom->textContent ?? '';
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? '');

        return Str::limit($text, $limit);
    }
}
