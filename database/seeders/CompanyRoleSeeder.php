<?php

namespace Database\Seeders;

use App\Models\CompanyRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Leader', 'Manager', 'Quality controller', 'Call agent'];

        foreach ($roles as $key => $role) {
            CompanyRole::create(['name' => $role]);
        }
    }
}
