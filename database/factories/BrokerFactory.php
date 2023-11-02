<?php

namespace Database\Factories;

use App\Models\Broker;
use App\Models\CustomerCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Broker>
 */
class BrokerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'customer_company_id'           =>  fn()=> CustomerCompany::inRandomOrder()->first()->id,
            'name'                          =>  $this->faker->company(),
            'street_number'                 =>  $this->faker->text(25),
            'street_name'                   =>  $this->faker->text(25),
            'zip_code'                      =>  $this->faker->postcode(),
            'city'                          =>  $this->faker->city(),
            'country_iso_code'              =>  $this->faker->countryCode(),
            'contact_person_first_name'     =>  $this->faker->firstName(),
            'contact_person_last_name'      =>  $this->faker->firstName(),
            'contact_person_email'          =>  $this->faker->firstName(),
            'contact_person_phone_iso_code' =>  $this->faker->countryCode(),
            'contact_person_phone'          =>  $this->faker->phoneNumber(),
            'status'                        =>  $this->faker->randomElement([Broker::ACTIVE, Broker::INACTIVE]),

            // 'prefix_id',  'contact_person_full_phone_number', 'status'
        ];
    }
}
