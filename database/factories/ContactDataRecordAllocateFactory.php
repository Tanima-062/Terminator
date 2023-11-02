<?php

namespace Database\Factories;

use App\Models\Broker;
use App\Models\BrokerUser;
use App\Models\Campaign;
use App\Models\ContactDataRecord;
use App\Models\ContactDataRecordAllocate;
use App\Models\CustomerCompanyAdmin;
use App\Models\InternalUser;
use App\Models\OrganizationElement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactDataRecordAllocate>
 */
class ContactDataRecordAllocateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // $contactDataRecord =  ContactDataRecord::doesntHave('allocation')->first();
        $contactDataRecord =  ContactDataRecord::inRandomOrder()->first();

        $type = $this->faker->randomElement([
                ContactDataRecordAllocate::LEADER_HEAD_OF,
                ContactDataRecordAllocate::MANAGER,
                ContactDataRecordAllocate::QUALITY_CONTROLLER,
                ContactDataRecordAllocate::CALL_AGENT,
                ContactDataRecordAllocate::BROKER,
                ContactDataRecordAllocate::BROKER_USER,
                ContactDataRecordAllocate::VARIABLE_A,
            ]);
        $internal_user=null;
        $broker_user= null;
        $user_id= null;

        if(in_array($type, [ContactDataRecordAllocate::LEADER_HEAD_OF, ContactDataRecordAllocate::MANAGER, ContactDataRecordAllocate::QUALITY_CONTROLLER, ContactDataRecordAllocate::CALL_AGENT, ])) {
            $internal_user =  InternalUser::inRandomOrder()->where('customer_company_id', $contactDataRecord->customer_company_id)->first();
            $user_id = $internal_user?->user_id;
        }else if($type == ContactDataRecordAllocate::BROKER_USER){
            $broker_user =  BrokerUser::inRandomOrder()->where('customer_company_id', $contactDataRecord->customer_company_id)->first();
            $user_id = $broker_user?->user_id;
        }

        return [
            'allocate_by_user_id'               =>  fn()=>CustomerCompanyAdmin::inRandomOrder()->where('customer_company_id', $contactDataRecord->customer_company_id)->first()->user_id,
            'customer_company_id'               =>  $contactDataRecord->customer_company_id,
            'contact_data_record_id'            => fn()=> $contactDataRecord->id,
            'user_id'                           => $user_id,
            'broker_user_id'        => $broker_user?->id,
            'internal_user_id'      =>  $internal_user?->id,
            'organization_element_id'           =>  function() use($type, $contactDataRecord) {
                if($type == ContactDataRecordAllocate::VARIABLE_A){
                    return OrganizationElement::inRandomOrder()->where('customer_company_id', $contactDataRecord->customer_company_id)->first()->id;
                }

                return null;
            },
            'broker_id'                         =>  function() use($type, $contactDataRecord){
                if($type == ContactDataRecordAllocate::BROKER){
                    return Broker::inRandomOrder()->where('customer_company_id', $contactDataRecord->customer_company_id)->first()->id;
                }

                return null;
            },
            'campaign_id'                       =>  function(){
                if($this->faker->boolean(70)){
                    return Campaign::inRandomOrder()->first()->id;
                }

                return null;
            },
            'type'                              =>  $type
        ];
    }
}
