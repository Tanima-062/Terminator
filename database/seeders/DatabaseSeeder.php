<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Language;
use App\Models\InternalUser;
use App\Models\CustomerCompany;
use Illuminate\Database\Seeder;
use App\Models\HierarchyElement;
use App\Models\ContactDataRecord;
use App\Models\WorkflowSetting;
use Illuminate\Support\Facades\Redis;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Redis::flushDB();

        $this->call(LanguageSeeder::class);
        $this->call(CompanyRoleSeeder::class);
        $this->call(WorkflowSettingSeeder::class);
        User::factory(1)->systemAdmin()->active()->create([
            'email'         => 'systemadmin@mail.com',
            'language_id'   =>  '2'
        ]);

        $this->call(CustomerCompanySeeder::class);
        $this->call(OrganizationElementSeeder::class);

        User::factory(1, ['customer_company_id' => 1])->hasCustomerCompanyAdmin([
            'customer_company_id' => 1
            ])->customerCompanyAdmin()->active()->create([
                'email' => 'customercompanyadmin@mail.com',
        ]);


        foreach ([1 => 'leader', 2 => 'manager', 3 => 'quality_controller', 4 => 'call_agent'] as $key => $value) {
            User::factory(1, ['customer_company_id' => 1])->hasInternalUser([
                'customer_company_id' => 1,
                'roles_id' => $key
            ])->internalUser()->active()->create([
                'email' => "$value@mail.com"
            ]);
        }


        User::factory()->count(10)->hasInternalUser(['customer_company_id' => 1])->internalUser()->active()->create();
        $this->call(CampaignSeeder::class);

        User::factory()->count(10)->hasCustomerCompanyAdmin()->customerCompanyAdmin()->active()->create();
        User::factory()->count(10)->hasCustomerCompanyAdmin()->customerCompanyAdmin()->create();
        User::factory()->count(10)->hasInternalUser()->internalUser()->active()->create();

        $this->call(BrokerSeeder::class);
        $this->call(BrokerUserSeeder::class);


        $this->call(ContactDataRecordSeeder::class);
        $this->call(ContactDataRecordAllocateSeeder::class);
        $this->call(ContactDataRecordAppointmentSeeder::class);
        $this->call(ContactDataRecordFeedbackSeeder::class);
    }
}
