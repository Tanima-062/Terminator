<?php

namespace Database\Seeders;

use App\Models\WorkflowSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkflowSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WorkflowSetting::create(['call_attempt_limit' => 10, 'contact_limit' => 5]);
    }
}
