<?php

namespace App\Http\Controllers;

use App\Models\BlackListDetail;
use App\Models\Cities;
use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\ClientMessage;
use App\Models\Country;
use App\Models\District;
use App\Models\Nationality;
use App\Models\Occupations;
use App\Models\PurchasePolicy;
use App\Rules\AdultRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\ClientHomeInsurance;
use App\Models\ClientOfficeInsurance;
use App\Models\ClientLifeInsurance;
use App\Models\CriticalIllnessInsurance;
use App\Models\ClientPersonalAccidentInsurance;
use App\Models\ClientTravelInsurance;
use App\Models\ClientMarineInsurance;
use App\Models\ClientDentalsInsurance;
use App\Models\ClientPetsInsurance;
use App\Models\ClientFamilyMedicalInsurance;
use App\Models\ClientMotorInsurance;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::with('agent')->where('is_blacklisted', 2)->get();
        return view('admin.client.client', compact('clients'));
    }
    // public function purchased_policy(Request $request, $id)
    // {
    //     $purchased_policy = PurchasePolicy::with(['client', 'insurance_company'])
    //         ->where('client_id', $id)
    //         ->get();

    //     if ($purchased_policies->isEmpty()) {
    //         return response()->json([
    //             'purchased_policy' => [],
    //             'status' => 404,
    //             'message' => 'No purchased policies found for this client.'
    //         ]);
    //     }

    //     $models = [
    //         ClientHomeInsurance::class,
    //         ClientOfficeInsurance::class,
    //         ClientLifeInsurance::class,
    //         CriticalIllnessInsurance::class,
    //         ClientPersonalAccidentInsurance::class,
    //         ClientTravelInsurance::class,
    //         ClientMarineInsurance::class,
    //         ClientDentalsInsurance::class,
    //         ClientPetsInsurance::class,
    //         ClientFamilyMedicalInsurance::class,
    //         ClientMotorInsurance::class,
    //     ];

    //     $result = [];

    //     foreach ($purchased_policies as $policy) {
    //         $policyNo = $policy->policy_no;
    //         $policyDetails = null;
    //         $sourceTable = null;

    //         foreach ($models as $model) {
    //             $record = $model::select(
    //                 'police_no',
    //                 'first_name',
    //                 'last_name',
    //                 'third_name',
    //                 'family_name',
    //                 'gender',
    //                 'birth_date'
    //             )
    //                 ->where('police_no', $policyNo)
    //                 ->first();

    //             if ($record) {
    //                 $policyDetails = $record;
    //                 $sourceTable = $record->getTable();
    //                 break;
    //             }
    //         }

    //         $result[] = [
    //             'purchase_policy' => $policy,
    //             'details' => $policyDetails,
    //             'source_table' => $sourceTable,
    //         ];
    //     }

    //     return response()->json([
    //         'purchased_policy' => $result,
    //         'status' => 200
    //     ]);
    // }

    public function purchased_policy(Request $request, $id)
    {
        $purchased_policy = PurchasePolicy::with(['client', 'insurance_company'])
            ->where('client_id', $id)
            ->get();

        if ($purchased_policy->isNotEmpty()) {

            //  Add details to each policy
            $purchased_policy->transform(function ($policy) {
                $policy->details = [
                    'police_no'   => $policy->policy_no ?? 'N/A',
                    'first_name'  => $policy->client->first_name ?? 'N/A',
                    'last_name'   => $policy->client->surname ?? 'N/A',
                    'third_name'  => $policy->client->grandfather_name ?? 'N/A',
                    'family_name' => $policy->client->father_name ?? 'N/A',
                    'gender' => ($policy->client->gender == 1) ? 'Male' : (($policy->client->gender == 2) ? 'Female' : 'N/A'),
                    'birth_date'  => $policy->client->birth_date ?? 'N/A',
                ];
                return $policy;
            });

            return response()->json([
                'purchased_policy' => $purchased_policy,
                'status' => 200
            ]);
        }

        return response()->json([
            'purchased_policy' => [],
            'status' => 404
        ]);
    }


    public function view(Request $request)
    {
        dd('View');
    }

    public function add(Request $request)
    {
        $nationalities = Nationality::all();
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $occupations = Occupations::all();
        return view('admin.client.add_client', compact('nationalities', 'countries', 'cities', 'districts', 'occupations'));
    }

    public function create(Request $request)
    {
        $clientId = null;
        if ($request->filled('client_id')) {
            $clientId = $request->client_id;
        }
        try {
            // $request->validate([
            //     'first_name' => 'required',
            //     'father_name' => 'required',
            //     'grandfather_name' => 'required',
            //     'surname' => 'required',
            //     'language' => 'required',
            //     'nationality_id' => 'required',
            //     'national_id_number' => 'required',
            //     'residence_id_number' => 'required',
            //     'birth_date' => ['required', 'date', new AdultRule],
            //     'gender' => 'required',
            //     'marital_status' => 'required',
            //     'email_id' => ['required', 'email', 'unique:clients,email_id,' . $clientId . ',id,deleted_at,NULL'],
            //     'mobile_no' => ['required', 'numeric', 'unique:clients,mobile_no,' . $clientId . ',id,deleted_at,NULL'],
            //     'country_id' => 'required|numeric',
            //     'residing_country_same' => 'required',
            //     'city_id' => 'required|numeric',
            //     'district_id' => 'required|numeric',
            //     'street_name' => 'required',
            //     'building_no' => 'required',
            //     'company_name' => 'required',
            //     'occupation_id' => 'required|numeric',
            //     'work_nature' => 'required',
            //     'company_city_id' => 'required|numeric',
            //     'company_district_id' => 'required|numeric',
            //     'company_street_name' => 'required',
            //     'company_building_no' => 'required',
            //     'company_contact_no' => 'required',
            //     'id_front' => [$clientId ? 'nullable' : 'required', 'file', 'max:10240', 'image'],
            //     'id_back' => [$clientId ? 'nullable' : 'required', 'file', 'max:10240', 'image'],
            //     'profile_pic' => [$clientId ? 'nullable' : 'required', 'file', 'max:10240', 'image'],
            //     'agent_id' => 'nullable|numeric',
            //     'password' => 'required_without:client_id',
            //     'has_company' => 'required',
            //     'client_company_name' => 'required_if:has_company,1',
            //     'client_company_registered_national_id_no' => 'required_if:has_company,1',
            //     'client_company_registration_no' => 'required_if:has_company,1',
            //     'client_company_country_id' => 'required_if:has_company,1|numeric',
            //     'client_company_city_id' => 'required_if:has_company,1|numeric',
            //     'client_company_district_id' => 'required_if:has_company,1|numeric',
            //     'client_company_street_name' => 'required_if:has_company,1',
            //     'client_company_building_no' => 'required_if:has_company,1',
            //     'client_company_office_no' => 'required_if:has_company,1',
            //     'client_company_telephone_no' => 'required_if:has_company,1',
            //     'client_company_owner_first_name' => 'required_if:has_company,1',
            //     'client_company_owner_father_name' => 'required_if:has_company,1',
            //     'client_company_owner_grandfather_name' => 'required_if:has_company,1',
            //     'client_company_owner_surname' => 'required_if:has_company,1',
            //     'client_company_owner_telephone_no' => 'required_if:has_company,1',
            //     'is_partner' => 'required_if:has_company,1',
            //     'is_authorized' => 'required_if:has_company,1',
            //     'authorized_position' => 'required_if:is_authorized,1',
            //     'is_authorization_in_registration' => 'required_if:is_authorized,1',
            //     'issuer_authorization_document' => ['required_if:has_company,1|required_unless:client_id,null', 'file', 'max:10240', 'mimes:jpeg,png,gif,bmp,pdf'],
            //     'ownership_document' => ['required_if:has_company,1|required_unless:client_id,null', 'file', 'max:10240', 'mimes:jpeg,png,gif,bmp,pdf'],
            //     'career_municipality_license' => ['required_if:has_company,1|required_unless:client_id,null', 'file', 'max:10240', 'mimes:jpeg,png,gif,bmp,pdf'],
            //     'company_tax_certificate' => ['required_if:has_company,1|required_unless:client_id,null', 'file', 'max:10240', 'mimes:jpeg,png,gif,bmp,pdf'],
            //     'practice_certificate' => ['required_if:has_company,1|required_unless:client_id,null', 'file', 'max:10240', 'mimes:jpeg,png,gif,bmp,pdf'],
            // ]/*, $customMessages*/);
 if (empty($request->agent_id)) {
            return back()->withInput()->with('error', 'Agent ID is required.');
        }

        $agentExists = \App\Models\AgentModel::where('agent_code', $request->agent_id)->exists();
        if (!$agentExists) {
            return back()->withInput()->with('error', 'Selected agent does not exist.');
        }

        $request->validate([
            'first_name'          => 'required',
            'father_name'         => 'required',
            'grandfather_name'    => 'required',
            'surname'             => 'required',
            'language'            => 'required',
            'nationality_id'      => 'required',
            'national_id_number'  => 'required',
            'residence_id_number' => 'required',
            'birth_date'          => ['required', 'date', new AdultRule],
            'gender'              => 'required',
            'marital_status'      => 'required',

            // ✅ FIX: Properly ignore current client ID on edit for email & mobile
            'email_id'  => [
                'required',
                'email',
                'unique:clients,email_id,' . ($clientId ?? 'NULL') . ',id,deleted_at,NULL'
            ],
            'mobile_no' => [
                'required',
                'numeric',
                'unique:clients,mobile_no,' . ($clientId ?? 'NULL') . ',id,deleted_at,NULL'
            ],

            'country_id'               => 'required|numeric',
            'residing_country_same'    => 'required',
            'city_id'                  => 'required|numeric',
            'district_id'              => 'required|numeric',
            'street_name'              => 'required',
            'building_no'              => 'required',
            'company_name'             => 'required',
            'occupation_id'            => 'required|numeric',
            'work_nature'              => 'required',
            'company_city_id'          => 'required|numeric',
            'company_district_id'      => 'required|numeric',
            'company_street_name'      => 'required',
            'company_building_no'      => 'required',
            'company_contact_no'       => 'required',

            'id_front'    => [$clientId ? 'nullable' : 'required', 'file', 'max:10240', 'image'],
            'id_back'     => [$clientId ? 'nullable' : 'required', 'file', 'max:10240', 'image'],
            'profile_pic' => [$clientId ? 'nullable' : 'required', 'file', 'max:10240', 'image'],

            // ✅ agent_id is now validated above, keep as nullable here
            'agent_id'    => 'nullable|string',

            'password'    => 'required_without:client_id',
            'has_company' => 'required',

            'client_company_name'                      => 'required_if:has_company,1',
            'client_company_registered_national_id_no' => 'required_if:has_company,1',
            'client_company_registration_no'           => 'required_if:has_company,1',
            'client_company_country_id'                => 'required_if:has_company,1|numeric',
            'client_company_city_id'                   => 'required_if:has_company,1|numeric',
            'client_company_district_id'               => 'required_if:has_company,1|numeric',
            'client_company_street_name'               => 'required_if:has_company,1',
            'client_company_building_no'               => 'required_if:has_company,1',
            'client_company_office_no'                 => 'required_if:has_company,1',
            'client_company_telephone_no'              => 'required_if:has_company,1',
            'client_company_owner_first_name'          => 'required_if:has_company,1',
            'client_company_owner_father_name'         => 'required_if:has_company,1',
            'client_company_owner_grandfather_name'    => 'required_if:has_company,1',
            'client_company_owner_surname'             => 'required_if:has_company,1',
            'client_company_owner_telephone_no'        => 'required_if:has_company,1',
            'is_partner'                               => 'required_if:has_company,1',
            'is_authorized'                            => 'required_if:has_company,1',
            'authorized_position'                      => 'required_if:is_authorized,1',
            'is_authorization_in_registration'         => 'required_if:is_authorized,1',

            'issuer_authorization_document' => ['required_if:has_company,1|required_unless:client_id,null', 'file', 'max:10240', 'mimes:jpeg,png,gif,bmp,pdf'],
            'ownership_document'            => ['required_if:has_company,1|required_unless:client_id,null', 'file', 'max:10240', 'mimes:jpeg,png,gif,bmp,pdf'],
            'career_municipality_license'   => ['required_if:has_company,1|required_unless:client_id,null', 'file', 'max:10240', 'mimes:jpeg,png,gif,bmp,pdf'],
            'company_tax_certificate'       => ['required_if:has_company,1|required_unless:client_id,null', 'file', 'max:10240', 'mimes:jpeg,png,gif,bmp,pdf'],
            'practice_certificate'          => ['required_if:has_company,1|required_unless:client_id,null', 'file', 'max:10240', 'mimes:jpeg,png,gif,bmp,pdf'],
        ]);
            if (!$request->filled('client_id')) {
                $client = new Client();
                $client->first_name = $request->first_name;
                $client->father_name = $request->father_name;
                $client->grandfather_name = $request->grandfather_name;
                $client->surname = $request->surname;
                $client->language = $request->language;
                $client->nationality_id = $request->nationality_id;
                $client->national_id_number = $request->national_id_number;
                $client->residence_id_number = $request->residence_id_number;
                $client->birth_date = $request->birth_date;
                $client->gender = $request->gender;
                $client->marital_status = $request->marital_status;
                $client->email_id = $request->email_id;
                $client->mobile_no = $request->mobile_no;
                $client->country_id = $request->country_id;
                $client->residing_country_same = $request->residing_country_same;
                $client->residing_country_id = $request->residing_country_same == 1 ? $request->country_id : $request->residing_country_id;
                $client->city_id = $request->city_id;
                $client->district_id = $request->district_id;
                $client->street_name = $request->street_name;
                $client->building_no = $request->building_no;
                $client->company_name = $request->company_name;
                $client->occupation_id = $request->occupation_id;
                $client->work_nature = $request->work_nature;
                $client->company_city_id = $request->company_city_id;
                $client->company_district_id = $request->company_district_id;
                $client->company_street_name = $request->company_street_name;
                $client->company_building_no = $request->company_building_no;
                $client->company_contact_no = $request->company_contact_no;
                $client->agent_id = $request->filled('agent_id') ? $request->agent_id : null;
                $client->password = Hash::make($request->password);
                $client->has_company = $request->has_company;
                $client->save();

                $files = [
                    'id_front',
                    'id_back',
                    'profile_pic'
                ];
                foreach ($files as $file) {
                    if ($request->hasFile($file)) {
                        $uploadedFile = $request->file($file);
                        $filename = $uploadedFile->getClientOriginalName(); // Original filename
                        $newFilename = $file . "_file_" . time() . "." . $uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                        $uploadedFile->move(public_path('uploads/clients/' . $client->id), $newFilename);

                        $client->$file = $newFilename; // Store the filename in the database
                        $client->save();
                    }
                }
                if ($request->has_company == 1) {
                    $client_company = new ClientCompany();
                    $client_company->client_id = $client->id;
                    $client_company->client_company_name = $request->client_company_name;
                    $client_company->client_company_registered_national_id_no = $request->client_company_registered_national_id_no;
                    $client_company->client_company_registration_no = $request->client_company_registration_no;
                    $client_company->client_company_country_id = $request->client_company_country_id;
                    $client_company->client_company_city_id = $request->client_company_city_id;
                    $client_company->client_company_district_id = $request->client_company_district_id;
                    $client_company->client_company_street_name = $request->client_company_street_name;
                    $client_company->client_company_building_no = $request->client_company_building_no;
                    $client_company->client_company_office_no = $request->client_company_office_no;
                    $client_company->client_company_telephone_no = $request->client_company_telephone_no;
                    $client_company->client_company_owner_first_name = $request->client_company_owner_first_name;
                    $client_company->client_company_owner_father_name = $request->client_company_owner_father_name;
                    $client_company->client_company_owner_grandfather_name = $request->client_company_owner_grandfather_name;
                    $client_company->client_company_owner_surname = $request->client_company_owner_surname;
                    $client_company->client_company_owner_telephone_no = $request->client_company_owner_telephone_no;
                    $client_company->is_partner = $request->is_partner;
                    $client_company->is_authorized = $request->is_authorized;
                    $client_company->authorized_position = $request->is_authorized == 1 ? $request->authorized_position : null;
                    $client_company->is_authorization_in_registration = $request->is_authorized == 1 ? $request->is_authorization_in_registration : 2;
                    $client_company->save();
                    $company_files = [
                        'issuer_authorization_document',
                        'ownership_document',
                        'career_municipality_license',
                        'company_tax_certificate',
                        'practice_certificate'
                    ];
                    foreach ($company_files as $file) {
                        if ($request->hasFile($file)) {
                            $uploadedFile = $request->file($file);
                            $filename = $uploadedFile->getClientOriginalName(); // Original filename
                            $newFilename = $file . "_company_file_" . time() . "." . $uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                            $uploadedFile->move(public_path('uploads/clients/' . $client->id), $newFilename);
                            $client_company->$file = $newFilename; // Store the filename in the database
                            $client_company->save();
                        }
                    }
                }
            } else {
                $client = Client::find($request->client_id);
                $client->first_name = $request->first_name;
                $client->father_name = $request->father_name;
                $client->grandfather_name = $request->grandfather_name;
                $client->surname = $request->surname;
                $client->language = $request->language;
                $client->nationality_id = $request->nationality_id;
                $client->national_id_number = $request->national_id_number;
                $client->residence_id_number = $request->residence_id_number;
                $client->birth_date = $request->birth_date;
                $client->gender = $request->gender;
                $client->marital_status = $request->marital_status;
                $client->email_id = $request->email_id;
                $client->mobile_no = $request->mobile_no;
                $client->country_id = $request->country_id;
                $client->residing_country_same = $request->residing_country_same;
                $client->residing_country_id = $request->residing_country_same == 1 ? $request->country_id : $request->residing_country_id;
                $client->city_id = $request->city_id;
                $client->district_id = $request->district_id;
                $client->street_name = $request->street_name;
                $client->building_no = $request->building_no;
                $client->company_name = $request->company_name;
                $client->occupation_id = $request->occupation_id;
                $client->work_nature = $request->work_nature;
                $client->company_city_id = $request->company_city_id;
                $client->company_district_id = $request->company_district_id;
                $client->company_street_name = $request->company_street_name;
                $client->company_building_no = $request->company_building_no;
                $client->company_contact_no = $request->company_contact_no;
                $client->agent_id = $request->filled('agent_id') ? $request->agent_id : null;
                if ($request->filled('password')) {
                    $client->password = Hash::make($request->password);
                }
                $client->has_company = $request->has_company;
                $client->save();
                $files = [
                    'id_front',
                    'id_back',
                    'profile_pic'
                ];

                foreach ($files as $file) {
                    if ($request->hasFile($file)) {
                        $uploadedFile = $request->file($file);
                        $filename = $uploadedFile->getClientOriginalName(); // Original filename
                        $newFilename = $file . "_file_" . time() . "." . $uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                        $uploadedFile->move(public_path('uploads/clients/' . $client->id), $newFilename);

                        $client->$file = $newFilename; // Store the filename in the database
                        $client->save();
                    }
                }
                if ($request->has_company == 1) {
                    $client_company = ClientCompany::where('client_id', $request->client_id)->first();
                    if (!$client_company) {
                        $client_company = new ClientCompany();
                        $client_company->client_id = $request->client_id;
                    }
                    $client_company->client_company_name = $request->client_company_name;
                    $client_company->client_company_registered_national_id_no = $request->client_company_registered_national_id_no;
                    $client_company->client_company_registration_no = $request->client_company_registration_no;
                    $client_company->client_company_country_id = $request->client_company_country_id;
                    $client_company->client_company_city_id = $request->client_company_city_id;
                    $client_company->client_company_district_id = $request->client_company_district_id;
                    $client_company->client_company_street_name = $request->client_company_street_name;
                    $client_company->client_company_building_no = $request->client_company_building_no;
                    $client_company->client_company_office_no = $request->client_company_office_no;
                    $client_company->client_company_telephone_no = $request->client_company_telephone_no;
                    $client_company->client_company_owner_first_name = $request->client_company_owner_first_name;
                    $client_company->client_company_owner_father_name = $request->client_company_owner_father_name;
                    $client_company->client_company_owner_grandfather_name = $request->client_company_owner_grandfather_name;
                    $client_company->client_company_owner_surname = $request->client_company_owner_surname;
                    $client_company->client_company_owner_telephone_no = $request->client_company_owner_telephone_no;
                    $client_company->is_partner = $request->is_partner;
                    $client_company->is_authorized = $request->is_authorized;
                    $client_company->authorized_position = $request->is_authorized == 1 ? $request->authorized_position : null;
                    $client_company->is_authorization_in_registration = $request->is_authorized == 1 ? $request->is_authorization_in_registration : 2;
                    $client_company->save();
                    $company_files = [
                        'issuer_authorization_document',
                        'ownership_document',
                        'career_municipality_license',
                        'company_tax_certificate',
                        'practice_certificate'
                    ];
                    foreach ($company_files as $file) {
                        if ($request->hasFile($file)) {
                            $uploadedFile = $request->file($file);
                            $filename = $uploadedFile->getClientOriginalName(); // Original filename
                            $newFilename = $file . "_file_" . time() . "." . $uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                            $uploadedFile->move(public_path('uploads/clients/' . $request->client_id), $newFilename);
                            $client_company->$file = $newFilename; // Store the filename in the database
                            $client_company->save();
                        }
                    }
                } else {
                    $client_company = ClientCompany::where('client_id', $request->client_id)->first();
                    if ($client_company) {
                        $client_company->delete();
                    }
                }
            }
            if ($clientId) {
                $msg = __('messages.clients.client_edit_success');
            } else {
                $msg = __('messages.clients.client_add_success');
            }
            return redirect()->route('client')->with('success', $msg);
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->first();
            return back()->withInput()->with('error', $errors);
        } catch (\Exception $e) {
            error_log($e->getTraceAsString());
            error_log($e->getMessage());
            if ($clientId) {
                $msg = __('messages.clients.client_edit_error');
            } else {
                $msg = __('messages.clients.client_add_error');
            }
            return back()->withInput()->with('error', $msg);
        }
    }

    public function edit(Request $request, $id)
    {
        $client = Client::find($id);
        if ($client) {
            $nationalities = Nationality::all();
            $countries = Country::all();
            $cities = Cities::all();
            $districts = District::all();
            $occupations = Occupations::all();
            return view('admin.client.add_client', compact('client', 'nationalities', 'countries', 'cities', 'districts', 'occupations'));
        } else {
            return back()->with('error', __('messages.clients.not_found'));
        }
    }

    public function delete(Request $request)
    {
        $client_id = $request->delete_customer_id;
        $client = Client::find($client_id);
        $client->delete();
        return back()->with('success', __('messages.clients.delete_success'));
    }

    public function search(Request $request, $id)
    {
        $client = Client::with(['company'])->find($id);
        if ($client) {
            $languages = [
                'en' => __('messages.clients.english'),
                'ar' => __('messages.clients.arabic'),
            ];
            $genders = [
                '1' => __('messages.clients.male'),
                '2' => __('messages.clients.female'),
            ];
            $marital_status = [
                '1' => __('messages.clients.single'),
                '2' => __('messages.clients.married'),
                '3' => __('messages.clients.divorced'),
                '4' => __('messages.clients.widowed'),
            ];
            $merger_arr = [
                'language' => $languages[$client->language],
                'nationality' => $client->nationality->name,
                'gender' => $genders[$client->gender],
                'marital_status' => $marital_status[$client->marital_status],
                'country' => $client->country->name,
                'residing_country' => $client->residing_country->name,
                'city' => $client->city->name,
                'district' => $client->district->name,
                'position' => $client->occupation->name,
                'company_city' => $client->company_city->name,
                'company_district' => $client->company_district->name,
                'agent_code' => $client->agent?->agent_code ?? '',
                'storage_path' => asset('uploads/clients/')
            ];
            $company_merger_arr = [];
            $is_partner = [
                '1' => __('messages.clients.yes'),
                '2' => __('messages.clients.no'),
            ];
            $is_authorized = [
                '1' => __('messages.clients.yes'),
                '2' => __('messages.clients.no'),
            ];
            $is_authorization_in_registration = [
                '1' => __('messages.clients.yes'),
                '2' => __('messages.clients.no'),
            ];
            if (!empty($client->company)) {
                $company_merger_arr = [
                    'client_company_country' => $client->company->country->name,
                    'client_company_city' => $client->company->city->name,
                    'client_company_district' => $client->company->district->name,
                    'is_partner' => $is_partner[$client->company->is_partner],
                    'is_authorized' => $is_authorized[$client->company->is_authorized],
                    'is_authorization_in_registration' => $is_authorization_in_registration[$client->company->is_authorization_in_registration],
                ];
            }
            $client = array_merge(json_decode(json_encode($client), 1), $merger_arr, $company_merger_arr);
            // Return the customer data as JSON response
            return response()->json([
                'client' => $client,
                'status' => 200
            ]);
        } else {
            return response()->json([
                'agent' => '',
                'status' => 404
            ]);
        }
    }

    // public function send_message(Request $request)
    // {
    //     try {
    //         $client_id = explode(',', $request->send_client_id);
    //         $all_clients = Client::whereIn('id', $client_id)->get();
    //         if ($all_clients->count()) {
    //             foreach ($all_clients as $single) {
    //                 $client_message = new ClientMessage();
    //                 $client_message->client_id = $single->id;
    //                 $client_message->message = $request->message;
    //                 $client_message->save();
    //                 $to_name = $single->full_name;
    //                 $to_email = $single->email_id;
    //                 $data = array('name' => $to_name, 'body' => nl2br($request->message));
    //                 Mail::send('mail', $data, function ($message) use ($to_name, $to_email) {
    //                     $message->to($to_email, $to_name)
    //                         ->subject(__('messages.clients.send_message_subject'));
    //                     $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
    //                 });
    //             }
    //         }
    //         return back()->with('success', __('messages.clients.message_sent'));
    //     } catch (\Exception $e) {
    //         logger()->error($e->getTraceAsString());
    //         logger()->error($e->getMessage());
    //         return back()->with('error', __('messages.clients.message_not_sent'));
    //     }
    // }
     public function send_message(Request $request)
    {

    // dd($request->all());
    // die();

        $request->validate([
            'send_client_id' => 'required|string',
            'message' => 'required|string',
        ]);

        try {

            $clientIds = array_filter(
                array_map('trim', explode(',', $request->send_client_id))
            );

            $allClients = Client::whereIn('id', $clientIds)->get();

            if ($allClients->isEmpty()) {
                return back()->with(
                    'error',
                    'No clients selected.'
                );
            }

            foreach ($allClients as $client) {

                // Save message
                $clientMessage = new ClientMessage();
                $clientMessage->client_id = $client->id;
                $clientMessage->message = $request->message;
                $clientMessage->save();

                // Send email only if email exists
                if (!empty($client->email_id)) {

                    $toName = $client->full_name;
                    $toEmail = $client->email_id;

                    $data = [
                        'name' => $toName,
                        'body' => nl2br($request->message),
                    ];

                    Mail::send('mail', $data, function ($message) use ($toName, $toEmail) {
                        $message->to($toEmail, $toName)
                            ->subject(
                                __('messages.clients.send_message_subject')
                            );

                        $message->from(
                            env('MAIL_USERNAME'),
                            env('MAIL_FROM_NAME')
                        );
                    });
                }
            }

            return back()->with(
                'success',
                __('messages.clients.message_sent')
            );

        } catch (\Exception $e) {

            logger()->error($e->getMessage());
            logger()->error($e->getTraceAsString());

            return back()->with(
                'error',
                __('messages.clients.message_not_sent')
            );
        }
    }

    public function add_to_blacklist(Request $request)
    {
        try {
            $client_id = explode(',', $request->add_to_blacklist_client_id);
            Client::whereIn('id', $client_id)->update(['is_blacklisted' => 1]);
            foreach ($client_id as $single) {
                $black_list_details = new BlackListDetail();
                $black_list_details->client_id = $single;
                $black_list_details->save();
            }
            return back()->with('success', __('messages.clients.blacklist_success'));
        } catch (\Exception $e) {
            logger()->error($e->getTraceAsString());
            logger()->error($e->getMessage());
            return back()->with('error', __('messages.clients.blacklist_error'));
        }
    }


    public function check_mobile(Request $request)
    {
        $mobile_no = $request->mobile_no;
        $client_id = $request->client_id;

        if ($client_id) {
            $found = Client::where('mobile_no', $mobile_no)->whereNot('id', $client_id)->first();
        } else {
            $found = Client::where('mobile_no', $mobile_no)->first();
        }

        if ($found) {
            return response()->json([
                'exists' => true,
            ]);
        } else {
            return response()->json([
                'exists' => false,
            ]);
        }
    }

    public function check_email(Request $request)
    {
        $email_id = $request->email_id;
        $client_id = $request->client_id;

        if ($client_id) {
            $found = Client::where('email_id', $email_id)->whereNot('id', $client_id)->first();
        } else {
            $found = Client::where('email_id', $email_id)->first();
        }

        if ($found) {
            return response()->json([
                'exists' => true,
            ]);
        } else {
            return response()->json([
                'exists' => false,
            ]);
        }
    }
}
