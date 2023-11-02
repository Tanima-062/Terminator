<?php

namespace Database\Factories;

use App\Models\CompanyRole;
use App\Models\CustomerCompany;
use App\Models\DeviceAuthAndHierarchyElementRole;
use App\Models\HierarchyElement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HierarchyElement>
 */
class HierarchyElementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $company_id = CustomerCompany::inRandomOrder()->first()->id;
        return [
            'name' => $this->faker->name(),
            'hierarchy_level' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
            'customer_company_id'   => $company_id
        ];
    }


    public function none()
    {
        return $this->state(function (array $attribute) {
            return [
                'hierarchy_level' => null,
            ];
        });
    }
}
