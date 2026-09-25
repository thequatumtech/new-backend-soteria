<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which is
| assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/terms-and-conditions', [TermsController::class, 'getTerms']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/*
|--------------------------------------------------------------------------
| Client Public APIs
|--------------------------------------------------------------------------
|
| SetLocale is now included in the global API middleware group in Kernel.php.
| Therefore all API routes automatically use the selected language.
|
*/

// login
Route::post('/login', [ClientController::class, 'Login']);
Route::post('/userRegister', [ClientController::class, 'user_register']);
Route::post('/updateProfile', [ClientController::class, 'updateProfile']);

Route::post('forgot-otp-send', [ClientController::class, 'sendForgotOtp']);
Route::post('forgot-password', [ClientController::class, 'forgotPassword']);

Route::post('register-otp-send', [ClientController::class, 'sendRegisterOtp']);


/*
|--------------------------------------------------------------------------
| Authenticated Client APIs
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['apitoken']], function () {

    Route::post('/change-language', [ClientController::class, 'changeLanguage']);
    Route::post('/save-signature', [ClientController::class, 'saveSignature']);
    Route::post('/generate-final-pdf', [ClientController::class, 'generate_final_pdf']);

    //write by digvijay live chat
    Route::post('/chat/start', [ChatController::class, 'startChat']);
    Route::post('/chat/send', [ChatController::class, 'sendMessage']);
    Route::get('/chat/{chatId}/messages', [ChatController::class, 'getMessages']);
    Route::post('/chat/{chatId}/mark-read', [ChatController::class, 'markAsRead']);
    Route::get('/chat/list', [ChatController::class, 'chatList']);
    //end live chat

    //write by digvijay device token
    Route::post('/device-token', [DeviceTokenController::class, 'store']);
    Route::delete('/device-token', [DeviceTokenController::class, 'destroy']);
    //end device token

    //write by digvijay notification
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    //end notification

    Route::get('/getProfile', [ClientController::class, 'getProfileDetail']);

    Route::get('/logout', [ClientController::class, 'Logout']);

    Route::get('/customers', [CustomerController::class, 'getCustomers']);
    Route::get('/customer/{id}', [CustomerController::class, 'getCustomer']);
    Route::post('/customer', [CustomerController::class, 'createCustomer']);
    Route::put('/customer/{id}', [CustomerController::class, 'updateCustomer']);

    Route::post('/check-blacklist', [BlackListController::class, 'checkBlackList']);

    // MarineInsurance
    Route::post('/marine-insurance', [MarineInsuranceController::class, 'store']);
    Route::put('/marine-insurance/{id}', [MarineInsuranceController::class, 'update']);

    //Accident Insurance Route
    // Route::post('/accident-insurance', [AccidentInsuranceController::class, 'store']);
    // Route::put('/accident-insurance/{id}', [AccidentInsuranceController::class, 'update']);

    //Automative Insurance Route
    Route::post('/automative-insurance', [AutomativeInsuranceController::class, 'store']);
    Route::put('/automative-insurance/{id}', [AutomativeInsuranceController::class, 'update']);

    // home insurance
    Route::post('/home-insurance', [CustomerController::class, 'homeInsurance']);

    //individual-insurance
    Route::post('/individual-insurance', [IndividualController::class, 'individualInsurance']);

    // Motor Insurance plan
    Route::get('/getComprehensivePlan', [MotorInsuranceController::class, 'getComprehensivePlan']);
    Route::get('/getCompulsory3MonthsPlan', [MotorInsuranceController::class, 'getCompulsory3MonthsPlan']);
    Route::get('/getCompulsory6MonthsPlan', [MotorInsuranceController::class, 'getCompulsory6MonthsPlan']);
    Route::get('/getCompulsory9MonthsPlan', [MotorInsuranceController::class, 'getCompulsory9MonthsPlan']);
    Route::get('/getCompulsory12MonthsPlan', [MotorInsuranceController::class, 'getCompulsory12MonthsPlan']);
    Route::get('/getTotalLossPlan', [MotorInsuranceController::class, 'MotorInsurancePlanTotalLossPremium']);

    Route::post('/add-motor-insurance', [MotorInsuranceController::class, 'storeMotorInsurance']);

    //Home Insurance Plan
    Route::get('/getHomeInsurancePlan', [HomeInsuranceController::class, 'getHomeInsurancePlan']);
    Route::post('/add-home-insurance', [HomeInsuranceController::class, 'storeHomeInsurance']);

    //Office Insurance Plan
    Route::get('/getOfficeInsurancePlan', [OfficeInsuranceController::class, 'getOfficeInsurancePlan']);
    Route::post('/add-office-insurance', [OfficeInsuranceController::class, 'storeOfficeInsurance']);

    //Life Insurance Plan
    Route::get('/getLifeInsurancePlan', [LifeInsuranceController::class, 'getLifeInsurancePlan']);
    Route::post('/add-life-insurance', [LifeInsuranceController::class, 'storeLifeInsurance']);

    //CriticalIllness Insurance Plan
    Route::get('/getCriticalIllnessInsurancePlan', [CriticalIllnessInsuranceController::class, 'getCriticalIllnessInsurancePlan']);
    Route::post('/add-critical-illness-insurance', [CriticalIllnessInsuranceController::class, 'storeCriticalIllnessInsurance']);

    //Accident Insurance Plan
    Route::get('/getPersonalAccidentPlanInsurancePlan', [PersonalAccidentInsuranceController::class, 'getPersonalAccidentPlanInsurancePlan']);
    Route::post('/add-personal-accident-insurance', [PersonalAccidentInsuranceController::class, 'storePersonalAccidentInsurance']);

    //InPatient Plan
    Route::get('/getInPatientInsurancePlan', [InPatientController::class, 'getInPatientInsurancePlan']);
    Route::get('/getOutPatientInsurancePlan', [InPatientController::class, 'getOutPatientInsurancePlan']);

    // family insurance
    Route::post('/add-family-medical-insurance', [ClientFamilyMedicalInsuranceController::class, 'storeFamilyMedicalInsurance']);
    Route::post('/add-individual-medical-insurance', [ClientFamilyMedicalInsuranceController::class, 'storeFamilyMedicalInsurance']);

    //Pets Insurance Plan
    Route::get('/getPetsInsurancePlan', [PetInsuranceController::class, 'getPetsInsurancePlan']);
    Route::post('/add-pets-insurance', [PetInsuranceController::class, 'storePetsInsurance']);

    //Dental Insurance Plan
    Route::get('/getDentalInsurancePlan', [DentalInsuranceController::class, 'getDentalInsurancePlan']);
    Route::post('/add-dental-insurance', [DentalInsuranceController::class, 'storeDentalInsurance']);

    //Travel Insurance Plan
    Route::get('/getTravelInsurancePlan', [TravelInsuranceController::class, 'getTravelInsurancePlan']);
    Route::post('/add-travel-insurance', [TravelInsuranceController::class, 'storeTravelInsurance']);

    //Marine Insurance Plan
    Route::get('/getMarineInsurancePlan', [MarineInsuranceController::class, 'getMarineInsurancePlan']);
    Route::post('/add-marine-insurance', [MarineInsuranceController::class, 'storeMarineInsurance']);

    Route::get('/insuranceLimit', [AdminbasicController::class, 'insuranceLimit']);
    Route::get('/insuranceCurrent', [AdminbasicController::class, 'insuranceCurrent']);

    Route::get('/remove-upload-document', [AdminbasicController::class, 'deleteDocument']);
    Route::post('/upload-document', [AdminbasicController::class, 'storeDocument']);

    // getPolicyDetails
    Route::get('/getPolicyDetails', [ClientController::class, 'getPolicyDetails']);

    

    Route::post('/check-policy-renewal', [ClientController::class, 'check_policy_renewal']);

    // Transaction
    Route::post('/store-transaction', [ClientController::class, 'storeTransaction']);

    // Claims
    Route::post('/add-claims', [ClaimsController::class, 'storeClaims']);
    Route::post('/edit-claims', [ClaimsController::class, 'updateClaims']);
    Route::get('/remove-claims-document/{id}', [ClaimsController::class, 'removeClaimsDocument']);
    Route::get('/claims-list', [ClaimsController::class, 'ClaimsList']);
    Route::post('/send-claims-message', [ClaimsController::class, 'sendClaimMessage']);
    Route::get('/claims-chats-list', [ClaimsController::class, 'claimsChatsList']);

    // Contact message
    Route::post('/send-contact-message', [ContactUsController::class, 'sendCantactMessage']);
    Route::get('/contact-chats-list', [ContactUsController::class, 'contactChatsList']);

    // Complaint
    Route::get('/get-insurance-type', [ComplaintController::class, 'getInsuranceType']);
    Route::get('/get-insurance-company', [ComplaintController::class, 'getInsuranceCompany']);
    Route::get('/get-life-insurance-period', [AdminbasicController::class, 'getLifeInsurancePeriod']);

    Route::post('/add-complaint', [ComplaintController::class, 'storeComplaint']);
    Route::get('/get-complaint-list', [ComplaintController::class, 'getComplaintList']);

    Route::get('/get-social-media', [SocialMediaController::class, 'getSocialMedia']);

    // discount-coupons
    Route::get('/get-discount-coupons', [DiscountCouponController::class, 'getDiscountCoupons']);
    Route::post('/get-discount-amount', [DiscountCouponController::class, 'getDiscountAmount']);

    Route::post('/change-password', [ClientController::class, 'changePassword']);

    // terms
    // Route::get('/terms-and-conditions', [TermsController::class, 'getTerms']);
});


/*
|--------------------------------------------------------------------------
| Admin Basic / Public APIs
|--------------------------------------------------------------------------
*/

Route::get('/get-banner', [BannersController::class, 'getBanner']);
Route::get('/get-ages', [AdminbasicController::class, 'getAges']);
Route::get('/get-occupations', [AdminbasicController::class, 'getOccupations']);
Route::get('/get-chronic-disease', [AdminbasicController::class, 'getChronicDisease']);
Route::get('/get-claim-status', [AdminbasicController::class, 'getClaimStatus']);
Route::get('/get-country', [AdminbasicController::class, 'getCountry']);
Route::get('/get-district', [AdminbasicController::class, 'getDistrict']);
Route::get('/get-city', [AdminbasicController::class, 'getCities']);
Route::get('/get-complaint-status', [AdminbasicController::class, 'getComplaintStatus']);
Route::get('/get-dangerous-activities', [AdminbasicController::class, 'getDangerousActivities']);
Route::get('/get-engine-capacity', [AdminbasicController::class, 'getEngineCapacity']);
Route::get('/get-engine-type', [AdminbasicController::class, 'getEngineType']);
Route::get('/get-insurance-period', [AdminbasicController::class, 'getInsurancePeriod']);
Route::get('/get-medical-network', [AdminbasicController::class, 'getMedicalNetwork']);
Route::get('/get-motor-plan', [AdminbasicController::class, 'getMotorPlan']);
Route::get('/get-protection-system', [AdminbasicController::class, 'getProtectionSystem']);
Route::get('/get-in-patient-deductible', [AdminbasicController::class, 'getInPatientDeductible']);
Route::get('/get-out-patient-deductible', [AdminbasicController::class, 'getOutPatientDeductible']);
Route::get('/get-number-of-visits', [AdminbasicController::class, 'getNumberOfVisits']);
Route::get('/get-claim-deductible', [AdminbasicController::class, 'getClaimDeductible']);
Route::get('/get-language', [AdminbasicController::class, 'getLanguage']);
Route::get('/get-nationality', [AdminbasicController::class, 'getNationality']);
Route::get('/get-currency', [AdminbasicController::class, 'getCurrency']);
Route::get('/get-geographical-area', [AdminbasicController::class, 'getGeographicalArea']);
Route::get('/get-vehicle-brands', [AdminbasicController::class, 'getVehicleBrands']);
Route::get('/get-vehicle-category', [AdminbasicController::class, 'getVehicleCategory']);
Route::get('/get-vehicle-color', [AdminbasicController::class, 'getVehicleColor']);
Route::get('/get-vehicle-type', [AdminbasicController::class, 'getVehicleType']);
Route::get('/get-type-cover', [AdminbasicController::class, 'getTypeCover']);
Route::get('/get-item-category', [AdminbasicController::class, 'getItemCategory']);
Route::get('/get-item-subcategory', [AdminbasicController::class, 'getItemSubcategory']);
Route::post('/check-dangerous-activity', [AdminbasicController::class, 'checkDangerousActivity']);

Route::get('/pet-breeds', [PetBreedController::class, 'index']);
Route::get('/pet-breeds/{id}', [PetBreedController::class, 'show']);
