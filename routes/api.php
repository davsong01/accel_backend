<?php

use App\Models\SpecialRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Models\LeaveAndAbenceManagement;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\EstateController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\JobRoleController;
use App\Http\Controllers\JobTypeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PlotSizeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\SelfServiceController;
use App\Http\Controllers\BuildingTypeController;
use App\Http\Controllers\EstateSettingController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\SpecialRequestController;
use App\Http\Controllers\CompanyDocumentController;
use App\Http\Controllers\AdvanceAndClaimsController;
use App\Http\Controllers\CustomerContractController;
use App\Http\Controllers\PropertyTypeDetailsController;
use App\Http\Controllers\AccountClassificationController;
use App\Http\Controllers\LeaveAndAbenceManagementController;
use App\Http\Controllers\EstateConstructionSettingController;
use App\Http\Controllers\TransactionTypeDefinitionController;
use App\Models\AdvanceAndClaims;

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

Route::post('/register', [AuthController::class, 'register']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('documents/{filename}', function ($filename) {
        $filename = base64_decode($filename);
        $realpath = base_path() . '/documents' . '/' . $filename;
        return response()->download($realpath);
    });
});

Route::group(['prefix' => 'admin', 'before' => 'admin'], function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'company_check', 'user_roles', 'otherchecks'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/permissions', [MenuController::class, 'linkGenerator'])->name('permissions.index');
        Route::get('/single-menu-generator/{parent_id}', [MenuController::class, 'singleLinkGenerator'])->name('single.linkgenerator');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Staff Module Routes
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
        Route::post('import-bulk-estate', [EstateController::class, 'importBulkEstates']);
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
        Route::resource('company-document', CompanyDocumentController::class);
        Route::get('company-settings', [CompanySettingController::class, 'index']);
        // Route::get('get-countries-currencies/{country_id?}', [CompanySettingController::class, 'countryAnCurrencies']);

        Route::resource('roles', RoleController::class);

        Route::get('get-all-staff', [StaffController::class, 'getAllStaff']);
        Route::post('staff/store', [StaffController::class, 'storeStaff']);
        Route::post('update-staff', [StaffController::class, 'updateStaff']);
        Route::post('update-staff-personal-information', [StaffController::class, 'updateStaffPersonalInfo']);
        
        Route::get('get-active-staff', [StaffController::class, 'getActiveStaff']);
        Route::get('get-single-staff-profile/{staff_id}', [StaffController::class, 'getSingleStaffProfile']);
        Route::get('get-active-staff-in-role/{staff_id}', [StaffController::class, 'getActiveStaffInRole']);
        Route::post('assign-role-to-staff', [StaffController::class, 'assignRoleToStaff']);
        Route::get('staff-roles/{staff_id}', [StaffController::class, 'getSingleStaffRoles']);
        
        Route::get('staff-identification-settings/{id?}', [StaffController::class, 'staffIdentificationSetting']);
        Route::post('update-staff-identification-settings', [StaffController::class, 'updateStaffIdentificationSetting']);
        Route::get('staff-document-settings/{id?}', [StaffController::class, 'staffDocumentSetting']);
        Route::post('staff-document-setting', [StaffController::class, 'addStaffDocumentSetting']);
        Route::post('staff-document-setting/{id}', [StaffController::class, 'updateStaffDocumentSetting']);
        Route::get('delete-staff-identification-settings/{id}', [StaffController::class, 'deleteStaffDocumentSetting']);

        Route::resource('special-request', SpecialRequestController::class);
        Route::resource('department', DepartmentController::class);
        Route::get('sample-bulk-department-import', [DepartmentController::class, 'sampleBulkDepartment']);
        Route::post('import-bulk-department', [DepartmentController::class, 'importBulkDepartment']);

        Route::get('leave-and-absence/{id?}', [LeaveAndAbenceManagementController::class, 'index']);
        Route::get('single-staff-leave-and-absence/{staff_id}', [LeaveAndAbenceManagementController::class, 'staffLeaveAndAbsence']);
        Route::post('create-leave-and-absence', [LeaveAndAbenceManagementController::class, 'store']);
        Route::get('approve-leave-and-absence/{id}', [LeaveAndAbenceManagementController::class, 'approveLeaveAndAbsence']);
        Route::get('decline-leave-and-absence/{id}', [LeaveAndAbenceManagementController::class, 'declineLeaveAndAbsence']);

        Route::get('advance-and-claims/{id?}', [AdvanceAndClaimsController::class, 'index']);
        Route::get('single-staff-advance-and-claims/{staff_id}', [AdvanceAndClaimsController::class, 'staffAdvanceAndClaims']);
        Route::post('create-advance-and-claims', [AdvanceAndClaimsController::class, 'store']);

        Route::resource('branch', BranchController::class);
        Route::resource('jobrole', JobRoleController::class);
        Route::resource('jobtype', JobTypeController::class);
        Route::get('sample-bulk-jobrole-import', [JobRoleController::class, 'sampleBulkJobRole']);
        Route::post('import-bulk-jobrole', [JobRoleController::class, 'importBulkJobRole']);

        Route::post('form-expiry-status', [EstateController::class, 'changeFormExpiry']);
        Route::get('change-special-request-status', [MenuController::class, 'changeSpecialRequestStatus']);
        Route::post('add-new-contract', [CustomerContractController::class, 'store']);
        Route::get('single-customer-contract/{customer_id}', [CustomerContractController::class, 'index']);

        Route::get('customercontract/{contract_id}', [CustomerContractController::class, 'show']);
        Route::get('estate-property-type', [PropertyTypeDetailsController::class, 'index']);
        
        Route::get('get-leads', [CustomerController::class, 'getLeads']);
        Route::get('get-prospects', [CustomerController::class, 'getProspects']);
        Route::get('get-subscribers', [CustomerController::class, 'getSubscribers']);
        Route::get('get-single-customer-details/{id}', [CustomerController::class, 'show']);
        Route::get('get-single-customer-contract-count/{customer_id}', [CustomerController::class, 'singleCustomerContractCount']);
        Route::resource('account-type', AccountTypeController::class);
        Route::resource('account-classification', AccountClassificationController::class);
        Route::resource('chart-of-account', ChartOfAccountController::class);
        Route::resource('transaction-type-definition', TransactionTypeDefinitionController::class);
        Route::resource('account', AccountController::class);

        Route::get('single-customer-form-payment-details/{customer_id}', [CustomerContractController::class, 'singleCustomerFormDetails']);

        Route::get('form-payment-history/{customer_id}', [CustomerContractController::class, 'formPaymentHistory']);
        Route::post('make-form-payment', [CustomerContractController::class, 'makeFormPayment']);
        Route::get('get-countries', [GeneralController::class, 'getCountries']);
        Route::get('get-states', [GeneralController::class, 'getStates']);
        Route::get('get-lga', [GeneralController::class, 'getLgas']);
        Route::get('get-banks', [GeneralController::class, 'getBanks']);
        Route::get('get-currencies', [GeneralController::class, 'getCurrencies']);

        // Self Service
        // Route::get('self-service/my-profile', [SelfServiceController::class, 'myProfile']);
        Route::get('self-service/dashboard', [SelfServiceController::class, 'dashboard']);
        Route::get('self-service/my-profile', [SelfServiceController::class, 'myWorkProfile']);
        Route::post('self-service/update-my-profile', [SelfServiceController::class, 'updateMyWorkProfile']);
        Route::post('self-service/add-avatar', [SelfServiceController::class, 'addAvatar']);
        Route::post('self-service/delete-avatar', [SelfServiceController::class, 'deleteAvatar']);
        Route::post('self-service/get-avatar/{staff_id}', [SelfServiceController::class, 'getAvatar']);
        Route::post('self-service/upload-documents', [SelfServiceController::class, 'uploadDocuments']);
        // Route::post('self-service/create-leave-and-absence', [SelfServiceController::class, 'createLeaveAndAbsence']);

    });
});
