<?php

namespace App\Http\Controllers\Api\Web\CustomerCompany;

use Illuminate\Http\Request;
use App\Models\CustomerCompany;
use Illuminate\Support\Facades\DB;
use App\Models\HierarchyElement;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\CustomerCompany\StoreRequest;
use App\Http\Requests\CustomerCompany\UpdateRequest;
use App\Models\DeviceAuthAndHierarchyElementRole;
use App\Http\Resources\CustomerCompany\CustomerCompanyResource;
use App\Models\User;
use App\Models\CompanyRole;

class CustomerCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth_user = auth()->user();
        if(!$auth_user->canAccess('customer-companye:viewAny')) {
           abort(403);
        }

        $customer_companeis = $this->getCustomerCompanyQuery()
            ->orderByColumn()
            ->paginate(request('per_page', 25));

        return CustomerCompanyResource::collection($customer_companeis);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $auth_user = auth()->user();
        if(!$auth_user->canAccess('customer-companye:edit')) {
           abort(403);
        }

        $input = $request->safe()->except(['affected_user_roles', 'hierarchy_elements']);
        $customer_company = CustomerCompany::create($input);
        if ($input['device_authentication_required']) {
            $device_auth_required_user_roles = collect($request->affected_user_roles)->map(fn ($role) => new DeviceAuthAndHierarchyElementRole(['company_role_id' => $role]));
            $customer_company->deviceAuthRoles()->saveMany($device_auth_required_user_roles);
        }
        if ($input['hierarchy_elements_required']) {
            collect($request->hierarchy_elements)->each(function ($element) use ($customer_company) {
                $hierarchyElement = $customer_company->hierarchyElements()->save(new HierarchyElement(['name' => $element['name'], 'hierarchy_level' => $element['hierarchy_level'], 'updated_at' => null]));
                $directSubordinateRoles = collect($element['direct_subordinated_role'])->map(fn ($role) => new DeviceAuthAndHierarchyElementRole(['company_role_id' => $role, 'role_type' => DeviceAuthAndHierarchyElementRole::DIRECT_SUBORDINATE_ROLE]));
                $responsibleRoles = collect($element['responsible_role'])->map(fn ($role) => new DeviceAuthAndHierarchyElementRole(['company_role_id' => $role, 'role_type' => DeviceAuthAndHierarchyElementRole::RESPONSIBLE_ROLE]));

                $hierarchyElement->roles()->saveMany($directSubordinateRoles->merge($responsibleRoles));
            });
        }

        return $customer_company->load(['hierarchyElements', 'deviceAuthRoles']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerCompany $customer_company)
    {
        $auth_user = auth()->user();
        if(!$auth_user->canAccess('customer-companye:view')) {
           abort(403);
        }

        $customer_company->load(['hierarchyElements' => ['responsibleRoles.companyRole', 'directSubordinateRoles.companyRole']]);

        $customer_company->hierarchy_elements = $customer_company->hierarchyElements->map(function ($element) {
            $element->responsible_role = $element->responsibleRoles->map(fn ($responsibleRole) => $responsibleRole->company_role_id);
            $element->direct_subordinated_role = $element->directSubordinateRoles->map(fn ($directSubordinateRole) => $directSubordinateRole->company_role_id);
            return $element->only('id', 'name', 'hierarchy_level', 'responsible_role', 'direct_subordinated_role', 'status', 'created_at', 'updated_at');
        });
        $customer_company->affected_user_roles = $customer_company->deviceAuthRoles->map(fn ($deviceUserRole) => $deviceUserRole->company_role_id);
        $customer_company->user_roles = implode(', ',CompanyRole::whereIn('id',$customer_company->affected_user_roles)->pluck('name')->toArray());
        return response()->json($customer_company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, CustomerCompany $customer_company)
    {
        $auth_user = auth()->user();
        if(!$auth_user->canAccess('customer-companye:edit')) {
           abort(403);
        }

        $input = $request->safe()->except(['affected_user_roles', 'hierarchy_elements']);
        $customer_company->update($input);

        if ($input['device_authentication_required']) {
            $device_auth_required_user_roles = collect($request->affected_user_roles)->map(fn ($role) => new DeviceAuthAndHierarchyElementRole(['company_role_id' => $role]));
            $customer_company->deviceAuthRoles()->delete();
            $customer_company->deviceAuthRoles()->saveMany($device_auth_required_user_roles);
        }

        if ($input['hierarchy_elements_required']) {

            collect($request->hierarchy_elements)->each(function ($element) use ($customer_company) {
                if (isset($element['id'])) {
                    $hierarchyElement = HierarchyElement::find($element['id']);
                    $hierarchyElement->update($element);
                } else {
                    $hierarchyElement = $customer_company->hierarchyElements()->save(new HierarchyElement(['name' => $element['name'], 'hierarchy_level' => $element['hierarchy_level'], 'updated_at' => null]));
                }

                $hierarchyElement->roles()->delete();
                $directSubordinateRoles = collect($element['direct_subordinated_role'])->map(fn ($role) => new DeviceAuthAndHierarchyElementRole(['company_role_id' => $role, 'role_type' => DeviceAuthAndHierarchyElementRole::DIRECT_SUBORDINATE_ROLE]));
                $responsibleRoles = collect($element['responsible_role'])->map(fn ($role) => new DeviceAuthAndHierarchyElementRole(['company_role_id' => $role, 'role_type' => DeviceAuthAndHierarchyElementRole::RESPONSIBLE_ROLE]));
                $hierarchyElement->roles()->saveMany($directSubordinateRoles->merge($responsibleRoles));
            });
        }

        return $customer_company->load(['hierarchyElements', 'deviceAuthRoles']);
    }

    public function updateStatus(Request $request, CustomerCompany $customer_company)
    {
        $auth_user = auth()->user();
        if(!$auth_user->canAccess('customer-companye:edit')) {
           abort(403);
        }

        $request->validate([
            'status'    =>  ['required', 'in:active,inactive']
        ]);

        $customer_company->update(['status' => request('status')]);

        if ($request->status == CustomerCompany::INACTIVE) {
            $users = DB::table('customer_company_admins')
                ->select('user_id')
                ->where('customer_company_id', $customer_company->id)
                ->pluck('user_id')
                ->toArray();

            User::whereIn('id', $users)
                ->update(['status' => User::INACTIVE]);
        }

        return response(['success' => true], Response::HTTP_ACCEPTED);
    }

    /**
     * Get customer companies overview fitler data
     *
     * @param Request $request
     * @return void
     */
    public function getFilterData(Request $request)
    {
        $query = $this->getCustomerCompanyQuery();

        if (request('column') == 'customer_companies') {

            $data = $query
                ->select('name as label', 'id as value')
                ->orderBy('name', 'ASC')
                ->paginate(10);

            return $data;
        }

        if (request('column') == 'company_locations') {
            $data = $query->select('city as label', 'city as value')
                ->distinct('city')
                ->orderBy('city', 'ASC')
                ->paginate(10)
            ;

            return $data;
        }

        if (request('column') == 'status') {
            $data = $query->select('status as label', 'status as value')
                ->distinct('status')
                ->orderBy('status', 'ASC')
                ->paginate(10)
            ;

            return $data;
        }
    }



    /**
     * Generate customer company overview queries
     *
     *
     */
    private function getCustomerCompanyQuery()
    {
        $query = CustomerCompany::query()
            ->when(request('status') && request('except_filter') != 'status', fn ($q) => $q->filterByStatus())
            ->when(request('customer_companies') && request('except_filter') != 'customer_companies', fn ($q) => $q->filterByCompanyName())
            ->when(request('company_locations') && request('except_filter') != 'company_locations', fn ($q) => $q->filterByLocation())
            ->when(request('start_date') && request('end_date'), fn ($q) =>  $q->filterByBetweenCreatedAt())
            ->when(request('search'), fn ($q) => $q->overviewSearch());

        return $query;
    }

    public function getCustomerCompany()
    {
        $customer_companeis  = CustomerCompany::query()->select('name as label', 'id as value', 'country_iso_code')->where(['status' => 'active'])->orderBy('name', 'ASC')->get();
        return $customer_companeis;
    }

    /**
     * Get Hierarchy Levels
     *
     * @param Request $request
     * @return Response
     */

    public function getHierarchyLevels(CustomerCompany $customer_company)
    {
        $customer_company->load(['hierarchyElements' => fn ($q) => $q->orderBy('hierarchy_level')]);

        return response()->json($customer_company->hierarchyElements);
    }
}
