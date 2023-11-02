<?php

namespace App\Http\Controllers\Api\Web\BrokerUser;

use App\Models\Role;
use App\Models\User;
use App\Models\Broker;
use App\Models\BrokerUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BrokerUser\StoreRequest;
use App\Http\Requests\BrokerUser\UpdateRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\BrokerUser\BrokerUserResource;
use Illuminate\Support\Facades\Mail;
use App\Mail\BrokerUser\Registration;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Support\Facades\Auth;

class BrokerUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $auth_user = auth()->user();
         if(!$auth_user->canAccess('broker-user:view')) {
            abort(403);
         }

        $broker_users = $this->getBrokerUserQuery()
            ->with(['broker:id,name', 'user:id,full_name,status'])
            ->orderByColumn()
            ->paginate(request('per_page', 25));
        return BrokerUserResource::collection($broker_users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {

        $request->merge(['user_id' => auth()->user()->id,'customer_company_id' => auth()->user()->customer_company_id]);

        $token = md5(uniqid() . rand(1000, 9000));
        $user = User::create($request->only('first_name', 'last_name', 'email', 'language_id', 'send_mail','customer_company_id') + [
            'status'        =>  $request->send_mail ? User::PENDING : User::NEW,
            'type'          =>  User::BROKER_USER,
            'verification_token' =>  $request->send_mail ? $token : null,
            'password' => '',
        ]);

        $broker_user = BrokerUser::create( [
            'customer_company_id' => $request->customer_company_id,
            'user_id' => $user->id,
            'broker_id' => $request->broker_id,
            'role' => $request->role,
            'salutation'=> $request->salutation,
            'correspondence_language' => $request->correspondence_language,
            'phone_iso_code' =>  $request->phone_iso_code ?? null,
            'phone_number' =>  $request->phone_number ?? null,
            'full_phone_number' =>  $request->phone_number && $request->phone_iso_code ? PhoneNumber::make($request->phone_number, $request->phone_iso_code)->formatE164() : null,
        ]);


        if ($request->send_mail){
            $broker_user->load('broker','user');
            $this->sendEmail($broker_user);  
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BrokerUser $brokerUser)
    {
        // abort(403);
        $auth_user = auth()->user();
        if(!$auth_user->canAccess('broker-user:view') || $brokerUser->customer_company_id != $auth_user->customer_company_id ) {
           abort(403);
        }

        $brokerUser->load('broker', 'user');
        return response()->json($brokerUser);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, BrokerUser $broker_user)
    {
        $auth_user = auth()->user();

        if(!$auth_user->canAccess('broker-user:edit') || $broker_user->customer_company_id != $auth_user->customer_company_id ) {
            abort(403);
        }

        User::where('id', $request->user_id)->update(['first_name' => $request->first_name, 'last_name' => $request->last_name, 'full_name' => $request->first_name . ' '. $request->last_name]);
        $broker_user->update($request->all());
        $broker_user->load('user');
        return $broker_user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function sendEmail($broker_user)
    {
        return Mail::to($broker_user->user)->send(new Registration($broker_user));
    }


    private function getBrokerUserQuery()
    {
        $query = BrokerUser::query()
            ->join('users', 'users.id', '=', 'broker_users.user_id')
            ->join('brokers', 'brokers.id', '=', 'broker_users.broker_id')
            ->select('broker_users.id','broker_users.broker_id','brokers.name','broker_users.user_id','users.full_name','broker_users.created_at','broker_users.correspondence_language','broker_users.role')
            ->where('broker_users.customer_company_id', auth()->user()->customer_company_id)
            ->when(request('status') && request('except_filter') != 'status', fn ($q) => $q->filterByStatus())
            ->when(request('brokers') && request('except_filter') != 'brokers', fn ($q) => $q->filterByBrokerName())
            ->when(request('users') && request('except_filter') != 'users', fn ($q) => $q->filterByUserName())
            ->when(request('roles') && request('except_filter') != 'roles', fn ($q) => $q->filterByRole())
            ->when(request('start_date') && request('end_date'), fn ($q) =>  $q->filterByBetweenCreatedAt())
            ->when(request('search'), fn ($q) => $q->overviewSearch());

        return $query;
    }

    public function getFilterData(Request $request)
    {
        $query = $this->getBrokerUserQuery();

        if (request('column') == 'users') {
            $data = $query
                ->select('users.full_name as label', 'users.id as value')
                ->distinct('broker_users.id')
                ->orderBy('users.full_name', 'ASC')
                ->paginate(10);

            return $data;
        }

        if (request('column') == 'brokers') {

            $data = $query
                ->select('brokers.name as label', 'brokers.id as value')
                ->distinct('brokers.name')
                ->orderBy('brokers.name', 'ASC')
                ->paginate(10);

            return $data;
        }

        if (request('column') == 'roles') {
            $data = $query->select('broker_users.role as label', 'broker_users.role as value')
                ->distinct('broker_users.role')
                ->orderBy('broker_users.role', 'ASC')
                ->paginate(10)
                // ->pluck('city')
                // ->count()
                // ->get()
                // ->unique()
                // ->values()
                // ->map(fn($item)=> ['label'=>$item->city, 'value'=>$item->city])
            ;

            return $data;
        }

        if (request('column') == 'status') {
            $data = $query->select('users.status as label', 'users.status as value')
                ->distinct('users.status')
                ->orderBy('users.status', 'ASC')
                ->paginate(10)
                // ->pluck('status')
                // ->unique()
                // ->values()
                // ->map(fn($item)=> ['label'=>$item, 'value'=>$item])
            ;

            return $data;
        }
    }

    public function updateStatus(Request $request, BrokerUser $brokerUser)
    {
        $request->validate([
            'status'    =>  ['required', 'in:active,inactive']
        ]);

        User::where('id', $brokerUser->user_id)->update(['status' => request('status')]);

        if (request('status') == User::INACTIVE)

        User::where('id', $brokerUser->user_id)->first()->tokens()->delete();

        return response(['success' => true], Response::HTTP_ACCEPTED);
    }

    public function getPrefixId(){
        return BrokerUser::generatePrefixIdByCompanyId(auth()->user()->customer_company_id);
    }

    //send invitation email
    public function sendInvitationEmail(Request $request, User $user)
    {
        $user->update(['verification_token' => md5(uniqid() . rand(1000, 9000)), 'status' => 'pending']);
        $user->load('language');

        $broker_user = BrokerUser::where('user_id',$user->id)->first();
        Mail::to($user)->send(new Registration($broker_user));

        return response()->json(
            ['message' =>   __(
                'Email has been sent to :name .',
                ['name' => $user->name]

            )],
            Response::HTTP_OK
        );
    }

}
