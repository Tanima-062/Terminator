<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Redis::flushDB();

        $this->call(LanguageSeeder::class);
        \App\Models\User::factory()->systemAdmin()->active()->create([
            'email'         => 'systemadmin@mail.com',
            'language_id'   =>  '2'
        ]);
        $this->call(CompanyRoleSeeder::class);
        $this->call(CampaignSeeder::class);
        $this->call(WorkflowSettingSeeder::class);
    }
}
