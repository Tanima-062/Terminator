<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Language;
use Illuminate\Support\Str;
use App\Models\CustomerCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'language_id'       =>  fn()=>Language::inRandomOrder()->first()->id,
            // 'photo'     =>  $this->faker->imageUrl(64, 64),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'type'      =>  $this->faker->randomElement([User::SYSTEM_ADMIN, User::CUSTOMER_COMPANY_ADMIN, User::INTERNAL_USER, User::BROKER_USER]),
            'status'    =>  $this->faker->randomElement([User::NEW, User::PENDING, User::ACTIVE, User::INACTIVE,]),
            'customer_company_id'   => fn()=>  CustomerCompany::inRandomOrder()->first()->id
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model's status should be active.
     *
     * @return static
     */
    public function active()
    {
        return $this->state(fn (array $attributes) => [
            'status' => User::ACTIVE,
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
            'status' => User::INACTIVE,
        ]);
    }

    /**
     * Indicate that the model's type should be system admin.
     *
     * @return static
     */
    public function systemAdmin()
    {
        return $this->state(fn (array $attributes) => [
            'type' => User::SYSTEM_ADMIN,
            'language_id'   =>  2,
            'customer_company_id'   => null
        ]);
    }
    /**
     * Indicate that the model's type should be customer company admin.
     *
     * @return static
     */
    public function customerCompanyAdmin()
    {
        return $this->state(fn (array $attributes) => [
            'type' => User::CUSTOMER_COMPANY_ADMIN,
            'language_id'   =>  1
        ]);
    }
    /**
     * Indicate that the model's type should be internal user.
     *
     * @return static
     */
    public function internalUser()
    {
        return $this->state(fn (array $attributes) => [
            'type' => User::INTERNAL_USER,
            'language_id'   =>  1
        ]);
    }
}
