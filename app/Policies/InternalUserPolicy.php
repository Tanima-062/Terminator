<?php

namespace App\Policies;

use App\Models\CustomerCompany;
use App\Models\InternalUser;
use App\Models\User;
use App\Traits\HasPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class InternalUserPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->canAccess('internal-users.store') && $user->type == User::CUSTOMER_COMPANY_ADMIN && $user->customerCompanyAdmin->customerCompany->status == CustomerCompany::ACTIVE;
    }
}
