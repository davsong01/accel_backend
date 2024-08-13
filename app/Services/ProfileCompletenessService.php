<?php

namespace App\Services;

use App\Models\Staff;

class ProfileCompletenessService
{
    public $weights = [];
    public $completeness = [];
    public $requiredDocumentNames = [];

    public function __construct()
    {
        // Define section weights
        $this->weights = [
            'personal_details' => 20,
            'job_information' => 15,
            'additional_information' => 20,
            'profile_picture' => 10,
            'bank_account_details' => 10,
            'next_of_kin_information' => 10,
            'documents' => 15,
        ];
        $this->completeness = [];
    }

    public function setRequiredDocumentTypes(
        array $requiredDocumentNames=[
            'NYSC',
            'Drivers Licence'
        ]
    ){
        // Define required document names
        $this->requiredDocumentNames = $requiredDocumentNames;
        
    }

    public function calculateProfileCompleteness(Staff $profile, array $requiredDocumentNames): int
    {
        $this->setRequiredDocumentTypes($requiredDocumentNames);
        // Calculate section completeness
        $this->completeness = [
            'personal_details' => $this->calculateSectionCompleteness([
                isset($profile->user->first_name) ? $profile->user->first_name : null,
                isset($profile->user->last_name) ? $profile->user->last_name : null,
                isset($profile->user->username) ? $profile->user->username : null,
                isset($profile->user->email) ? $profile->user->email : null,
                isset($profile->user->phone_number) ? $profile->user->phone_number : null,
                isset($profile->user->gender) ? $profile->user->gender : null,
            ]),
            'job_information' => $this->calculateSectionCompleteness([
                isset($profile->user->staff_id) ? $profile->user->staff_id : null,
                isset($profile->date_of_employment) ? $profile->date_of_employment : null,
                isset($profile->branch_id) ? $profile->branch_id : null,
                isset($profile->department_id) ? $profile->department_id : null,
                isset($profile->jobtype_id) ? $profile->jobtype_id : null,
                isset($profile->job_title_id) ? $profile->job_title_id : null,
                isset($profile->commission_id) ? $profile->commission_id : null,
            ]),
            'additional_information' => $this->calculateSectionCompleteness([
                isset($profile->user->date_of_birth) ? $profile->user->date_of_birth : null,
                isset($profile->user->marital_status) ? $profile->user->marital_status : null,
                isset($profile->user->middle_name) ? $profile->user->middle_name : null,
                isset($profile->user->country_id) ? $profile->user->country_id : null,
                isset($profile->user->state_id) ? $profile->user->state_id : null,
                isset($profile->user->lga_id) ? $profile->user->lga_id : null,
                isset($profile->user->residential_address) ? $profile->user->residential_address : null,
            ]),
            'profile_picture' => isset($profile->user->avatar) ? 1 : 0,
            'bank_account_details' => $this->calculateSectionCompleteness([
                isset($profile->bank->name) ? $profile->bank->name : null,
                isset($profile->account_number) ? $profile->account_number : null,
                isset($profile->account_name) ? $profile->account_name : null,
                isset($profile->sort_code) ? $profile->sort_code : null,
                isset($profile->swift_code) ? $profile->swift_code : null,
                isset($profile->nhf_number) ? $profile->nhf_number : null,
                isset($profile->pfa_id) ? $profile->pfa_id : null,
                isset($profile->pfa->pfa_code) ? $profile->pfa->pfa_code : null,
            ]),
            'next_of_kin_information' => $this->calculateSectionCompleteness([
                isset($profile->user->next_of_kin["nok_first_name"]) ? $profile->user->next_of_kin["nok_first_name"] : null,
                isset($profile->user->next_of_kin["nok_last_name"]) ? $profile->user->next_of_kin["nok_last_name"] : null,
                isset($profile->user->next_of_kin["nok_phone_number"]) ? $profile->user->next_of_kin["nok_phone_number"] : null,
                isset($profile->user->next_of_kin["nok_email_address"]) ? $profile->user->next_of_kin["nok_email_address"] : null,
                isset($profile->user->next_of_kin["nok_house_address"]) ? $profile->user->next_of_kin["nok_house_address"] : null,
                isset($profile->user->next_of_kin["nok_relationship"]) ? $profile->user->next_of_kin["nok_relationship"] : null,
            ]),
            'documents' => $this->calculateDocumentCompleteness($profile->staff_documents->toArray()),
        ];

        // Calculate total completeness
        $totalCompleteness = 0;
        // dd($this->completeness);
        foreach ($this->weights as $section => $weight) {
            $sectionCompleteness = $this->calculateSectionPercentage($this->completeness[$section], $this->getFieldCount($section));
            $totalCompleteness += $sectionCompleteness * ($weight / 100);
        }

        return round($totalCompleteness);
    }

    private function calculateSectionCompleteness(array $fields): int
    {
        $filledFields = array_filter($fields, function ($field) {
            return !empty($field);
        });

        return count($filledFields);
    }

    private function calculateDocumentCompleteness(array $documents): int
    {
        // Extract document names from the uploaded documents
        $uploadedDocumentNames = array_map(function ($doc) {
            return $doc['name']; 
        }, $documents);

        // Check if all required document names are present
        $requiredDocumentsCount = count(array_intersect($this->requiredDocumentNames, $uploadedDocumentNames));

        // Return 1 if all required documents are present, otherwise 0
        return $requiredDocumentsCount;
    }

    private function getFieldCount(string $section): int
    {
        $fieldCounts = [
            'personal_details' => 6,
            'job_information' => 7,
            'additional_information' => 7,
            'profile_picture' => 1,
            'bank_account_details' => 8,
            'next_of_kin_information' => 6,
            'documents' => count($this->requiredDocumentNames),
        ];

        return $fieldCounts[$section] ?? 0;
    }

    private function calculateSectionPercentage(int $filledFields, int $totalFields): float
    {
        return ($totalFields > 0) ? ($filledFields / $totalFields) * 100 : 0;
    }
}
