<?php

namespace App\Http\Controllers\Api\Web\ContactDataRecords;

use OpenSearch\Client;
use Illuminate\Http\Request;
use App\Models\ContactDataRecord;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\LeadsTabDataSendToEmailJob;
use App\Exports\ContactDataRecords\AllTabDataExport;
use App\Exports\ContactDataRecords\LeadTabDataExport;
use App\Mail\ContactDataRecords\AllTabDataExportEmail;
use App\Exports\ContactDataRecords\TerminTabDataExport;
use App\Mail\ContactDataRecords\LeadsTabDataExportEmail;
use App\Mail\ContactDataRecords\TerminTabDataExportEmail;
use App\Traits\ContactDataRecords\GetAllTabDataOpenSearchQuery;
use App\Traits\ContactDataRecords\GetTerminDataOpenSearchQuery;
use App\Traits\ContactDataRecords\GetLeadTabDataOpenSearchQuery;

class ExportContactDataRecordController extends Controller
{
    use GetLeadTabDataOpenSearchQuery, GetTerminDataOpenSearchQuery, GetAllTabDataOpenSearchQuery;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportToEmail(Request $request, Client $client)
    {


        $request->validate([
            'email' =>  ['required', 'email:filter',],
            'tab'       =>  ['required'],
        ]);

        if($request->tab == 'leads') {
            $data =  $this->exportLeadTabData($request, $client);

            $filenanme = uniqid().'.csv';

            Excel::store(new LeadTabDataExport($data), $filenanme, 'public');

            Mail::to($request->email)->send(new LeadsTabDataExportEmail($filenanme));
        }

        if($request->tab == 'termin') {
            $data =  $this->exportTerminTabData($request, $client);

            $filenanme = uniqid().'.csv';

            Excel::store(new TerminTabDataExport($data), $filenanme, 'public');

            Mail::to($request->email)->send(new TerminTabDataExportEmail($filenanme));
        }

        if($request->tab == 'all') {
            $data =  $this->exportAllTabData($request, $client);

            $filenanme = uniqid().'.csv';

            Excel::store(new AllTabDataExport($data), $filenanme, 'public');

            Mail::to($request->email)->send(new AllTabDataExportEmail($filenanme));
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportToFile(Request $request, Client $client)
    {
        $request->validate([
            'tab'       =>  ['required'],
        ]);

        if($request->tab == 'leads') {
            $data =  $this->exportLeadTabData($request, $client);

            return Excel::download(new LeadTabDataExport($data) ,'contact-data-record-leads.csv');
        }

        if($request->tab == 'termin') {
            $data =  $this->exportTerminTabData($request, $client);

            return Excel::download(new TerminTabDataExport($data) ,'contact-data-record-termin.csv');
        }

        if($request->tab == 'all') {
            $data =  $this->exportAllTabData($request, $client);

            return Excel::download(new AllTabDataExport($data) ,'contact-data-record-all.csv');
        }
    }



    /**
     * Get lead tab export data
     *
     * @param Request $request
     * @return void
     */
    public function exportLeadTabData(Request $request, Client $client)
    {
        // info($request->tab);

        $per_page = 10000;

        $queryData = $this->getLeadRecords($client, 1, $per_page);
        $contactDataRecordsId = $queryData['records'];
        $total = $queryData['total'];



        $data = collect([]);

        $chunks = collect($contactDataRecordsId)->chunk(1000);

        foreach($chunks as $ids) {
            $ids = $ids->toArray();
            $records = ContactDataRecord::query()
            ->with(['campaign:id,name', 'creator:id,full_name', 'allocation'=>function($q){
                $q->select(['id','contact_data_record_id', 'user_id','broker_user_id','internal_user_id', 'organization_element_id', 'broker_id', 'type']);
                $q->with(['user:id,full_name', 'broker:id,name', 'organizationElement:id,name']);
            }])
            ->select(['id', 'prefix_id', 'created_at', 'user_id', 'campaign_id', 'correspondence_language',
            'canton', 'first_name', 'last_name', 'lead', 'contact_record_status', 'date_of_birth'])
            ->whereIn('id', $ids)
            ->WithLastFeedback()
            ->when(count($ids), fn($q)=>$q->orderByRaw("field(id," . implode(',', $ids) . ")"))
            ->get()
            ;

            $data->push(...$records);
        }

        return $data;
    }



     /**
     * Get termin tab export data
     *
     * @param Request $request
     * @return void
     */
    public function exportTerminTabData(Request $request, Client $client)
    {
        // info($request->tab);

        $per_page = 10000;

        $queryData = $this->getTerminRecords($client, 1, $per_page);
        $contactDataRecordsId = $queryData['records'];
        $total = $queryData['total'];



        $data = collect([]);

        $chunks = collect($contactDataRecordsId)->chunk(1000);

        foreach($chunks as $ids) {
            $ids = $ids->toArray();

            $records = ContactDataRecord::query()
            ->with(['campaign:id,name', 'allocation'=>function($q){
                $q->select(['id','contact_data_record_id', 'user_id','broker_user_id','internal_user_id', 'organization_element_id', 'broker_id', 'type']);
                $q->with(['user:id,full_name', 'broker:id,name', 'organizationElement:id,name']);
            }])
            ->select(['id', 'campaign_id', 'correspondence_language', 'canton', 'first_name', 'last_name', 'zip_code', 'city', 'number_of_persons_in_household', 'contact_record_status'])
            ->WithLastAppointment()
            ->whereIn('id', $ids)
            ->when(count($ids), fn($q)=>$q->orderByRaw("field(id," . implode(',', $ids) . ")"))
            ->get()
            ;


            $data->push(...$records);
        }

        return $data;
    }


     /**
     * Get all tab export data
     *
     * @param Request $request
     * @return void
     */
    public function exportAllTabData(Request $request, Client $client)
    {
        // info($request->tab);

        $per_page = 10000;

        $queryData = $this->getAllTabRecords($client, 1, $per_page);
        $contactDataRecordsId = $queryData['records'];
        $total = $queryData['total'];


        $data = collect([]);

        $chunks = collect($contactDataRecordsId)->chunk(1000);

        foreach($chunks as $ids) {
            $ids = $ids->toArray();

            $records = ContactDataRecord::query()
            ->with(['campaign:id,name', 'allocation'=>function($q){
                $q->select(['id','contact_data_record_id', 'user_id','broker_user_id','internal_user_id', 'organization_element_id', 'broker_id', 'type']);
                $q->with(['user:id,full_name', 'broker:id,name', 'organizationElement:id,name']);
            }])
            ->select(['id', 'campaign_id', 'correspondence_language', 'prefix_id', 'created_at', 'category', 'first_name', 'last_name', 'lead', 'contact_record_status'])
            ->WithLastAppointment()
            ->whereIn('id', $ids)
            ->when(count($ids), fn($q)=>$q->orderByRaw("field(id," . implode(',', $ids) . ")"))
            ->get()
            ;


            $data->push(...$records);
        }

        return $data;
    }


}
