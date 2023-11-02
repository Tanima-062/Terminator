<?php

namespace App\Http\Controllers\Api\Web\ContactDataRecords;

use Illuminate\Http\Request;
use App\Models\ContactDataRecord;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContactDataRecords\ContactDataRecordDetailsResource;

class DetailContactDataRecordController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show(ContactDataRecord $contactDataRecord)
    {
        $contactDataRecord
            ->loadCount('availability')
            ->load([
                'campaign',
                'creator:id,full_name',
                'allocation',
                'appointments'
            ]);

        $contactDataRecord->lastFeedback = $contactDataRecord->feedbacks()->latest()->first();
        $contactDataRecord->setRelation('duplicates', $contactDataRecord->duplicates()->where('customer_company_id', auth()->user()->customer_company_id)->whereNot('id', $contactDataRecord->id)->whereIn('contact_record_status', [ContactDataRecord::STATUS_CHECK_DUPLICATE, ContactDataRecord::STATUS_DUPLICATE])->select('id', 'prefix_id', 'phone_number')->get());
        return new ContactDataRecordDetailsResource($contactDataRecord);
    }

    /**
     * Edit the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(ContactDataRecord $contactDataRecord)
    {
        $contactDataRecord
            ->loadCount('availability')
            ->load([
                'campaign',
                'creator:id,full_name',
                'allocation',
                'availability',
                'leadControlTasks',
                'availability',
                'appointments' => fn ($q) => $q->orderBy('created_at', 'desc')
            ]);

        $contactDataRecord->allocation?->append('assigned_organization_element');
        $contactDataRecord->lastFeedback = $contactDataRecord->feedbacks()->latest()->first();
        $contactDataRecord->setRelation('duplicates', $contactDataRecord->duplicates()->where('customer_company_id', auth()->user()->customer_company_id)->whereNot('id', $contactDataRecord->id)->whereIn('contact_record_status', [ContactDataRecord::STATUS_CHECK_DUPLICATE, ContactDataRecord::STATUS_DUPLICATE])->select('id', 'prefix_id', 'phone_number')->get());
        return $contactDataRecord;
    }

    public function history(ContactDataRecord $contactDataRecord)
    {
        return response()->json($contactDataRecord->history->load(['user:id,full_name,type', 'user' => ['internalUser:user_id,id', 'customerCompanyAdmin:user_id,id']]));
    }
}
