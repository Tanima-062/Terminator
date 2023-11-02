<?php

namespace App\Imports\ContactDataRecords;

use Illuminate\Support\Carbon;
use App\Models\ContactDataRecord;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class ContactDataRecordImport implements ToCollection, WithHeadingRow, WithEvents {
    use RegistersEventListeners;


    public static $file_name;
    public static $disk;

    public function __construct(public $campaign_id, public $user_id, public $customer_company_id, $file_name= null, $disk = 'temp' )
    {
        self::$file_name = $file_name;
        self::$disk = $disk;
    }

    public function collection(Collection $rows)
    {

        $this->validateData($rows);
        // info($rows);

        foreach($rows as $row) {
            $this->storeData($row);
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }


    public static function afterImport(AfterImport $event)
    {
        if(self::$file_name) {
            Storage::disk(self::$disk)->delete(self::$file_name);
        }
    }




    // public function rules(): array
    // {
    //     return [
    //         'email' => Rule::in(['patrick@maatwebsite.nl']),

    //          // Above is alias for as it always validates in batches
    //          '*.email' => Rule::in(['patrick@maatwebsite.nl']),
    //     ];
    // }

    public function storeData($row) {
        $data = [
            'category'                                  => 'lead',
            'source'                                    => 'online',
            'user_id'                                   => $this->user_id,
            'customer_company_id'                       => $this->customer_company_id,
            'campaign_id'                               => $this->campaign_id,
            // 'contact_record_status'                     =>  'New',
            'salutation'                                =>  $row['salutation']?? null,
            'first_name'                                =>  $row['first_name']?? null,
            'last_name'                                 =>  $row['last_name']?? null,
            'full_name'                                 =>  sprintf("%s %s", $row['first_name']?? '',  $row['last_name']?? '' ),
            'date_of_birth'                             =>  $row['date_of_birth']?? null,
            'phone_number'                              =>  $row['phone_number']?? null,
            'phone_number_iso_code'                     =>  $row['phone_number_iso_code']?? null,
            // 'full_phone_number'                         =>  $row['full_phone_number']?? null,
            'email'                                     =>  $row['email']?? null,
            'street'                                    =>  $row['street']?? null,
            'house_number'                              =>  $row['house_number']?? null,
            'zip_code'                                  =>  $row['zip_code']?? null,
            'city'                                      =>  $row['city']?? null,
            'country_iso_code'                          =>  $row['country_iso_code']?? null,
            'canton'                                    =>  $row['canton']?? null,
            'region'                                    =>  $row['region']?? null,
            'other_languages'                           =>  $this->getOtherLanguages($row),
            'correspondence_language'                   =>  $row['correspondence_language']?? null,
            'car_insurance'                             =>  $row['car_insurance']?? null,
            'third_piller'                              =>  $row['third_piller']?? null,
            'household_goods'                           =>  $row['household_goods']?? null,
            'legal_protection'                          =>  $row['legal_protection']?? null,
            'health_status'                             =>  $row['health_status']?? null,
            'contact_person_for_insurance_questions'    =>  $row['contact_person_for_insurance_questions']?? null,
            'health_insurance'                          =>  $row['health_insurance']?? null,
            'accident'                                  =>  $row['accident']?? null,
            'franchise'                                 =>  $row['franchise']?? null,
            'supplementary_insurance'                   =>  $row['supplementary_insurance']?? null,
            'save'                                      =>  $row['save']?? null,
            'last_health_insurance_change'              =>  $row['last_health_insurance_change']?? null,
            'satisfaction'                              =>  $row['satisfaction']?? null,
            'number_of_persons_in_household'            =>  $row['number_of_persons_in_household']?? null,
            'work_activity'                             =>  $row['work_activity']?? null,
            'desired_consultation_channel'              =>  $row['desired_consultation_channel']?? null,
            'competition'                               =>  $row['competition']?? null,
            'origin_link'                               =>  $row['origin_link']?? null,
            'contact_desired'                           =>  $row['contact_desired']?? null,
            // 'lead'                                      =>  $row['lead']?? null,
            'remarks_control_lead'                      =>  $row['remarks_control_lead']?? null,
            // 'data_verified_updated'                     =>  $row['data_verified_updated']?? false,
        ];
        $contactDataRecord = ContactDataRecord::create($data);

        //Availability
        if($row['availability'] == true || $row['availability'] == 1) {
                $now = now();

                $data = [];

                if( (!is_null($row['monday_first_start_time']) && !is_null($row['monday_first_end_time'])) || (!is_null($row['monday_last_start_time']) && !is_null($row['monday_last_end_time']))) {
                    $data[] =   [
                        'contact_data_record_id'    =>  $contactDataRecord->id,
                        'day'                       =>  'Monday',
                        'first_start_time'          =>  $row['monday_first_start_time'] ? Carbon::createFromTimeString($row['monday_first_start_time'])->format('H:i') : null,
                        'first_end_time'            =>  $row['monday_first_end_time'] ? Carbon::createFromTimeString($row['monday_first_end_time'])->format('H:i') : null,
                        'last_start_time'           =>  $row['monday_last_start_time'] ? Carbon::createFromTimeString($row['monday_last_start_time'])->format('H:i') : null,
                        'last_end_time'             =>  $row['monday_last_end_time'] ? Carbon::createFromTimeString($row['monday_last_end_time'])->format('H:i') : null,
                        'created_at'                =>  $now,
                        'updated_at'                =>  $now,
                    ];
                }

                if( (!is_null($row['tuesday_first_start_time']) && !is_null($row['tuesday_first_end_time'])) || (!is_null($row['tuesday_last_start_time']) && !is_null($row['tuesday_last_end_time']))) {
                    $data[] =    [
                        'contact_data_record_id'    =>  $contactDataRecord->id,
                        'day'                       =>  'Tuesday',
                        'first_start_time'          =>  $row['tuesday_first_start_time']  ? Carbon::createFromTimeString($row['tuesday_first_start_time'])->format('H:i') : null,
                        'first_end_time'            =>  $row['tuesday_first_end_time']  ? Carbon::createFromTimeString($row['tuesday_first_end_time'])->format('H:i') : null,
                        'last_start_time'           =>  $row['tuesday_last_start_time']  ? Carbon::createFromTimeString($row['tuesday_last_start_time'])->format('H:i') : null,
                        'last_end_time'             =>  $row['tuesday_last_end_time']  ? Carbon::createFromTimeString($row['tuesday_last_end_time'])->format('H:i') : null,
                        'created_at'                =>  $now,
                        'updated_at'                =>  $now,
                    ];
                }

                if( (!is_null($row['wednesday_first_start_time']) && !is_null($row['wednesday_first_end_time'])) || (!is_null($row['wednesday_last_start_time']) && !is_null($row['wednesday_last_end_time']))) {
                    $data[] =  [
                        'contact_data_record_id'    =>  $contactDataRecord->id,
                        'day'                       =>  'Wednesday',
                        'first_start_time'          =>  $row['wednesday_first_start_time'] ? Carbon::createFromTimeString($row['wednesday_first_start_time'])->format('H:i') : null,
                        'first_end_time'            =>  $row['wednesday_first_end_time'] ? Carbon::createFromTimeString($row['wednesday_first_end_time'])->format('H:i') : null,
                        'last_start_time'           =>  $row['wednesday_last_start_time'] ? Carbon::createFromTimeString($row['wednesday_last_start_time'])->format('H:i') : null,
                        'last_end_time'             =>  $row['wednesday_last_end_time'] ? Carbon::createFromTimeString($row['wednesday_last_end_time'])->format('H:i') : null,
                        'created_at'                =>  $now,
                        'updated_at'                =>  $now,
                    ];
                }

                if( (!is_null($row['thursday_first_start_time']) && !is_null($row['thursday_first_end_time'])) || (!is_null($row['thursday_last_start_time']) && !is_null($row['thursday_last_end_time']))) {
                    $data[] = [
                        'contact_data_record_id'    =>  $contactDataRecord->id,
                        'day'                       =>  'Thursday',
                        'first_start_time'          =>  $row['thursday_first_start_time'] ? Carbon::createFromTimeString($row['thursday_first_start_time'])->format('H:i') : null,
                        'first_end_time'            =>  $row['thursday_first_end_time'] ? Carbon::createFromTimeString($row['thursday_first_end_time'])->format('H:i') : null,
                        'last_start_time'           =>  $row['thursday_last_start_time'] ? Carbon::createFromTimeString($row['thursday_last_start_time'])->format('H:i') : null,
                        'last_end_time'             =>  $row['thursday_last_end_time'] ? Carbon::createFromTimeString($row['thursday_last_end_time'])->format('H:i') : null,
                        'created_at'                =>  $now,
                        'updated_at'                =>  $now,
                    ];
                }
                if( (!is_null($row['friday_first_start_time']) && !is_null($row['friday_first_end_time'])) || (!is_null($row['friday_last_start_time']) && !is_null($row['friday_last_end_time']))) {
                    $data[] =  [
                        'contact_data_record_id'    =>  $contactDataRecord->id,
                        'day'                       =>  'Friday',
                        'first_start_time'          =>  $row['friday_first_start_time']  ? Carbon::createFromTimeString($row['friday_first_start_time'])->format('H:i') : null,
                        'first_end_time'            =>  $row['friday_first_end_time']  ? Carbon::createFromTimeString($row['friday_first_end_time'])->format('H:i') : null,
                        'last_start_time'           =>  $row['friday_last_start_time']  ? Carbon::createFromTimeString($row['friday_last_start_time'])->format('H:i') : null,
                        'last_end_time'             =>  $row['friday_last_end_time']  ? Carbon::createFromTimeString($row['friday_last_end_time'])->format('H:i') : null,
                        'created_at'                =>  $now,
                        'updated_at'                =>  $now,
                    ];
                }
                if( (!is_null($row['saturday_first_start_time']) && !is_null($row['saturday_first_end_time'])) || (!is_null($row['saturday_last_start_time']) && !is_null($row['saturday_last_end_time']))) {
                    $data[] = [
                        'contact_data_record_id'    =>  $contactDataRecord->id,
                        'day'                       =>  'Saturday',
                        'first_start_time'          =>  $row['saturday_first_start_time']  ? Carbon::createFromTimeString($row['saturday_first_start_time'])->format('H:i') : null,
                        'first_end_time'            =>  $row['saturday_first_end_time']  ? Carbon::createFromTimeString($row['saturday_first_end_time'])->format('H:i') : null,
                        'last_start_time'           =>  $row['saturday_last_start_time']  ? Carbon::createFromTimeString($row['saturday_last_start_time'])->format('H:i') : null,
                        'last_end_time'             =>  $row['saturday_last_end_time']  ? Carbon::createFromTimeString($row['saturday_last_end_time'])->format('H:i') : null,
                        'created_at'                =>  $now,
                        'updated_at'                =>  $now,
                    ];
                }
                if( (!is_null($row['sunday_first_start_time']) && !is_null($row['sunday_first_end_time'])) || (!is_null($row['sunday_last_start_time']) && !is_null($row['sunday_last_end_time']))) {
                    $data[] = [
                        'contact_data_record_id'    =>  $contactDataRecord->id,
                        'day'                       =>  'Sunday',
                        'first_start_time'          =>  $row['sunday_first_start_time']  ? Carbon::createFromTimeString($row['sunday_first_start_time'])->format('H:i') : null,
                        'first_end_time'            =>  $row['sunday_first_end_time']  ? Carbon::createFromTimeString($row['sunday_first_end_time'])->format('H:i') : null,
                        'last_start_time'           =>  $row['sunday_last_start_time']  ? Carbon::createFromTimeString($row['sunday_last_start_time'])->format('H:i') : null,
                        'last_end_time'             =>  $row['sunday_last_end_time']  ? Carbon::createFromTimeString($row['sunday_last_end_time'])->format('H:i') : null,
                        'created_at'                =>  $now,
                        'updated_at'                =>  $now,
                    ];
                }

                DB::table('contact_data_record_availabilities')->insert($data);
        }



    }


    public function validateData($rows)
    {
        Validator::make($rows->toArray(), [
            '*.competition'                                     => ['required',],
            '*.origin_link'                                     => ['required',],
            '*.salutation'                                      => ['nullable', function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$salutation_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.first_name'                                      => ['nullable', 'max:30'],
            '*.last_name'                                       => ['nullable', 'max:30'],
            '*.date_of_birth'                                   => ['nullable', 'date'],
            '*.phone_number'                                    => ['nullable'],
            '*.phone_number_iso_code'                           => ['nullable', function($attribute, $value, $fail){
                if(!in_array($value, $this->getPhoneNumberIsoCodeLists())) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.email'                                           => ['nullable', 'email:rfc,dns'],
            '*.street'                                          => ['nullable', 'max:30' ],
            '*.house_number'                                    => ['nullable', 'max:30'],
            '*.zip_code'                                        => ['nullable', 'max:30'],
            '*.city'                                            => ['nullable', 'max:30'],
            '*.country_iso_code'                                => ['nullable', function($attribute, $value, $fail){
                if(!in_array($value, $this->getPhoneNumberIsoCodeLists())) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.canton'                                          => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$canton_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.region'                                          => ['nullable', function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$region_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.other_languages'                                 => ['nullable', function($attribute, $value, $fail){
                $lists = explode(',', $value);
                if(count($lists)){
                    foreach($lists as $list) {
                        if(!in_array($list, $this->getLanguageCodeLists())) {
                            $fail("The {$attribute} value {$list} is invalid.");
                        }
                    }
                }
            }],
            '*.correspondence_language'                         => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, $this->getLanguageCodeLists())) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.car_insurance'                                   => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$car_insurance_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.third_piller'                                    => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$third_piller_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.household_goods'                                 => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$household_good_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.legal_protection'                                => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$legal_protection_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.health_status'                                   => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$health_status_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.contact_person_for_insurance_questions'          => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$contact_person_for_insurance_question_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.health_insurance'                                => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$health_insurance_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.accident'                                => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$accident_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.franchise'                                => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$francise_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.supplementary_insurance'                                => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$supplementary_insurance_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.save'                                            => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$save_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.last_health_insurance_change'                    => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$last_health_insurance_change_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.satisfaction'                                    => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$satisfaction_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.number_of_persons_in_household'                  => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$number_of_persons_in_household_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.work_activity'                                   => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$work_activity_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.desired_consultation_channel'                    => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$desired_consultation_channel_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            '*.contact_desired'                                 => ['nullable',  function($attribute, $value, $fail){
                if(!in_array($value, array_column(ContactDataRecord::$contact_desired_lists, 'value'))) {
                    $fail("The {$attribute} value {$value} is invalid.");
                }
            }],
            // '*.lead'                                            => ['nullable',  function($attribute, $value, $fail){
            //     if(!in_array($value, array_column(ContactDataRecord::$lead_lists, 'value'))) {
            //         $fail("The {$attribute} value {$value} is invalid.");
            //     }
            // }],
            '*.remarks_control_lead'                            => ['nullable',],
            // '*.data_verified_updated'                           => ['nullable', 'in:0,1'],

            '*.monday_first_start_time'                             => ['nullable', 'regex:/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]/u', ],
            '*.monday_first_end_time'                               => ['nullable', 'regex:/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]/u', ],
            // '*.monday_first_end_time'                            => ['nullable', 'date_format:H:i', ],
        ])->validate();
    }


    /**
     * Get all country iso code
     *
     * @return array
     */
    private function getPhoneNumberIsoCodeLists(): array{
        return [
            'ch', 'de', 'at', 'fr', 'it', 'gb', 'us', 'af', 'al', 'dz', 'as', 'ad', 'ao', 'ai', 'ag', 'ar', 'am', 'aw', 'au', 'az', 'bs', 'bh', 'bd', 'bb', 'by', 'be', 'bz', 'bj', 'bm', 'bt', 'bo', 'ba', 'bw', 'br', 'io', 'vg', 'bn', 'bg', 'bf', 'bi', 'kh', 'cm', 'ca', 'cv', 'bq', 'ky', 'cf', 'td', 'cl', 'cn', 'cx', 'cc', 'co', 'km', 'cg', 'ck', 'cr', 'ci', 'hr', 'cu', 'cw', 'cy', 'cz', 'dk', 'dj', 'dm', 'do', 'ec', 'eg', 'sv', 'gq', 'er', 'ee', 'et', 'fk', 'fo', 'fj', 'fi', 'gf', 'pf', 'ga', 'gm', 'ge', 'gh', 'gi', 'gr', 'gl', 'gd', 'gp', 'gu', 'gt', 'gg', 'gn', 'gw', 'gy', 'ht', 'hn', 'hk', 'hu', 'is', 'in', 'id', 'ir', 'iq', 'ie', 'im', 'il', 'jm', 'jp', 'je', 'jo', 'kz', 'ke', 'ki', 'xk', 'kw', 'kg', 'la', 'lv', 'lb', 'ls', 'lr', 'ly', 'li', 'lt', 'lu', 'mo', 'mk', 'mg', 'mw', 'my', 'mv', 'ml', 'mt', 'mh', 'mq', 'mr', 'mu', 'yt', 'mx', 'fm', 'md', 'mc', 'mn', 'me', 'ms', 'ma', 'mz', 'mm', 'na', 'nr', 'np', 'nl', 'nc', 'nz', 'ni', 'ne', 'ng', 'nu', 'nf', 'kp',
        ];
    }



      /**
     * Get all langauge code list
     *
     * @return array
     */
    private function getLanguageCodeLists(): array{
        return [
            'aa', 'ab', 'ae', 'af', 'ak', 'am', 'an', 'ar', 'as', 'av', 'ay', 'az', 'ba', 'be', 'bg', 'bh', 'bi', 'bm', 'bn', 'bo', 'br', 'bs', 'ca', 'ce', 'ch', 'co', 'cr', 'cs', 'cu', 'cv', 'cy', 'da', 'de', 'dv', 'dz', 'ee', 'el', 'en', 'eo', 'es', 'et', 'eu', 'fa', 'ff', 'fi', 'fj', 'fo', 'fr', 'fy', 'ga', 'gd', 'gl', 'gn', 'gu', 'gv', 'ha', 'he', 'hi', 'ho', 'hr', 'ht', 'hu', 'hy', 'hz', 'ia', 'id', 'ie', 'ig', 'ii', 'ik', 'io', 'is', 'it', 'iu', 'ja', 'jv', 'ka', 'kg', 'ki', 'kj', 'kk', 'kl', 'km', 'kn', 'ko', 'kr', 'ks', 'ku', 'kv', 'kw', 'ky', 'la', 'lb', 'lg', 'li', 'ln', 'lo', 'lt', 'lu', 'lv', 'mg', 'mh', 'mi', 'mk', 'ml', 'mn', 'mr', 'ms', 'mt', 'my', 'na', 'nb', 'nd', 'ne', 'ng', 'nl', 'nn', 'no', 'nr', 'nv', 'ny', 'oc', 'oj', 'om', 'or', 'os', 'pa', 'pi', 'pl', 'ps', 'pt', 'qu', 'rm', 'rn', 'ro', 'ru', 'rw', 'sa', 'sc', 'sd', 'se', 'sg', 'si', 'sk', 'sl', 'sm', 'sn', 'so', 'sq', 'sr', 'ss', 'st', 'su', 'sv', 'sw', 'ta', 'te', 'tg', 'th', 'ti', 'tk', 'tl', 'tn', 'to', 'tr', 'ts', 'tt', 'tw', 'ty', 'ug', 'uk', 'ur', 'uz', 've', 'vi', 'vo', 'wa', 'wo', 'xh', 'yi', 'yo', 'za', 'zh', 'zu',
        ];
    }


    /**
     * Get languages from row
     *
     * @param array $row
     * @return void
     */
    private function getOtherLanguages($row) {
        if($row['other_languages']) {
            $lists = explode(',', $row['other_languages']);
            if(count($lists)){
                return $lists;
            }
        }

        return null;
    }
}
