<?php
namespace App\Traits\ContactDataRecords;

use App\Models\ContactDataRecordFeedback;
use Illuminate\Support\Facades\Cache;

trait OrderByDirection {
    use DropdownOption;


    /**
     * Language Code order by user language
     *
     * @return void
     */
    public function getOrderLanaugeByUser()
    {
        $language_code = auth()->user()->language->code;


        $languages =  Cache::rememberForever('countries-'.$language_code, function () {
            $languages_content = file_get_contents(base_path(sprintf('node_modules/@cospired/i18n-iso-languages/langs/%s.json', auth()->user()->language->code)));

            $languages = json_decode(($languages_content), TRUE);
            $languages = $languages['languages'];

            asort($languages);

            $count = 1;
            $lang = [];
            foreach($languages as $key => $value){
                $lang[$key] =  $count;
                $count++;
            }

            return $lang;

        });

        return $languages;
    }





    /**
     * Lead order by user language
     *
     * @return void
     */
    public function getOrderLeadStatusByUser()
    {
        $language_code = auth()->user()->language->code;

        $lead_lists =  self::$lead_lists;

        $data = [];
        foreach($lead_lists as $item){
            $data[]= [
                'value'     =>  $item['value'],
                'label'     =>  trans($item['label'], [], $language_code)
            ];
        }

        $sorted =  collect($data)->sortBy('label')->toArray();

        $sorted_array = [];
        $count = 1;
        foreach($sorted as $item){
            $sorted_array[$item['value']]= $count;
            $count++;
        }

        return $sorted_array;
    }

    /**
     * Control record status order by user language
     *
     * @return void
     */
    public function getOrderControlRecordStatusByUser()
    {
        $language_code = auth()->user()->language->code;

        $lead_lists =  self::$contact_record_status_lists;

        $data = [];
        foreach($lead_lists as $item){
            $data[]= [
                'value'     =>  $item['value'],
                'label'     =>  trans($item['label'], [], $language_code)
            ];
        }

        $sorted =  collect($data)->sortBy('label')->toArray();

        $sorted_array = [];
        $count = 1;
        foreach($sorted as $item){
            $sorted_array[$item['value']]= $count;
            $count++;
        }

        return $sorted_array;
    }


    /**
     * Last feedback order by user language
     *
     * @return void
     */
    public function getOrderLastFeedbackByUser()
    {
        $language_code = auth()->user()->language->code;

        $lead_lists =  [
            ContactDataRecordFeedback::NOT_REACHED,
            ContactDataRecordFeedback::WRONG_NUMBER,
            ContactDataRecordFeedback::NO_INTEREST,
            ContactDataRecordFeedback::SICK,
            ContactDataRecordFeedback::ALREADY_TERMINATED,
            ContactDataRecordFeedback::OTHER_OFFER_RECEIVED,
            ContactDataRecordFeedback::CALL_LATER,
            ContactDataRecordFeedback::APPOINTMENT,
            ContactDataRecordFeedback::NO_POTENTIAL,
        ];

        // return $lead_lists;

        $data = [];
        foreach($lead_lists as $item){
            $data[]= [
                'label' =>  trans($item, [], $language_code),
                'value' =>  $item
            ];
        }

        // return $data;

        $sorted =  collect($data)->sortBy('label')->toArray();


        $sorted_array = [];
        $count = 1;
        foreach($sorted as $item){
            $sorted_array[$item['value']]= $count;
            $count++;
        }

        return $sorted_array;

    }
}
