<?php

namespace Database\Factories;

use App\Models\HierarchyElement;
use App\Models\OrganizationElement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrganizationElement>
 */
class OrganizationElementFactory extends Factory
{

    public function configure()
    {
        return $this->afterCreating(function (OrganizationElement $organizationElement) {
            $hierarchy = HierarchyElement::findOrFail($organizationElement->type_id);
            if ($hierarchy->hierarchy_level == null) {
                $parent_elements =  OrganizationElement::whereIn('type_id', HierarchyElement::where('customer_company_id', $hierarchy->customer_company_id)->where('hierarchy_level', null)->pluck('id'))->pluck('id');
                $organizationElement->parentOrganizationElements()->attach($parent_elements);
            } else {
                $parent_elements = OrganizationElement::whereIn("type_id", HierarchyElement::where('customer_company_id', $hierarchy->customer_company_id)->where('hierarchy_level', '<', $hierarchy->hierarchy_level)->pluck('id'))->pluck('id');
                $organizationElement->parentOrganizationElements()->attach($parent_elements);
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
        $hierarchy = HierarchyElement::inRandomOrder()->first();
        return [
            'type_id'                   => $hierarchy->id,
            'name'                      => $this->faker->name,
            'customer_company_id'       => $hierarchy->customer_company_id,
            'status'                    => $this->faker->randomElement([OrganizationElement::STATUS_ACTIVE, OrganizationElement::STATUS_INACTIVE]),
        ];
    }
}
