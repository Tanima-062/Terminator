<?php

namespace App\Http\Resources\ContactDataRecords;

use App\Models\ContactDataRecordAllocate;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactDataRecordDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public static $wrap = null;

    public function toArray($request)
    {
        $allocation = [];
        if ($this->allocation && $this->allocation->assignedOrganizationElement) {
            $allocation[$this->allocation->assignedOrganizationElement->hierarchyType->name] = $this->allocation->assignedOrganizationElement->name;
        }

        return [
            "id" => $this->id,
            'general_information' => [
                "ID" => $this->prefix_id,
                "Source" => $this->source,
                "Category" => $this->category,
                "Campaign" => $this->campaign->name,
                "Creation Date" => Carbon::parse($this->created_at)->format('d.m.Y'),
                "Creator" => $this->creator->full_name,
                "Allocated to" =>  $this->allocation?->assigned_broker?->name,
                ...$allocation,
                "Last Feedback" => $this->lastFeedback?->feedback,
                "Feedback Time" => $this->lastFeedback?->created_at,
                "Control status (Lead)" => $this->lead,
            ],
            'person_information' => [
                "Salutation" => $this->salutation,
                "First Name" => $this->first_name,
                "Last Name" => $this->last_name,
                "Date of Birth" => Carbon::parse($this->date_of_birth)->format('d.m.Y'),
                "Mobile Phone Number" => $this->full_phone_number,
                "Email" => $this->email,
                "Correspondence Language" => $this->correspondence_language,
                "Other Languages" => $this->other_languages,
            ],
            "address" => [
                "Street" => $this->street,
                "House number" => $this->house_number,
                "Zip Code"  => $this->zip_code,
                "City"  => $this->city,
                "Country" => $this->country_iso_code,
                "Canton"    => $this->canton,
                "Region"    => $this->region,
            ],
            "appointment_relevant_information" => [
                "Car Insurance" => $this->car_insurance,
                "3rd Pillar" => $this->third_piller,
                "Household Goods" => $this->household_goods,
                "Legal Protection" => $this->legal_protection,
                "Health Status" => $this->health_status,
                "Health Insurance" => $this->health_insurance,
                "Contact person for insurance questions" => $this->contact_person_for_insurance_questions,
                "Saving" => $this->save,
                "Last Health Insurance Change" => $this->street,
                "Satisfaction" => $this->satisfaction,
                "Number of persons in household" => $this->number_of_persons_in_household,
                "Work Activity" => $this->work_activity,
            ],
            "contact_information" => [
                "Availability" => $this->availability_count > 0 ? false : true,
                "Desired consulting channel" => $this->desired_consultation_channel,
                "Competition" => $this->competition,
                "Origin link" => $this->origin_link,
                "Desired contact"  => $this->contact_desired,
            ],
            "costs_revenues_and_profit" => [
                "Costs" => $this->cost,
                "Revenue" => $this->revenue,
                "Profit" => $this->profit,
            ],
            "dates" => $this->appointments,

            "allocation" => $this->allocation?->append(['assigned_broker', 'assigned_organization_element']),
            "duplicates" => $this->duplicates->map(fn ($duplicate) => ['prefix_id' => $duplicate->prefix_id, 'id' => $duplicate->id, 'contact_record_status' => $duplicate->contact_record_status]),
            'availability' => $this->availability,
        ];
    }
}
