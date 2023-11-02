<?php

namespace Database\Seeders;

use App\Models\CompanyRole;
use App\Models\CustomerCompany;
use App\Models\DeviceAuthAndHierarchyElementRole;
use App\Models\HierarchyElement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomerCompany::factory(5)->create(['status' => CustomerCompany::ACTIVE]);
        CustomerCompany::factory(5)->create(['status' => CustomerCompany::INACTIVE]);

        foreach (range(1, CustomerCompany::orderBy('id', 'DESC')->first()->id) as $index) {
            $levels = [1, 2, 3, 4];
            $roles = [1, 2, 3, 4];

            HierarchyElement::factory(5)
                ->has(DeviceAuthAndHierarchyElementRole::factory(1, ['role_type' => DeviceAuthAndHierarchyElementRole::RESPONSIBLE_ROLE, 'company_role_id' => 1]), 'roles')
                ->has(DeviceAuthAndHierarchyElementRole::factory(1, ['role_type' => DeviceAuthAndHierarchyElementRole::RESPONSIBLE_ROLE, 'company_role_id' => 2]), 'roles')
                ->has(DeviceAuthAndHierarchyElementRole::factory(1, ['role_type' => DeviceAuthAndHierarchyElementRole::RESPONSIBLE_ROLE, 'company_role_id' => 3]), 'roles')
                ->has(DeviceAuthAndHierarchyElementRole::factory(1, ['role_type' => DeviceAuthAndHierarchyElementRole::RESPONSIBLE_ROLE, 'company_role_id' => 4]), 'roles')
                ->has(DeviceAuthAndHierarchyElementRole::factory(1, function () use (&$roles) {
                    return ['role_type' => DeviceAuthAndHierarchyElementRole::DIRECT_SUBORDINATE_ROLE, 'company_role_id' => array_pop($roles) ?: 1];
                }), 'roles')
                ->create(function () use (&$levels, $index) {
                    return ['hierarchy_level' => array_pop($levels), 'customer_company_id' => $index];
                });
        }
    }
}
