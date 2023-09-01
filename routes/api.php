<?php

use App\Models\SpecialRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Models\LeaveAndAbenceManagement;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\EstateController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobRoleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PlotSizeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\BuildingTypeController;
use App\Http\Controllers\EstateSettingController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\SpecialRequestController;
use App\Http\Controllers\CompanyDocumentController;
use App\Http\Controllers\ContractCustomerController;
use App\Http\Controllers\LeaveAndAbenceManagementController;
use App\Http\Controllers\EstateConstructionSettingController;

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

Route::group(['prefix' => 'admin', 'before' => 'admin'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware(['auth:sanctum', 'company_check', 'user_roles', 'otherchecks'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/permissions', [MenuController::class, 'linkGenerator'])->name('permissions.index');
        Route::get('/single-menu-generator/{parent_id}', [MenuController::class, 'singleLinkGenerator'])->name('single.linkgenerator');
        
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Admin Module Routes
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
        Route::post('import-bulk-plotsizes', [PlotSizeController::class,'importBulkPlotSize']);

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

        Route::resource('estate-construction-setting', EstateConstructionSettingController::class);

        Route::post('update-company-profile', [CompanySettingController::class, 'updateCompanyProfile']);
        Route::resource('company-document', CompanyDocumentController::class);
        Route::get('company-settings', [CompanySettingController::class, 'index']);
        Route::get('get-countries-currencies/{country_id?}', [CompanySettingController::class, 'countryAnCurrencies']);

        Route::resource('roles', RoleController::class);
        
        Route::get('get-all-admins', [AdminController::class, 'getAllAdmins']);
        Route::post('admin/store', [AdminController::class, 'storeAdmin']);
        Route::get('get-active-admins', [AdminController::class, 'getActiveAdmins']);
        Route::get('get-active-admins-in-role/{id}', [AdminController::class, 'getActiveAdminsInRole']);
        Route::post('assign-role-to-admin', [AdminController::class, 'assignRoleToAdmin']);
        Route::get('admin-roles/{admin_id}', [AdminController::class, 'getSingleAdminRoles']);

        Route::resource('special-request', SpecialRequestController::class);
        Route::resource('department', DepartmentController::class);
        Route::get('sample-bulk-department-import', [DepartmentController::class, 'sampleBulkDepartment']);
        Route::post('import-bulk-department', [DepartmentController::class, 'importBulkDepartment']);

        Route::get('leave-and-absence/{id?}', [LeaveAndAbenceManagementController::class, 'index']);
        Route::post('create-leave-and-absence', [LeaveAndAbenceManagementController::class, 'store']);
        Route::get('approve-leave-and-absence/{id}', [LeaveAndAbenceManagementController::class, 'approveLeaveAndAbsence']);
        Route::get('decline-leave-and-absence/{id}', [LeaveAndAbenceManagementController::class, 'declineLeaveAndAbsence']);

        Route::resource('branch', BranchController::class);
        Route::resource('jobrole', JobRoleController::class);
        Route::get('sample-bulk-jobrole-import', [JobRoleController::class, 'sampleBulkJobRole']);
        Route::post('import-bulk-jobrole', [JobRoleController::class, 'importBulkJobRole']);
        
        Route::post('form-expiry-status', [EstateController::class, 'changeFormExpiry']);
        Route::get('change-special-request-status', [MenuController::class, 'changeSpecialRequestStatus']);
        Route::post('add-new-contract', [ContractCustomerController::class, 'store']);
    });
});