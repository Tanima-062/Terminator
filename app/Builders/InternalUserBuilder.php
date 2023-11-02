<?php

namespace App\Builders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InternalUserBuilder extends Builder
{


    /**
     * Order By columns
     *
     * @return self
     */
    public function orderByColumn(): self
    {
        if (request('order_by') == 'full_name') {
            return $this->orderBy('users.full_name', request('direction', 'DESC'));
        }
        if (request('order_by') == 'status') {
            return $this->orderBy('users.status', request('direction', 'DESC'));
        }
        if (request('order_by') == 'roles') {
            return $this->orderBy('company_roles.name', request('direction', 'DESC'));
        }
        if (request('order_by') == 'campaign') {
            return $this->orderBy('campaigns.name', request('direction', 'DESC'));
        }
        if (request('order_by') == 'created_at') {
            return $this->orderBy('internal_users.created_at', request('direction', 'DESC'));
        }
        if(request('order_by') == 'correspondence_language_code'){
            $query = getOrderByLanguageNameByUser(request('direction', 'asc'));

            return $this->orderBy(DB::raw($query));
        }

        return $this->orderBy('internal_users.id', 'DESC');
    }


    /**
     * Filter by company roles id
     *
     * @return self
     */
    public function filterByCompanyRoles(): self
    {
        $companyRoleArray = explode(',', request('roles_id'));

        return  $this->whereIn('company_roles.id', $companyRoleArray);
    }

    /**
     * Filter by status
     *
     * @return self
     */
    public function filterByStatus(): self
    {
        $statusArray = explode(',', request('status'));

        return  $this->whereIn('users.status', $statusArray);
    }
    /**
     * Filter by competence status
     *
     * @return self
     */
    public function filterByCompetenceStatus(): self
    {
        $competencesStatusArray = explode(',', request('competence_status'));

        return  $this->whereIn('competences.status', $competencesStatusArray);
    }
    /**
     * active or inactive users list
     *
     * @return self
     */
    public function active_inactive(): self
    {
        return  $this->whereIn('users.status', ['active', 'inactive']);
    }
    /**
     * Filter by competence level
     *
     * @return self
     */
    public function filterByCompetenceLevel(): self
    {
        $competencesLevelArray = explode(',', request('level'));

        return  $this->whereIn('competences.level', $competencesLevelArray);
    }

    /**
     * Filter by other competence
     *
     * @return self
     */
    public function filterByOtherCompetence(): self
    {
        $otherCompetenceArray = explode(',', request('other_competence'));

        return  $this->whereIn('competences.other_competence', $otherCompetenceArray);
    }



    /**
     * Filter by company name
     *
     * @return self
     */
    public function filterByCompanyName(): self
    {
        $customer_companies = explode(',', request('customer_companies'));

        return $this->whereIn('id', $customer_companies);
    }


    /**
     * Filter by company location/city
     *
     * @return self
     */
    public function filterByLocation(): self
    {
        $company_locations = explode(',', request('company_locations'));

        return $this->whereIn('city', $company_locations);
    }
    /**
     * Filter by  alignment
     *
     * @return self
     */
    public function filterByAlignment(): self
    {
        $alignment_id = explode(',', request('alignment_id'));

        return $this->whereIn('alignment_id', $alignment_id);
    }
    /**
     * Filter by  organization element
     *
     * @return self
     */
    public function filterByOrganizationElement(): self
    {
        $organization_element_ids = explode(',', request('organization_elements'));

        return $this->whereIn('organization_element_users.organization_element_id', $organization_element_ids);
    }
    /**
     * Filter by  organization element and cusotm organization element of none hierarchy
     *
     * @return self
     */
    public function filterByOrganizationElementHierarchyNone(): self
    {
        $organization_element_ids = explode(',', request('organization_elements_all'));

        return $this->whereIn('custom_organization_element_users.organization_element_id', $organization_element_ids);
    }
    /**
     * Filter by  organization element
     *
     * @return self
     */
    public function filterByOrganizationElementNone(): self
    {
        $organization_element_ids = explode(',', request('hierarchy_none_org'));

        return $this->whereIn('custom_organization_element_users.organization_element_id', $organization_element_ids);
    }
    /**
     * Filter by company location/city
     *
     * @return self
     */
    public function filterByLangCode(): self
    {
        $competence_lang_code = explode(',', request('lang_code'));

        return $this->whereIn('lang_code', $competence_lang_code);
    }



    /**
     * Filter between created_at
     *
     * @return self
     */
    public function filterByBetweenCreatedAt(): self
    {
        $start_date = Carbon::parse(request('start_date'))->startOfDay();
        $end_date = Carbon::parse(request('end_date'))->endOfDay();

        return $this->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date);
    }

    /**
     * Filter based on user permission and type
     *
     * @return self
     */

    public function filterByUserPermission()
    {
        $user = Auth::user();

        switch ($user->type) {
            case User::CUSTOMER_COMPANY_ADMIN:
                return $this->where("users.customer_company_id", $user->customer_company_id)->where('internal_users.customer_company_id', $user->customer_company_id);
                break;


            default:
                return $this;
                break;
        }
    }

    /**
     * Search by prefix_id, name, city, contact person first_name, last_name, email, phone number
     *
     * @return self
     */
    public function overviewSearch(): self
    {
        return $this->where(function ($q) {
            $search = request('search');

            $q->Where('internal_users.full_phone_number', 'LIKE', "$search%")
                ->orWhere('users.first_name', 'LIKE', "$search%")
                ->orWhere('users.last_name', 'LIKE', "$search%")
                ->orWhere('users.full_name', 'LIKE', "$search%")
                ->orWhere('users.email', 'LIKE', "$search%");
        });
    }
    public function name(): self
    {
        return $this->where(function ($q) {
            $search = request('first_last_name');

            $q->orWhere('users.first_name', 'LIKE', "$search%")
                ->orWhere('users.last_name', 'LIKE', "$search%")
                ->orWhere('users.full_name', 'LIKE', "$search%");
        });
    }
}
