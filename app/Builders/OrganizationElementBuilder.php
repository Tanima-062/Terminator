<?php

namespace App\Builders;

use App\Models\DeviceAuthAndHierarchyElementRole;
use App\Models\OrganizationElement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OrganizationElementBuilder extends Builder
{

    /**
     * Joins for overview page
     *
     * @return self
     */

    public function overviewPageJoins()
    {
        $tableName = (new OrganizationElement())->getTable();
        return $this
            ->join('hierarchy_elements', 'organization_elements.type_id', '=', 'hierarchy_elements.id')
            ->join('customer_companies', 'organization_elements.customer_company_id', '=', 'customer_companies.id')
            ->leftJoin('organization_element_users as responsible_organization_users', fn ($join) => $join->on('responsible_organization_users.organization_element_id', '=', "$tableName.id")->where('responsible_organization_users.type', DeviceAuthAndHierarchyElementRole::RESPONSIBLE_ROLE))
            ->leftJoin('users as responsible_users', fn ($join) => $join->on('responsible_users.id', '=', "responsible_organization_users.user_id"));
    }

    /**
     * Order By columns
     *
     * @return self
     */
    public function orderByColumn($column, $direction): self
    {
        $column = $column ?: 'created_at';
        $direction = $direction ?: 'desc';
        if (strtolower($direction) !== 'asc' && strtolower($direction) !== 'desc') {
            return $this->orderByColumn($column, $direction);
        }

        if ($column == 'responsible_user') {
            return $this->orderByRaw("MIN(responsible_users.full_name) $direction");
        }
        return $this->orderBy($column, $direction);
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
                return $this->where("customer_companies.id", $user->customerCompanyAdmin->customer_company_id);
                break;


            default:
                return $this;
                break;
        }
    }


    /**
     * Filter by status
     *
     * @return self
     */
    public function filterByStatus(): self
    {
        $tableName = (new OrganizationElement())->getTable();

        if (!request('status')) return $this;

        $statusArray = explode(',', request('status'));
        return  $this->whereIn("$tableName.status", $statusArray);
    }

    /**
     * Filter by Customer Company
     *
     * @return self
     */
    public function filterByCustomerCompany(): self
    {
        if (!request('customer_companies')) return $this;

        $customerCompanies = explode(',', request('customer_companies'));
        return  $this->whereIn("customer_companies.id", $customerCompanies);
    }

    /**
     * Filter by Hierarchy Type
     *
     * @return self
     */
    public function filterByHierarchyType(): self
    {
        if (!request('hierarchy_type')) return $this;

        $hierarchyTypes = explode(',', request('hierarchy_type'));
        return  $this->whereIn("organization_elements.type_id", $hierarchyTypes);
    }

    /**
     * Filter by Hierarchy Type
     *
     * @return self
     */
    public function filterByResponsibleUser(): self
    {
        if (!request('responsible_users')) return $this;

        $hierarchyTypes = explode(',', request('responsible_users'));
        return  $this->whereIn("responsible_users.id", $hierarchyTypes);
    }


    /**
     * Filter between created_at
     *
     * @return self
     */
    public function filterByBetweenCreatedAt(): self
    {
        $tableName = (new OrganizationElement())->getTable();
        $start_date = request('start_date');
        $end_date = request('end_date');

        if ($start_date &&  $end_date) {
            $start_date = Carbon::parse(request('start_date'))->startOfDay();
            $end_date = Carbon::parse(request('end_date'))->endOfDay();

            return $this->where("$tableName.created_at", '>=', $start_date)
                ->where("$tableName.created_at", '<=', $end_date);
        }
        return $this;
    }



    /**
     * Search by prefix_id, name, city, contact person first_name, last_name, email, phone number
     *
     * @return self
     */
    public function overviewSearch(): self
    {
        $tableName = (new OrganizationElement())->getTable();
        $user_type = Auth::user()->type;

        if (!request('search')) return $this;

        return $this->where(function ($q) use ($tableName, $user_type) {
            $search = request('search');

            $q
                ->where("$tableName.prefix_id", 'LIKE', "$search%")
                ->orWhere("$tableName.name", 'LIKE', "$search%")
                ->orWhere('responsible_users.first_name', "LIKE", "$search%")
                ->orWhere('responsible_users.last_name', "LIKE", "$search%")
                ->orWhere('responsible_users.full_name', "LIKE", "$search%")
                ->when($user_type == User::SYSTEM_ADMIN, fn ($q) => $q->orWhere("customer_companies.name", "LIKE", "$search%"));
        });
    }
}
