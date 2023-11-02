<?php

namespace App\Http\Controllers\Api\Web\InternalUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\InternalUser\StoreRequest;
use App\Http\Resources\InternalUser\InternaluserResource;
use App\Models\AlignmentUser;
use App\Models\CampaignInternal;
use App\Models\DeviceAuthAndHierarchyElementRole;
use App\Models\HierarchyElement;
use App\Models\User;
use App\Models\CompanyRole;
use App\Models\Competence;
use App\Models\CompetenceLog;
use Illuminate\Http\Request;
use App\Models\OrganizationElement;
use App\Models\OrganizationElementUser;
use Faker\Provider\ar_EG\Internet;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Alignment;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CustomerCompanyAdmin\AcceptInvitaton;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Mail\InternalUser\Invitation;
use App\Models\CustomOrganizationElementUser;
use App\Models\InternalUser;
use App\Http\Requests\InternalUser\UpdateRequest;
use App\Http\Requests\InternalUser\StatusChangeRequest;
use App\Mail\InternalUser\Verification;

class InternalUserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer_companeis = $this->getInternalUserQuery()
            ->with(['user:id,first_name,last_name,full_name,email,send_mail,status', 'companyRole:id,name', 'user.campaignInternal.campaign'])
            ->select('internal_users.*')
            ->orderByColumn()
            ->distinct('internal_users.id')
            ->paginate(request('per_page', 25));

        return InternaluserResource::collection($customer_companeis);
    }

    /**
     * Generate internal user queries
     *
     *
     */
    private function getInternalUserQuery($status_active_inactive = false)
    {

        // SELECT DISTINCT i.id as internal_id, i.*,u.status FROM internal_users as i JOIN users as u JOIN company_roles as c ON i.user_id = u.id AND c.id = i.roles_id AND u.status = 'Active' OR u.status = 'Inactive' AND i.customer_company_id =1;

        $query = InternalUser::query()
            ->join('users', 'users.id', '=', 'internal_users.user_id')
            ->filterByUserPermission()
            ->join('company_roles', 'company_roles.id', '=', 'internal_users.roles_id')
            ->leftJoin('campaign_internals', 'campaign_internals.user_id', '=', 'internal_users.user_id')
            ->leftJoin('competences', 'competences.user_id', '=', 'internal_users.user_id')
            ->leftJoin('campaigns', 'campaign_internals.campaign_id', '=', 'campaigns.id')
            ->leftJoin('organization_element_users', 'organization_element_users.user_id', '=', 'users.id')
            ->leftJoin('custom_organization_element_users', 'custom_organization_element_users.user_id', '=', 'users.id')
            ->leftJoin('alignment_users', 'alignment_users.user_id', '=', 'users.id')
            ->when(request('status') && request('except_filter') != 'status', fn ($q) => $q->filterByStatus())
            ->when(request('other_competence') && request('except_filter') != 'other_competence', fn ($q) => $q->filterByOtherCompetence())
            ->when(request('competence_status') && request('except_filter') != 'competence_status', fn ($q) => $q->filterByCompetenceStatus())
            ->when(request('level') && request('except_filter') != 'level', fn ($q) => $q->filterByCompetenceLevel())
            ->when(request('roles_id') && request('except_filter') != 'roles_id', fn ($q) => $q->filterByCompanyRoles())
            ->when(request('lang_code') && request('except_filter') != 'lang_code', fn ($q) => $q->filterByLangCode())
            ->when(request('alignment_id') && request('except_filter') != 'alignment_id', fn ($q) => $q->filterByAlignment())
            ->when(request('organization_elements') && request('except_filter') != 'organization_elements', fn ($q) => $q->filterByOrganizationElement())
            ->when(request('organization_elements_all') && request('except_filter') != 'organization_elements_all', fn ($q) => $q->filterByOrganizationElement())
            ->when(request('organization_elements_all') && request('except_filter') != 'organization_elements_all', fn ($q) => $q->filterByOrganizationElementHierarchyNone())
            ->when(request('hierarchy_none_org') && request('except_filter') != 'hierarchy_none_org', fn ($q) => $q->filterByOrganizationElementNone())
            ->when(request('search'), fn ($q) => $q->overviewSearch())
            ->when(request('first_last_name'), fn ($q) => $q->name())
            ->when($status_active_inactive, fn ($q) => $q->active_inactive());


        return $query;
    }

    /**
     * Only get active and inactive users
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getInterUserActiveInactive()
    {
        $customer_companeis = $this->getInternalUserQuery($status_active_inactive = true)
            ->with(['user:id,first_name,last_name,full_name,email,send_mail,status', 'companyRole:id,name', 'user.campaignInternal.campaign'])
            ->select('internal_users.*')
            ->orderByColumn()
            ->distinct('internal_users.id')->get();
            // ->paginate(request('per_page', 25));

        return InternaluserResource::collection($customer_companeis);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $request->merge(['user_id' => auth()->user()->id, 'customer_company_id' => auth()->user()->customerCompanyAdmin->customer_company_id]);
        $token = md5(uniqid() . rand(1000, 9000));
        // Transaction
        DB::transaction(
            function () use ($request, $token) {
                $now = now();
                $user = new User();
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->full_name = $request->first_name . ' ' . $request->last_name;
                $user->language_id = $request->language_id;
                $user->customer_company_id = $request->customer_company_id;
                $user->email = $request->email;
                $user->type = 'internal_user';
                $user->password = '';
                $user->status = $request->send_mail ? User::PENDING : User::NEW;
                $user->send_mail = $request->send_mail;
                $user->verification_token = $request->send_mail ? $token : null;
                $user->password = '';
                $user->save();


                $user->internalUser()->create($request->only('customer_company_id', 'roles_id', 'salutation', 'correspondence_language_code') + [
                    'access_right' => $request->access_right ?? null,
                    'phone_iso_code' =>  $request->phone_iso_code ?? null,
                    'phone_number' =>  $request->phone_number ?? null,
                    'full_phone_number' =>  $request->phone_number && $request->phone_iso_code ? PhoneNumber::make($request->phone_number, $request->phone_iso_code)->formatE164() : null,
                ]);

                if ($request->send_mail)
                    $this->sendEmail($user);


                if ($request->hirarchy_with_elements) {
                    $this->hirarchyWithElementsUserStore($request->hirarchy_with_elements, $user);
                }

                if ($request->none_hirarchy) {
                    $this->noneHirarchyWithElemenUserStore($request->none_hirarchy,  $user);
                }

                if ($request->custom_hirarchy_with_elements) {

                    $this->customOrganizationElementUserStore($request->custom_hirarchy_with_elements, $user);
                }

                if ($request->custom_none_hirarchy) {

                    $this->customOrganizationElementUserStore($request->custom_none_hirarchy, $user);
                }

                if ($request->campaign_id) {
                    $this->campaignInternalStore($request->campaign_id, $user);
                }

                if ($request->alligment && ($request->roles_id == 3 || $request->roles_id == 4)) {
                    $this->alignmentStore($request->alligment, $user);
                }

                if (($request->roles_id == 3 || $request->roles_id == 4) && $request->competence) {
                    $competenceCollection = collect($request->competence)->each(function ($item, int $key) use ($user, $request) {
                        $competence = Competence::create([
                            'user_id' => $user->id,
                            'type' => $item["type"],
                            'lang_code' => $item["lang_code"],
                            'other_competence' => $item["other_competence"],
                            'level' => $item["level"],
                            'status' => $item["status"],
                        ]);
                        $competence->competenceLog()->create([
                            'created_by' => $request->user_id,
                            'status' => $item["status"],
                        ]);
                    });
                }
            }
        );
    }

    //send invitation email
    public function sendInvitationEmail(Request $request, User $user)
    {
        $user->update(['verification_token' => md5(uniqid() . rand(1000, 9000))]);
        $user->load('language');
        $this->sendEmail($user);

        return response()->json(
            ['message' =>   __(
                'Email has been sent to :name .',
                ['name' => $user->name]

            )],
            Response::HTTP_OK
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(InternalUser $internalUser)
    {
        return $internalUser->load(['companyRole', 'user.organizationElementUser.organizationElement.hierarchyType', 'user.organizationElementUser.organizationElement.parentOrganizationElements.hierarchyType', 'user.customOrganizationElementUser.organizationElement.parentOrganizationElements.hierarchyType', 'user.alignmentUser', 'user.competence', 'user.competence.competenceLog', 'user.competence.competenceLog.user', 'user.competence.competenceLog.user.customerCompanyAdmin', 'user.campaignInternal.campaign', 'user.language', 'user.company.deviceAuthRoles' => function ($query) use ($internalUser) {
            $query->where('company_role_id', $internalUser->roles_id);
        }]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, InternalUser $internal_user)
    {

        // Transaction
        $change_mail = DB::transaction(
            function () use ($request, $internal_user) {
                $change_mail = false;

                $user_data = $request->only('first_name', 'last_name', 'language_id') + [
                    'full_name' => $request->first_name . ' ' . $request->last_name,
                ];

                $user = User::find($request->user_id);
                if ($user->email !== $request->email) {
                    $user_data["status"] = User::EMAIL_VERIFICATION_PENDING;
                    $user_data["email"] = $request->email;
                    $user_data['send_mail'] = true;
                    $user_data["password"] = "";
                    $token = md5(uniqid() . rand(1000, 9000));
                    $user_data["verification_token"] = $token;
                    $user->email = $request->email;
                    $user->verification_token = $token;
                    $change_mail = true;
                }

                User::where('id', $request->user_id)->update($user_data);


                $internal_user_data = $request->only('roles_id', 'salutation', 'correspondence_language_code') + [
                    'access_right' => ($request->roles_id == 1 || $request->roles_id == 2) && $request->access_right ? $request->access_right : null,
                    'phone_iso_code' =>  $request->phone_iso_code ?? null,
                    'phone_number' =>  $request->phone_number ?? null,
                    'full_phone_number' =>  $request->phone_number && $request->phone_iso_code ? PhoneNumber::make($request->phone_number, $request->phone_iso_code)->formatE164() : null,
                ];
                $internal_user->update($internal_user_data);

                OrganizationElementUser::where('user_id', $request->user_id)->delete();
                CustomOrganizationElementUser::where('user_id', $request->user_id)->delete();
                CampaignInternal::where('user_id', $request->user_id)->delete();
                AlignmentUser::where('user_id', $request->user_id)->delete();


                if ($request->hirarchy_with_elements) {

                    $this->hirarchyWithElementsUserStore($request->hirarchy_with_elements, $user);
                }
                if ($request->none_hirarchy) {

                    $this->noneHirarchyWithElemenUserStore($request->none_hirarchy,  $user);
                }

                if ($request->custom_hirarchy_with_elements) {

                    $this->customOrganizationElementUserStore($request->custom_hirarchy_with_elements, $user);
                }

                if ($request->custom_none_hirarchy) {

                    $this->customOrganizationElementUserStore($request->custom_none_hirarchy, $user);
                }

                if ($request->campaign_id) {
                    $this->campaignInternalStore($request->campaign_id, $user);
                }

                if ($request->alligment && ($request->roles_id == 3 || $request->roles_id == 4)) {
                    $this->alignmentStore($request->alligment, $user);
                }
                if (($request->roles_id == 3 || $request->roles_id == 4) && $request->competence) {

                    $this->competenceUpdate($request->competence, $user);
                }

                  if ($change_mail) {
                    $user = User::find($request->user_id);
                        $this->sendEmailVerification($user);
                  }
                    

                return $change_mail;
            }
        );

        return response()->json(
            [
                'name' => $internal_user->user->name,
                'change_mail' => $change_mail
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(StatusChangeRequest $request, InternalUser $internal_users)
    {
        $internal_users->user->update(['status' => request('status')]);
        if (request('status') == User::INACTIVE)
            $internal_users->user->tokens()->delete();

        return response(['success' => true], Response::HTTP_ACCEPTED);
    }

  

    public function internalUsersListByHierarchy(Request $request, HierarchyElement $hierarchy_element)
    {
        $responsible_roles_id = $hierarchy_element->responsibleRoles->pluck('company_role_id')->unique();
        $suboridinate_roles_id = $hierarchy_element->directSubordinateRoles->pluck('company_role_id')->unique();

        $company_id = $request->user('sanctum')->customerCompanyAdmin->customer_company_id;

        $responsible_users = User::with(['internalUser.companyRole'])->where('type', User::INTERNAL_USER)
            ->whereHas('internalUser', function ($q) use ($company_id, $responsible_roles_id) {
                $q->where('customer_company_id', $company_id)->whereIn('roles_id', $responsible_roles_id);
            })
            ->where('status', User::ACTIVE)
            ->select('id', 'first_name', 'last_name', 'full_name as label')
            ->get()
            ->map(function ($user) {
                return [...$user->only('first_name', 'last_name', 'label'), 'value' => $user->id, 'internal_user_type' => DeviceAuthAndHierarchyElementRole::RESPONSIBLE_ROLE, 'role' => $user->internalUser->roles_id, 'internal_user_id' => $user->internalUser->id];
            });

        $suboridinate_users = User::with(['internalUser.companyRole'])->where('type', User::INTERNAL_USER)
            ->whereHas('internalUser', function ($q) use ($company_id, $suboridinate_roles_id) {
                $q->where('customer_company_id', $company_id)->whereIn('roles_id', $suboridinate_roles_id);
            })
            ->where('status', User::ACTIVE)
            ->select('id', 'first_name', 'last_name', 'full_name as label')
            ->get()
            ->map(function ($user) {
                return [...$user->only('first_name', 'last_name', 'label'), 'value' => $user->id, 'internal_user_type' => DeviceAuthAndHierarchyElementRole::DIRECT_SUBORDINATE_ROLE, 'role' => $user->internalUser->roles_id, 'internal_user_id' => $user->internalUser->id];
            });

        return $suboridinate_users->concat($responsible_users);
    }

    public function getHierarchyByRole(Request $request, $roleId)
    {
        $company_id = $request->user('sanctum')->customerCompanyAdmin->customer_company_id;
        $company_role_id = $roleId;

        // find hierarchy id which assign to subordiante and requested role id
        $highrarchyIds =  DeviceAuthAndHierarchyElementRole::select('roleable_id')->where([
            ['company_role_id', '=', $company_role_id],
            ['role_type', '=', 'direct_subordinate'],
        ])->hierarchyElement()->distinct()->pluck('roleable_id')->unique();

        // find hierarchy id which assign to responsible and requested role id
        if (count($highrarchyIds) == 0) {
            $highrarchyIds =  DeviceAuthAndHierarchyElementRole::select('roleable_id')->where([
                ['company_role_id', '=', $company_role_id],
                ['role_type', '=', 'responsible'],
            ])->hierarchyElement()->distinct()->pluck('roleable_id')->unique();
        }

        // find  max hierarchy for same company and role
        $hierarchyLabel = HierarchyElement::where([
            ['customer_company_id', '=', $company_id],
            ['status', '=', HierarchyElement::STATUS_ACTIVE]
        ])->whereIn('id', $highrarchyIds)->distinct()->max('hierarchy_level');

        // find all till max hierarchy with organization elements which active
        $hierarchyElement = HierarchyElement::withWhereHas('organizationElements', function ($query) {
            $query->where('status', '=', HierarchyElement::STATUS_ACTIVE);
        })
            ->where([
                ['hierarchy_level', '<=', $hierarchyLabel],
                ['status', '=', HierarchyElement::STATUS_ACTIVE],
                ['customer_company_id', '=', $company_id]
            ])->orderBy('hierarchy_level', 'DESC')->get();

        return $hierarchyElement;
    }

    /**
     * Process the invitation from submit
     *
     * @param AcceptInvitaton $request
     * @return \Illuminate\Http\Response
     */
    public function acceptInvitaion(AcceptInvitaton $request)
    {

        User::where('email', $request->email)
            ->update([
                'password' => Hash::make($request->password),
                'verification_token' => null,
                'email_verified_at' => now(),
                'status' => USER::ACTIVE
            ]);

        return response()->json(['message' => 'Email verified successfully'], Response::HTTP_ACCEPTED);
    }

    public function sendEmail($user)
    {

        return Mail::to($user)->send(new Invitation($user));
    }

    public function sendEmailVerification($user)
    {

        return Mail::to($user)->send(new Verification($user));
    }

    /**
     * Custom organization element user create
     *
     * @param hierarchy_element object
     * @param user $object
     */
    protected function customOrganizationElementUserStore($hierarchy_element, $user)
    {
        $now = now();
        collect($hierarchy_element)->map(
            function ($item, int $key) use ($user, $now) {
                if (count($item) > 0) {
                    foreach ($item["organization_elements"] as $key => $value) {

                        CustomOrganizationElementUser::insert([
                            'user_id' => $user->id,
                            'organization_element_id' => $value,
                            'created_at'  =>  $now,
                            'updated_at'  =>  $now,
                        ]);
                    }
                }
            }
        )->toArray();
    }

    protected function hirarchyWithElementsUserStore($hirarchy_with_elements, $user)
    {
        $now = now();
        $organization_element_user_data = collect($hirarchy_with_elements)->first();
        $organization_element_user  = [
            'user_id' => $user->id,
            'organization_element_id' => $organization_element_user_data["organization_elements"],
            'type' => $organization_element_user_data["direct_subordinate_roles"] ? 'direct_subordinate' : 'responsible',
            'created_at'  =>  $now,
            'updated_at'  =>  $now,
        ];

        OrganizationElementUser::insert($organization_element_user);
    }

    protected function noneHirarchyWithElemenUserStore($none_hirarchy, $user)
    {
        $now = now();
        collect($none_hirarchy)->map(
            function ($item, int $key) use ($user, $now) {
                if (count($item) > 0) {
                    foreach ($item["organization_elements"] as $key => $value) {

                        OrganizationElementUser::insert([
                            'user_id' => $user->id,
                            'organization_element_id' => $value,
                            'type' => "direct_subordinate",
                            'created_at'  =>  $now,
                            'updated_at'  =>  $now,
                        ]);
                    }
                }
            }
        )->toArray();
    }

    protected function campaignInternalStore($campaign_id, $user)
    {
        $now = now();
        $campaign_ids = collect($campaign_id)->map(function ($item, int $key) use ($user, $now) {

            return [
                'user_id' => $user->id,
                'campaign_id' => $item,
                'created_at'  =>  $now,
                'updated_at'  =>  $now,
            ];
        })->toArray();
        CampaignInternal::insert($campaign_ids);
    }

    protected function alignmentStore($alignment_id, $user)
    {
        $now = now();
        $alligment_ids = collect($alignment_id)->map(function ($item, int $key) use ($user, $now) {

            return [
                'user_id' => $user->id,
                'alignment_id' => $item,
                'created_at'  =>  $now,
                'updated_at'  =>  $now,
            ];
        })->toArray();
        AlignmentUser::insert($alligment_ids);
    }

    protected function competenceUpdate($competence, $user)
    {
        $company_admin_id =   auth()->user()->id;
        $competenceCollection = collect($competence)->each(function ($item, int $key) use ($user, $company_admin_id) {
            if (array_key_exists("id", $item)) {
                Competence::where(['id' => $item['id'], 'user_id' => $user->id])
                    ->update(
                        [
                            'type' => $item["type"],
                            'lang_code' => $item["lang_code"],
                            'other_competence' => $item["other_competence"],
                            'level' => $item["level"],
                            'status' => $item["status"],
                        ]
                    );
                $comp_exit_with_same_status =  CompetenceLog::where(['competence_id' => $item['id'], 'status' => $item["status"], 'created_by' => $company_admin_id])->first();
                if (!$comp_exit_with_same_status) {
                    CompetenceLog::create([
                        'competence_id' => $item['id'],
                        'created_by' => $company_admin_id,
                        'status' => $item["status"],
                    ]);
                }
            } else {
                $competence = Competence::create([
                    'user_id' => $user->id,
                    'type' => $item["type"],
                    'lang_code' => $item["lang_code"],
                    'other_competence' => $item["other_competence"],
                    'level' => $item["level"],
                    'status' => $item["status"],
                ]);
                $competence->competenceLog()->create([
                    'created_by' => $company_admin_id,
                    'status' => $item["status"],
                ]);
            }
        });
    }

    /**
     * Get internal user  overview fitler data
     *
     * @param Request $request
     * @return void
     */
    public function getFilterData(Request $request)
    {
        $query = $this->getInternalUserQuery();

        // if (request('column') == 'customer_companies') {
        //     $data = $query
        //         ->select('name as label', 'id as value')
        //         ->orderBy('name', 'ASC')
        //         ->paginate(10);

        //     return $data;
        // }

        if (request('column') == 'status') {
            $data = $query->select('users.status as label', 'users.status as value')
                ->distinct('users.status')
                ->orderBy('users.status', 'ASC')
                ->paginate(10);

            return $data;
        }

        if (request('column') == 'other_competence') {
            $data = $query->select('competences.other_competence as label', 'competences.other_competence as value')
                ->where('competences.other_competence', '<>', '')
                ->distinct('competences.other_competence')
                ->orderBy('competences.other_competence', 'ASC')
                ->paginate(request('per_page', 10));

            return $data;
        }
        if (request('column') == 'competence_status') {
            $data = $query->select('competences.status as label', 'competences.status as value')
                ->where('competences.status', '<>', '')
                ->distinct('competences.status')
                ->orderBy('competences.status', 'ASC')
                ->paginate(request('per_page', 10));

            return $data;
        }
        if (request('column') == 'level') {
            $data = $query->select('competences.level as label', 'competences.level as value')
                ->where('competences.level', '<>', '')
                ->distinct('competences.level')
                ->orderBy('competences.level', 'ASC')
                ->paginate(request('per_page', 10));

            return $data;
        }
        if (request('column') == 'roles_id') {
            $data = $query->select('company_roles.name as label', 'company_roles.id as value')
                ->distinct('internal_users.roles_id')
                ->orderBy('company_roles.id', 'ASC')
                ->paginate(request('per_page', 10));

            return $data;
        }
        if (request('column') == 'lang_code') {
            $data = $query->select('competences.lang_code as label', 'competences.lang_code as value')
                ->where('competences.lang_code', '<>', '')
                ->distinct('competences.lang_code')
                ->orderBy('competences.lang_code', 'ASC')
                ->paginate(request('per_page', 10));

            return $data;
        }
        if (request('column') == 'alignment_id') {
            $data = $query->select('alignment_users.alignment_id as label', 'alignment_users.alignment_id as value')
                ->distinct('alignment_users.alignment_id')
                ->where('alignment_users.alignment_id', '<>', '')
                ->orderBy('alignment_users.alignment_id', 'ASC')
                ->paginate(request('per_page', 10));

            return $data;
        }
    }
}
