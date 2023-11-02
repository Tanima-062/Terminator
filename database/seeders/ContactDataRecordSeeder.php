<?php

namespace Database\Seeders;

use App\Models\BrokerUser;
use App\Models\ContactDataRecord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactDataRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //duplicate contact
        ContactDataRecord::factory(5)->create(['lead' => null, 'category' => 'lead', 'source' => 'not_online', 'contact_record_status' => ContactDataRecord::STATUS_NEW, 'data_verified_updated' => 0, 'customer_company_id' => 1, 'created_at' => now()->subDays(2)]);

        ContactDataRecord::factory(5)->hasAllocation(fn ($attributes, ContactDataRecord $contactDataRecord) => ['customer_company_id' => $contactDataRecord->customer_company_id, 'broker_user_id' => BrokerUser::inRandomOrder()->first()?->id])->create(['lead' => null, 'category' => 'lead', 'source' => 'not_online', 'contact_record_status' => ContactDataRecord::STATUS_NEW, 'data_verified_updated' => 0, 'customer_company_id' => 1, 'created_at' => now()->subDays(2)]);

        ContactDataRecord::factory(5)->hasIntermediaryFeedback()->hasAllocation(fn ($attributes, ContactDataRecord $contactDataRecord) => ['customer_company_id' => $contactDataRecord->customer_company_id, 'broker_user_id' => BrokerUser::inRandomOrder()->first()?->id])->create(['lead' => 'Health Insurance', 'category' => 'Appointment', 'source' => 'not_online', 'contact_record_status' => ContactDataRecord::STATUS_ALLOCATED, 'data_verified_updated' => 0, 'customer_company_id' => 1, 'created_at' => now()->subDays(2)]);

        ContactDataRecord::factory(5)->create(['phone_number' => '123455667', 'contact_record_status' => ContactDataRecord::STATUS_CHECK_DUPLICATE, 'customer_company_id' => 1]);

        ContactDataRecord::factory(500)->create();
        // ContactDataRecord::factory(1000)->create(['category' => 'termination_appointment']);
    }
}
