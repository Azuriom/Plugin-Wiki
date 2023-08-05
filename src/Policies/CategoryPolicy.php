<?php

namespace Azuriom\Plugin\Wiki\Policies;

use Azuriom\Models\User;
use Azuriom\Plugin\Wiki\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the category.
     */
    public function view(?User $user, Category $category): bool
    {
        if ($category->roles === null) {
            return true;
        }

        return $user !== null && $category->hasRole($user->role);
    }
}
