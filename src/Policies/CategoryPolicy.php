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
     *
     * @param  \Azuriom\Models\User|null  $user
     * @param  \Azuriom\Plugin\Wiki\Models\Category  $category
     * @return mixed
     */
    public function view(?User $user, Category $category)
    {
        if ($category->roles === null) {
            return true;
        }

        return $user !== null && $category->hasRole($user->role);
    }
}
