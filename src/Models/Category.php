<?php

namespace Azuriom\Plugin\Wiki\Models;

use Azuriom\Models\Role;
use Azuriom\Models\Traits\HasTablePrefix;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $icon
 * @property string $name
 * @property string $slug
 * @property int $position
 * @property int|null $parent_id
 * @property array|null $roles
 * @property bool $is_enabled
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Azuriom\Plugin\Shop\Models\Category $parent
 * @property \Illuminate\Support\Collection|\Azuriom\Plugin\Shop\Models\Category[] $categories
 * @property \Illuminate\Support\Collection|\Azuriom\Plugin\Wiki\Models\Page[] $pages
 *
 * @method static \Illuminate\Database\Eloquent\Builder enabled()
 * @method static \Illuminate\Database\Eloquent\Builder parents()
 */
class Category extends Model
{
    use HasTablePrefix;

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
        'icon', 'name', 'slug', 'roles', 'position', 'parent_id', 'is_enabled',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'roles' => 'array',
        'is_enabled' => 'boolean',
    ];

    /**
     * Get the parent category of this category.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id')->orderBy('position');
    }

    /**
     * Get the subcategories in this category.
     */
    public function categories()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('position');
    }

    /**
     * Get the pages in this category.
     */
    public function pages()
    {
        return $this->hasMany(Page::class)->orderBy('position');
    }

    public function hasRole(Role $role): bool
    {
        return in_array($role->id, $this->roles, true);
    }

    public function setRolesAttribute(?array $roles): void
    {
        $ids = $roles === null ? $roles : array_map(fn ($val) => (int) $val, $roles);

        $this->attributes['roles'] = json_encode($ids);
    }

    /**
     * Scope a query to only include enabled categories.
     */
    public function scopeEnabled(Builder $query): void
    {
        $query->where('is_enabled', true);
    }

    /**
     * Scope a query to only include parents categories.
     */
    public function scopeParents(Builder $query): void
    {
        $query->whereNull('parent_id')->orderBy('position');
    }
}
