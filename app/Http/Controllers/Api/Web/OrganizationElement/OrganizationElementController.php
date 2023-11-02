<?php

namespace App\Http\Controllers\Api\Web\OrganizationElement;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationElement\StoreRequest;
use App\Http\Requests\OrganizationElement\UpdateRequest;
use App\Http\Resources\OrganizationElement\OrganizationElementOverviewResource;
use App\Models\DeviceAuthAndHierarchyElementRole;
use App\Models\HierarchyElement;
use App\Models\OrganizationElement;
use App\Models\OrganizationElementUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizationElementController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auth_user = auth()->user();


        if(!$auth_user->canAccess('organization-element:view') ) {
            abort(403);
         }


        $select = "organization_elements.id, organization_elements.name, organization_elements.status, organization_elements.type_id, organization_elements.customer_company_id, organization_elements.created_at, organization_elements.updated_at,  customer_companies.name as customer_company_name, hierarchy_elements.name as hierarchy_name";
        $groupBy = "organization_elements.id, organization_elements.name, organization_elements.status, organization_elements.type_id, organization_elements.customer_company_id, organization_elements.created_at, organization_elements.updated_at, customer_company_name, hierarchy_name";

        $organization_elements_query = $this->organizationElementsQuery($request, $select)
            ->orderByColumn($request->order_by, $request->direction)
            ->groupByRaw($groupBy);

        $organization_elements = $organization_elements_query->paginate(request('per_page', 25));
        return OrganizationElementOverviewResource::collection($organization_elements);
    }


    public function getAllOrganizationList(Request $request) {
        $select = "organization_elements.id, organization_elements.name, organization_elements.status, organization_elements.type_id, organization_elements.customer_company_id, organization_elements.created_at, organization_elements.updated_at,  customer_companies.name as customer_company_name, hierarchy_elements.name as hierarchy_name";
        $groupBy = "organization_elements.id, organization_elements.name, organization_elements.status, organization_elements.type_id, organization_elements.customer_company_id, organization_elements.created_at, organization_elements.updated_at, customer_company_name, hierarchy_name";

        $organization_elements_query = $this->organizationElementsQuery($request, $select)
            ->orderByColumn($request->order_by, $request->direction)
            ->groupByRaw($groupBy);

        $organization_elements = $organization_elements_query->get();
        return OrganizationElementOverviewResource::collection($organization_elements);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\OrganizationElement\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $input = $request->safe()->except(['parent_hierarchy', 'responsible_users', 'subordinate_users']);
        $parent_organization_element_ids = collect($request->parent_hierarchy)->pluck('organization_element_id');

        $hierarchy = HierarchyElement::findOrFail($request->type_id);
        $organization_element = OrganizationElement::create($input + ['customer_company_id' => $hierarchy->customer_company_id]);


        $responsible_users = collect($request->responsible_users)->map(fn ($user_id) => ['user_id' => $user_id, 'organization_element_id' => $organization_element->id, 'type' => DeviceAuthAndHierarchyElementRole::RESPONSIBLE_ROLE]);
        $subordinate_users = collect($request->subordinate_users)->map(fn ($user_id) => ['user_id' => $user_id, 'organization_element_id' => $organization_element->id, 'type' => DeviceAuthAndHierarchyElementRole::DIRECT_SUBORDINATE_ROLE]);

        $internal_users = $responsible_users->merge($subordinate_users)->toArray();
        OrganizationElementUser::upsert($internal_users, ['user_id', 'organization_element_id', 'type']);

        $organization_element->parentOrganizationElements()->sync($parent_organization_element_ids, false);
        return $organization_element;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OrganizationElement $organization_element)
    {
        //
        $auth_user = auth()->user();

        if(!$auth_user->canAccess('organization-element:view') || $organization_element->customer_company_id != $auth_user->customer_company_id ) {
           abort(403);
        }

        return $organization_element->load(['hierarchyType', 'parentOrganizationElements.hierarchyType', 'directSubordinateRoleUsers.user.internalUser.companyRole', 'responsibleRoleUsers.user.internalUser.companyRole']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\OrganizationElement\UpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, OrganizationElement $organization_element)
    {
        $input = $request->safe()->except(['parent_hierarchy', 'responsible_users', 'internal_users']);

        $parent_organization_element_ids = collect($request->parent_hierarchy)->pluck('organization_element_id');

        $responsible_users = collect($request->responsible_users)->map(fn ($user_id) => ['user_id' => $user_id, 'organization_element_id' => $organization_element->id, 'type' => DeviceAuthAndHierarchyElementRole::RESPONSIBLE_ROLE]);
        $subordinate_users = collect($request->subordinate_users)->map(fn ($user_id) => ['user_id' => $user_id, 'organization_element_id' => $organization_element->id, 'type' => DeviceAuthAndHierarchyElementRole::DIRECT_SUBORDINATE_ROLE]);

        $internal_users = $responsible_users->merge($subordinate_users)->toArray();

        OrganizationElementUser::where('organization_element_id', $organization_element->id)->delete();
        OrganizationElementUser::upsert($internal_users, ['user_id', 'organization_element_id', 'type']);

        $organization_element->parentOrganizationElements()->sync($parent_organization_element_ids);
        $organization_element->update($input);
        return $organization_element->fresh();
    }

    /**
     * Update resource status
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\OrganizationElement $organizational_element
     *
     * @return \Illuminate\Http\Response
     */

    public function updateStatus(Request $request, OrganizationElement $organization_element)
    {
        $request->validate([
            'status' => 'in:' . OrganizationElement::STATUS_ACTIVE . ',' . OrganizationElement::STATUS_INACTIVE,
        ]);

        if ($request->status == OrganizationElement::STATUS_INACTIVE) {
            $organization_element->internalUsers()->delete();
        }
        $organization_element->load(['hierarchyType']);
        $organization_element->update(['status' => $request->status]);
        return $organization_element;
    }

    /**
     * Get Filter data
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */

    public function getFilterData(Request $request)
    {
        $query = $this->organizationElementsQuery($request);

        if ($request->column == 'customer_companies') {
            return $query->select('customer_companies.id as value', 'customer_companies.name as label')
                ->orderBy('label', "ASC")
                ->distinct()
                ->paginate(10);
        }

        if ($request->column == 'hierarchy_type') {
            return $query->select('hierarchy_elements.name as label', 'organization_elements.type_id as value')
                ->distinct()
                ->orderBy('label', "ASC")
                ->paginate(10);
        }

        if ($request->column == 'responsible_users') {
            return $query->select('responsible_users.full_name as label', 'responsible_users.id as value')->orderBy('label', 'ASC')->whereNotNull('responsible_users.id')
                ->distinct()
                ->orderBy('label', "ASC")
                ->paginate(10);
        }

        if ($request->column == 'status') {
            return $query->select('organization_elements.status as label', 'organization_elements.status as value')
                ->distinct()
                ->orderBy('organization_elements.status', 'ASC')
                ->paginate(10);
        }
        return [];
    }

    private function organizationElementsQuery(Request $request, $select = ""): Builder
    {
        return OrganizationElement::query()
            ->with(['hierarchyType.customerCompany', 'responsibleRoleUsers.user.internalUser'])
            ->filterByUserPermission()
            ->overviewPageJoins()
            ->overviewSearch()
            ->filterByBetweenCreatedAt()
            ->filterByStatus()
            ->filterByCustomerCompany()
            ->filterByHierarchyType()
            ->filterByResponsibleUser()
            ->selectRaw($select);
    }

    public function organizationElementsByHierarchyId(Request $request, $hierarchy_id)
    {
        $company_id = $request->user('sanctum')->customerCompanyAdmin->customer_company_id;

        return OrganizationElement::withWhereHas('parentOrganizationElements', function ($query) {
                $query->where('status',OrganizationElement::STATUS_ACTIVE);
            })
            ->where([
                ['status', '=', OrganizationElement::STATUS_ACTIVE],
                ['customer_company_id', '=', $company_id],
                ['type_id', '=', $hierarchy_id]
            ])->get();
    }
}
