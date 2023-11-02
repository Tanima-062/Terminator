<?php

namespace App\Policies;

use App\Models\CustomerCompany;
use App\Models\OrganizationElement;
use App\Models\User;
use App\Traits\HasPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationElementPolicy
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
        return $user->canAccess('organization-element:view') && $user->type == User::CUSTOMER_COMPANY_ADMIN;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OrganizationElement  $organizationElement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, OrganizationElement $organizationElement)
    {
        if ($user->type == User::SYSTEM_ADMIN) return true;
        return $user->canAccess('organization-element:view') && $user->type == User::CUSTOMER_COMPANY_ADMIN && $organizationElement->hierarchyType->customer_company_id == $user->customerCompanyAdmin->customer_company_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->canAccess('organization-element:edit') && $user->type == User::CUSTOMER_COMPANY_ADMIN && $user->customerCompanyAdmin->customerCompany->status == CustomerCompany::ACTIVE;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OrganizationElement  $organizationElement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, OrganizationElement $organizationElement)
    {
        return $user->canAccess('organization-element:edit') && $user->type == User::CUSTOMER_COMPANY_ADMIN && $organizationElement->hierarchyType->customer_company_id == $user->customerCompanyAdmin->customer_company_id && $user->customerCompanyAdmin->customerCompany->status == CustomerCompany::ACTIVE;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OrganizationElement  $organizationElement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, OrganizationElement $organizationElement)
    {
        return $user->canAccess('organization-element:view') && $user->type == User::CUSTOMER_COMPANY_ADMIN && $organizationElement->hierarchyType->customer_company_id == $user->customerCompanyAdmin->customer_company_id && $user->customerCompanyAdmin->customerCompany->status == CustomerCompany::ACTIVE;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OrganizationElement  $organizationElement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, OrganizationElement $organizationElement)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OrganizationElement  $organizationElement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, OrganizationElement $organizationElement)
    {
        //
    }
}
