<?php

namespace Database\Factories;

use App\Models\Broker;
use App\Models\BrokerUser;
use App\Models\CustomerCompany;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BrokerUser>
 */
class BrokerUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $customer_company = CustomerCompany::inRandomorder()->first();
        $broker = Broker::where('customer_company_id', $customer_company->id)->first();
        return [
            //
                'user_id'                   =>  fn()=> User::factory()->create([
                    'type'      =>  User::BROKER_USER,
                    'customer_company_id'   =>  $customer_company->id,
                    'language_id'           =>  1,
                ]),
                'broker_id'                 =>  fn()=> $broker->id,
                'customer_company_id'       => $customer_company->id,
                'correspondence_language'   =>  $this->faker->languageCode(),
                'salutation'                =>  $this->faker->randomElement(['Ms', 'Mr', '/']),
                'phone_iso_code'            =>  $this->faker->countryCode(),
                'phone'                     =>  $this->faker->phoneNumber(),
                'role'                      =>  $this->faker->randomElement([BrokerUser::ADMIN, BrokerUser::INTERMEDIARY])
        ];
    }
}
