<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{VehicleCategory, Ages, ChronicDisease, Country, District, Cities, ClaimStatus, ComplaintStatus, DangerousActivities, EngineCapacity, EngineType, InsurancePeriod, MedicalNetwork, MotorPlan, ProtectionSystem, InPatientDeductible, NoOfVisit, OutPatientDeductible, ClaimDeductible, Occupations, Language, Nationality, Currency, GeographicalArea, VehicleBrand, VehicleColor, VehicleType, TypeOfCover, InsuredItemCategory, InsuredItemSubCategory, PurchasePolicy};
use App\Models\InsurancePlanModels\{HomePlan, LifePlan, CriticalIllnessPlan, PersonalAccidentPlan, InPatientPlan, InOutPatientPlan, PetPlan, DentalPlan, TravelPlan, MarinePlan, MotorInsurancePlan};
use App\Models\InsurancePlanModels\OfficePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers\InsurancePlanHelper;
use App\Models\InsuranceCompany;
use App\Models\Client;

class AdminbasicController extends Controller
{
    public function getAges(Request $request)
    {
        try {
            $data = Ages::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_ages_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getChronicDisease(Request $request)
    {
        try {
            $data = ChronicDisease::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_chronic_disease_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getClaimStatus(Request $request)
    {
        try {
            $data = ClaimStatus::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_claim_status_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getCountry(Request $request)
    {
        try {
            $data = Country::orderBy('name', 'asc')->get();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_country_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }

    public function getDistrict(Request $request)
    {
        try {
            if ($request->city_id) {
                $data = District::where('city_id', $request->city_id)->orderBy('name', 'asc')->get();
            } else {
                $data = District::orderBy('name', 'asc')->get();
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_district_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }

    public function getCities(Request $request)
    {
        try {
            if ($request->country_id) {
                $data = Cities::where('country_id', $request->country_id)->orderBy('name', 'asc')->get();
            } else {
                $data = Cities::orderBy('name', 'asc')->get();
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_cities_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }

    public function getComplaintStatus(Request $request)
    {
        try {
            $data = ComplaintStatus::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_complaint_status_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getDangerousActivities(Request $request)
    {
        try {
            $data = DangerousActivities::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_dangerous_activities_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getEngineCapacity(Request $request)
    {
        try {
            $data = EngineCapacity::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_engine_capacity_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getEngineType(Request $request)
    {
        try {
            $data = EngineType::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_engine_type_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getInsurancePeriod(Request $request)
    {
        try {
            $data = InsurancePeriod::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_insurance_period_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getLifeInsurancePeriod(Request $request)
    {
        $periods = [];

        for ($i = 1; $i <= 13; $i++) {
            $periods[] = [
                'id' => $i,
                'name' => $i . ' year' . ($i > 1 ? 's' : ''),
            ];
        }

        return response()->json($periods);
    }

    public function getMedicalNetwork(Request $request)
    {
        try {
            $data = MedicalNetwork::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_medical_network_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getMotorPlan(Request $request)
    {
        try {
            $data = MotorPlan::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_motor_plan_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getProtectionSystem(Request $request)
    {
        try {
            $data = ProtectionSystem::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_protection_system_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getInPatientDeductible(Request $request)
    {
        try {
            $data = InPatientDeductible::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_in_patient_deductible_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getOutPatientDeductible(Request $request)
    {
        try {
            $data = OutPatientDeductible::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_out_patient_deductible_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getNumberOfVisits(Request $request)
    {
        try {
            $data = NoOfVisit::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_number_of_visits_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getClaimDeductible(Request $request)
    {
        try {
            $data = ClaimDeductible::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_claim_deductible_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getOccupations(Request $request)
    {
        try {
            $data = Occupations::orderBy('name', 'asc')->get();

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_occupations_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }

    public function getLanguage(Request $request)
    {
        try {
            $data = Language::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_language_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getNationality(Request $request)
    {
        try {
            $data = Nationality::orderBy('name', 'asc')->get();

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_nationality_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }

    public function getCurrency(Request $request)
    {
        try {
            $data = Currency::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_currency_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getGeographicalArea(Request $request)
    {
        try {
            $data = GeographicalArea::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_geographical_area_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getVehicleBrands(Request $request)
    {
        try {
            $data = VehicleBrand::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_vehicle_brands_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getVehicleCategory(Request $request)
    {
        try {
            $data = VehicleCategory::where('vehicle_brand_id', $request->brand_id)->get();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_vehicle_category_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getVehicleColor(Request $request)
    {
        try {
            $data = VehicleColor::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_vehicle_color_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getTypeCover(Request $request)
    {
        try {
            $data = TypeOfCover::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_type_of_cover_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getItemCategory(Request $request)
    {
        try {
            $data = InsuredItemCategory::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_insured_item_category_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function getItemSubcategory(Request $request)
    {
        try {
            $data = InsuredItemSubCategory::where('insured_item_category_id', $request->id)->get();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_insured_item_subcategory_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function insuranceLimit(Request $request)
    {
        $client = Client::find($request->user_id);

        if (!$client) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => __('messages.api.client_not_found'),
                'data' => [],
                'total' => 0,
            ]);
        }

        $currency = Currency::where(
            'country_id',
            $client->country_id
        )->first();

        if (!$currency) {
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.no_currency_found'),
                'data' => [],
                'total' => 0,
                'currency' => null,
            ]);
        }

        try {
            $data = [];

            if ($request->insurance_type == 1) {
                $plan = $plan = HomePlan::getHomeInsurancePlanLimit(
                    $request->user_id
                );
                $data = [];

                foreach ($plan as $val) {
                    $plan_name = HomePlan::getHomeInsurancePlanByLimit(
                        $val['limit'],
                        $request->user_id
                    );

                    $data[] = [
                        'limit' => $val['limit'],
                        'plan_name' => $plan_name
                    ];
                }
            } elseif ($request->insurance_type == 2) {
                // $plan = OfficePlan::getOfficeInsurancePlanLimit();
                $plan = OfficePlan::getOfficeInsurancePlanLimit($request->user_id);

                $data = [];

                foreach ($plan as $val) {
                    $plan_name = OfficePlan::getOfficeInsurancePlanByLimit($val['limit'], $request->user_id);
                    // $plan_name = OfficePlan::getOfficeInsurancePlanByLimit($val['limit']);
                    $data[] = [
                        'limit' => $val['limit'],
                        'plan_name' => $plan_name
                    ];
                }
            } elseif ($request->insurance_type == 3) {
                // DB::enableQueryLog();
                // $plan = LifePlan::getLifeInsurancePlanLimit();
                $plan = LifePlan::getLifeInsurancePlanLimit($request->user_id);
                $data = [];

                foreach ($plan as $val) {
                    $plan_name = LifePlan::getLifeInsurancePlanByLimit($val['limit'], $request->user_id);
                    // $plan_name = LifePlan::getLifeInsurancePlanByLimit($val['limit']);
                    $data[] = [
                        'limit' => $val['limit'],
                        'plan_name' => $plan_name
                    ];
                }
                // dd(\DB::getQueryLog());
            } elseif ($request->insurance_type == 4) {
                // $plan = CriticalIllnessPlan::getCriticalIllnessInsurancePlanLimit();
                $plan = CriticalIllnessPlan::getCriticalIllnessInsurancePlanLimit($request->user_id);
                $data = [];

                foreach ($plan as $val) {
                    // $plan_names = CriticalIllnessPlan::getCriticalIllnessInsurancePlanByLimit($val['limit']);
                    $plan_names = CriticalIllnessPlan::getCriticalIllnessInsurancePlanByLimit($val['limit'], $request->user_id);

                    $data[] = [
                        // 'limit'     => (int)$val['limit'],
                        // 'limit'     => (int) str_replace(',', '', $val['limit']),
                        'limit'     => $val['limit'],
                        'plan_name' => $plan_names->map(fn($p) => [
                            'plan_name' => $p->plan_name,
                            'policy_period' => $p->policy_period,
                        ])->values()
                    ];
                }
            } elseif ($request->insurance_type == 5) {
                // $plan = PersonalAccidentPlan::getPersonalAccidentInsurancePlanLimit();
                $plan = PersonalAccidentPlan::getPersonalAccidentInsurancePlanLimit(
                    $request->user_id
                );

                $data = [];

                foreach ($plan as $val) {
                    // $plan_name = PersonalAccidentPlan::getPersonalAccidentInsurancePlanByLimit($val['limit']);
                    $plan_name = PersonalAccidentPlan::getPersonalAccidentInsurancePlanByLimit(
                        $val['limit'],
                        $request->user_id
                    );

                    $data[] = [
                        'limit' => $val['limit'],
                        'plan_name' => $plan_name
                    ];
                }
            } elseif ($request->insurance_type == 6) {
                // $plan = InPatientPlan::getInPatientPlanLimit();
                $plan = InPatientPlan::getInPatientPlanLimit(
                    $request->user_id
                );

                $data = [];

                foreach ($plan as $val) {
                    // $plan_names = InPatientPlan::getInPatientInsurancePlanByLimit($val['limit']);

                    $plan_names = InPatientPlan::getInPatientInsurancePlanByLimit(
                        $val['limit'],
                        $request->user_id
                    );

                    $data[] = [
                        'limit' => $val['limit'],
                        'plan_name' => $plan_names->map(fn($p) => [
                            'plan_name' => $p->plan_name,
                            'policy_period' => $p->policy_period,
                        ])->values()
                    ];
                }
            } elseif ($request->insurance_type == 7) {
                // $plan = InOutPatientPlan::getInOutPatientPlanLimit();
                $plan = InOutPatientPlan::getInOutPatientPlanLimit(
                    $request->user_id
                );

                $data = [];

                foreach ($plan as $val) {
                    // $plan_names = InOutPatientPlan::getInOutPatientInsurancePlanByLimit($val['limit']);
                    $plan_names = InOutPatientPlan::getInOutPatientInsurancePlanByLimit(
                        $val['limit'],
                        $request->user_id
                    );

                    $data[] = [
                        'limit' => $val['limit'],
                        'plan_name' => $plan_names->map(fn($p) => [
                            'plan_name' => $p->plan_name,
                            'policy_period' => $p->policy_period,
                        ])->values()
                    ];
                }
            } elseif ($request->insurance_type == 8) {
                $plan = PetPlan::getPetPlanLimit();
                $data = [];

                foreach ($plan as $val) {
                    $plan_names = PetPlan::getPetInsurancePlanByLimit($val['limit']);
                    $data[] = [
                        'limit' => $val['limit'],
                        'plan_name' => $plan_names->map(fn($p) => [
                            'plan_name' => $p->plan_name,
                            'policy_period' => $p->policy_period,
                        ])->values()
                    ];
                }
            } elseif ($request->insurance_type == 9) {
                $plan = DentalPlan::getDentalPlanLimit();
                $data = [];

                foreach ($plan as $val) {
                    $plan_names = DentalPlan::getDentalInsurancePlanByLimit($val['limit']);
                    $data[] = [
                        'limit'     => $val['limit'],
                        'plan_name' => $plan_names->map(fn($p) => [
                            'plan_name' => $p->plan_name,
                            'policy_period' => $p->policy_period,
                        ])->values()
                    ];
                }
            }
            // elseif ($request->insurance_type == 10) {
            //     $plan = TravelPlan::getTravelPlanLimit();
            //     $data = [];

            //     foreach ($plan as $val) {
            //         $plan_name = TravelPlan::getTravelInsurancePlanByLimit($val['limit']);
            //         $data[] = [
            //             'limit' => (int)$val['limit'],
            //             'plan_name' => $plan_name
            //         ];
            //     }
            // }
            elseif ($request->insurance_type == 10) {
                $data = [];

                // $plan_names = TravelPlan::select('plan_name', 'policy_period')->get();
                //    $query = TravelPlan::select('plan_name', 'policy_period')
                //     ->whereHas('insurance_company', function ($q) {
                //         $q->whereNull('deleted_at');
                //     });
                $query = TravelPlan::select(
                    'plan_name',
                    'policy_period'
                );

                // Filter according to client's country currency
                $query = InsurancePlanHelper::filterByClientCurrency(
                    $query,
                    $request->user_id
                );

                if ($request->has('destination_country_id') && !empty($request->destination_country_id)) {
                    $query->where(function ($q) use ($request) {
                        $q->whereJsonContains('countries', (string)$request->destination_country_id)
                            ->orWhereJsonContains('countries', (int)$request->destination_country_id)
                            ->orWhereHas('geographical_area', function ($q2) use ($request) {
                                $q2->whereJsonContains('countries', (string)$request->destination_country_id)
                                    ->orWhereJsonContains('countries', (int)$request->destination_country_id);
                            });
                    });
                }

                $plan_names = $query->groupBy('plan_name', 'policy_period')->get();

                foreach ($plan_names as $val) {
                    $data[] = [
                        'plan_name' => $val->plan_name,
                        'policy_period' => $val->policy_period,
                    ];
                }
            } elseif ($request->insurance_type == 11) {
                // $plan = MarinePlan::getMarinePlanLimit();
                $plan = MarinePlan::getMarinePlanLimit(
                    $request->user_id
                );

                $data = [];

                foreach ($plan as $val) {
                    // $plan_names = MarinePlan::getMarineInsurancePlanByLimit($val['limit']);
                    $plan_names = MarinePlan::getMarineInsurancePlanByLimit(
                        $val['limit'],
                        $request->user_id
                    );

                    $data[] = [
                        'limit' => $val['limit'],
                        'plan_name' => $plan_names->map(fn($p) => [
                            'plan_name' => $p->plan_name,
                            'policy_period' => $p->policy_period,
                        ])->values()
                    ];
                }
            } elseif ($request->insurance_type == 12) {
                // $plan = MotorInsurancePlan::getMotorPlanLimit();
                $plan = MotorInsurancePlan::getMotorPlanLimit(
                    $request->user_id
                );

                $data = [];

                foreach ($plan as $val) {
                    // $plan_names = MotorInsurancePlan::getMotorInsurancePlanByLimit($val->limit);
                    $plan_names = MotorInsurancePlan::getMotorInsurancePlanByLimit(
                        $val->limit,
                        $request->user_id
                    );

                    $data[] = [
                        'limit' => $val->limit,
                        'plan_name' => $plan_names->map(fn($p) => [
                            'plan_name' => $p->plan_name,
                            'policy_period' => $p->policy_period,
                        ])->values()
                    ];
                }
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_insurance_limit_successfully'),
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }

    public function storeDocument(Request $request)
    {
        try {
            $data = [];

            // Function to generate a unique filename
            $generateUniqueFileName = function ($file) {
                return uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            };

            // Define the folder paths for each insurance type
            $insuranceFolders = [
                1  => 'home_insurance',
                2  => 'office_insurance',
                3  => 'life_insurance',
                4  => 'CriticalIllness_insurance',
                5  => 'PersonalAccident_insurance',
                6  => 'individual_medical_insurance',
                7  => 'family_medical_insurance',
                8  => 'Pet_insurance',
                9  => 'Dental_insurance',
                10 => 'Travel_insurance',
                11 => 'Marine Insurance',
                12 => 'Motor Insurance'
            ];

            // Check if there is a file and a valid insurance type
            if ($request->hasFile('document') && isset($insuranceFolders[$request->insurance_type])) {
                $folder = 'uploads/' . $insuranceFolders[$request->insurance_type]; // Get the correct folder based on insurance type

                // If multiple files are uploaded, handle each file
                if (is_array($request->file('document'))) {
                    $files = $request->file('document');
                    $paths = [];

                    foreach ($files as $file) {
                        $filename = $generateUniqueFileName($file);
                        $file->move($folder, $filename); // Move file to its respective folder
                        $paths[] = $folder . '/' . $filename;
                    }

                    // Store paths as a comma-separated string
                    $data['document'] = implode(',', $paths);
                } else {
                    // Single file upload case
                    $file = $request->file('document');
                    $filename = $generateUniqueFileName($file);
                    $file->move($folder, $filename);
                    $data['document'] = $folder . '/' . $filename;
                }
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.store_document_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }

    // public function deleteDocument(Request $request)
    // {
    //     try {
    //         $filePath = $request->input('document');
    //         if (file_exists(public_path($filePath))) {
    //             unlink(public_path($filePath));
    //         } else {
    //             return response()->json(['status' => false, 'status_code' => 404, 'message' => 'File not found', 'data' => []]);
    //         }
    //         return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Document deleted successfully', 'data' => []]);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
    //     }
    // }

    public function deleteDocument(Request $request)
    {
        try {
            $filePath = $request->input('document');

            // Check if document parameter is empty
            if (empty($filePath)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => __('messages.api.document_path_required'),
                    'data' => []
                ], 400);
            }

            $fullPath = public_path($filePath);

            // Check that it is actually a file
            if (!file_exists($fullPath)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => __('messages.api.file_not_found'),
                    'data' => []
                ], 404);
            }

            if (!is_file($fullPath)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => __('messages.api.document_path_not_file'),
                    'data' => []
                ], 400);
            }

            unlink($fullPath);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.document_deleted_successfully'),
                'data' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function getVehicleType(Request $request)
    {
        try {
            $data = VehicleType::all();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_vehicle_type_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function insuranceCurrent(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $data = PurchasePolicy::where('client_id', $user_id)
                ->where('policy_type', $request->insurance_type)
                ->orderBy('expiry_date', 'DESC')
                ->first();

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_policy_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    // public function checkDangerousActivity(Request $request)
    // {
    //     $request->validate([
    //         'activity_id' => 'required|integer'
    //     ]);

    //     $exists = DangerousActivities::where('id', $request->activity_id)->exists();

    //     if ($exists) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'You cannot buy this insurance. Activity is dangerous.'
    //         ], 403);
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'You can buy this insurance.'
    //     ], 200);
    // }

    public function checkDangerousActivity(Request $request)
    {
        $request->validate([
            'activity_ids' => 'required|array',
            'activity_ids.*' => 'integer'
        ]);

        $dangerous = DangerousActivities::whereIn('id', $request->activity_ids)->pluck('id')->toArray();
        $safe = array_diff($request->activity_ids, $dangerous);

        return response()->json([
            'status' => empty($dangerous),
            'dangerous_ids' => $dangerous,
            'safe_ids' => $safe,
            'message' => empty($dangerous)
                ? __('messages.api.all_activities_safe')
                : __('messages.api.some_activities_dangerous')
        ], empty($dangerous) ? 200 : 403);
    }
}
