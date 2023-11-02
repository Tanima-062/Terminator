<?php

namespace Database\Factories;

use App\Models\ContactDataRecord;
use App\Models\ContactDataRecordFeedback;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactDataRecordFeedback>
 */
class ContactDataRecordFeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'contact_data_record_id'        =>  fn()=> ContactDataRecord::inRandomOrder()->first()->id,
            'feedback'                      =>  $this->faker->randomElement(ContactDataRecordFeedback::getAllFeedbackLists()),
            'feedback_remarks'              =>  $this->faker->text()
        ];
    }
}
