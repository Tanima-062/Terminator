<?php

namespace Database\Seeders;

use App\Models\InternalUser;
use Database\Factories\InternalUserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class InternalUserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // InternalUser::factory()->create([
        //     'user_id'           =>  function () {
        //         return User::factory()->internalUser()->active()->create()->id;
        //     },
        // ]);

    }
}
