<?php

namespace App\Http\Controllers\Api\Web\ContactDataRecords;

use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Models\ContactDataRecord;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CustomerCompanyAdmin;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ContactDataRecords\DropdownOption;
use App\Http\Requests\ContactDataRecord\CreateContactDataRecordRequest;
use App\Models\InternalUser;
use App\Models\User;

class CreateContactDataRecordController extends Controller
{
    use DropdownOption;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(CreateContactDataRecordRequest $request)
    {
        // info($request->all());
        // return $request->all();

        $auth_user = auth()->user();
        $customer_company_id = null;

        if($auth_user->type == User::CUSTOMER_COMPANY_ADMIN){
            $customerCompanyAdmin = CustomerCompanyAdmin::where('user_id', auth()->id())->firstOrFail();
            $customer_company_id = $customerCompanyAdmin->customer_company_id;
        }
        if($auth_user->type == User::INTERNAL_USER){
            $internal_user = InternalUser::where('user_id', auth()->id())->firstOrFail();
            $customer_company_id = $internal_user->customer_company_id;
        }

        if(!$customer_company_id) {
            abort(403);
        }

        $contactDataRecord = ContactDataRecord::create($request->except(['category', 'source'])  + [
            'category' => 'lead',
            'source' => 'not_online',
            'user_id' => $auth_user->id,
            'customer_company_id' => $customer_company_id
        ]);

        if (($request->workAvailabilityDays && count($request->workAvailabilityDays)) && ($request->workAvailabilityForm && count($request->workAvailabilityForm))) {
            $data = [];

            $now = now();
            foreach ($request->workAvailabilityDays as $key => $value) {
                if ($value) {
                    $day = $request->workAvailabilityForm[$key];

                    if (preg_match("/_(monday|tuesday|wednesday|thursday|friday|saturday|sunday)$/", $key, $match)) {
                        //'Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
                        $day_name = ucfirst($match[1]);
                        $data[] = [
                            'contact_data_record_id'    =>  $contactDataRecord->id,
                            'day'                       =>  $day_name,
                            'first_start_time'          =>  $day['first_start_time'],
                            'first_end_time'            =>  $day['first_end_time'],
                            'last_start_time'           =>  $day['last_start_time'],
                            'last_end_time'             =>  $day['last_end_time'],
                            'created_at'                =>  $now,
                            'updated_at'                =>  $now,
                        ];
                    }
                }
            }

            DB::table('contact_data_record_availabilities')->insert($data);
        }

        return response()->json([
            'status'    =>  'sucess'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactDataRecord  $contactDataRecord
     * @return \Illuminate\Http\Response
     */
    public function show(ContactDataRecord $contactDataRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactDataRecord  $contactDataRecord
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactDataRecord $contactDataRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactDataRecord  $contactDataRecord
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactDataRecord $contactDataRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactDataRecord  $contactDataRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactDataRecord $contactDataRecord)
    {
        //
    }


    public function getCreateOptionsData()
    {
        // $prefix_id =nextId('contact_data_records', ContactDataRecord::PREFIX);
        $prefix_id = ContactDataRecord::generatePrefixIdByCompanyId(auth()->user()->customer_company_id);

        $campaign_lists = Campaign::query()->select('name as label', 'id as value')->where('status', 'active')->get();

        return response()->json([
            'prefix_id'                                     =>  $prefix_id,
            'source_lists'                                  =>  [['label' => 'not_online', 'value' => 'not_online']],
            'category_lists'                                =>   [['label' => 'lead', 'value' => 'lead']],
            'campaign_lists'                                =>  $campaign_lists,
            'salutation_lists'                              =>  self::$salutation_lists,
            'canton_lists'                                  =>  self::$canton_lists,
            'region_lists'                                  =>  self::$region_lists,
            'car_insurance_lists'                           =>  self::$car_insurance_lists,
            'third_piller_lists'                            =>  self::$third_piller_lists,
            'household_good_lists'                          =>  self::$household_good_lists,
            'legal_protection_lists'                        =>  self::$legal_protection_lists,
            'health_status_lists'                           =>  self::$health_status_lists,
            'health_insurance_lists'                        =>  self::$health_insurance_lists,
            'contact_person_for_insurance_question_lists'   =>  self::$contact_person_for_insurance_question_lists,
            'save_lists'                                    =>  self::$save_lists,
            'last_health_insurance_change_lists'            =>  self::$last_health_insurance_change_lists,
            'satisfaction_lists'                            =>  self::$satisfaction_lists,
            'number_of_persons_in_household_lists'          =>  self::$number_of_persons_in_household_lists,
            'work_activity_lists'                           =>  self::$work_activity_lists,
            'desired_consultation_channel_lists'            =>  self::$desired_consultation_channel_lists,
            'contact_desired_lists'                         =>  self::$contact_desired_lists,
            'lead_lists'                                    => self::$lead_lists,
        ]);
    }
}
