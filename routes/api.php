<?php

use App\Models\AccountType;
use App\Models\SpecialRequest;
use App\Models\AdvanceAndClaims;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Models\LeaveAndAbsenceSetting;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use App\Models\LeaveAndAbsenceManagement;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\EstateController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\JobRoleController;
use App\Http\Controllers\JobTypeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PlotSizeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\SelfServiceController;
use App\Http\Controllers\AccountGroupController;
use App\Http\Controllers\BuildingTypeController;
use App\Http\Controllers\CustomerFormController;
use App\Http\Controllers\EmployeeExitController;
use App\Http\Controllers\EstateSettingController;
use App\Http\Controllers\LegalDocumentController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\SpecialRequestController;
use App\Http\Controllers\AccountCategoryController;
use App\Http\Controllers\CompanyDocumentController;
use App\Http\Controllers\ContractPaymentController;
use App\Http\Controllers\TransactionTypeController;
use App\Http\Controllers\AdvanceAndClaimsController;
use App\Http\Controllers\CustomerContractController;
use App\Http\Controllers\ContractAllocationController;
use App\Http\Controllers\TransactionPurposeController;
use App\Http\Controllers\PropertyTypeDetailsController;
use App\Http\Controllers\AccountClassificationController;
use App\Http\Controllers\ConstructionPaymentController;
use App\Http\Controllers\LegalDocumentTemplateController;
use App\Http\Controllers\GeneratedLegalDocumentController;
use App\Http\Controllers\EstateConstructionSettingController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GetStartedController;
use App\Http\Controllers\GLAccountController;
use App\Http\Controllers\GLLedgerController;
use App\Http\Controllers\HCMController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\LeaveAndAbsenceManagementController;
use App\Http\Controllers\PaymentModeController;
use App\Http\Controllers\RecipientGroupController;
use App\Http\Controllers\StaffDisengagementController;
use App\Http\Controllers\TransactionTypeDefinitionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('documents/{filename}', function ($filename) {
        $filename = base64_decode($filename);
        $realpath = base_path() . '/' .  $filename;

        try {
            return response()->download($realpath);
            //code...
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'The file does not exist',
                'data' => [],
            ], 200);
        }
    });

    Route::get('uploads/{filename}', function ($filename) {
        $filename = base64_decode($filename);
    
        $realpath = base_path() . '/' .  $filename;

        try {
            return response()->download($realpath);
            //code...
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'The file does not exist',
                'data' => [],
            ], 200);
        }
    });
    
    // Route::get('generated-legal-documents/{filename}', function ($filename) {
    //     $filename = base64_decode($filename);
    //     $realpath = base_path() . '/uploads/generated-legal-documents' . '/' . $filename;
        
    //     return response()->download($realpath);
    // });
});


Route::get('domain-details/{slug}', [CompanySettingController::class, 'domainDetails']);
Route::group(['middleware' => 'throttle:60,1'], function () {
    // [...]
});
Route::group(['prefix' => 'admin', 'before' => 'admin'], function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle.login');
    Route::post('forgot-password', [ForgotPasswordController::class, 'forgot'])
    ->name('forget.password');
    Route::post('reset-password', [ForgotPasswordController::class, 'store'])
    ->name('reset.password');

    Route::post('/get-permission-to-resource', [MenuController::class, 'getPermissionToResource'])->middleware(['auth:sanctum', 'company_check']);

    Route::middleware(['auth:sanctum', 'company_check', 'otherchecks'])->group(function () {
        Route::prefix('self-service')->group(function(){
            Route::post('update-my-profile', [SelfServiceController::class, 'updateMyWorkProfile']);
        });
    });

    Route::middleware(['auth:sanctum', 'company_check', 'user_roles', 'otherchecks'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/permissions', [MenuController::class, 'linkGenerator'])->name('permissions.index');
        Route::get('/single-menu-generator/{parent_id}', [MenuController::class, 'singleLinkGenerator'])->name('single.linkgenerator');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Staff Module Routes
        // Route::post('filter-customers', [CustomerController::class, 'index']);
        Route::get('customers/total', [CustomerController::class, 'count']);
        Route::resource('customers', CustomerController::class);
        Route::get('customers-count', [CustomerController::class, 'CustomerCount']);

        Route::resource('city', CityController::class);
        Route::resource('location', LocationController::class);
        Route::get('city-locations/{city_id}', [LocationController::class, 'cityLocations'])->name('dashboard');

        Route::resource('plotsize', PlotSizeController::class);
        Route::resource('building-type', BuildingTypeController::class);
        Route::get('sample-bulk-building-type-import', [BuildingTypeController::class, 'sampleBulkBuildingType']);
        Route::post('import-bulk-building-type', [BuildingTypeController::class, 'importBulkBuildingType']);
        
        Route::get('sample-bulk-plotsizes-import', [PlotSizeController::class, 'sampleBulkPlotSize']);
        Route::post('import-bulk-plotsizes', [PlotSizeController::class, 'importBulkPlotSize']);

        Route::resource('estate', EstateController::class);
        Route::get('filter-estates', [EstateController::class, 'filterEstates']);
        Route::get('sample-bulk-estate-import', [EstateController::class, 'sampleBulkEstate']);
        Route::post('import-bulk-estate', [EstateController::class, 'importBulkEstates'])->name('import.bulk.estate');
        Route::get('change-estate-status/{id}', [EstateController::class, 'changeEstateStatus']);
        // Route::get('estate-count/{estate_id}/{search?}', [EstateController::class, 'filterPropertyCount']);
        Route::get('property_count', [EstateController::class, 'getPropertyCount']);
        Route::post('add-estate-property-type', [EstateController::class, 'addEstatePropertyType']);
        Route::post('update-estate-property-type/{id}', [EstateController::class, 'updateEstatePropertyType']);
        Route::get('change-estate-property-type-status/{id}', [EstateController::class, 'changeEstatePropertyTypeStatus']);
        Route::get('delete-estate-property-type/{id}', [EstateController::class, 'deleteEstatePropertyType']);
        Route::post('update-estate-setting', [EstateSettingController::class, 'update']);
        Route::get('get-estate-setting', [EstateSettingController::class, 'getEstateSettings']);
        Route::get('estate-in-location/{location_id}', [EstateController::class, 'getEstateInLocation']);

        Route::resource('estate-construction-setting', EstateConstructionSettingController::class);

        Route::post('update-company-profile', [CompanySettingController::class, 'updateCompanyProfile']);
        Route::get('all-staff-and-role/{staff_id?}', [CompanySettingController::class, 'staffAndRoles']);

        Route::resource('company-document', CompanyDocumentController::class);
        Route::get('company-settings', [CompanySettingController::class, 'index']);
        Route::match(['GET', 'POST', 'PUT', 'PATCH'], 'company-settings/application-settings', [CompanySettingController::class, 'applicationSettings']);
        // Route::get('get-countries-currencies/{country_id?}', [CompanySettingController::class, 'countryAnCurrencies']);

        Route::resource('roles', RoleController::class);
        // Route::get('job-roles-by-department-id/{id}', [JobRoleController::class, 'getJobRoleByDepartmentId']);

        Route::get('get-all-staff', [StaffController::class, 'getAllStaff']);
        Route::get('is-not-on-leave', [StaffController::class, 'isNotOnLeave']);
        Route::post('staff/store', [StaffController::class, 'storeStaff']);
        Route::post('update-staff', [StaffController::class, 'updateStaff']);
        Route::post('update-staff-personal-information', [StaffController::class, 'updateStaffPersonalInfo']);

        Route::get('get-active-staff', [StaffController::class, 'getActiveStaff']);
        Route::get('get-single-staff-profile/{staff_id}', [StaffController::class, 'getSingleStaffProfile']);
        Route::get('get-active-staff-in-role/{staff_id}', [StaffController::class, 'getActiveStaffInRole']);
        Route::post('assign-role-to-staff', [StaffController::class, 'assignRoleToStaff']);
        Route::get('staff-roles/{staff_id}', [StaffController::class, 'getSingleStaffRoles']);

        Route::get('staff-identification-settings', [StaffController::class, 'staffIdentificationSetting']);
        Route::post('update-staff-identification-settings', [StaffController::class, 'updateStaffIdentificationSetting']);
        Route::post('upload-staff-documents', [selfServiceController::class, 'uploadDocuments']);
        Route::get('delete-staff-document/{id}', [SelfServiceController::class, 'deleteDocument']);

        Route::get('staff-document-settings/{id?}', [StaffController::class, 'staffDocumentSetting']);
        Route::post('staff-document-setting', [StaffController::class, 'addStaffDocumentSetting']);
        Route::post('staff-document-setting/{id}', [StaffController::class, 'updateStaffDocumentSetting']);
        Route::get('delete-staff-document-settings/{id}', [StaffController::class, 'deleteStaffDocumentSetting']);

        Route::resource('special-request', SpecialRequestController::class);
        Route::resource('department', DepartmentController::class);
        Route::get('sample-bulk-department-import', [DepartmentController::class, 'sampleBulkDepartment']);
        Route::post('import-bulk-department', [DepartmentController::class, 'importBulkDepartment']);

        Route::get('leave-and-absence/{id?}', [LeaveAndAbsenceManagementController::class, 'index']);
        Route::get('single-staff-leave-and-absence/{staff_id}', [LeaveAndAbsenceManagementController::class, 'staffLeaveAndAbsence']);
        Route::post('create-leave-and-absence', [LeaveAndAbsenceManagementController::class, 'store']);
        Route::get('approve-leave-and-absence/{id}', [LeaveAndAbsenceManagementController::class, 'approveLeaveAndAbsence']);
        Route::get('decline-leave-and-absence/{id}', [LeaveAndAbsenceManagementController::class, 'declineLeaveAndAbsence']);

        Route::get('advance-and-claims/{id?}', [AdvanceAndClaimsController::class, 'index']);
        Route::get('single-staff-advance-and-claims/{staff_id}', [AdvanceAndClaimsController::class, 'staffAdvanceAndClaims']);
        Route::post('create-advance-and-claims', [AdvanceAndClaimsController::class, 'store']);
        Route::get('approve-advance-and-claims/{id}', [AdvanceAndClaimsController::class, 'approveAdvanceAndClaims']);
        Route::get('decline-advance-and-claims/{id}', [AdvanceAndClaimsController::class, 'declineAdvanceAndClaims']);

        Route::resource('branch', BranchController::class);
        Route::resource('jobrole', JobRoleController::class);
        Route::resource('jobtype', JobTypeController::class);
        Route::get('sample-bulk-jobrole-import', [JobRoleController::class, 'sampleBulkJobRole']);
        Route::post('import-bulk-jobrole', [JobRoleController::class, 'importBulkJobRole']);

        Route::post('form-expiry-status', [EstateController::class, 'changeFormExpiry']);
        Route::get('change-special-request-status', [MenuController::class, 'changeSpecialRequestStatus']);
        Route::post('add-new-contract', [CustomerContractController::class, 'store']);
        Route::post('update-single-contract/{CustomerContract}', [CustomerContractController::class, 'update']);

        Route::get('single-customer-contract/{customer_id}', [CustomerContractController::class, 'index']);
        Route::get('delete-customer-contract/{contract_id}', [CustomerContractController::class, 'destroy']);

        Route::get('customercontract/{contract_id}', [CustomerContractController::class, 'show']);
        Route::get('single-contract-payment-history/{contract_id}', [ContractPaymentController::class, 'contractPaymentHistory']);
        
        Route::get('estate-property-type', [PropertyTypeDetailsController::class, 'index']);

        Route::get('get-leads', [CustomerController::class, 'getLeads']);
        Route::get('get-prospects', [CustomerController::class, 'getProspects']);
        Route::get('get-subscribers', [CustomerController::class, 'getSubscribers']);
        Route::get('get-single-customer-details/{id}', [CustomerController::class, 'show']);
        Route::get('get-single-customer-contract-count/{customer_id}', [CustomerController::class, 'singleCustomerContractCount']);
        Route::resource('account-type', AccountTypeController::class);
        Route::get('modify-account-type/{id}', [AccountTypeController::class, 'modifyAccountType']);

        Route::resource('account-classification', AccountClassificationController::class);
        Route::resource('chart-of-account', ChartOfAccountController::class);
        Route::get('modify-achart-of-account/{id}', [ChartOfAccountController::class, 'modifyChartOfAccount']);

        Route::resource('transaction-type-definition', TransactionTypeDefinitionController::class);
        Route::resource('account', AccountController::class);

        Route::get('single-customer-form-payment-details/{customer_id}', [CustomerContractController::class, 'singleCustomerFormDetails']);
        // Submit application form 2
        Route::post('customer-form-response', [CustomerFormController::class, 'store'])->name('application.form.submit');
        
        Route::get('customer-form-response/{id?}', [CustomerFormController::class, 'index']);
        Route::get('approve-form2-request/{id?}', [CustomerFormController::class, 'approveForm2Request']);
        Route::post('decline-form2-request/{id?}', [CustomerFormController::class, 'declineForm2Request']);

        Route::get('approve-customer-form-response/{id?}', [CustomerFormController::class, 'approveCustomerFormResponse']);
        Route::post('decline-customer-form-response/{id?}', [CustomerFormController::class, 'declineCustomerFormResponse']);

        Route::get('form-payment-history/{customer_id}', [CustomerContractController::class, 'formPaymentHistory']);
        Route::post('make-form-payment', [CustomerContractController::class, 'makeFormPayment']);
        Route::get('customer-payment-history/{customer_id}', [CustomerContractController::class, 'customerPaymentHistory']);
        Route::get('get-countries', [GeneralController::class, 'getCountries']);
        Route::get('get-states', [GeneralController::class, 'getStates']);
        Route::get('get-lga', [GeneralController::class, 'getLgas']);
        Route::get('get-banks', [GeneralController::class, 'getBanks']);
        Route::get('get-pfas', [GeneralController::class, 'getPfas']);
        Route::get('get-currencies', [GeneralController::class, 'getCurrencies']);
        Route::get('nudge-customer/{customer_id}', [CustomerFormController::class, 'nudgeCustomer']);
        Route::get('nudge-admin/{form_id}', [CustomerFormController::class, 'nudgeAdmin']);

        // Contract payments
        // Route::resource('contract-payment', ContractPaymentController::class); // causing 503
        Route::post('contract-payment', [ContractPaymentController::class, 'contractPayment']);
        Route::post('setting-excavation-payment', [ContractPaymentController::class, 'makeSettingAndEscavationPayment']);
        Route::get('get-se-escavation-setting/{contract}', [ContractPaymentController::class, 'seEscavationSetting']);
        Route::post('allocate-contract', [ContractAllocationController::class, 'allocateContract']);
        Route::post('continue-payment', [ContractPaymentController::class, 'continueContractPayment']);

        Route::get('staff-deals/{staff_id}', [StaffController::class, 'staffDeals']);


        // Self Service
        Route::group(['prefix' => 'self-service', 'before' => 'self-service'], function () {
            Route::get('dashboard', [SelfServiceController::class, 'dashboard']);
            Route::get('my-profile', [SelfServiceController::class, 'myWorkProfile']);
            Route::post('add-avatar/{staff_id?}', [SelfServiceController::class, 'addAvatar']);
            Route::get('delete-avatar/{staff_id?}', [SelfServiceController::class, 'deleteAvatar']);
            Route::get('get-avatar/{staff_id}', [SelfServiceController::class, 'getAvatar']);
            Route::post('upload-staff-documents', [SelfServiceController::class, 'uploadDocuments']);
            Route::get('delete-staff-document/{id}', [SelfServiceController::class, 'deleteDocument']);
            Route::get('staff-documents', [SelfServiceController::class, 'getStaffDocument']);
            Route::get('staff-documents-by-document-setting-id/{id}', [SelfServiceController::class, 'getStaffDocumentBySettingId']);
            Route::post('send-reset-password-mail', [SelfServiceController::class, 'sendResetPasswordEmail']);
            Route::post('reset-password', [SelfServiceController::class, 'resetPassword']);
            Route::get('unread-notifications/{staff_id?}', [SelfServiceController::class, 'fetchUnreadNotifications']);
            Route::get('viewed-notifications', [SelfServiceController::class, 'fetchViewedNotifications']);
            Route::get('read-notification/{notification_id?}', [SelfServiceController::class, 'markNotificationsAsRead']);
            Route::get('view-single-notification/{notification_id}', [SelfServiceController::class, 'viewSingleNotification']);
            Route::get('all-notifications', [SelfServiceController::class, 'fetchAllNotifications']);
            Route::get('{staff_id}/{type}', [EmployeeExitController::class, 'getLeaveAndAbsencePeriodLeft']);
            Route::post('create-exit', [EmployeeExitController::class, 'store']);
            Route::get('leave-and-absence', [LeaveAndAbsenceManagementController::class, 'index']);
            // Route::get('single-leave-and-absence', [LeaveAndAbsenceManagementController::class, 'staffLeaveAndAbsence']);
            Route::get('advance-and-claims', [AdvanceAndClaimsController::class, 'index']);
            // Route::get('single-advance-and-claims', [AdvanceAndClaimsController::class, 'staffAdvanceAndClaims']);
            Route::get('my-deals', [SelfServiceController::class, 'myDeals']);


        });

        Route::post('update-leave-and-absence-setting', [LeaveAndAbsenceManagementController::class, 'updateLeaveAndAbsenceSetting']);
        Route::get('get-leave-and-absence-setting', [LeaveAndAbsenceManagementController::class, 'getLeaveAndAbsenceSetting']);

        Route::get('sample-bulk-staff-import', [StaffController::class, 'sampleBulkStaff']);
        Route::post('import-bulk-staff', [StaffController::class, 'importBulkStaff']);
        Route::get('generate-staff-id', [Controller::class, 'generateStaffId']);

        Route::get('get-staff-leave-remnant/{staff_id}/{type}', [AdvanceAndClaimsController::class, 'getLeaveAndAbsencePeriodLeft']);

        Route::resource('commission', CommissionController::class);
        Route::resource('account-category', AccountCategoryController::class);
        Route::get('modify-account-category/{id}', [AccountCategoryController::class, 'modifyAccountCategory']);

        Route::resource('account-group', AccountGroupController::class);
        Route::get('modify-account-group/{id}', [AccountGroupController::class, 'modifyAccountGroup']);
        Route::post('create-exit', [EmployeeExitController::class, 'store']);
        Route::get('exits/search', [EmployeeExitController::class, 'search']);
        Route::get('exits/{staff_id?}', [EmployeeExitController::class, 'index']);
        Route::match(['put', 'patch'],'exits/{employeeExit}', [EmployeeExitController::class, 'update']);
        Route::get('access-control-list', [MenuController::class, 'adminAccessControls']);

        Route::get('staff-documents/{staff_id}', [SelfServiceController::class, 'getStaffDocument']);
        Route::get('staff-documents-by-document-setting-id/{id}/{staff_id}', [SelfServiceController::class, 'getStaffDocumentBySettingId']);

        Route::get('generate-customer-id', [Controller::class, 'generateCustomerId']);
        Route::get('customer-identification-settings', [CustomerController::class, 'customerIdentificationSetting']);
        Route::post('update-customer-identification-settings', [CustomerController::class, 'updateCustomerIdentificationSetting']);

        Route::controller(LegalDocumentController::class)->prefix('legal-documents')->group(function () {
            Route::get('type/', 'type');
            Route::get('template/preview', 'preview');
            Route::get('template/preview/sample', 'preview')->name('template.preview.sample');
            Route::post('template', 'template');
            Route::post('generate', 'generate');
            Route::patch('{document}/regenerate', 'regenerate');
            Route::get('estate-contracts', 'estateContracts');
            Route::get('search', 'search');
        });

        Route::controller(GLLedgerController::class)->name('finance.')->prefix('finance')->group(function () {
            Route::get('gl-ledger', 'index');
            Route::post('gl-ledger/create', 'create');
            Route::get('gl-ledger/search', 'search');
            Route::get('gl-ledger/{transaction}', 'show');
        });

        Route::controller(GLAccountController::class)->name('finance.')->prefix('finance')->group(function () {
            Route::get('gl-account', 'index')->name('gl-account');
            Route::get('gl-account/search', 'search')->name('gl-account.search');
            Route::get('gl-account/{account}/transactions', 'transactions')->name('gl-account.transactions');
        });

        Route::prefix('finance')->group(function(){
            Route::get('/', [FinanceController::class, 'index']);
            Route::get('transaction/type/search', [TransactionTypeController::class, 'search']);
            Route::apiResource('transaction/type', TransactionTypeController::class);
            
            Route::get('transaction/purpose/search', [TransactionPurposeController::class, 'search']);
            Route::apiResource('transaction/purpose', TransactionPurposeController::class);
            Route::get('payment/mode/search', [PaymentModeController::class, 'search']);
            Route::apiResource('payment/mode', PaymentModeController::class);
        });
        
        Route::get('audit/log', [LogController::class, 'audit']);
        Route::get('audit/log/search', [LogController::class, 'search']);
        
        Route::prefix('hcm')->group(function(){
            Route::get('/', [HCMController::class, 'index']);
            Route::get('staffs/disengagement/search', [StaffDisengagementController::class, 'search']);
            Route::apiResource('staffs/disengagement', StaffDisengagementController::class);
            Route::post('job/settings/title/bulk', [JobTitleController::class, 'bulk']);
            Route::get('job/settings/title/bulk/sample', [JobTitleController::class, 'sample']);
            Route::apiResource('job/settings/title', JobTitleController::class);

            Route::get('job-titles-by-department-id/{id}', [JobTitleController::class, 'getJobRoleByDepartmentId']);
        });

        Route::prefix('crm')->group(function(){
            Route::prefix('settings/recipients/')->group(function(){
                Route::get('group/search', [RecipientGroupController::class, 'search']);
                Route::apiResource('group', RecipientGroupController::class);
            });
        });

        Route::prefix('rem')->group(function () {
            Route::get('generated/legal/documents', [LegalDocumentController::class, 'index']);
            Route::apiResource('construction/payments', ConstructionPaymentController::class);
        });

        Route::prefix('cities/locations')->group(function(){
            Route::get('search', [LocationController::class, 'search']);
        });

        Route::prefix('locations/estates')->group(function(){
            Route::get('search', [EstateController::class, 'search']);
        });

        route::get('my-operations', [LogController::class, 'operations']);
    });
});

Route::group(['prefix' => 'customer', 'before' => 'customer'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logoutCustomer']);

    Route::post('/validate', [AuthController::class, 'validateCustomer']);

    Route::middleware(['auth:sanctum', 'company_check'])->group(function () {
        Route::get('details/{id}', [CustomerController::class, 'show']);
        Route::post('form-response', [CustomerFormController::class, 'store']);
        Route::get('form-response', [CustomerFormController::class, 'index']);
        
        Route::get('get-countries', [GeneralController::class, 'getCountries']);
        Route::get('get-states', [GeneralController::class, 'getStates']);
        Route::get('get-lga', [GeneralController::class, 'getLgas']);
        Route::get('get-banks', [GeneralController::class, 'getBanks']);
        Route::get('get-currencies', [GeneralController::class, 'getCurrencies']);
        Route::get('get-locations', [LocationController::class, 'index']);
        Route::get('get-cities', [CityController::class, 'index']);
        Route::get('get-locations-in-city/{city_id}', [LocationController::class, 'cityLocations']);
        Route::get('get-single-city/{city}', [CityController::class, 'show']);
        Route::get('get-estates-in-location/{location_id}', [EstateController::class, 'getEstateInLocation']);
        Route::get('get-estate-property-type', [PropertyTypeDetailsController::class, 'index']);
        Route::get('get-customer-contract/{customer_id}', [CustomerContractController::class, 'getSingleCustomerContract']);
    });
});

Route::post('get-started', [GetStartedController::class, 'store']);