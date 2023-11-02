<?php

namespace App\Http\Controllers\Api\Web\OrganizationElement;

use App\Http\Controllers\Controller;
use App\Http\Requests\HierarchyElement\StoreRequest;
use App\Http\Requests\HierarchyElement\UpdateRequest;
use App\Models\HierarchyElement;
use App\Models\DeviceAuthAndHierarchyElementRole;
use App\Models\OrganizationElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HierarchyElementController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(HierarchyElement::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return HierarchyElement::onlyAuthorized()->with(['organizationElements' => fn ($q) => $q->where('status', OrganizationElement::STATUS_ACTIVE), 'responsibleRoles', 'directSubordinateRoles'])->statusIn()->get();
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HierarchyElement  $hierarchy_element
     * @return \Illuminate\Http\Response
     */
    public function show(HierarchyElement $hierarchy_element)
    {
        return $hierarchy_element;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\HierarchyElement\UpdateRequest  $request
     * @param  \App\Models\HierarchyElement  $hierarchy_element
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, HierarchyElement $hierarchy_element)
    {
        $hierarchy_element->update($request->validated() + ['updated_at' => now()]);
        $hierarchy_element->roles()->delete();
        $responsible_roles = collect($request->responsible_role)->map(fn ($role) => new DeviceAuthAndHierarchyElementRole(['company_role_id' => $role, 'role_type' => DeviceAuthAndHierarchyElementRole::RESPONSIBLE_ROLE]));
        $direct_subordinate_roles = collect($request->direct_subordinated_role)->map(fn ($role) => new DeviceAuthAndHierarchyElementRole(['company_role_id' => $role, 'role_type' => DeviceAuthAndHierarchyElementRole::DIRECT_SUBORDINATE_ROLE]));
        $hierarchy_element->roles()->saveMany($responsible_roles->merge($direct_subordinate_roles));
        return $hierarchy_element->fresh();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HierarchyElement  $hierarchy_element
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, HierarchyElement $hierarchy_element)
    {
        $request->validate(['status' => 'in:' . HierarchyElement::STATUS_ACTIVE . ',' . HierarchyElement::STATUS_INACTIVE]);
        $hierarchy_element->update(['status' => $request->status]);
        return $hierarchy_element;
    }

    /**
     * Get None Lavel Hierarchy with organization element
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getHierarchyNone(Request $request)
    {
        $company_id = $request->user('sanctum')->customerCompanyAdmin->customer_company_id;
        $hierarchyElement = HierarchyElement::withWhereHas('organizationElements', function ($query) {
            $query->where('status', '=', OrganizationElement::STATUS_ACTIVE);
        })->where([
            ['status', '=', HierarchyElement::STATUS_ACTIVE],
            ['customer_company_id', '=', $company_id]
        ])->whereNull('hierarchy_level')
            ->orderBy('name', 'ASC')->get();
        return $hierarchyElement;
    }
    /**
     * Get Lavel Hierarchy with organization element
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getHierarchy(Request $request)
    {
        $company_id = $request->user('sanctum')->customerCompanyAdmin->customer_company_id;
        $hierarchyElement = HierarchyElement::withWhereHas('organizationElements', function ($query) {
            $query->where('status', '=', OrganizationElement::STATUS_ACTIVE);
        })->withWhereHas('organizationElements.internalUsers', function ($query) {
            // $query->groupBy('organization_element_users.organization_element_id')->select('organization_element_users.organization_element_id');
        })->where([
            ['status', '=', HierarchyElement::STATUS_ACTIVE],
            ['customer_company_id', '=', $company_id],
            ['hierarchy_level', '<>', '']
        ])

        ->orderBy('hierarchy_level', 'DESC')->get();
        return $hierarchyElement;
    }
    /**
     * Get Lavel Hierarchy with organization element
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getHierarchyNoneInternal(Request $request)
    {
        $company_id = $request->user('sanctum')->customerCompanyAdmin->customer_company_id;
        $hierarchyElement = HierarchyElement::withWhereHas('organizationElements', function ($query) {
            $query->where('status', '=', OrganizationElement::STATUS_ACTIVE);
        })->withWhereHas('organizationElements.internalUsers', function ($query) {
        })->where([
            ['status', '=', HierarchyElement::STATUS_ACTIVE],
            ['customer_company_id', '=', $company_id],
        ])->whereNull('hierarchy_level')

        ->orderBy('name', 'ASC')->get();
        return $hierarchyElement;
    }
}
