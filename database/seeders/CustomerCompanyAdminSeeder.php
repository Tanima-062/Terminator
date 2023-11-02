<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\CustomerCompanyAdmin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerCompanyAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomerCompanyAdmin::factory()->count(1000)->create();

        CustomerCompanyAdmin::factory()->create([
            'user_id'           =>  function(){
                return User::factory()->create([
                    'type'  =>  User::CUSTOMER_COMPANY_ADMIN,
                    // 'language_id'   =>  1,
                    'email' =>  'customercompanyadmin@mail.com',
                    'status'  =>    User::ACTIVE,
                ])->id;
            },
        ]);
    }
}
