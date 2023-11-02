<?php

namespace Database\Factories;

use App\Models\ContactDataRecord;
use App\Models\CustomerCompanyAdmin;
use App\Models\ContactDataRecordAppointment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactDataRecordAppointment>
 */
class ContactDataRecordAppointmentFactory extends Factory
{
    public function configure()
    {
        return $this->afterCreating(function (ContactDataRecordAppointment $contactDataRecordAppointment) {
            $contactDataRecordAppointment->contactDataRecord()->update([
                'category' => 'termination_appointment'
            ]);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $contactDataRecord =  ContactDataRecord::query()->inRandomOrder()->first();

        return [
            'customer_company_id'               => fn()=> $contactDataRecord->customer_company_id,
            'contact_data_record_id'            => fn()=> $contactDataRecord->id,
            'user_id'                           => fn()=> CustomerCompanyAdmin::inRandomOrder()->where('customer_company_id', $contactDataRecord->customer_company_id)->first()->id,
            'appointment_date'                  =>  $this->faker->date(),
            'appointment_time'                  =>  $this->faker->time(),
            'notes'                             =>  $this->faker->randomElement([null, $this->faker->text()]),
            'control_status_appointment'        =>   $this->faker->randomElement([null, $this->faker->firstName()]),
            // 'control_status_appointment'        =>  null,
        ];
    }
}
