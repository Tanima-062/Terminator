<?php

namespace Database\Seeders;

use App\Models\ContactDataRecordFeedback;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactDataRecordFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ContactDataRecordFeedback::factory(1000)->create();
    }
}
