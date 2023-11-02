<?php

namespace App\Http\Controllers\Api\Web\ContactDataRecords;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ContactDataRecords\ContactDataRecordImport;

class ImportContactDataRecordController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->file('import_file');
        $request->validate([
            'campaign_id'   =>  ['required', 'exists:campaigns,id'],
            // 'import_file'   =>  ['required', 'mimes:csv,xlsx,xls'],
            'import_file'   =>  ['required', 'mimes:csv']
        ]);

        $full_name = sprintf("imports/%s.%s", uniqid(), $request->file('import_file')->getClientOriginalExtension() );
        $request->file('import_file')->storeAs('', $full_name, 'temp');

        $user_id = auth()->user()->id;
        $campaign_id = request('campaign_id');
        $customer_company_id = auth()->user()->customer_company_id;

        Excel::import(new ContactDataRecordImport($campaign_id, $user_id, $customer_company_id, $full_name), $full_name, 'temp');


    }


}
