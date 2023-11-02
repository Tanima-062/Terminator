<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactDataRecordAppointment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContactDataRecordAppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ContactDataRecordAppointment::factory(1000)->create();
    }
}
