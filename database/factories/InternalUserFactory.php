<?php

namespace Database\Factories;

use App\Models\AlignmentUser;
use App\Models\CompanyRole;
use App\Models\CustomerCompany;
use App\Models\InternalUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InternalUser>
 */
class InternalUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $customer_company = CustomerCompany::inRandomOrder()->first();

        return [
            'user_id'           => fn()=> User::factory()->create([
                'type'          =>  User::INTERNAL_USER,
                'customer_company_id'   =>  $customer_company->id
            ])->id,
            'customer_company_id'        =>  $customer_company->id,
            'roles_id'          => fn()=>  CompanyRole::inRandomOrder()->first()->id,
            'salutation'        => $this->faker->randomElement(['Ms', 'Mr', '/']),
            'access_right'         => $this->faker->randomElement([1, 2, 3]),
            'correspondence_language_code'    => 'de',
        ];
    }


    public function configure()
    {
        return $this->afterCreating(function (InternalUser $internalUser) {
            if($internalUser->companyRole->name == CompanyRole::CALL_AGENT){
                $allignment_ids = $this->faker->randomElements([1, 2]);
                foreach($allignment_ids as $id){
                    AlignmentUser::create([
                        'user_id'           =>  $internalUser->user_id,
                        'alignment_id'      =>  $id,
                    ]);
                }
            }
        });
    }
}
