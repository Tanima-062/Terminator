<?php

namespace App\Policies;

use App\Models\HierarchyElement;
use App\Models\User;
use App\Traits\HasPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class HierarchyElementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if ($user->type == User::SYSTEM_ADMIN) return true;
        return $user->canAccess('hierarchy-element:view') && $user->type == User::CUSTOMER_COMPANY_ADMIN;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HierarchyElement  $hierarchyElement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, HierarchyElement $hierarchyElement)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HierarchyElement  $hierarchyElement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, HierarchyElement $hierarchyElement)
    {
        return $user->canAccess('hierarchy-element:edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HierarchyElement  $hierarchyElement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, HierarchyElement $hierarchyElement)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HierarchyElement  $hierarchyElement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, HierarchyElement $hierarchyElement)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HierarchyElement  $hierarchyElement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, HierarchyElement $hierarchyElement)
    {
        //
    }
}
