<?php

namespace App\Http\Controllers\Api\Intermediary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Intermediary\IntermediaryResource;
use App\Models\BrokerUser;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use App\Mail\Intermediary\Registration;
use Illuminate\Support\Facades\Mail;
use Propaganistas\LaravelPhone\PhoneNumber;
use App\Http\Requests\Intermediary\StoreRequest;
use App\Http\Requests\Intermediary\UpdateRequest;

class IntermediaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    //    dd(getOrderByLanguageNameByUser());

        $auth_user = auth()->user();
        if(!$auth_user->canAccess('intermediares.view')) {
           abort(403);
        }

        $intermediaries = $this->getBrokerUserQuery()
            ->with(['user:id,full_name,status'])
            ->orderByColumn()
            ->paginate(request('per_page', 25));
        return IntermediaryResource::collection($intermediaries);
    }

    private function getBrokerUserQuery()
    {
        $query = BrokerUser::query()
            ->join('users', 'users.id', '=', 'broker_users.user_id')
            ->select('broker_users.id','broker_users.user_id','users.full_name','broker_users.created_at','broker_users.correspondence_language','broker_users.role')
            ->where('broker_users.customer_company_id', auth()->user()->customer_company_id)
            ->where('broker_users.role', 'intermediary')
            ->when(request('status') && request('except_filter') != 'status', fn ($q) => $q->filterByStatus())
            ->when(request('users') && request('except_filter') != 'users', fn ($q) => $q->filterByUserName())
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

    public function updateStatus(Request $request, BrokerUser $intermediary)
    {
        $request->validate([
            'status'    =>  ['required', 'in:active,inactive']
        ]);

        User::where('id', $intermediary->user_id)->update(['status' => request('status')]);


        return response(['success' => true], Response::HTTP_ACCEPTED);
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

        return response()->json(Response::HTTP_CREATED);
    }


    public function sendEmail($broker_user)
    {
        return Mail::to($broker_user->user)->send(new Registration($broker_user));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BrokerUser $intermediary)
    {
        $auth_user = auth()->user();

        if(!$auth_user->canAccess('intermediares.view') || $intermediary->customer_company_id != $auth_user->customer_company_id ) {
           abort(403);
        }

        $intermediary->load('broker', 'user');
        return response()->json($intermediary);
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
    public function update(UpdateRequest $request, BrokerUser $intermediary)
    {
        User::where('id', $request->user_id)->update(['first_name' => $request->first_name, 'last_name' => $request->last_name, 'full_name' => $request->first_name . ' '. $request->last_name]);
        $intermediary->update($request->all());
        $intermediary->load('user');
        return $intermediary;
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
