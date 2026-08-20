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

class AdminbasicController extends Controller
{
    public function getAges(Request $request)
    {
        try {
            $data = Ages::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Ages successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getChronicDisease(Request $request)
    {
        try {
            $data = ChronicDisease::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Chronic Disease successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getClaimStatus(Request $request)
    {
        try {
            $data = ClaimStatus::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Claim Status successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getCountry(Request $request)
    {
        try {
            $data = Country::orderBy('name', 'asc')->get();

            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Country successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
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

            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get District successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
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

            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Cities successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
        }
    }
    public function getComplaintStatus(Request $request)
    {
        try {
            $data = ComplaintStatus::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Complaint Status successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getDangerousActivities(Request $request)
    {
        try {
            $data = DangerousActivities::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Dangerous Activities successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getEngineCapacity(Request $request)
    {
        try {
            $data = EngineCapacity::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Engine Capacity successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getEngineType(Request $request)
    {
        try {
            $data = EngineType::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Engine Type successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getInsurancePeriod(Request $request)
    {
        try {
            $data = InsurancePeriod::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Insurance Period successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
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
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Medical Network successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getMotorPlan(Request $request)
    {
        try {
            $data = MotorPlan::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Motor Plan successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getProtectionSystem(Request $request)
    {
        try {
            $data = ProtectionSystem::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Protection System successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getInPatientDeductible(Request $request)
    {
        try {
            $data = InPatientDeductible::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get In-Patient Deductible successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getOutPatientDeductible(Request $request)
    {
        try {
            $data = OutPatientDeductible::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Out-Patient Deductible successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getNumberOfVisits(Request $request)
    {
        try {
            $data = NoOfVisit::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Number Of Visits successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getClaimDeductible(Request $request)
    {
        try {
            $data = ClaimDeductible::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Claim Deductible successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getOccupations(Request $request)
    {
        try {
            $data = Occupations::orderBy('name', 'asc')->get();

            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Occupations successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
        }
    }
    public function getLanguage(Request $request)
    {
        try {
            $data = Language::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Language successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getNationality(Request $request)
    {
        try {
            $data = Nationality::orderBy('name', 'asc')->get();

            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Nationality successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
        }
    }
    public function getCurrency(Request $request)
    {
        try {
            $data = Currency::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Currency successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getGeographicalArea(Request $request)
    {
        try {
            $data = GeographicalArea::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Geographical Area successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getVehicleBrands(Request $request)
    {
        try {
            $data = VehicleBrand::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Vehicle Brands successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getVehicleCategory(Request $request)
    {
        try {
            $data = VehicleCategory::where('vehicle_brand_id', $request->brand_id)->get();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Vehicle Category successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getVehicleColor(Request $request)
    {
        try {
            $data = VehicleColor::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Vehicle Color successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getTypeCover(Request $request)
    {
        try {
            $data = TypeOfCover::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Type Of Cover successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getItemCategory(Request $request)
    {
        try {
            $data = InsuredItemCategory::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Insured Item Category successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getItemSubcategory(Request $request)
    {
        try {
            $data = InsuredItemSubCategory::where('insured_item_category_id', $request->id)->get();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Insured Item Subategory successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function insuranceLimit(Request $request)
    {
        try {
            $data = [];
            if ($request->insurance_type == 1) {
                $plan = HomePlan::getHomeInsurancePlanLimit();
                $data = [];

                foreach ($plan as $val) {
                    $plan_name = HomePlan::getHomeInsurancePlanByLimit($val['limit']);
                    $data[] = [
                        'limit' => $val['limit'],
                        'plan_name' => $plan_name
                    ];
                }
            } elseif ($request->insurance_type == 2) {
                $plan = OfficePlan::getOfficeInsurancePlanLimit();
                $data = [];

                foreach ($plan as $val) {
                    $plan_name = OfficePlan::getOfficeInsurancePlanByLimit($val['limit']);
                    $data[] = [
                        'limit' => $val['limit'],
                        'plan_name' => $plan_name
                    ];
                }
            } elseif ($request->insurance_type == 3) {
                // DB::enableQueryLog();
                $plan = LifePlan::getLifeInsurancePlanLimit();
                $data = [];

                foreach ($plan as $val) {
                    $plan_name = LifePlan::getLifeInsurancePlanByLimit($val['limit']);
                    $data[] = [
                        'limit' => $val['limit'],
                        'plan_name' => $plan_name
                    ];
                }
                // dd(\DB::getQueryLog());
            } elseif ($request->insurance_type == 4) {
                $plan = CriticalIllnessPlan::getCriticalIllnessInsurancePlanLimit();
                $data = [];

                foreach ($plan as $val) {
                    $plan_names = CriticalIllnessPlan::getCriticalIllnessInsurancePlanByLimit($val['limit']);
                    $data[] = [
                        // 'limit'     => (int)$val['limit'],
                        // 'limit'     => (int) str_replace(',', '', $val['limit']),
                        'limit'     => $val['limit'],
                        'plan_name' => $plan_names->map(fn($p) => [
                            'plan_name'          => $p->plan_name,
                            'policy_period' => $p->policy_period,
                        ])->values()
                    ];
                }
            } elseif ($request->insurance_type == 5) {
                $plan = PersonalAccidentPlan::getPersonalAccidentInsurancePlanLimit();
                $data = [];

                foreach ($plan as $val) {
                    $plan_name = PersonalAccidentPlan::getPersonalAccidentInsurancePlanByLimit($val['limit']);
                    $data[] = [
                        'limit' => $val['limit'],
                        'plan_name' => $plan_name
                    ];
                }
            } elseif ($request->insurance_type == 6) {
                $plan = InPatientPlan::getInPatientPlanLimit();
                $data = [];

                foreach ($plan as $val) {
                    $plan_names = InPatientPlan::getInPatientInsurancePlanByLimit($val['limit']);
                    $data[] = [
                        'limit'     => $val['limit'],
                        'plan_name' => $plan_names->map(fn($p) => [
                            'plan_name'     => $p->plan_name,
                            'policy_period' => $p->policy_period,
                        ])->values()
                    ];
                }
            } elseif ($request->insurance_type == 7) {
                $plan = InOutPatientPlan::getInOutPatientPlanLimit();
                $data = [];

                foreach ($plan as $val) {
                    $plan_names = InOutPatientPlan::getInOutPatientInsurancePlanByLimit($val['limit']);
                    $data[] = [
                        'limit'     => $val['limit'],
                        'plan_name' => $plan_names->map(fn($p) => [
                            'plan_name'     => $p->plan_name,
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
                        'limit'     => $val['limit'],
                        'plan_name' => $plan_names->map(fn($p) => [
                            'plan_name'     => $p->plan_name,
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
                            'plan_name'     => $p->plan_name,
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
                   $query = TravelPlan::select('plan_name', 'policy_period')
                    ->whereHas('insurance_company', function ($q) {
                        $q->whereNull('deleted_at');
                    });

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
                        'plan_name'     => $val->plan_name,
                        'policy_period' => $val->policy_period,
                    ];
                }
            } elseif ($request->insurance_type == 11) {
                $plan = MarinePlan::getMarinePlanLimit();
                $data = [];

                foreach ($plan as $val) {
                    $plan_names = MarinePlan::getMarineInsurancePlanByLimit($val['limit']);
                    $data[] = [
                        'limit'     => $val['limit'],
                        'plan_name' => $plan_names->map(fn($p) => [
                            'plan_name'     => $p->plan_name,
                            'policy_period' => $p->policy_period,
                        ])->values()
                    ];
                }
            } elseif ($request->insurance_type == 12) {
                $plan = MotorInsurancePlan::getMotorPlanLimit();
                $data = [];

                foreach ($plan as $val) {
                    $plan_names = MotorInsurancePlan::getMotorInsurancePlanByLimit($val->limit);
                    $data[] = [
                        'limit'     => $val->limit,
                        'plan_name' => $plan_names->map(fn($p) => [
                            'plan_name'     => $p->plan_name,
                            'policy_period' => $p->policy_period,
                        ])->values()
                    ];
                }
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Get Insurance Limit successfully',
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

            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Store Document successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
        }
    }

    public function deleteDocument(Request $request)
    {
        try {
            $filePath = $request->input('document');
            if (file_exists(public_path($filePath))) {
                unlink(public_path($filePath));
            } else {
                return response()->json(['status' => false, 'status_code' => 404, 'message' => 'File not found', 'data' => []]);
            }
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Document deleted successfully', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
        }
    }
    public function getVehicleType(Request $request)
    {
        try {
            $data = VehicleType::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Vehicle Type successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function insuranceCurrent(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $data = PurchasePolicy::where('client_id', $user_id)->where('policy_type', $request->insurance_type)->orderBy('expiry_date', 'DESC')->first();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Policy Successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
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
                ? 'All activities are safe.'
                : 'Some activities are dangerous.'
        ], empty($dangerous) ? 200 : 403);
    }
}
