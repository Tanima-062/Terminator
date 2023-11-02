<?php

namespace Database\Factories;

use App\Models\ContactDataRecord;
use App\Models\ContactDataRecordHistory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactDataRecordHistory>
 */
class ContactDataRecordHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'                   =>  fn()=> User::inRandomOrder()->where('type', '!=', User::SYSTEM_ADMIN)->first()->id,
            'contact_data_record_id'    =>  fn()=> ContactDataRecord::inRandomOrder()->first()->id,
            'action'                    =>  ContactDataRecordHistory::CONTACT_DATA_RECORD_CREATION,
            'status_change'             =>  $this->faker->boolean(),
            'old_status'                =>  null,
            'new_status'                =>  null,
            'category_change'           =>  $this->faker->boolean(),
            'old_category'              =>  null,
            'new_category'              =>  null,
            'user_type'                 =>  $this->faker->randomElement([User::BROKER_USER, User::INTERNAL_USER]),
            'notes'                     =>  $this->faker->text(),
        ];
    }
}
