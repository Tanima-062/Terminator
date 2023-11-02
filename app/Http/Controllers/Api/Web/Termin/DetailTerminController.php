<?php

namespace App\Http\Controllers\Api\Web\Termin;

use Illuminate\Http\Request;
use App\Models\ContactDataRecord;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContactDataRecords\ContactDataRecordDetailsResource;
use App\Http\Resources\Termin\TerminDetailResource;

class DetailTerminController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function show($id)
     {
         $contactDataRecord = ContactDataRecord::query()
            ->where('id', $id)
            ->WithLastAppointment()
            ->withCount('availability')
            ->with([
                'allocation',
                'intermediaryFeedback'
            ])
            ->firstOrFail()
             ;

        // return $contactDataRecord;

        //  $contactDataRecord->lastFeedback = $contactDataRecord->feedbacks()->latest()->first();
        //  $contactDataRecord->setRelation('duplicates', $contactDataRecord->duplicates()->where('customer_company_id', auth()->user()->customer_company_id)->whereNot('id', $contactDataRecord->id)->whereIn('contact_record_status', [ContactDataRecord::STATUS_CHECK_DUPLICATE, ContactDataRecord::STATUS_DUPLICATE])->select('id', 'prefix_id', 'phone_number')->get());
         return new TerminDetailResource($contactDataRecord);
     }

}
