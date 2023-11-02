<?php

namespace App\Http\Controllers\Api\Web\Broker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Broker\StoreRequest;
use App\Http\Requests\Broker\UpdateRequest;
use App\Models\Broker;
use App\Http\Resources\Broker\BrokerResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BrokerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth_user = auth()->user();
        if(!$auth_user->canAccess('broker:view')) {
           abort(403);
        }

        $brokers = $this->getBrokerQuery()
            ->with(['customerCompany:id,name'])
            ->orderByColumn()
            ->paginate(request('per_page', 25));

        return BrokerResource::collection($brokers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $input = $request->all();
        $broker = Broker::create($input+['customer_company_id' => auth()->user()->customer_company_id]);
        return $broker;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Broker $broker)
    {
        $auth_user = auth()->user();
        if(!$auth_user->canAccess('broker:view') || $broker->customer_company_id != $auth_user->customer_company_id ) {
           abort(403);
        }

        return response()->json($broker);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Broker $broker)
    {
        $input = $request->all();
        $broker->update($input);
        return $broker;
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

    public function fetchBrokers(){
        return response()->json(Broker::where(['customer_company_id' => auth()->user()->customer_company_id,'status'=>'active'])->orderBy('id', 'asc')->get());
    }

    private function getBrokerQuery()
    {
        $query = Broker::query()
            ->join('customer_companies', 'customer_companies.id', '=', 'brokers.customer_company_id')
            ->select('brokers.name','brokers.city','brokers.customer_company_id','brokers.contact_person_full_name','brokers.contact_person_email','brokers.created_at','brokers.id','brokers.status')
            ->where('brokers.customer_company_id', auth()->user()->customer_company_id)
            ->when(request('status') && request('except_filter') != 'status', fn ($q) => $q->filterByStatus())
            ->when(request('brokers') && request('except_filter') != 'brokers', fn ($q) => $q->filterByBrokerName())
            ->when(request('broker_locations') && request('except_filter') != 'broker_locations', fn ($q) => $q->filterByLocation())
            ->when(request('start_date') && request('end_date'), fn ($q) =>  $q->filterByBetweenCreatedAt())
            ->when(request('search'), fn ($q) => $q->overviewSearch());

        return $query;
    }

    public function getFilterData(Request $request)
    {
        $query = $this->getBrokerQuery();

        if (request('column') == 'customer_company') {
            $data = $query
                ->select('customer_companies.name as label', 'customer_companies.id as value')
                ->distinct('brokers.id')
                ->orderBy('customer_companies.name', 'ASC')
                ->paginate(10);

            return $data;
        }

        if (request('column') == 'brokers') {
            // $data = $query->select('name as label', 'id as value')->get();

            $data = $query
                ->select('brokers.name as label', 'brokers.id as value')
                ->orderBy('brokers.name', 'ASC')
                ->paginate(10);

            return $data;
        }

        if (request('column') == 'broker_locations') {
            $data = $query->select('brokers.city as label', 'brokers.city as value')
                ->distinct('brokers.city')
                ->whereNotNull('brokers.city')
                ->orderBy('brokers.city', 'ASC')
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
            $data = $query->select('brokers.status as label', 'brokers.status as value')
                ->distinct('brokers.status')
                ->orderBy('brokers.status', 'ASC')
                ->paginate(10)
                // ->pluck('status')
                // ->unique()
                // ->values()
                // ->map(fn($item)=> ['label'=>$item, 'value'=>$item])
            ;

            return $data;
        }
    }

    public function updateStatus(Request $request, Broker $broker)
    {
        $request->validate([
            'status'    =>  ['required', 'in:active,inactive']
        ]);

        $broker->update(['status' => request('status')]);

        if ($request->status == Broker::INACTIVE) {
            $users = DB::table('broker_users')
                ->distinct('user_id')
                ->where('broker_id', $broker->id)
                ->pluck('user_id')
                ->toArray();

            User::whereIn('id', $users)
                ->update(['status' => User::INACTIVE]);
        }

        return response(['success' => true], Response::HTTP_ACCEPTED);
    }

    public function getPrefixId(){
        return Broker::generatePrefixIdByCompanyId(auth()->user()->customer_company_id);
    }

}
