<?php

use App\Models\InternalUser;
use Illuminate\Http\Request;
use App\Models\OrganizationElement;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetNextPrefixId;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Web\Broker\BrokerController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Web\Campaign\CampaignController;
use App\Http\Controllers\Api\Web\Languages\LanguageController;
use App\Http\Controllers\Api\Web\Dashboard\DashboardController;
use App\Http\Controllers\Api\Web\Termin\DetailTerminController;
use App\Http\Controllers\Api\Web\Termin\GetTerminDataController;
use App\Http\Controllers\Api\Intermediary\IntermediaryController;
use App\Http\Controllers\Api\Web\BrokerUser\BrokerUserController;
use App\Http\Controllers\Api\Web\Competences\CompetenceController;
use App\Http\Controllers\Api\Web\CompanyRole\CompanyRoleController;
use App\Http\Controllers\Api\Web\Termin\TerminAllocationController;

use App\Http\Controllers\Api\Web\InternalUser\InternalUserController;
use App\Http\Controllers\Api\Web\WorkflowSettings\WorkflowController;

use App\Http\Controllers\Api\Web\WorkflowSettings\StepsAndConstController;
use App\Http\Controllers\Api\Web\CustomerCompany\CustomerCompanyController;
use App\Http\Controllers\Api\Web\Termin\GetTerminFilterOptionDataController;
use App\Http\Controllers\Api\Web\OrganizationElement\HierarchyElementController;
use App\Http\Controllers\Api\Web\ContactDataRecords\Termin\AppointmentController;
use App\Http\Controllers\Api\Web\OrganizationElement\OrganizationElementController;
use App\Http\Controllers\Api\Web\ContactDataRecords\EditContactDataRecordController;
use App\Http\Controllers\Api\Web\CustomerCompanyAdmin\CustomerCompanyAdminController;

use App\Http\Controllers\Api\Web\ContactDataRecords\ContactDataRecordStatusController;
use App\Http\Controllers\Api\Web\ContactDataRecords\CreateContactDataRecordController;
use App\Http\Controllers\Api\Web\ContactDataRecords\DetailContactDataRecordController;
use App\Http\Controllers\Api\Web\ContactDataRecords\ExportContactDataRecordController;
use App\Http\Controllers\Api\Web\ContactDataRecords\ImportContactDataRecordController;
use App\Http\Controllers\Api\Web\ContactDataRecords\All\GetAllContactDataRecordController;
use App\Http\Controllers\Api\Web\ContactDataRecords\ContactDataRecordAllocationController;
use App\Http\Controllers\Api\Web\ContactDataRecords\ContactDataRecordSetLeadAgainController;
use App\Http\Controllers\Api\Web\ContactDataRecords\ContactDataRecordDuplicateCheckController;
use App\Http\Controllers\Api\Web\ContactDataRecords\Leads\GetLeadsContactDataRecordController;
use App\Http\Controllers\Api\Web\ContactDataRecords\Termin\GetTerminContactDataRecordController;
use App\Http\Controllers\Api\Web\ContactDataRecords\All\GetContactDataRecordAllFilterOptionDataController;
use App\Http\Controllers\Api\Web\ContactDataRecords\ContactDataRecordSetAppointmentLeadController;
use App\Http\Controllers\Api\Web\ContactDataRecords\Leads\GetContactDataRecordLeadsFilterOptionDataController;
use App\Http\Controllers\Api\Web\ContactDataRecords\Termin\GetContactDataRecordTerminFilterOptionDataController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::middleware('guest')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::post('password/reset', [ForgotPasswordController::class, 'reset'])->name('password.reset');
        Route::post('token-validate', [ForgotPasswordController::class, 'tokenValidation'])->name('token.validate');
        Route::post('token-validate-email-change', [CustomerCompanyAdminController::class, 'tokenValidation'])->name('token.validate-email');
        Route::post('customer-company-admins/accept-invitation', [CustomerCompanyAdminController::class, 'acceptInvitaion'])->name('customer-company-admins.accept-invitaion');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('next-prefix-id/{modelName}', GetNextPrefixId::class)->name('next-prefix-id');

        Route::get('/user', [AuthController::class, 'getUser'])->name('user');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::apiResource('customer-companies', CustomerCompanyController::class)->except('destroy');
        Route::get('customer-companies-get-filters', [CustomerCompanyController::class, 'getFilterData'])->name('customer-companies.get-filters');
        Route::put('customer-companies/{customer_company}/update-status', [CustomerCompanyController::class, 'updateStatus'])->name('customer-companies.update-status');
        Route::get('customer-companies-get-list', [CustomerCompanyController::class, 'getCustomerCompany'])->name('customer-companies.get-list');

        //========Hierarchy Element========
        Route::apiResource('hierarchy-elements', HierarchyElementController::class)->except('destroy');
        Route::put('hierarchy-elements/{hierarchy_element}/update-status', [HierarchyElementController::class, 'updateStatus'])->name('hierarchy-elements.update-status');
        Route::get('hierarchy_none', [HierarchyElementController::class, 'getHierarchyNone'])->name('hierarchy-lavel-none.organization-elements');
        Route::get('hierarchy_level', [HierarchyElementController::class, 'getHierarchy'])->name('hierarchy-element.organization-elements');
        Route::get('hierarchy_level_none', [HierarchyElementController::class, 'getHierarchyNoneInternal'])->name('hierarchy-none.organization-elements');

        //Customer company admins
        Route::get('customer-company-admins/customer-company', [CustomerCompanyAdminController::class, 'getCustomerCompanyOfCurrentCustomerCompanyAdmin'])->name('customer-company-admins.customer-company');
        Route::apiResource('customer-company-admins', CustomerCompanyAdminController::class)->only('index', 'store', 'update', 'show');
        Route::get('customer-company-admins-get-filters', [CustomerCompanyAdminController::class, 'getFilterData'])->name('customer-company-admins.get-filters');
        Route::put('customer-company-admins/{customer_company_admin}/update-status', [CustomerCompanyAdminController::class, 'updateStatus'])->name('customer-company-admins.update-status');
        Route::get('customer-company-admin-id', [CustomerCompanyAdminController::class, 'getCustomerCompanyAdminId'])->name('customer-company-admin.next-prefix-id');
        Route::post('/customer-company-admin/{user}/send-invitation-email', [CustomerCompanyAdminController::class, 'sendInvitationEmail'])->name('customer-company-admins.send-invitation-email');

        //Broker
        Route::apiResource('brokers', BrokerController::class);
        Route::get('brokers-get-filters', [BrokerController::class, 'getFilterData'])->name('brokers.get-filters');
        Route::put('brokers/{broker}/update-status', [BrokerController::class, 'updateStatus'])->name('brokers.update-status');
        Route::get('brokers-get-list', [BrokerController::class, 'getBroker'])->name('brokers.get-list');
        Route::get('brokers-get-prefixId', [BrokerController::class, 'getPrefixId'])->name('brokers.get-prefixId');



        Route::get('fetch-brokers', [BrokerController::class, 'fetchBrokers'])->name('brokers.fetch');

        //Broker User
        Route::apiResource('broker-users', BrokerUserController::class);
        Route::get('broker-users-get-filters', [BrokerUserController::class, 'getFilterData'])->name('brokerUsers.get-filters');
        Route::put('broker-users/{broker_user}/update-status', [BrokerUserController::class, 'updateStatus'])->name('broker-users.update-status');
        Route::get('broker-users-get-prefixId', [BrokerUserController::class, 'getPrefixId'])->name('broker-users.get-prefixId');
        Route::post('/broker-user/{user}/send-invitation-email', [BrokerUserController::class, 'sendInvitationEmail'])->name('broker_users.send-invitation-email');

        Route::apiResource('intermediaries', IntermediaryController::class);
        Route::get('intermediaries-get-filters', [IntermediaryController::class, 'getFilterData'])->name('intermediaries.get-filters');
        Route::put('intermediaries/{intermediary}/update-status', [IntermediaryController::class, 'updateStatus'])->name('intermediaries.update-status');
        Route::post('/intermediary/{user}/send-invitation-email', [IntermediaryController::class, 'sendInvitationEmail'])->name('intermediaries.send-invitation-email');

        //organization elements
        Route::apiResource('organization-elements', OrganizationElementController::class)->except('destroy');
        Route::put('organization-elements/{organization_element}/update-status', [OrganizationElementController::class, 'updateStatus'])->name('organization-elements.update-status');
        Route::get('organization-elements-get-filters', [OrganizationElementController::class, 'getFilterData'])->name('organization-elements.get-filters');
        Route::get('organization-element-with-parent/{hierachy_id}', [OrganizationElementController::class, 'organizationElementsByHierarchyId'])->name('hierarchy.organization-elements-with-parent');
        Route::get('organization-element-all', [OrganizationElementController::class, 'getAllOrganizationList'])->name('organization-elements.all');

        //====Internal Users====
        Route::apiResource('internal-users', InternalUserController::class)->only('index', 'store', 'show', 'update');
        Route::get('hierarchy/{hierarchy_element}/internal-users-list', [InternalUserController::class, 'internalUsersListByHierarchy'])->name('hierarchy.internal-users')->can('create', OrganizationElement::class);
        Route::get('hierarchy/{role_id}/role-type', [InternalUserController::class, 'getHierarchyByRole'])->name('hierarchy.organization-elements');
        Route::get('internal-users-get-filters', [InternalUserController::class, 'getFilterData'])->name('internal-users.get-filters');
        Route::put('internal-users/{internal_users}/update-status', [InternalUserController::class, 'updateStatus'])->name('internal-users.update-status');
        Route::post('/internal-users/{user}/send-invitation-email', [InternalUserController::class, 'sendInvitationEmail'])->name('internal-users.send-invitation-email');
        Route::get('/internal-users-active-inactive', [InternalUserController::class, 'getInterUserActiveInactive'])->name('internal-users.active-inactive');

        //Company roles
        Route::apiResource('company-role', CompanyRoleController::class)->only('index');;

        //campaign
        Route::apiResource('campaign', CampaignController::class)->only('index');

        //language
        Route::apiResource('language', LanguageController::class)->only('index');



        //==========Contact Data Records==================
        //Leads tab
        Route::get('contact-data-records', [GetLeadsContactDataRecordController::class, 'index'])->name('contact-data-records.index');
        Route::get('contact-data-records-leads-filter-options', [GetContactDataRecordLeadsFilterOptionDataController::class, 'index'])->name('contact-data-records.leads.get-filters');

        //Termin tab
        Route::get('contact-data-records-termin', [GetTerminContactDataRecordController::class, 'index'])->name('contact-data-records.termin.index');
        Route::get('contact-data-records-termin-filter-options', [GetContactDataRecordTerminFilterOptionDataController::class, 'index'])->name('contact-data-records.termin.get-filters');

        //All tab
        Route::get('contact-data-records-all', [GetAllContactDataRecordController::class, 'index'])->name('contact-data-records.all.index');
        Route::get('contact-data-records-all-filter-options', [GetContactDataRecordAllFilterOptionDataController::class, 'index'])->name('contact-data-records.all.get-filters');





        Route::post('contact-data-records', [CreateContactDataRecordController::class, 'store'])->name('contact-data-records.store');
        Route::put('contact-data-records/{contact_data_record}', EditContactDataRecordController::class)->name('contact-data-records.update');
        Route::get('contact-data-records/get-create-options-data', [CreateContactDataRecordController::class, 'getCreateOptionsData'])->name('contact-data-records.get-create-options.data');
        Route::get('contact-data-records/{contact_data_record}', [DetailContactDataRecordController::class, 'show'])->name('contact-data-records.show');
        Route::get('contact-data-records/{contact_data_record}/edit', [DetailContactDataRecordController::class, 'edit'])->name('contact-data-records.edit');
        Route::get('contact-data-records/{contact_data_record}/history', [DetailContactDataRecordController::class, 'history'])->name('contact-data-records.history');
        Route::put('contact-data-records/{contact_data_record}/update-status', ContactDataRecordStatusController::class)->name('contact-data-records.update-status');
        Route::put('contact-data-records/{contact_data_record}/duplicate-check', [ContactDataRecordDuplicateCheckController::class, 'duplicateCheckStatusUpdate'])->name('contact-data-records.duplicate-check');

        //appoientments
        Route::post('appointments/{contact_data_record}', [AppointmentController::class, 'store'])->name('appointment.store');

        //Contact Data Records allocation
        Route::get('contact-data-records-allocation-get-options-data', [ContactDataRecordAllocationController::class, 'getOptionData'])->name('contact-data-records.allocation.get-options-data');
        Route::post('contact-data-records-allocation', [ContactDataRecordAllocationController::class, 'store'])->name('contact-data-records.allocation.store');

        //Contact data records set as lead again
        Route::post('contact-data-records-set-lead-again', [ContactDataRecordSetLeadAgainController::class, 'update'])->name('contact-data-records.set-lead-again');
        Route::post('contact-data-records-set-appointment-lead', ContactDataRecordSetAppointmentLeadController::class)->name('contact-data-records.set-appointment-lead');

        // Route::post('contact-data-records-exports-email', [ExportContactDataRecordController::class, 'exportToEmail'])->name('contact-data-records.exports.email');
        // Route::post('contact-data-records-exports-file', [ExportContactDataRecordController::class, 'exportToFile'])->name('contact-data-records.exports.file');
        Route::post('contact-data-records-exports-email', [ExportContactDataRecordController::class, 'exportToEmail'])->name('contact-data-records.exports.email');
        Route::post('contact-data-records-exports-file', [ExportContactDataRecordController::class, 'exportToFile'])->name('contact-data-records.exports.file');

        Route::post('contact-data-records-imports', [ImportContactDataRecordController::class, 'store'])->name('contact-data-records.imports');

        //Termin
        Route::get('termins', [GetTerminDataController::class, 'index'])->name('termin.index');
        Route::get('termins/{contact_data_record}', [DetailTerminController::class, 'show'])->name('termin.show');
        Route::get('termins-get-filter-options', [GetTerminFilterOptionDataController::class, 'index'])->name('termins.get-filters');
        Route::get('termins-allocation-get-options-data', [TerminAllocationController::class, 'getOptionData'])->name('termins.allocation.get-options-data');
        Route::post('termins-allocation', [TerminAllocationController::class, 'store'])->name('termins.allocation.store');



        //======Competence========
        Route::apiResource('competence', CompetenceController::class)->only('destroy');

        //=========Workflow Settings=========
        Route::get('workflow-settings', [StepsAndConstController::class, 'show'])->name('workflow-settings');
        Route::post('workflow-settings', [StepsAndConstController::class, 'save'])->name('workflow-settings.save');

        //=========Continue Workflow=========
        Route::post('continue-workflow/{contact_data_record}', WorkflowController::class)->name('continue-workflow');

        //=========Dashboard============
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    });
});
