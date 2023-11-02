<?php

namespace App\Http\Controllers\Api\Web\ContactDataRecords;

use App\Http\Controllers\Controller;
use App\Models\ContactDataRecord;
use App\Models\ContactDataRecordHistory;
use Illuminate\Http\Request;

class ContactDataRecordDuplicateCheckController extends Controller
{
    public function duplicateCheckStatusUpdate(Request $request, ContactDataRecord $contact_data_record)
    {
        $request->validate([
            'duplicate_records'                             => 'required|array',
            'duplicate_records.*'                           => 'required|array:contact_data_record_id,contact_record_status',
            'duplicate_records.*.contact_data_record_id'    => 'required|exists:contact_data_records,id',
            'duplicate_records.*.contact_record_status'     => 'required|in:New,Duplicate',
        ]);

        $duplicate_contact_data_records = ContactDataRecord::whereIn('id', array_map(fn ($record) => $record['contact_data_record_id'], $request->duplicate_records))->get();
        $histories = [];

        foreach ($request->duplicate_records as $key => $record) {

            $duplicate_contact_data_record = $duplicate_contact_data_records->where('id', $record['contact_data_record_id'])->firstOrFail();
            if ($duplicate_contact_data_record->contact_record_status  ==  $record['contact_record_status']) {
                continue;
            }
            $duplicate_contact_data_record->fill(['contact_record_status' => $record['contact_record_status'], 'category' => $record['contact_record_status'] == ContactDataRecord::STATUS_DUPLICATE  ? 'duplicate' : 'lead']);
            $histories[] = [
                'contact_data_record_id'     => $record['contact_data_record_id'],
                'user_id'                    => $request->user('sanctum')->id,
                'action'                     => $record['contact_record_status'] == ContactDataRecord::STATUS_DUPLICATE ? ContactDataRecordHistory::DUPLICATE : ContactDataRecordHistory::NO_DUPLICATE,
                'status_change'              => $duplicate_contact_data_record->isDirty('contact_record_status'),
                'old_status'                 => $duplicate_contact_data_record->isDirty('contact_record_status') ? $duplicate_contact_data_record->getOriginal('contact_record_status') : null,
                'new_status'                 => $duplicate_contact_data_record->isDirty('contact_record_status') ? $duplicate_contact_data_record->contact_record_status : null,
                'category_change'            => $duplicate_contact_data_record->isDirty('category'),
                'old_category'               => $duplicate_contact_data_record->isDirty('category') ? $duplicate_contact_data_record->getOriginal('category') : null,
                'new_category'               => $duplicate_contact_data_record->isDirty('category') ? $duplicate_contact_data_record->category : null,
                'created_at'                 => now(),
                'updated_at'                 => now(),
            ];
            $duplicate_contact_data_record->save();
        }

        ContactDataRecordHistory::insert($histories);

        if (!$contact_data_record->duplicates()->whereIn('contact_record_status', [ContactDataRecord::STATUS_CHECK_DUPLICATE, ContactDataRecord::STATUS_DUPLICATE])->whereNot('id', $contact_data_record->id)->exists()) {
            $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_NEW, 'category' => 'lead']);
            $contact_data_record->history()->create([
                'user_id'           => $request->user('sanctum')->id,
                'action'            => ContactDataRecordHistory::NO_DUPLICATE,
                'status_change'     => $contact_data_record->isDirty('contact_record_status'),
                'old_status'        => $contact_data_record->isDirty('contact_record_status') ? $contact_data_record->getOriginal('contact_record_status') : null,
                'new_status'        => $contact_data_record->isDirty('contact_record_status') ? $contact_data_record->contact_record_status : null,
                'category_change'   => $contact_data_record->isDirty('category'),
                'old_category'      => $contact_data_record->isDirty('category') ? $contact_data_record->getOriginal('category') : null,
                'new_category'      => $contact_data_record->isDirty('category') ? $contact_data_record->category : null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
            $contact_data_record->save();
        }

        return;
    }
}
