<?php

namespace Database\Seeders;

use App\Models\ContactDataRecordAllocate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactDataRecordAllocateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<1000; $i++){
            ContactDataRecordAllocate::factory()->create();
        }
    }
}
