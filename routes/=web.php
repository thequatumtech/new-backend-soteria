<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\InsuranceCompanyController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\Pagecontroller;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\HomePlanController;
use App\Http\Controllers\OfficePlanController;
use App\Http\Controllers\LifePlanController;
use App\Http\Controllers\CriticalIllnessPlanController;
use App\Http\Controllers\PersonalAccidentPlanController;
use App\Http\Controllers\MarinePlanController;
use App\Http\Controllers\RenewalSectionController;
use App\Http\Controllers\TravelPlanController;
use App\Http\Controllers\InPatientPlanController;
use App\Http\Controllers\InOutPatientPlanController;
use App\Http\Controllers\DentalPlanController;
use App\Http\Controllers\PetPlanController;
use App\Http\Controllers\DiscountCouponController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\PlanandRateController;
use App\Http\Controllers\PrivacyandPolicyController;
use App\Http\Controllers\RenewalSubscriptionController;
use App\Http\Controllers\SubAdminController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\UtilsController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\VehicleBrandController;
use App\Http\Controllers\VehicleCategoryController;
use App\Http\Controllers\VehicleColorController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\TypeOfCoverController;
use App\Http\Controllers\InsuredItemCategoryController;
use App\Http\Controllers\InsuredItemSubCategoryController;
use App\Http\Controllers\AgeController;
use App\Http\Controllers\ChronicDiseaseController;
use App\Http\Controllers\ClaimStatusController;
use App\Http\Controllers\ComplaintStatusController;
use App\Http\Controllers\DangerousActivitiesController;
use App\Http\Controllers\EngineCapacityController;
use App\Http\Controllers\EngineTypeController;
use App\Http\Controllers\GeographicalAreaController;
use App\Http\Controllers\InsurancePeriodController;
use App\Http\Controllers\MedicalNetworkController;
use App\Http\Controllers\MotorPlanController;
use App\Http\Controllers\ProtectionSystemController;
use App\Http\Controllers\InPatientDeductibleController;
use App\Http\Controllers\OutPatientDeductibleController;
use App\Http\Controllers\NoOfVisitController;
use App\Http\Controllers\ClaimDeductibleController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\OccupationsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NationalityController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BlackListController;
use App\Http\Controllers\TermsAndConditionController;
use App\Http\Middleware\CheckAdminAuthorization;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AdminAuthController::class, 'getLogin'])->middleware('guest')->name('adminLogin');
Route::get('/logout', [AdminAuthController::class, 'adminLogout'])->name('adminLogout');
Route::get('/download/{id}', [UtilsController::class, 'downloadMedia'])->name('downloadMedia');
Route::get('/terms-and-conditions', [TermsAndConditionController::class, 'terms'])->name('terms_and_conditions');

//TODO Remove below route in production env
if(env('APP_ENV') == 'local') {
    Route::get('/clear-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        print_r('Cache cleared successfully');
    });
}
Route::post('/get-cities', [CitiesController::class, 'get_cities'])->name('get_cities');
Route::post('/get-districts', [DistrictController::class, 'get_districts'])->name('get_districts');


Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {

    Route::post('/login', [AdminAuthController::class, 'postLogin'])->name('adminLoginPost');

    Route::group(['middleware' => ['adminauth','checkAdminAuthorization']], function () {
        Route::get('/delete-media/{id}', [UtilsController::class, 'deleteMedia'])->name('deleteMedia');
/*        Route::get('/', function () {
            return view('welcome');
        })->name('adminDashboard');*/
        Route::get('/', [DashboardController::class, 'index'])->name('pages.index');
        // Route::get('/customer', [Pagecontroller::class, 'customer'])->name('pages.customer');
        Route::get('/customer', [CustomerController::class, 'index'])->name('pages.customer');


        Route::get('/discount', [DiscountController::class, 'index'])->name('pages.discount');
        Route::get('/privacy', [PrivacyandPolicyController::class, 'index'])->name('pages.privacy');
//        Route::get('/subadmin', [SubAdminController::class, 'index'])->name('pages.subadmin');
        Route::get('/renewal', [RenewalSubscriptionController::class, 'index'])->name('pages.renewal');
        /**
         * Customer Routes
         */
        Route::get('/customer', [CustomerController::class, 'index'])->name('pages.customer');
        Route::post('customer-create', [CustomerController::class, 'CustomerCreate'])->name('customer.create');
        // Route::put('/customer/{id}/customerupdate', [CustomerController::class, 'CustomerCreate'])->name('customer.update');
        Route::get('customer/edit/{id}', [CustomerController::class, 'CustomerEdit'])->name('customer.edit');
        Route::post('/customer-update', [CustomerController::class, 'CustomerUpdate'])->name('customer.update');
        Route::delete('delete-customer', [CustomerController::class, 'destoryCustomer'])->name('destoryCustomer');

        /**
         * Agent Routes
         */
        Route::get('/agents', [AgentController::class, 'index'])->name('pages.agent');
        Route::get('/agent/{id}', [AgentController::class, 'agent'])->name('pages.agent_view');
        Route::post('agent-create', [AgentController::class, 'AgentCreate'])->name('agent.create');
        Route::get('agent/edit/{id}', [AgentController::class, 'AgentEdit'])->name('agent.edit');
        Route::post('/agent-update', [AgentController::class, 'agentUpdate'])->name('agent.update');
        Route::delete('delete-agent', [AgentController::class, 'destoryAgent'])->name('destoryAgent');
        Route::get('agent-check-mobile', [AgentController::class, 'check_mobile'])->name('agents.check_mobile');
        Route::get('agent-check-email', [AgentController::class, 'check_email'])->name('agents.check_email');


        /**
         * Client Routes
         */
        Route::get('client', [ClientController::class, 'index'])->name('client');
        Route::get('view-client/{id}', [ClientController::class, 'view'])->name('client.view');
        Route::get('add-client', [ClientController::class, 'add'])->name('client.add');
        Route::post('add-client', [ClientController::class, 'create'])->name('client.create');
        Route::get('edit-client/{id}', [ClientController::class, 'edit'])->name('client.edit');
        Route::post('delete-client', [ClientController::class, 'delete'])->name('client.delete');
        Route::get('search-client/{id}', [ClientController::class, 'search'])->name('client.search');
        Route::get('purchased-policy-client/{id}', [ClientController::class, 'purchased_policy'])->name('client.purchased_policy');
        Route::post('client/send-message', [ClientController::class, 'send_message'])->name('client.send_message');
        Route::post('add-to-blacklist', [ClientController::class, 'add_to_blacklist'])->name('client.add_to_blacklist');
        Route::get('check-mobile', [ClientController::class, 'check_mobile'])->name('client.check_mobile');
        Route::get('check-email', [ClientController::class, 'check_email'])->name('client.check_email');


        /**
         * Sub-Admin Routes
         */
        Route::get('sub-admin', [SubAdminController::class, 'index'])->name('sub_admin');
        Route::get('add-sub-admin', [SubAdminController::class, 'add'])->name('sub_admin.add');
        Route::post('add-sub-admin', [SubAdminController::class, 'create'])->name('sub_admin.create');
        Route::get('edit-sub-admin/{id}', [SubAdminController::class, 'edit'])->name('sub_admin.edit');
        Route::post('delete-sub-admin', [SubAdminController::class, 'delete'])->name('sub_admin.delete');

        /**
         * Blacklist Routes
         */
        Route::get('black-list', [BlackListController::class, 'index'])->name('black_list');
        Route::post('restore-black-list', [BlackListController::class, 'restore'])->name('restore_black_list');
        Route::post('send-black-list-email', [BlackListController::class, 'send_blacklist_email'])->name('send_blacklist_email');
        Route::get('black-list-client/{id}', [BlackListController::class, 'client'])->name('black_list_client');
        Route::get('black-list-client/edit/{id}', [BlackListController::class, 'client_edit'])->name('edit_black_list_client');
        Route::post('black-list-client/save', [BlackListController::class, 'client_save'])->name('save_black_list_client');
        Route::post('send-client-info-email', [BlackListController::class, 'send_client_info_email'])->name('send_client_info_email');


        Route::withoutMiddleware('checkAdminAuthorization')->middleware('checkAdminAuthorizationForInsurancePlans')->group(function () {
            /**
             * Motor Plan Routes
             */
//        Route::get('plans', [PlanController::class, 'index'])->name('plan');
            Route::get('comprehensive', [PlanController::class, 'motor_plan_comprehensive'])->name('motor_plan.motor_plan_comprehensive');
            Route::get('add-motor-plan-comprehensive', [PlanController::class, 'add_motor_plan_comprehensive'])->name('motor_plan.add_motor_plan_comprehensive');
            Route::get('compulsory-3-months', [PlanController::class, 'motor_plan_compulsory_3_months'])->name('motor_plan.motor_plan_compulsory_3_months');
            Route::get('add-motor-plan-compulsory-3-months', [PlanController::class, 'add_motor_plan_compulsory_3_months'])->name('motor_plan.add_motor_plan_compulsory_3_months');
            Route::get('compulsory-6-months', [PlanController::class, 'motor_plan_compulsory_6_months'])->name('motor_plan.motor_plan_compulsory_6_months');
            Route::get('add-motor-plan-compulsory-6-months', [PlanController::class, 'add_motor_plan_compulsory_6_months'])->name('motor_plan.add_motor_plan_compulsory_6_months');
            Route::get('compulsory-9-months', [PlanController::class, 'motor_plan_compulsory_9_months'])->name('motor_plan.motor_plan_compulsory_9_months');
            Route::get('add-motor-plan-compulsory-9-months', [PlanController::class, 'add_motor_plan_compulsory_9_months'])->name('motor_plan.add_motor_plan_compulsory_9_months');
            Route::get('compulsory-12-months', [PlanController::class, 'motor_plan_compulsory_12_months'])->name('motor_plan.motor_plan_compulsory_12_months');
            Route::get('add-motor-plan-compulsory-12-months', [PlanController::class, 'add_motor_plan_compulsory_12_months'])->name('motor_plan.add_motor_plan_compulsory_12_months');
            Route::get('total-loss', [PlanController::class, 'motor_plan_total_loss'])->name('motor_plan.motor_plan_total_loss');
            Route::get('add-motor-plan-total-loss', [PlanController::class, 'add_motor_plan_total_loss'])->name('motor_plan.add_motor_plan_total_loss');
            Route::post('add-motor-plan', [PlanController::class, 'add_motor_plan'])->name('motor_plan.save');
            Route::get('edit-motor-plan/{id}', [PlanController::class, 'edit_motor_plan'])->name('motor_plan.edit');
            Route::post('delete-motor-plan', [PlanController::class, 'delete_motor_plan'])->name('motor_plan.delete');

            /**
             * Home Plan Routes
             */
            Route::get('home-plans', [HomePlanController::class, 'home_plan'])->name('home_plan.home_plan');
            Route::get('add-home-plan', [HomePlanController::class, 'add_home_plan'])->name('home_plan.add_home_plan');
            Route::post('save-home-plan', [HomePlanController::class, 'save_home_plan'])->name('home_plan.save');
            Route::get('edit-home-plan/{id}', [HomePlanController::class, 'edit_home_plan'])->name('home_plan.edit');
            Route::post('delete-home-plan', [HomePlanController::class, 'delete_home_plan'])->name('home_plan.delete');

            /**
             * Office Plan Routes
             */
            Route::get('office-plan', [OfficePlanController::class, 'office_plan'])->name('office_plan.office_plan');
            Route::get('add-office-plan', [OfficePlanController::class, 'add_office_plan'])->name('office_plan.add_office_plan');
            Route::post('save-office-plan', [OfficePlanController::class, 'save_office_plan'])->name('office_plan.save');
            Route::get('edit-office-plan/{id}', [OfficePlanController::class, 'edit_office_plan'])->name('office_plan.edit');
            Route::post('delete-office-plan', [OfficePlanController::class, 'delete_office_plan'])->name('office_plan.delete');

            /**
             * Life Plan Routes
             */
            Route::get('life-plan', [LifePlanController::class, 'life_plan'])->name('life_plan.life_plan');
            Route::get('add-life-plan', [LifePlanController::class, 'add_life_plan'])->name('life_plan.add_life_plan');
            Route::post('save-life-plan', [LifePlanController::class, 'save_life_plan'])->name('life_plan.save');
            Route::get('edit-life-plan/{id}', [LifePlanController::class, 'edit_life_plan'])->name('life_plan.edit');
            Route::post('delete-life-plan', [LifePlanController::class, 'delete_life_plan'])->name('life_plan.delete');

            /**
             * Critical Illness Plan Routes
             */
            Route::get('critical-illness-plan', [CriticalIllnessPlanController::class, 'critical_illness_plan'])->name('critical_illness_plan.critical_illness_plan');
            Route::get('add-critical-illness-plan', [CriticalIllnessPlanController::class, 'add_critical_illness_plan'])->name('critical_illness_plan.add_critical_illness_plan');
            Route::post('save-critical-illness-plan', [CriticalIllnessPlanController::class, 'save_critical_illness_plan'])->name('critical_illness_plan.save');
            Route::get('edit-critical-illness-plan/{id}', [CriticalIllnessPlanController::class, 'edit_critical_illness_plan'])->name('critical_illness_plan.edit');
            Route::post('delete-critical-illness-plan', [CriticalIllnessPlanController::class, 'delete_critical_illness_plan'])->name('critical_illness_plan.delete');

            /**
             * Personal Accident Plan Routes
             */
            Route::get('personal-accident-plan', [PersonalAccidentPlanController::class, 'personal_accident_plan'])->name('personal_accident_plan.personal_accident_plan');
            Route::get('add-personal-accident-plan', [PersonalAccidentPlanController::class, 'add_personal_accident_plan'])->name('personal_accident_plan.add_personal_accident_plan');
            Route::post('save-personal-accident-plan', [PersonalAccidentPlanController::class, 'save_personal_accident_plan'])->name('personal_accident_plan.save');
            Route::get('edit-personal-accident-plan/{id}', [PersonalAccidentPlanController::class, 'edit_personal_accident_plan'])->name('personal_accident_plan.edit');
            Route::post('delete-personal-accident-plan', [PersonalAccidentPlanController::class, 'delete_personal_accident_plan'])->name('personal_accident_plan.delete');

            /**
             * In-Patient Plan Routes
             */
            Route::get('in-patient-plan', [InPatientPlanController::class, 'in_patient_plan'])->name('in_patient_plan.in_patient_plan');
            Route::get('add-in-patient-plan', [InPatientPlanController::class, 'add_in_patient_plan'])->name('in_patient_plan.add_in_patient_plan');
            Route::post('save-in-patient-plan', [InPatientPlanController::class, 'save_in_patient_plan'])->name('in_patient_plan.save');
            Route::get('edit-in-patient-plan/{id}', [InPatientPlanController::class, 'edit_in_patient_plan'])->name('in_patient_plan.edit');
            Route::post('delete-in-patient-plan', [InPatientPlanController::class, 'delete_in_patient_plan'])->name('in_patient_plan.delete');

            /**
             * In & Out Patient Plan Routes
             */
            Route::get('in-out-patient-plan', [InOutPatientPlanController::class, 'in_out_patient_plan'])->name('in_out_patient_plan.in_out_patient_plan');
            Route::get('add-in-out-patient-plan', [InOutPatientPlanController::class, 'add_in_out_patient_plan'])->name('in_out_patient_plan.add_in_out_patient_plan');
            Route::post('save-in-out-patient-plan', [InOutPatientPlanController::class, 'save_in_out_patient_plan'])->name('in_out_patient_plan.save');
            Route::get('edit-in-out-patient-plan/{id}', [InOutPatientPlanController::class, 'edit_in_out_patient_plan'])->name('in_out_patient_plan.edit');
            Route::post('delete-in-out-patient-plan', [InOutPatientPlanController::class, 'delete_in_out_patient_plan'])->name('in_out_patient_plan.delete');

            /**
             * Travel Plan Routes
             */
            Route::get('travel-plan', [TravelPlanController::class, 'travel_plan'])->name('travel_plan.travel_plan');
            Route::get('add-travel-plan', [TravelPlanController::class, 'add_travel_plan'])->name('travel_plan.add_travel_plan');
            Route::post('save-travel-plan', [TravelPlanController::class, 'save_travel_plan'])->name('travel_plan.save');
            Route::get('edit-travel-plan/{id}', [TravelPlanController::class, 'edit_travel_plan'])->name('travel_plan.edit');
            Route::post('delete-travel-plan', [TravelPlanController::class, 'delete_travel_plan'])->name('travel_plan.delete');

            /**
             * Marine Plan Routes
             */
            Route::get('marine-plan', [MarinePlanController::class, 'marine_plan'])->name('marine_plan.marine_plan');
            Route::get('add-marine-plan', [MarinePlanController::class, 'add_marine_plan'])->name('marine_plan.add_marine_plan');
            Route::post('save-marine-plan', [MarinePlanController::class, 'save_marine_plan'])->name('marine_plan.save');
            Route::get('edit-marine-plan/{id}', [MarinePlanController::class, 'edit_marine_plan'])->name('marine_plan.edit');
            Route::post('delete-marine-plan', [MarinePlanController::class, 'delete_marine_plan'])->name('marine_plan.delete');

            /**
             * Dental Plan Routes
             */
            Route::get('dental-plan', [DentalPlanController::class, 'dental_plan'])->name('dental_plan.dental_plan');
            Route::get('add-dental-plan', [DentalPlanController::class, 'add_dental_plan'])->name('dental_plan.add_dental_plan');
            Route::post('save-dental-plan', [DentalPlanController::class, 'save_dental_plan'])->name('dental_plan.save');
            Route::get('edit-dental-plan/{id}', [DentalPlanController::class, 'edit_dental_plan'])->name('dental_plan.edit');
            Route::post('delete-dental-plan', [DentalPlanController::class, 'delete_dental_plan'])->name('dental_plan.delete');

            /**
             * Pets Plan Routes
             */
            Route::get('pet-plans', [PetPlanController::class, 'pet_plan'])->name('pet_plan.pet_plan');
            Route::get('add-pet-plan', [PetPlanController::class, 'add_pet_plan'])->name('pet_plan.add_pet_plan');
            Route::post('save-pet-plan', [PetPlanController::class, 'save_pet_plan'])->name('pet_plan.save');
            Route::get('edit-pet-plan/{id}', [PetPlanController::class, 'edit_pet_plan'])->name('pet_plan.edit');
            Route::post('delete-pet-plan', [PetPlanController::class, 'delete_pet_plan'])->name('pet_plan.delete');
        });


        /**
         * Discount Coupons Routes
         */
        Route::get('coupons', [DiscountCouponController::class, 'index'])->name('coupons');
        Route::get('add-coupon', [DiscountCouponController::class, 'add_coupon'])->name('coupons.add');
        Route::post('save-coupon', [DiscountCouponController::class, 'save_coupon'])->name('coupons.save');
        Route::get('edit-coupon/{id}', [DiscountCouponController::class, 'edit_coupon'])->name('coupons.edit');
        Route::get('view-coupon/{id}', [DiscountCouponController::class, 'view_coupon'])->name('coupons.view');
        Route::post('send-coupon', [DiscountCouponController::class, 'send_coupon'])->name('coupons.send');
        Route::post('delete-coupon', [DiscountCouponController::class, 'delete_coupon'])->name('coupons.delete');
        Route::post('coupon-change-status', [DiscountCouponController::class, 'change_status'])->name('coupons.change_status');


        /**
         * Renewal Section Routes
         */
        Route::get('active-policies', [RenewalSectionController::class, 'active_policies'])->name('active_policies');
        Route::post('notify-renewal', [RenewalSectionController::class, 'notify_renewal'])->name('notify_renewal');
        Route::post('cancel-policy', [RenewalSectionController::class, 'cancel_policy'])->name('cancel_policy');
        Route::post('renew-policy', [RenewalSectionController::class, 'renew_policy'])->name('renew_policy');
        Route::get('expired-policies', [RenewalSectionController::class, 'expired_policies'])->name('expired_policies');


        /**
         * Manage Complaints Routes
         */
        Route::get('complaints', [ComplaintController::class, 'index'])->name('complaints');
        Route::get('view-complaint/{id}', [ComplaintController::class, 'view_complaint'])->name('complaints.view');
        Route::get('edit-complaint/{id}', [ComplaintController::class, 'edit_complaint'])->name('complaints.edit');
        Route::post('save-complaint', [ComplaintController::class, 'save_complaint'])->name('complaints.save');
        Route::post('send-reply-email', [ComplaintController::class, 'send_reply_email'])->name('complaints.send_reply_email');
        Route::get('complaint-emails', [ComplaintController::class, 'complaint_emails'])->name('complaint_emails');
        Route::post('complaint-emails', [ComplaintController::class, 'complaint_emails_create'])->name('complaint_emails.create');
        Route::patch('complaint-emails', [ComplaintController::class, 'complaint_emails_update'])->name('complaint_emails.update');
        Route::delete('complaint-emails', [ComplaintController::class, 'complaint_emails_destroy'])->name('complaint_emails.destroy');

        /**
         * Manage Claims Routes
         */
        Route::get('claims', [ClaimController::class, 'index'])->name('claims');
        Route::get('add-claim/{id}', [ClaimController::class, 'add_claim'])->name('claims.add');
        Route::get('manage-claim/{id?}', [ClaimController::class, 'view_claim'])->name('claims.view');
        Route::post('claim-change-status', [ClaimController::class, 'change_status'])->name('claims.change_status');
        Route::post('get-all-messages', [ClaimController::class, 'get_all_messages'])->name('claims.get_all_messages');
        Route::post('claims/send-message', [ClaimController::class, 'send_message'])->name('claims.send_message');

        /**
         * Contact Us Routes
         */
        Route::get('contactus', [ContactUsController::class, 'indexContact'])->name('pages.contactus');
        Route::post('/contact/store', [ContactUsController::class, 'store'])->name('contact.store');
        Route::get('/contactus/edit/{id}', [ContactUsController::class, 'edit'])->name('contactus.edit');
        Route::post('/contactus/update/{id}', [ContactUsController::class, 'update'])->name('contactus.update');
        Route::delete('/contactus/delete/{id}', [ContactUsController::class, 'destroy'])->name('contactus.delete');

        Route::get('contact-us', [ContactUsController::class, 'index'])->name('pages.contact-us');
//        Route::post('contact-us', [ContactUsController::class, 'create'])->name('contact-us.create');
//        Route::get('contact-us/edit/{id}', [ContactUsController::class, 'view'])->name('contact-us.edit');
//        Route::delete('contact-us', [ContactUsController::class, 'destroy'])->name('contact-us.destroy');
        Route::post('contact-us/send-message', [ContactUsController::class, 'send_message'])->name('contact-us.send_message');
        Route::post('contact-us/get-messages', [ContactUsController::class, 'load_messages'])->name('contact-us.load_messages');
        Route::post('contact-us/upload-file', [ContactUsController::class, 'upload_file'])->name('contact-us.upload_file');


        /**
         * Terms And Conditions Routes
         */

        Route::get('/terms-and-conditions', [TermsAndConditionController::class, 'index'])->name('pages.terms-and-conditions');
        Route::post('terms-and-conditions', [TermsAndConditionController::class, 'create'])->name('terms-and-conditions.create');
        Route::delete('terms-and-conditions', [TermsAndConditionController::class, 'destroy'])->name('terms-and-conditions.destroy');


        /**
         * Insurance Routes
         */
        Route::get('/insurance', [InsuranceCompanyController::class, 'index'])->name('pages.insurance');
        Route::post('insurance-create', [InsuranceCompanyController::class, 'create'])->name('insurance-company.create');
        Route::get('insurance/edit/{id}', [InsuranceCompanyController::class, 'edit'])->name('insurance-company.edit');
        Route::patch('/insurance-update', [InsuranceCompanyController::class, 'update'])->name('insurance-company.update');
        Route::delete('delete-insurance', [InsuranceCompanyController::class, 'destroy'])->name('insurance-company.destroy');
        Route::post('get-line-of-businesses', [InsuranceCompanyController::class, 'get_line_of_businesses']);
        Route::get('insurance-check-mobile', [InsuranceCompanyController::class, 'check_mobile'])->name('insurance-company.check_mobile');
        Route::get('insurance-check-email', [InsuranceCompanyController::class, 'check_email'])->name('insurance-company.check_email');


        /**
         * Discount Routes
         */
        Route::post('discount-create', [DiscountController::class, 'DiscountCreate'])->name('discount.create');
        Route::delete('delete-discount', [DiscountController::class, 'destory'])->name('destoryCode');

        /**
         * Supervisor Routes
         */
        Route::get('/supervisor', [SupervisorController::class, 'index'])->name('pages.supervisor');
        Route::post('supervisor-create', [SupervisorController::class, 'SupervisorCreate'])->name('supervisor.create');
        Route::get('supervisor/edit/{id}', [SupervisorController::class, 'supervisorEdit'])->name('supervisor.edit');
        Route::post('/supervisor-update', [SupervisorController::class, 'supervisorUpdate'])->name('supervisor.update');
        Route::delete('delete-supervisor', [SupervisorController::class, 'destorysupervisor'])->name('destorysupervisor');


        /**
         * Subadmin Routes
         */
/*        Route::post('subadmin-create', [SubAdminController::class, 'SubAdminCreate'])->name('subadmin.create');
        Route::get('subadmin/edit/{id}', [SubAdminController::class, 'SubAdminEdit'])->name('subadmin.edit');
        Route::post('/subadmin-update', [SubAdminController::class, 'subadminUpdate'])->name('subadminUpdate.update');
        Route::delete('delete-subadmin', [SubAdminController::class, 'destorySubadmin'])->name('destorySubadmin');
*/
        /**
         * Privacy Routes
         */

        Route::get('privacy-form', [PrivacyandPolicyController::class, 'privacyForm'])->name('privacy.form');
        Route::post('privacy-store', [PrivacyandPolicyController::class, 'privacyStore'])->name('privacy.store');

        /**
         * Plan Routes
         */
//        Route::get('/planandrate', [PlanandRateController::class, 'index'])->name('pages.plan');
//        Route::post('plan-create', [PlanandRateController::class, 'PlanCreate'])->name('plan.create');
//        Route::get('plan/edit/{id}', [PlanandRateController::class, 'PlanEdit'])->name('plan.edit');
//        Route::post('/plan-update', [PlanandRateController::class, 'PlanUpdate'])->name('plan.update');
//        Route::delete('delete-plan', [PlanandRateController::class, 'destoryPlan'])->name('destoryPlan');

        /**
         * Manage Social Media
         */
        Route::get('/social-media', [SocialMediaController::class, 'index'])->name('social_media.list');
        Route::post('social-media-create', [SocialMediaController::class, 'social_media_create'])->name('social_media.create');

        Route::withoutMiddleware('checkAdminAuthorization')->middleware('checkAdminAuthorizationForAdminBasics')->prefix('admin-basic')->group(function () {
            /**
             * Admin basic - Age
             */

            Route::get('/ages', [AgeController::class, 'index'])->name('ages.list');
            Route::post('age', [AgeController::class, 'create'])->name('ages.create');
            Route::get('age/edit/{id}', [AgeController::class, 'edit'])->name('ages.edit');
            Route::patch('age', [AgeController::class, 'update'])->name('ages.update');
            Route::delete('age', [AgeController::class, 'destroy'])->name('ages.destroy');


            /**
             * Admin basic - Chronic Disease
             */

            Route::get('/chronic_disease', [ChronicDiseaseController::class, 'index'])->name('chronic_disease.list');
            Route::post('chronic_disease', [ChronicDiseaseController::class, 'create'])->name('chronic_disease.create');
            Route::patch('chronic_disease', [ChronicDiseaseController::class, 'update'])->name('chronic_disease.update');
            Route::delete('chronic_disease', [ChronicDiseaseController::class, 'destroy'])->name('chronic_disease.destroy');

            /**
             * Admin basic - Claim Status
             */

            Route::get('/claim_status', [ClaimStatusController::class, 'index'])->name('claim_status.list');
            Route::post('claim_status', [ClaimStatusController::class, 'create'])->name('claim_status.create');
            Route::patch('claim_status', [ClaimStatusController::class, 'update'])->name('claim_status.update');
            Route::delete('claim_status', [ClaimStatusController::class, 'destroy'])->name('claim_status.destroy');

            /**
             * Admin basic - Complaint Status
             */

            Route::get('/complaint_status', [ComplaintStatusController::class, 'index'])->name('complaint_status.list');
            Route::post('complaint_status', [ComplaintStatusController::class, 'create'])->name('complaint_status.create');
            Route::patch('complaint_status', [ComplaintStatusController::class, 'update'])->name('complaint_status.update');
            Route::delete('complaint_status', [ComplaintStatusController::class, 'destroy'])->name('complaint_status.destroy');

            /**
             * Admin basic - Dangerous Activities
             */

            Route::get('/dangerous_activities', [DangerousActivitiesController::class, 'index'])->name('dangerous_activities.list');
            Route::post('dangerous_activities', [DangerousActivitiesController::class, 'create'])->name('dangerous_activities.create');
            Route::patch('dangerous_activities', [DangerousActivitiesController::class, 'update'])->name('dangerous_activities.update');
            Route::delete('dangerous_activities', [DangerousActivitiesController::class, 'destroy'])->name('dangerous_activities.destroy');

            /**
             * Admin basic - Engine Capacity
             */

            Route::get('/engine_capacity', [EngineCapacityController::class, 'index'])->name('engine_capacity.list');
            Route::post('engine_capacity', [EngineCapacityController::class, 'create'])->name('engine_capacity.create');
            Route::patch('engine_capacity', [EngineCapacityController::class, 'update'])->name('engine_capacity.update');
            Route::delete('engine_capacity', [EngineCapacityController::class, 'destroy'])->name('engine_capacity.destroy');

            /**
             * Admin basic - Engine Type
             */

            Route::get('/engine_type', [EngineTypeController::class, 'index'])->name('engine_type.list');
            Route::post('engine_type', [EngineTypeController::class, 'create'])->name('engine_type.create');
            Route::patch('engine_type', [EngineTypeController::class, 'update'])->name('engine_type.update');
            Route::delete('engine_type', [EngineTypeController::class, 'destroy'])->name('engine_type.destroy');


            /**
             * Admin basic - Insurance Period
             */

            Route::get('/insurance_period', [InsurancePeriodController::class, 'index'])->name('insurance_period.list');
            Route::post('insurance_period', [InsurancePeriodController::class, 'create'])->name('insurance_period.create');
            Route::patch('insurance_period', [InsurancePeriodController::class, 'update'])->name('insurance_period.update');
            Route::delete('insurance_period', [InsurancePeriodController::class, 'destroy'])->name('insurance_period.destroy');

            /**
             * Admin basic - Medical  Network
             */

            Route::get('/medical_network', [MedicalNetworkController::class, 'index'])->name('medical_network.list');
            Route::post('medical_network', [MedicalNetworkController::class, 'create'])->name('medical_network.create');
            Route::patch('medical_network', [MedicalNetworkController::class, 'update'])->name('medical_network.update');
            Route::delete('medical_network', [MedicalNetworkController::class, 'destroy'])->name('medical_network.destroy');

            /**
             * Admin basic - Motor Plan
             */

            Route::get('/motor_plan', [MotorPlanController::class, 'index'])->name('motor_plan.list');
            Route::post('motor_plan', [MotorPlanController::class, 'create'])->name('motor_plan.create');
            Route::patch('motor_plan', [MotorPlanController::class, 'update'])->name('motor_plan.update');
            Route::delete('motor_plan', [MotorPlanController::class, 'destroy'])->name('motor_plan.destroy');

            /**
             * Admin basic - Protection System
             */

            Route::get('/protection_system', [ProtectionSystemController::class, 'index'])->name('protection_system.list');
            Route::post('protection_system', [ProtectionSystemController::class, 'create'])->name('protection_system.create');
            Route::patch('protection_system', [ProtectionSystemController::class, 'update'])->name('protection_system.update');
            Route::delete('protection_system', [ProtectionSystemController::class, 'destroy'])->name('protection_system.destroy');


            /**
             * Admin basic - In-Patient Deductible
             */

            Route::get('/in_patient_deductible', [InPatientDeductibleController::class, 'index'])->name('in_patient_deductible.list');
            Route::post('in_patient_deductible', [InPatientDeductibleController::class, 'create'])->name('in_patient_deductible.create');
            Route::patch('in_patient_deductible', [InPatientDeductibleController::class, 'update'])->name('in_patient_deductible.update');
            Route::delete('in_patient_deductible', [InPatientDeductibleController::class, 'destroy'])->name('in_patient_deductible.destroy');

            /**
             * Admin basic - Out-Patient Deductible
             */

            Route::get('/out_patient_deductible', [OutPatientDeductibleController::class, 'index'])->name('out_patient_deductible.list');
            Route::post('out_patient_deductible', [OutPatientDeductibleController::class, 'create'])->name('out_patient_deductible.create');
            Route::patch('out_patient_deductible', [OutPatientDeductibleController::class, 'update'])->name('out_patient_deductible.update');
            Route::delete('out_patient_deductible', [OutPatientDeductibleController::class, 'destroy'])->name('out_patient_deductible.destroy');

            /**
             * Admin basic - No of visits
             */

            Route::get('/no_of_visits', [NoOfVisitController::class, 'index'])->name('no_of_visits.list');
            Route::post('no_of_visits', [NoOfVisitController::class, 'create'])->name('no_of_visits.create');
            Route::patch('no_of_visits', [NoOfVisitController::class, 'update'])->name('no_of_visits.update');
            Route::delete('no_of_visits', [NoOfVisitController::class, 'destroy'])->name('no_of_visits.destroy');


            /**
             * Admin basic - Claim Deductible
             */

            Route::get('/claim_deductible', [ClaimDeductibleController::class, 'index'])->name('claim_deductible.list');
            Route::post('claim_deductible', [ClaimDeductibleController::class, 'create'])->name('claim_deductible.create');
            Route::patch('claim_deductible', [ClaimDeductibleController::class, 'update'])->name('claim_deductible.update');
            Route::delete('claim_deductible', [ClaimDeductibleController::class, 'destroy'])->name('claim_deductible.destroy');

            /**
             * Admin basic - Cities
             */

            Route::get('/cities', [CitiesController::class, 'index'])->name('cities.list');
            Route::post('cities', [CitiesController::class, 'create'])->name('cities.create');
            Route::patch('cities', [CitiesController::class, 'update'])->name('cities.update');
            Route::delete('cities', [CitiesController::class, 'destroy'])->name('cities.destroy');
            Route::post('citiescsv', [CitiesController::class, 'csv'])->name('cities.csv');
            Route::get('/cities-sample', [CitiesController::class, 'downloadCitiesCsv'])->name('cities.sample.csv');

            /**
             * Admin basic - Country
             */

            Route::get('/country', [CountryController::class, 'index'])->name('country.list');
            Route::post('country', [CountryController::class, 'create'])->name('country.create');
            Route::patch('country', [CountryController::class, 'update'])->name('country.update');
            Route::delete('country', [CountryController::class, 'destroy'])->name('country.destroy');
            Route::post('countrycsv', [CountryController::class, 'csv'])->name('country.csv');
            Route::get('/country-sample', [CountryController::class, 'downloadCountriesCsv'])->name('country.sample.csv');

            /**
             * Admin basic - District
             */

            Route::get('/district', [DistrictController::class, 'index'])->name('district.list');
            Route::post('district', [DistrictController::class, 'create'])->name('district.create');
            Route::patch('district', [DistrictController::class, 'update'])->name('district.update');
            Route::delete('district', [DistrictController::class, 'destroy'])->name('district.destroy');
            Route::post('districtcsv', [DistrictController::class, 'csv'])->name('district.csv');
            Route::get('/districts-sample', [DistrictController::class, 'downloadDistrictsCsv'])->name('districts.sample.csv');

            /**
             * Admin basic - Occupations
             */

            Route::get('/occupations', [OccupationsController::class, 'index'])->name('occupations.list');
            Route::post('occupations', [OccupationsController::class, 'create'])->name('occupations.create');
            Route::patch('occupations', [OccupationsController::class, 'update'])->name('occupations.update');
            Route::delete('occupations', [OccupationsController::class, 'destroy'])->name('occupations.destroy');
            Route::post('occupationscsv', [OccupationsController::class, 'csv'])->name('occupations.csv');


            /**
             * Admin basic - Language
             */

            Route::get('/language', [LanguageController::class, 'index'])->name('language.list');
            Route::post('language', [LanguageController::class, 'create'])->name('language.create');
            Route::patch('language', [LanguageController::class, 'update'])->name('language.update');
            Route::delete('language', [LanguageController::class, 'destroy'])->name('language.destroy');
            Route::post('languagecsv', [LanguageController::class, 'csv'])->name('language.csv');

            /**
             * Admin basic - Nationality
             */

            Route::get('/nationality', [NationalityController::class, 'index'])->name('nationality.list');
            Route::post('nationality', [NationalityController::class, 'create'])->name('nationality.create');
            Route::patch('nationality', [NationalityController::class, 'update'])->name('nationality.update');
            Route::delete('nationality', [NationalityController::class, 'destroy'])->name('nationality.destroy');
            Route::post('nationalitycsv', [NationalityController::class, 'csv'])->name('nationality.csv');

            /**
             * Admin basic - Currency
             */

            Route::get('/currency', [CurrencyController::class, 'index'])->name('currency.list');
            Route::post('currency', [CurrencyController::class, 'create'])->name('currency.create');
            Route::patch('currency', [CurrencyController::class, 'update'])->name('currency.update');
            Route::delete('currency', [CurrencyController::class, 'destroy'])->name('currency.destroy');
            Route::post('currencycsv', [CurrencyController::class, 'csv'])->name('currency.csv');


            /**
             * Geographical Area
             */

            Route::get('/geographical-area', [GeographicalAreaController::class, 'index'])->name('geographical_area.list');
            Route::post('geographical-area', [GeographicalAreaController::class, 'create'])->name('geographical_area.create');
            Route::patch('geographical-area', [GeographicalAreaController::class, 'update'])->name('geographical_area.update');
            Route::delete('geographical-area', [GeographicalAreaController::class, 'destroy'])->name('geographical_area.destroy');

            /**
             * Vehicle Brand Routes
             */

            Route::get('/vehicle-brand', [VehicleBrandController::class, 'index'])->name('pages.vehicle-brand');
            Route::post('vehicle-brand', [VehicleBrandController::class, 'create'])->name('vehicle-brand.create');
            Route::get('vehicle-brand/edit/{id}', [VehicleBrandController::class, 'edit'])->name('vehicle-brand.edit');
            Route::patch('vehicle-brand', [VehicleBrandController::class, 'update'])->name('vehicle-brand.update');
            Route::delete('vehicle-brand', [VehicleBrandController::class, 'destroy'])->name('vehicle-brand.destroy');
            Route::get('/getCategory', [VehicleBrandController::class, 'getCategory'])->name('pages.getCategory');
            Route::post('brandcsv', [VehicleBrandController::class, 'csv'])->name('brand.csv');
            Route::get('/brands-sample', [VehicleBrandController::class, 'downloadBrandsCsv'])->name('brands.sample.csv');

            /**
             * Vehicle Categories Routes
             */

            Route::get('/vehicle-categories', [VehicleCategoryController::class, 'index'])->name('pages.vehicle-categories');
            Route::post('/vehicle-categories', [VehicleCategoryController::class, 'create'])->name('vehicle-categories.create');
            Route::post('/vehicle-categories/edit', [VehicleCategoryController::class, 'update'])->name('vehicle-categories.update');
            Route::post('/vehicle-categories/delete', [VehicleCategoryController::class, 'delete'])->name('vehicle-categories.delete');
            Route::post('category-csv', [VehicleCategoryController::class, 'csv'])->name('category.csv');
            Route::get('/categories-sample', [VehicleCategoryController::class, 'downloadCategoriesCsv'])->name('categories.sample.csv');

//        Route::get('/vehicle-category/{id}', [VehicleCategoryController::class, 'index'])->name('pages.vehicle-category');
//        Route::patch('vehicle-category', [VehicleCategoryController::class, 'update'])->name('vehicle-category.update');


            /**
             * Vehicle Color Routes
             */

            Route::get('/vehicle-color', [VehicleColorController::class, 'index'])->name('pages.vehicle-color');
            Route::post('vehicle-color', [VehicleColorController::class, 'create'])->name('vehicle-color.create');
            Route::get('vehicle-color/edit/{id}', [VehicleColorController::class, 'edit'])->name('vehicle-color.edit');
            Route::patch('vehicle-color', [VehicleColorController::class, 'update'])->name('vehicle-color.update');
            Route::delete('vehicle-color', [VehicleColorController::class, 'destroy'])->name('vehicle-color.destroy');
            Route::get('vehicle-color-sample', [VehicleColorController::class, 'downloadColoursCsv'])->name('color.sample.csv');
            Route::post('vehicle-color-csv', [VehicleColorController::class, 'csv'])->name('color.csv');


            /**
             * Vehicle Types Routes
             */

            Route::get('/vehicle-type', [VehicleTypeController::class, 'index'])->name('pages.vehicle-type');
            Route::post('vehicle-type', [VehicleTypeController::class, 'create'])->name('vehicle-type.create');
            Route::get('vehicle-type/edit/{id}', [VehicleTypeController::class, 'edit'])->name('vehicle-type.edit');
            Route::patch('vehicle-type', [VehicleTypeController::class, 'update'])->name('vehicle-type.update');
            Route::delete('vehicle-type', [VehicleTypeController::class, 'destroy'])->name('vehicle-type.destroy');

            /**
             * Type Of Cover Routes
             */

            Route::get('type-of-cover', [TypeOfCoverController::class, 'index'])->name('type_of_covers.list');
            Route::post('type-of-cover', [TypeOfCoverController::class, 'create'])->name('type_of_covers.create');
            Route::patch('type-of-cover', [TypeOfCoverController::class, 'update'])->name('type_of_covers.update');
            Route::delete('type-of-cover', [TypeOfCoverController::class, 'destroy'])->name('type_of_covers.destroy');

            /**
             * Insured Item Category Routes
             */

            Route::get('insured-items-categories', [InsuredItemCategoryController::class, 'index'])->name('insured-items-categories.list');
            Route::post('insured-items-categories', [InsuredItemCategoryController::class, 'create'])->name('insured-items-categories.create');
            Route::get('insured-items-categories/edit/{id}', [InsuredItemCategoryController::class, 'edit'])->name('insured-items-categories.edit');
            Route::patch('insured-items-categories', [InsuredItemCategoryController::class, 'update'])->name('insured-items-categories.update');
            Route::delete('insured-items-categories', [InsuredItemCategoryController::class, 'destroy'])->name('insured-items-categories.destroy');

            /**
             * Insured Item Sub-Category Routes
             */

            Route::get('insured-items-sub-categories', [InsuredItemSubCategoryController::class, 'index'])->name('insured_item_sub_categories.list');
            Route::post('insured-items-sub-categories', [InsuredItemSubCategoryController::class, 'create'])->name('insured_item_sub_categories.create');
            Route::post('insured-items-sub-categories/edit', [InsuredItemSubCategoryController::class, 'update'])->name('insured_item_sub_categories.update');
            Route::post('insured-items-sub-categories/delete', [InsuredItemSubCategoryController::class, 'delete'])->name('insured_item_sub_categories.delete');
        });

        /**
         * Reports
         */

        Route::get('sold-policy-report', [ReportController::class, 'sold_policy_report'])->name('sold_policy_report.list');
        Route::get('supervisor-report', [ReportController::class, 'supervisor_report'])->name('supervisor_report.list');
        Route::get('policy-commission', [ReportController::class, 'policy_commission'])->name('policy_commission.list');
        Route::get('policy-renewal-report', [ReportController::class, 'policy_renewal_report'])->name('policy_renewal_report.list');
        Route::get('travel-policies-report', [ReportController::class, 'travel_policies_report'])->name('travel_policies_report.list');
        Route::get('pets-policies-report', [ReportController::class, 'pets_policies_report'])->name('pets_policies_report.list');
        Route::get('cancelled-by-admin-report', [ReportController::class, 'cancelled_by_admin_report'])->name('cancelled_by_admin_report.list');
        Route::get('renewed-by-admin-report', [ReportController::class, 'renewed_by_admin_report'])->name('renewed_by_admin_report.list');
        Route::get('expired-without-renewal-report', [ReportController::class, 'expired_without_renewal_report'])->name('expired_without_renewal_report.list');
        Route::get('client-report', [ReportController::class, 'client_report'])->name('client_report.list');
        Route::get('sold_policies_by_location', [ReportController::class, 'sold_policies_by_location'])->name('sold_policies_by_location.list');
        Route::get('claims_report', [ReportController::class, 'claims_report'])->name('claims_report.list');
        Route::get('complaints_report', [ReportController::class, 'complaints_report'])->name('complaints_report.list');
    });
});







// Route::get('/', function () {
//     return view('welcome');
// });

// Route::group(['prefix' => 'admin'], function () {
// });
