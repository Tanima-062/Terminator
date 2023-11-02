<?php

namespace Database\Factories;

use App\Models\CustomerCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerCompany>
 */
class CustomerCompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'prefix_id'         =>  '',
            'name'              =>  $name = $this->faker->company(),
            'alias_name'        =>  $name,
            'street_name'       =>  $this->faker->streetName(),
            'street_number'     =>  random_int(10, 100),
            'zip_code'          =>  $this->faker->postcode(),
            'city'              =>  $this->faker->city(),
            'country_iso_code'  =>  $this->faker->countryCode(),
            'contact_person_first_name'    =>  $this->faker->firstName(),
            'contact_person_last_name'     =>  $this->faker->lastName(),
            'contact_person_email'         =>  $this->faker->safeEmail(),
            'contact_person_phone_iso_code'    =>  $this->faker->countryCode(),
            'contact_person_phone'         =>  $this->faker->phoneNumber(),
            'auto_logout_time'              =>  random_int(10, 100),
            'device_authentication_required'    =>  $this->faker->boolean(),
            'hierarchy_elements_required'  =>  $this->faker->boolean(),
            'status' =>  $this->faker->randomElement([CustomerCompany::ACTIVE, CustomerCompany::INACTIVE])
        ];
    }


     /**
     * Indicate that the model's status should be active.
     *
     * @return static
     */
    public function active()
    {
        return $this->state(fn (array $attributes) => [
            'staus' =>CustomerCompany::ACTIVE,
        ]);
    }


     /**
     * Indicate that the model's status should be inactive.
     *
     * @return static
     */
    public function inactive()
    {
        return $this->state(fn (array $attributes) => [
            'staus' =>CustomerCompany::INACTIVE,
        ]);
    }
}
