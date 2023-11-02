<?php

namespace Database\Factories;

use App\Models\ContactDataRecord;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ContactDataRecordIntermediaryFeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $appointment_took_place = mt_rand(0, 1);
        $outcome =  $this->faker->randomElement(['Positive', 'Negative', 'Follow up contact necessary']);
        $reason = $this->faker->randomElement($appointment_took_place ? ['Treatment', 'Did not want an appointment', 'Multi-year contract', 'Other'] : ['Not at home', 'Untraceable', 'Not reachable', 'Cancellation on the part of intermediary', 'Cancelled']);
        return [
            'contact_data_record_id'    => fn()=> ContactDataRecord::inRandomOrder()->first()->id,
            'appointment_took_place' => $appointment_took_place,
            'outcome' => $outcome,
            'contracts_concluded' => $appointment_took_place && $outcome == 'Positive' ? mt_rand(1, 20) : null,
            'intermediary_remarks' => $this->faker->text(240),
            'reason' => $reason,
            'other' => $reason == 'Other' ? $this->faker->text(240) : null,
            'expiry_year' => $reason == 'Multi-year contract' ? Carbon::now() : null,
            'follow_up_contact_date' => $outcome == 'Follow up contact necessary' ? Carbon::now() : null,
            'follow_up_contact_time' => $outcome == 'Follow up contact necessary' ? Carbon::now() : null,
        ];
    }
}
