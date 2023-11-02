<?php

namespace App\Http\Controllers\Api\Web\ContactDataRecords;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactDataRecord\EditContactDataRecordRequest;
use App\Models\ContactDataRecord;
use Illuminate\Http\Request;

class EditContactDataRecordController extends Controller
{
    public function __invoke(EditContactDataRecordRequest $request, ContactDataRecord $contact_data_record)
    {

        $contact_data_record->update($request->except(['category', 'source'])  + [
            'source' => 'not_online',
        ]);

        return response()->json([
            'status'    =>  'sucess'
        ], 200);
    }
}
