<?php

namespace App\Http\Controllers\Api\Web\CustomerCompanyAdmin;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\CustomerCompanyAdmin\Invitaion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerCompanyAdmin\AcceptInvitaton;
use App\Models\CustomerCompanyAdmin;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\CustomerCompanyAdmin\StoreRequest;
use App\Http\Requests\CustomerCompanyAdmin\UpdateRequest;
use App\Http\Resources\CustomerCompanyAdmin\CustomerCompanyAdminResource;
use App\Models\CustomerCompany;
use Illuminate\Support\Facades\Hash;
use App\Mail\CustomerCompanyAdmin\Verification;
use Illuminate\Validation\ValidationException;

class CustomerCompanyAdminController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $auth_user = auth()->user();
        if(!$auth_user->canAccess('customer-company-admin:view')) {
           abort(403);
        }

        $customer_company_admins = $this->getCustomerCompanyAdminQuery()
            ->select('customer_company_admins.*')
            ->with(['user:id,first_name,last_name,full_name,email,status', 'customerCompany:id,name'])
            ->orderByColumn()
            ->paginate(request('per_page', 25));

        return CustomerCompanyAdminResource::collection($customer_company_admins);
    }



    /**
     * Get Customer Company Admin PREFIX ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCustomerCompanyAdminId(Request $request)
    {
        return response()->json(CustomerCompanyAdmin::getPrefixIdForCompany());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $token = md5(uniqid() . rand(1000, 9000));

        // Transaction
        DB::transaction(function () use ($request, $token) {

            $user = User::create($request->only('first_name', 'last_name', 'email', 'language_id', 'send_mail', 'customer_company_id') + [
                'status'        =>  $request->send_mail ? User::PENDING : User::NEW,
                'type'          =>  User::CUSTOMER_COMPANY_ADMIN,
                'verification_token' =>  $request->send_mail ? $token : null,
                'password' => '',
            ]);

            $user->customerCompanyAdmin()->create($request->only('customer_company_id') + [
                'phone_iso_code' =>  $request->phone_iso_code ?? null,
                'phone_number' =>  $request->phone_number ?? null,
                'full_phone_number' =>  $request->phone_number && $request->phone_iso_code ? PhoneNumber::make($request->phone_number, $request->phone_iso_code)->formatE164() : null,
            ]);

            if ($request->send_mail)
                $this->sendEmail($user);
        });
        $message = $request->send_mail ? 'The Customer Company Admin was successfully created and the Email invitation sent.' :
            __('The Customer Company Admin was successfully created.');

        return response()->json(['message' => $message], Response::HTTP_CREATED);
    }

    /**
     * Update Customer admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, CustomerCompanyAdmin $customer_company_admin)
    {


        // Transaction
        $change_mail = DB::transaction(

            function () use ($request, $customer_company_admin) {
                $change_mail = false;
                $input = $request->only('first_name', 'last_name', 'email', 'language_id', 'customer_company_id') + [
                    'phone_iso_code' =>  $request->phone_iso_code ?? null,
                    'phone_number' =>  $request->phone_number ?? null,
                    'full_phone_number' =>  $request->phone_number && $request->phone_iso_code ? PhoneNumber::make($request->phone_number, $request->phone_iso_code)->formatE164() : null,
                ];

                if ($customer_company_admin->user->email !== $request->email) {
                    $input['send_mail'] = true;
                    $input['status'] = USER::EMAIL_VERIFICATION_PENDING;
                    $input['password'] = "";
                    $input['verification_token'] = md5(uniqid() . rand(1000, 9000));
                    $change_mail =  true;
                }

                $customer_company_admin->update($input);
                $customer_company_admin->user->update($input);

                if (isset($input['send_mail']))
                    $this->sendEmailVerification($customer_company_admin->user);

                return $change_mail;
            }
        );

        return response()->json(
            ['message' =>   __(
                $change_mail ? 'The Customer Company Admin “:Name” was successfully updated and an email address verification email sent.' : 'The Customer Company Admin “:Name“  was successfully updated.',
                ['Name' => $customer_company_admin->user->name]

            )],
            Response::HTTP_OK
        );
    }


    /**
     * Get customer company admin overview fitler data
     *
     * @param Request $request
     * @return void
     */
    public function getFilterData(Request $request)
    {
        $query = $this->getCustomerCompanyAdminQuery();

        if (request('column') == 'customer_companies') {
            $data = $query
                // ->select('customer_company_admins.id', 'customer_company_admins.customer_company_id', 'customer_companies.name as label', 'customer_companies.id as value')
                ->select('customer_companies.name as label', 'customer_companies.id as value')
                ->distinct('customer_company_admins.id')
                ->orderBy('customer_companies.name', 'ASC')
                ->paginate(10);

            return $data;
        }

        if (request('column') == 'names') {
            $data = $query
                ->select('users.full_name',)
                ->distinct('users.full_name')
                ->orderBy('users.full_name', 'ASC')
                ->paginate(10);

            $mappedData = $data
                ->getCollection()
                ->transform(fn ($item) => ['label' => $item->full_name, 'value' => $item->full_name]);

            $data->setCollection($mappedData);

            return $data;
        }

        if (request('column') == 'status') {
            $data = $query
                ->select('users.status as label', 'users.status as value')
                ->distinct('users.status')
                ->orderBy('users.status', 'ASC')
                ->paginate(10);

            return $data;
        }
    }

    /**
     * Generate customer company overview queries
     *
     *
     */
    private function getCustomerCompanyAdminQuery()
    {
        $query = CustomerCompanyAdmin::query()
            ->join('customer_companies', 'customer_companies.id', '=', 'customer_company_admins.customer_company_id')
            ->join('users', 'users.id', '=', 'customer_company_admins.user_id')
            ->when(request('status') && request('except_filter') != 'status', fn ($q) => $q->filterByStatus())
            ->when(request('customer_companies') && request('except_filter') != 'customer_companies', fn ($q) => $q->filterByCustomerCompanyName())
            ->when(request('names') && request('except_filter') != 'names', fn ($q) => $q->filterByUserName())
            ->when(request('start_date') && request('end_date'), fn ($q) =>  $q->filterByBetweenCreatedAt())
            ->when(request('search'), fn ($q) => $q->overviewSearch());

        return $query;
    }

    public function updateStatus(Request $request, CustomerCompanyAdmin $customer_company_admin)
    {
        $request->validate([
            'status'    =>  ['required', 'in:active,inactive']
        ]);

        $customer_company_admin->user->update(['status' => request('status')]);

        if (request('status') == User::INACTIVE)
            $customer_company_admin->user->tokens()->delete();

        return response(['success' => true], Response::HTTP_ACCEPTED);
    }

    public function show(CustomerCompanyAdmin $customer_company_admin)
    {
        $auth_user = auth()->user();
        if(!$auth_user->canAccess('customer-company-admin:view')) {
           abort(403);
        }

        $customer_company_admin->load('user', 'customerCompany', 'user.language');
        return response()->json($customer_company_admin);
    }

    /**
     * check token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tokenValidation(Request $request)
    {
        $token = User::where('verification_token', $request->token)
        ->where('email', $request->email)
        ->first();

        if (!$token) {
            throw ValidationException::withMessages([
                'token' => 'reset_token_invalid',
            ]);
        }

        return true;

    }

    /**
     * Process the invitation from submit
     *
     * @param AcceptInvitaton $request
     * @return \Illuminate\Http\Response
     */
    public function acceptInvitaion(AcceptInvitaton $request)
    {

        $token = User::where('verification_token', $request->token)
        ->where('email', $request->email)
        ->first();

        if (!$token) {
            throw ValidationException::withMessages([
                'token' => 'reset_token_invalid',
            ]);
        }


        User::where('email', $request->email)
            ->update([
                'password' => Hash::make($request->password),
                'verification_token' => null,
                'email_verified_at' => now(),
                'status' => USER::ACTIVE
            ]);

        return response()->json(['message' => 'Email verified successfully'], Response::HTTP_ACCEPTED);
    }

    //send invitation email
    public function sendInvitationEmail(Request $request, User $user)
    {
        $user->update(['verification_token' => md5(uniqid() . rand(1000, 9000))]);
        $user->load('language');
        Mail::to($user)->send(new Invitaion($user));

        return response()->json(
            ['message' =>   __(
                'Email has been sent to :name .',
                ['name' => $user->name]

            )],
            Response::HTTP_OK
        );
    }

    public function sendEmail($user)
    {

        return Mail::to($user)->send(new Invitaion($user));
    }

    public function sendEmailVerification($user)
    {

        return Mail::to($user)->send(new Verification($user));
    }

    /**
     * Get customer company of current logged in customer company admin
     *
     * @param Request $request
     *
     */

    public function getCustomerCompanyOfCurrentCustomerCompanyAdmin(Request $request)
    {
        $user = $request->user();
        $company_id = $user->customerCompanyAdmin->customer_company_id;
        return CustomerCompany::findOrFail($company_id);
    }
}
