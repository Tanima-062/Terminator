<?php


namespace App\Traits;

use App\Models\BrokerUser;
use App\Models\CompanyRole;
use App\Models\User;

trait HasPermission
{

    protected $permissions = array(
        'system_admin' => array(
            'customer-companye:viewAny',
            'customer-companye:view',
            'customer-companye:edit',
            // 'organization-element:view',
            'hierarchy-element:view',
            'hierarchy-element:edit',
            'customer-company-admin:view',
            'customer-company-admin:edit',
            // 'broker:view',
            // 'broker-user:view',
            // 'contact-data-record:view',
        ),
        'company_admin' => [
            'customer-companye:view',
            // 'organization-element:view',
            // 'organization-element:edit',
            'hierarchy-element:view',
            'internal-user:create',
            'internal-user:edit',
            'internal-user:view',
            'broker:view',
            'broker:edit',
            'broker-user:view',
            'broker-user:edit',
            'contact-data-record:view',
            'contact-data-record:edit',
            'contact-data-record:import',
            'contact-data-record:export',
            'contact-data-record:allocation',
            'contact-data-record:lead-again',


            'contact-data-record:lead-view',
            'contact-data-record:termin-view',
            'contact-data-record:all-view',

            'workflow-settings',
            'intermediares.view',
            'intermediares.create',
            'intermediares.edit',
            'dashboard-view'
        ],
        'internal_user' => [
            'dashboard-view',
            'customer-companye:view',
            // 'contact-data-record:lead-view',
            // 'contact-data-record:termin-view',
            // 'contact-data-record:view'
        ],
        'broker_user'   => [
            'customer-companye:view'
        ]
    );

    public function getAllPermissions()
    {
        if ($this->type == User::CUSTOMER_COMPANY_ADMIN) {
            $customer_company = $this->customerCompany;
            if($customer_company->hierarchy_elements_required) {
                $this->permissions[$this->type][] = 'organization-element:view';
                $this->permissions[$this->type][] = 'organization-element:edit';
            }

        }
        if ($this->type == 'internal_user') {
            //'internal-user:view',
            $internal_user = $this->internalUser;


            if ($internal_user->companyRole->name == CompanyRole::LEADER || $internal_user->companyRole->name == CompanyRole::MANAGER) {
                $this->permissions[$this->type][] = 'internal-user:view';
                $this->permissions[$this->type][] = 'contact-data-record:view';
                $this->permissions[$this->type][] = 'contact-data-record:lead-view';
                $this->permissions[$this->type][] = 'contact-data-record:termin-view';
                $this->permissions[$this->type][] = 'contact-data-record:all-view';
                $this->permissions[$this->type][] = 'contact-data-record:edit';
                $this->permissions[$this->type][] = 'contact-data-record:import';
                $this->permissions[$this->type][] = 'contact-data-record:export';
                $this->permissions[$this->type][] = 'contact-data-record:allocation';
                $this->permissions[$this->type][] = 'contact-data-record:lead-again';

            //     'contact-data-record:edit',
            // 'contact-data-record:import',
            }

            if ($internal_user->companyRole->name == CompanyRole::CALL_AGENT) {
                $this->permissions[$this->type][] = 'contact-data-record:view';
                $this->permissions[$this->type][] = 'contact-data-record:edit';
                // $this->permissions[$this->type][] = 'contact-data-record:import';

               $alignment_user = $this->alignmentUser;

              if($alignment_user->count()){
                $alignment_ids = $alignment_user->pluck('alignment_id')->toArray();

                //allignment Lead
                if(in_array(1, $alignment_ids)) {
                    $this->permissions[$this->type][] = 'contact-data-record:lead-view';
                }
                //allignment Termin
                if(in_array(2, $alignment_ids)) {
                    $this->permissions[$this->type][] = 'contact-data-record:termin-view';
                    $this->permissions[$this->type][] = 'contact-data-record:lead-view';
                }
              }
            }

            if ($internal_user->companyRole->name == CompanyRole::QUALITY_CONTROLLER) {
                $this->permissions[$this->type][] = 'contact-data-record:view';
                $this->permissions[$this->type][] = 'contact-data-record:edit';
                // $this->permissions[$this->type][] = 'contact-data-record:import';

               $alignment_user = $this->alignmentUser;

              if($alignment_user->count()){
                $alignment_ids = $alignment_user->pluck('alignment_id')->toArray();

                //allignment Lead
                if(in_array(1, $alignment_ids)) {
                    $this->permissions[$this->type][] = 'contact-data-record:lead-view';
                }
                //allignment Termin
                if(in_array(2, $alignment_ids)) {
                    $this->permissions[$this->type][] = 'contact-data-record:termin-view';
                }
              }
            }


            if ($internal_user->companyRole->name == 'Quality controller') {
                $this->permissions[$this->type][] = 'contact-data-record:view';
            }
        }

        if ($this->type == 'broker_user') {
            $broker_user = $this->brokerUser;
            //role
            if ($broker_user->role == BrokerUser::ADMIN) {
                $this->permissions[$this->type] =  [
                    'dashboard-view',
                    'intermediares.view',
                    'intermediares.create',
                    'intermediares.edit',
                    'termin.view',
                    'termin.edit',
                ];
            }
            if ($broker_user->role == BrokerUser::INTERMEDIARY) {
                $this->permissions[$this->type] =  [
                    // 'dashboard-view',
                    'termin.view',
                    'termin.edit',
                    // 'termin.edit',
                ];
            }
        }

        return $this->permissions[$this->type];
    }

    public function canAccess($permission, $arguments = [])
    {
        $this->getAllPermissions();

        return in_array($permission, $this->permissions[$this->type]);
        //return false;
    }
}
