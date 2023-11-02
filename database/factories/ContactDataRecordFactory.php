<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Campaign;
use App\Models\CustomerCompany;
use App\Models\ContactDataRecord;
use App\Models\CustomerCompanyAdmin;
use App\Models\ContactDataRecordAppointment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactDataRecord>
 */
class ContactDataRecordFactory extends Factory
{
    public function configure()
    {
        return $this->afterCreating(function (ContactDataRecord $contactDataRecord) {
            if($contactDataRecord->category == 'termination_appointment'){
                ContactDataRecordAppointment::factory()->create([
                    'customer_company_id'               => $contactDataRecord->customer_company_id,
                    'contact_data_record_id'            => $contactDataRecord->id,
                    'user_id'                           => $contactDataRecord->user_id
                ]);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $other_languages = [];
        for($i = 0; $i <mt_rand(0, 5); $i++){
            $other_languages[] = $this->faker->languageCode();
        }

        $customerCompanyAdmin = CustomerCompanyAdmin::inRandomOrder()->first();

        return [
            // 'campaign_id'               => fn()=> Campaign::inRandomOrder()->first()->id,
            'campaign_id'               => 1,
            'customer_company_id'       =>  fn()=> $customerCompanyAdmin->customer_company_id,
            'user_id'                   =>  fn()=> $customerCompanyAdmin->user_id,
            'source'                    =>  $this->faker->randomElement(ContactDataRecord::$source_lists)['value'],
            'category'                  =>  $this->faker->randomElement(ContactDataRecord::$category_lists)['value'],
            'salutation'                =>  $this->faker->randomElement(ContactDataRecord::$salutation_lists)['value'],
            'first_name'                =>  $first_name = $this->faker->firstName(),
            'last_name'                 =>  $last_name = $this->faker->lastName(),
            'full_name'                 =>  $first_name.' '.$last_name,
            'date_of_birth'             =>  $this->faker->date(),
            'phone_number'              =>  $phone = $this->faker->phoneNumber(),
            'phone_number_iso_code'     =>  $this->faker->countryCode(),
            'full_phone_number'         =>  $phone,
            'email'                     =>  $this->faker->safeEmail(),
            'street'                    =>  $this->faker->streetName(),
            'house_number'              =>  $this->faker->text(25),
            'zip_code'                  =>  $this->faker->postcode(),
            'city'                      =>  $this->faker->city(),
            'country_iso_code'          =>  $this->faker->countryCode(),
            'canton'                    =>  $this->faker->randomElement(ContactDataRecord::$canton_lists)['value'],
            'region'                    =>  $this->faker->randomElement(ContactDataRecord::$region_lists)['value'],
            'other_languages'           =>  $other_languages,
            'correspondence_language'   =>  $this->faker->languageCode(),
            'car_insurance'             =>  $this->faker->randomElement(ContactDataRecord::$car_insurance_lists)['value'],
            'third_piller'              =>  $this->faker->randomElement(ContactDataRecord::$third_piller_lists)['value'],
            'household_goods'           =>  $this->faker->randomElement(ContactDataRecord::$household_good_lists)['value'],
            'legal_protection'          =>  $this->faker->randomElement(ContactDataRecord::$legal_protection_lists)['value'],
            'health_status'             =>  $this->faker->randomElement(ContactDataRecord::$health_status_lists)['value'],
            'contact_person_for_insurance_questions'    =>  $this->faker->randomElement(ContactDataRecord::$contact_person_for_insurance_question_lists)['value'],
            'health_insurance'          =>  $this->faker->randomElement(ContactDataRecord::$health_insurance_lists)['value'],

            'accident'          =>  $this->faker->randomElement(ContactDataRecord::$accident_lists)['value'],
            'franchise'          =>  $this->faker->randomElement(ContactDataRecord::$francise_lists)['value'],
            'supplementary_insurance'          =>  $this->faker->randomElement(ContactDataRecord::$supplementary_insurance_lists)['value'],

            'save'                      =>  $this->faker->randomElement(ContactDataRecord::$save_lists)['value'],
            'last_health_insurance_change'  => $this->faker->randomElement(ContactDataRecord::$last_health_insurance_change_lists)['value'],
            'satisfaction'              => $this->faker->randomElement(ContactDataRecord::$satisfaction_lists)['value'],
            'number_of_persons_in_household'    =>  $this->faker->randomElement(ContactDataRecord::$number_of_persons_in_household_lists)['value'],
            'work_activity'             =>  $this->faker->randomElement(ContactDataRecord::$work_activity_lists)['value'],
            'desired_consultation_channel'  =>  $this->faker->randomElement(ContactDataRecord::$desired_consultation_channel_lists)['value'],
            'competition'               =>  null,
            'origin_link'               =>  null,
            'contact_desired'           =>  $this->faker->randomElement(ContactDataRecord::$contact_desired_lists)['value'],
            'lead'                      =>  $this->faker->randomElement(ContactDataRecord::$lead_lists)['value'],
            'remarks_control_lead'      =>  $this->faker->text(),
            'data_verified_updated'     =>  $this->faker->boolean(),
            'contact_record_status'     =>  $this->faker->randomElement(ContactDataRecord::$contact_record_status_lists)['value'],
        ];
    }



     /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function newStatus()
    {
        return $this->state(fn (array $attributes) => [
            'contact_record_status' => 'New',
        ]);
    }
}
