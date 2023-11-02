<?php

use App\Models\ContactDataRecord;
use OpenSearch\Client;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private $indexName;
    private $client;

    public function __construct()
    {
        $this->client = app(Client::class);
        $this->indexName = ContactDataRecord::OPENSEARCH_INDEX_NAME;
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_data_records', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('campaign_id')->constrained('campaigns', 'id');
            $table->unsignedBigInteger('campaign_id');
            // $table->foreignId('customer_company_id')->constrained('customer_companies', 'id');
            $table->unsignedBigInteger('customer_company_id');
            // $table->foreignId('user_id')->constrained('users', 'id');
            $table->unsignedBigInteger('user_id');
            $table->string('prefix_id')->nullable();
            $table->enum('source', array_column(ContactDataRecord::$source_lists, 'value'));
            $table->enum('category', array_column(ContactDataRecord::$category_lists, 'value'));
            $table->enum('salutation', array_column(ContactDataRecord::$salutation_lists, 'value'))->nullable();
            $table->string('first_name', 30)->nullable();
            $table->string('last_name', 30)->nullable();
            $table->string('full_name', 70)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('phone_number_iso_code')->nullable();
            $table->string('full_phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('street', 30)->nullable();
            $table->string('house_number', 30)->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city', 30)->nullable();
            $table->string('country_iso_code')->nullable();
            $table->string('canton')->nullable();
            $table->string('region')->nullable();
            $table->json('other_languages')->nullable();
            $table->string('correspondence_language')->nullable();
            $table->string('car_insurance')->nullable();
            $table->string('third_piller')->nullable();
            $table->string('household_goods')->nullable();
            $table->string('legal_protection')->nullable();
            $table->enum('health_status', array_column(ContactDataRecord::$health_status_lists, 'value'))->nullable();
            $table->enum('contact_person_for_insurance_questions', array_column(ContactDataRecord::$contact_person_for_insurance_question_lists, 'value'))->nullable();
            $table->string('health_insurance')->nullable();
            // $table->enum('accident', array_column(ContactDataRecord::$accident_lists, 'value'))->nullable();
            // $table->enum('franchise',  array_column(ContactDataRecord::$francise_lists, 'value'))->nullable();
            // $table->enum('supplementary_insurance',array_column(ContactDataRecord::$supplementary_insurance_lists, 'value'))->nullable();
            $table->enum('save', array_column(ContactDataRecord::$save_lists, 'value'))->nullable();
            $table->enum('last_health_insurance_change', array_column(ContactDataRecord::$last_health_insurance_change_lists, 'value'))->nullable();
            $table->enum('satisfaction', array_column(ContactDataRecord::$satisfaction_lists, 'value'))->nullable();
            $table->smallInteger('number_of_persons_in_household',)->nullable();
            $table->enum('work_activity', array_column(ContactDataRecord::$work_activity_lists, 'value'))->nullable();
            $table->enum('desired_consultation_channel', array_column(ContactDataRecord::$desired_consultation_channel_lists, 'value'))->nullable();
            $table->string('competition')->nullable();
            $table->string('origin_link')->nullable();
            $table->enum('contact_desired', array_column(ContactDataRecord::$contact_desired_lists, 'value'))->nullable();
            // $table->enum('feedback', ['Not Reached', 'Wrong Number', 'No Interest', 'Sick', 'Already terminated', 'Other Offer received', 'Call later', 'Appointment', 'No Potential']);
            // $table->text('feedback_remarks')->nullable();
            $table->enum('lead', array_column(ContactDataRecord::$lead_lists, 'value'))->nullable();
            $table->text('remarks_control_lead')->nullable();
            $table->text('remarks_control_appointment')->nullable();
            $table->integer('no_interest_count')->default(0);
            $table->integer('not_reached_count')->default(0);
            $table->integer('new_not_reached_count')->default(0);
            // $table->bigInteger('allocated_to');
            $table->boolean('data_verified_updated')->default(false);
            $table->boolean('residential_address_confirmed')->default(false);
            $table->enum('contact_record_status', array_column(ContactDataRecord::$contact_record_status_lists, 'value'))->default('New');
            $table->timestamps();
        });

        if (env('OPENSEARCH_ENABLED')) {
            try {
                // Delete Old Indices
                // $this->client->indices()->delete([
                //     'index' => 'contact_data_records_cloud'
                // ]);
                $this->client->indices()->delete([
                    'index' => $this->indexName
                ]);
            } catch (\Throwable $th) {
                //throw $th;
            }
            try {

                //code...
                // Create an index with non-default settings.
                $this->client->indices()->create([
                    'index' => $this->indexName,
                    'body' => [
                        'settings' => [
                            'index' => [
                                'number_of_shards' => 4,
                                'max_result_window' =>  500000000,
                            ],
                        ],
                        'mappings'   =>  [
                            'properties' => [
                                // 'prefix_id'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'source'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'category'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'salutation'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'first_name'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'last_name'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'full_name'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'phone_number'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'full_phone_number'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'email'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'street'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'house_number'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'zip_code'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'city'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'country_iso_code'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'canton'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'region'    => [
                                //     'type'  =>  'keyword'
                                // ],

                                // 'correspondence_language'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'car_insurance'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'third_piller'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'household_goods'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'legal_protection'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'health_status'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'contact_person_for_insurance_questions'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'health_insurance'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'save'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'last_health_insurance_change'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'satisfaction'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'work_activity'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'desired_consultation_channel'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'competition'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'origin_link'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'contact_desired'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'lead'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'contact_record_status'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'feedbacks'    => [
                                //     'type'  =>  'nested'
                                // ],
                                // 'other_languages'    => [
                                //     'type'  =>  'keyword'
                                // ],
                                // 'last_feedback'    => [
                                //     'type'  =>  'nested'
                                // ],
                                'last_appointment'    => [
                                    'type'  =>  'nested',
                                    'properties' => [
                                        'appointment_time'  => [
                                            'type'  =>  'date',
                                            'format' => 'hour_minute_second'
                                        ]
                                    ]
                                ],
                                'number_of_persons_in_household'    => [
                                    'type'  =>  'short'
                                ],
                            ]
                        ]
                    ]
                ]);
            } catch (Exception $th) {
                // dump($th->getMessage());
                //throw $th;
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_data_records');

        if (env('OPENSEARCH_ENABLED')) {
            try {
                // Delete Old Indices
                $this->client->indices()->delete([
                    'index' => $this->indexName
                ]);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }
};
