<?php

namespace App\Traits\ContactDataRecords;


trait DropdownOption
{

    public static $source_lists = [
        ['label'        =>  'online', 'value' =>  'online'],
        ['label'        =>  'not_online', 'value' =>  'not_online'],
    ];
    public static $category_lists = [
        ['label'        =>  'lead', 'value' =>  'lead'],
        ['label'        =>  'termination_appointment', 'value' =>  'termination_appointment'],
        ['label'        =>  'no_potential', 'value' =>  'no_potential'],
        ['label'        =>  'future_lead', 'value' =>  'future_lead'],
        ['label'        =>  'termination_lead', 'value' =>  'termination_lead'],
        ['label'        =>  'duplicate', 'value' =>  'duplicate'],
        ['label'        =>  'lead_again', 'value' =>  'lead_again'],
        ['label'        =>  'quality_topic', 'value' =>  'quality_topic'],
        ['label'        =>  'Appointment', 'value' =>  'Appointment'],
    ];
    // public $campaign_lists = [];
    public static $salutation_lists = [
        ['value' => 'Ms', 'label' => "Ms"],
        ['value' => 'Mr', 'label' => "Mr"],
        ['value' => 'Divers', 'label' => "Divers"],
    ];
    public static $canton_lists = [
        ['value' => 'Aargau', 'label' => "Aargau"],
        ['value' => 'Appenzell A.Rh.', 'label' => "Appenzell A.Rh."],
        ['value' => 'Appenzell I.Rh.', 'label' => "Appenzell I.Rh."],
        ['value' => 'Basel-Landschaft', 'label' => "Basel-Landschaft"],
        ['value' => 'Basel-Stadt', 'label' => "Basel-Stadt"],
        ['value' => 'Bern', 'label' => "Bern"],
        ['value' => 'Freiburg', 'label' => "Freiburg"],
        ['value' => 'Genf', 'label' => "Genf"],
        ['value' => 'Glarus', 'label' => "Glarus"],
        ['value' => 'Graubünden', 'label' => "Graubünden"],
        ['value' => 'Jura', 'label' => "Jura"],
        ['value' => 'Luzern', 'label' => "Luzern"],
        ['value' => 'Neuenburg', 'label' => "Neuenburg"],
        ['value' => 'Nidwalden', 'label' => "Nidwalden"],
        ['value' => 'Obwalden', 'label' => "Obwalden"],
        ['value' => 'Schaffhausen', 'label' => "Schaffhausen"],
        ['value' => 'Schwyz', 'label' => "Schwyz"],
        ['value' => 'Solothurn', 'label' => "Solothurn"],
        ['value' => 'St. Gallen', 'label' => "St. Gallen"],
        ['value' => 'Tessin', 'label' => "Tessin"],
        ['value' => 'Thurgau', 'label' => "Thurgau"],
        ['value' => 'Uri', 'label' => "Uri"],
        ['value' => 'Waadt', 'label' => "Waadt"],
        ['value' => 'Wallis', 'label' => "Wallis"],
        ['value' => 'Zug', 'label' => "Zug"],
        ['value' => 'Zürich', 'label' => "Zürich"],
    ];
    public static $region_lists = [
        ["value"    => "Deutschschweiz (Deutsch)", "label" => "Deutschschweiz (Deutsch)"],
        ["value"    => "Tessin (Italienisch)", "label" => "Tessin (Italienisch)"],
        ["value"    => "Westschweiz (Französisch)", "label" => "Westschweiz (Französisch)"],
        ["value"    => "Österreich", "label" => "Österreich"],
        ["value"    => "Deutschland", "label" => "Deutschland"],
    ];
    public static $car_insurance_lists = [
        ["value" => "No_Information", "label" => "No_Information"],
        ["value" => "Allianz", "label" => "Allianz"],
        ["value" => "Axa", "label" => "Axa"],
        ["value" => "Baloise", "label" => "Baloise"],
        ["value" => "Die Mobiliar", "label" => "Die Mobiliar"],
        ["value" => "Elvia", "label" => "Elvia"],
        ["value" => "Generali", "label" => "Generali"],
        ["value" => "Helvetia", "label" => "Helvetia"],
        ["value" => "Vaudoise", "label" => "Vaudoise"],
    ];
    public static $third_piller_lists = [
        ["value" => "No_Information", "label" => "No_Information"],
        ["value" => "Allianz", "label" => "Allianz"],
        ["value" => "Axa", "label" => "Axa"],
        ["value" => "Baloise", "label" => "Baloise"],
        ["value" => "Die Mobiliar", "label" => "Die Mobiliar"],
        ["value" => "Elvia", "label" => "Elvia"],
        ["value" => "Generali", "label" => "Generali"],
        ["value" => "Helvetia", "label" => "Helvetia"],
        ["value" => "Vaudoise", "label" => "Vaudoise"],
    ];
    public static $household_good_lists = [
        ["value" => "No_Information", "label" => "No_Information"],
        ["value" => "Allianz", "label" => "Allianz"],
        ["value" => "Axa", "label" => "Axa"],
        ["value" => "Baloise", "label" => "Baloise"],
        ["value" => "Die Mobiliar", "label" => "Die Mobiliar"],
        ["value" => "Elvia", "label" => "Elvia"],
        ["value" => "Generali", "label" => "Generali"],
        ["value" => "Helvetia", "label" => "Helvetia"],
        ["value" => "Vaudoise", "label" => "Vaudoise"],
    ];
    public static $legal_protection_lists = [
        ["value" => "No_Information", "label" => "No_Information"],
        ["value" => "Allianz", "label" => "Allianz"],
        ["value" => "Axa", "label" => "Axa"],
        ["value" => "Baloise", "label" => "Baloise"],
        ["value" => "Die Mobiliar", "label" => "Die Mobiliar"],
        ["value" => "Elvia", "label" => "Elvia"],
        ["value" => "Generali", "label" => "Generali"],
        ["value" => "Helvetia", "label" => "Helvetia"],
        ["value" => "Vaudoise", "label" => "Vaudoise"],
    ];
    public static $health_status_lists = [
        ["value"    => "Healthy", "label" => "Healthy"],
        ["value"    => "Not Healthy", "label" => "Not Healthy"],
    ];
    public static $health_insurance_lists = [
        ["value" => "No_Information", "label" => "No_Information"],
        ["value" => "Allianz", "label" => "Allianz"],
        ["value" => "Axa", "label" => "Axa"],
        ["value" => "Baloise", "label" => "Baloise"],
        ["value" => "Die Mobiliar", "label" => "Die Mobiliar"],
        ["value" => "Elvia", "label" => "Elvia"],
        ["value" => "Generali", "label" => "Generali"],
        ["value" => "Helvetia", "label" => "Helvetia"],
        ["value" => "Vaudoise", "label" => "Vaudoise"],
    ];

    public static $accident_lists = [
        ["value" => "Yes", "label" => "Yes"],
        ["value" => "No", "label" => "No"],
    ];
    public static $francise_lists = [
        ["value" => "300", "label" => "CHF 300"],
        ["value" => "500", "label" => "CHF 500"],
        ["value" => "1000", "label" => "CHF 1000"],
        ["value" => "1500", "label" => "CHF 1500"],
        ["value" => "2000", "label" => "CHF 2000"],
        ["value" => "2500", "label" => "CHF 2500"],
    ];


    public static $supplementary_insurance_lists = [
        ["value" => "No_Information", "label" => "No_Information"],
        ["value" => "Allianz", "label" => "Allianz"],
        ["value" => "Axa", "label" => "Axa"],
        ["value" => "Baloise", "label" => "Baloise"],
        ["value" => "Die Mobiliar", "label" => "Die Mobiliar"],
        ["value" => "Elvia", "label" => "Elvia"],
        ["value" => "Generali", "label" => "Generali"],
        ["value" => "Helvetia", "label" => "Helvetia"],
        ["value" => "Vaudoise", "label" => "Vaudoise"],
    ];

    public static $contact_person_for_insurance_question_lists = [
        ["value" => "Consultant", "label" => "Consultant"],
        ["value" => "Family Circle", "label" => "Family Circle"],
        ["value" => "None", "label" => "None"],
    ];
    public static $save_lists = [
        ["value" => "Yes", "label" => "Yes"],
        ["value" => "No", "label" => "No"],
        ["value" => "Partial", "label" => "Partial"],
    ];
    public static $last_health_insurance_change_lists = [
        ["value" => "6 Months ago", "label" => "6 Months ago"],
        ["value" => "1 year ago", "label" => "1 year ago"],
        ["value" => "2 years ago", "label" => "2 years ago"],
        ["value" => "3 or more years ago", "label" => "3 or more years ago"],
        ["value" => "Never", "label" => "Never"],
    ];
    public static $satisfaction_lists = [
        ["value" => "Not satisfied", "label" => "Not satisfied"],
        ["value" => "Satisfied", "label" => "Satisfied"],
    ];

    public static $number_of_persons_in_household_lists = [
        ["value" => "1", "label" => "1"],
        ["value" => "2", "label" => "2"],
        ["value" => "3", "label" => "3"],
        ["value" => "4", "label" => "4"],
        ["value" => "5", "label" => "5"],
        ["value" => "6", "label" => "6"],
        ["value" => "7", "label" => "7"],
        ["value" => "8", "label" => "8"],
        ["value" => "9", "label" => "9"],
        ["value" => "10", "label" => "10"],
        ["value" => "11", "label" => "11"],
        ["value" => "12", "label" => "12"],
        ["value" => "13", "label" => "13"],
        ["value" => "14", "label" => "14"],
        ["value" => "15", "label" => "15"],
    ];
    public static $work_activity_lists = [
        ["value" => "Yes", "label" => "Yes"],
        ["value" => "No", "label" => "No"],
        ["value" => "Temporary", "label" => "Temporary"],
    ];
    public static $desired_consultation_channel_lists = [
        ["value" => "Physically on site", "label" => "Physically on site"],
        ["value" => "Online", "label" => "Online"],
    ];
    public static $contact_desired_lists = [
        ["value" => "Yes", "label" => "Yes"],
        ["value" => "No", "label" => "No"],
    ];

    //Control status lead
    public static $lead_lists = [
        ["value" => "Not confirmed", "label" => "Not confirmed"],
        ["value" => "No Potential", "label" => "No Potential"],
        ["value" => "Future Lead", "label" => "Future Lead"],
        ["value" => "Back to Call Agent", "label" => "Back to Call Agent"],
        ["value" => "Life Insurance", "label" => "Life Insurance"],
        ["value" => "Car Insurance", "label" => "Car Insurance"],
        ["value" => "Health Insurance", "label" => "Health Insurance"],
        ["value" => "Internal", "label" => "Internal"],
        ["value" => "Not Required-Online Lead", "label" => "Not Required-Online Lead"],
        ["value" => "Confirmed", "label" => "Confirmed"],
    ];
    public static $contact_record_status_lists = [
        ["value" => "New", "label" => "New"],
        ["value" => "New-Not reached", "label" => "New-Not reached"],
        ["value" => "Check Duplicate", "label" => "Check Duplicate"],
        ["value" => "Duplicate", "label" => "Duplicate"],
        ["value" => "Appointment Entry", "label" => "Appointment Entry"],
        ["value" => "Inactive", "label" => "Inactive"],
        ["value" => "Future Lead", "label" => "Future Lead"],
        ["value" => "Completed", "label" => "Completed"],
        ["value" => "Quality Topic", "label" => "Quality Topic"],
        ["value" => "Allocated", "label" => "Allocated"],
        ["value" => "Open", "label" => "Open"],
        ["value" => "Negative completed", "label" => "Negative completed"],
        ["value" => "Positive completed", "label" => "Positive completed"],
        ["value" => "Appointment didn’t take place", "label" => "Appointment didn’t take place"],
        ["value" => "Confirmed (Reminder pending)", "label" => "Confirmed (Reminder pending)"],
        ["value" => "Not confirmed", "label" => "Not confirmed"],
        ["value" => "Confirmed", "label" => "Confirmed"],
        ["value" => "Call later", "label" => "Call later"],
        ["value" => "No Potential", "label" => "No Potential"],
        ["value" => "Rund", "label" => "Rund"],
        ["value" => "Not Reached", "label" => "Not Reached"],
        ["value" => "terminated", "label" => "terminated"],
        ["value" => "Quality topic solved", "label" => "Quality topic solved"],
        ["value" => "Confirmed &reminded", "label" => "Confirmed &reminded"],
        ["value" => "Not reached - Appointment reminder", "label" => "Not reached - Appointment reminder"],
        ["value" => "Positive concluded", "label" => "Positive concluded"],
        ["value" => "Negative concluded", "label" => "Negative concluded"],
        ["value" => "Appointment did not take place", "label" => "Appointment did not take place"],
    ];

    public static $resones = [
        ['label' => 'Not at home', 'value' => 'Not at home'],
        ['label' => 'Untraceable', 'value' => 'Untraceable'],
        ['label' => 'Not reachable', 'value' => 'Not reachable'],
        ['label' => 'Cancelled', 'value' => 'Cancelled'],
        ['label' => 'Cancellation on the part of intermediary', 'value' => 'Cancellation on the part of intermediary'],
        ['label' => 'Treatment', 'value' => 'Treatment'],
        ['label' => 'Did not want an appointment', 'value' => 'Did not want an appointment'],
        ['label' => 'Multi-year contract', 'value' => 'Multi-year contract'],
        ['label' => 'Other', 'value' => 'Other'],
    ];
}
