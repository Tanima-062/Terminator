<?php

namespace Database\Factories;

use App\Models\CustomerCompany;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerCompanyAdmin>
 */
class CustomerCompanyAdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $customer_company =  CustomerCompany::inRandomOrder()->first();
        return [
            'user_id'           =>  function() use($customer_company){
                return User::factory()->create([
                    'type'  =>  User::CUSTOMER_COMPANY_ADMIN,
                    'language_id'   =>  1,
                    'customer_company_id'   =>  $customer_company->id,
                ])->id;
            },
            'customer_company_id'           => $customer_company->id,
            'phone_number'      =>  $this->faker->e164PhoneNumber(),
            'phone_iso_code'    =>  $this->faker->countryCode(),
            'full_phone_number' =>  $this->faker->e164PhoneNumber()
        ];
    }
}
