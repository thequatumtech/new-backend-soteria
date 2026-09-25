<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\CompanyCommisionBusiness;
use App\Models\Country;
use App\Models\District;
use App\Models\InsuranceCompany;
use App\Models\InsuranceCompanyDocument;
use App\Models\InsurancePercentageOfCommision;
use App\Models\LineOfBusiness;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use App\Models\Currency;

class InsuranceCompanyController extends Controller
{
     public function index()
     {
          $insurance_companies = InsuranceCompany::all();
          $line_of_business = LineOfBusiness::all();

        $countries = Country::orderBy('name', 'asc')->get();
        $cities = Cities::orderBy('name', 'asc')->get();
        $districts = District::orderBy('name', 'asc')->get();
        $currencies = Currency::orderBy('name', 'asc')->get();


          return view('content.insuranceCompany.insuranceCompany', ['insurance_companies' => $insurance_companies, 'line_of_business' => $line_of_business, 'countries' => $countries, 'cities' => $cities, 'districts' => $districts,'currencies' => $currencies]);
     }

     public function create(Request $request)
     {
          try {
               $request->validate([

                    'company_name' => 'required|string|max:100|unique:insurance_companies,company_name,NULL,id,deleted_at,NULL',
                    'company_national_id' => 'required|string|max:100',
                    'register_number' => 'required|string|max:100|unique:insurance_companies,register_number,NULL,id,deleted_at,NULL',
                    'tax_number' => 'required|string|max:100',
                    'email' => 'required|email|unique:insurance_companies,email,NULL,id,deleted_at,NULL',
                    'email_1' => 'nullable|email',
                    'email_2' => 'nullable|email',
                    'claim_email' => 'required|email|unique:insurance_companies,claim_email,NULL,id,deleted_at,NULL',
                    'mobile_number' => 'required',
                    'telephone_number' => 'nullable',
                    'joining_date' => 'required|date|date_format:Y-m-d',
                    'country_id' => 'required|integer',
                    'city_id' => 'required|integer',
                    'district_id' => 'required|integer',
                    'street_name' => 'required|string|max:100',
                    'building_no' => 'required|string|max:100',
                    'ownership_document' => 'nullable|file|max:10240',
                    'municipality_license' => 'nullable|file|max:10240',
                    'practice_certificate' => 'nullable|file|max:10240',
                    'company_tax_certificate' => 'nullable|file|max:10240',
                    'company_stamp' => 'nullable|file|max:10240',
                    'authorized_signature' => 'nullable|file|max:10240',
                    'letterhead' => 'nullable|file|max:10240',
                    'logo' => 'nullable|file|max:10240',
               ]);

               $insuranceCompany = InsuranceCompany::create([
                    'company_name' => $request->company_name,
                    'line_of_business_id' => isset($request->line_of_business) ? json_encode($request->line_of_business) : null,
                    'national_id' => $request->company_national_id,
                    'register_number' => $request->register_number,
                    'tax_number' => $request->tax_number,
                    'email' => $request->email,
                    'email_1' => $request->email_1,
                    'email_2' => $request->email_2,
                    'claim_email' => $request->claim_email,
                    'mobile_number' => $request->mobile_number,
                    'telephone_number' => $request->telephone_number,
                    'joining_date' => $request->joining_date,
                    'currency_id' => $request->currency_id,
                    'country_id' => $request->country_id,
                    'city_id' => $request->city_id,
                    'district_id' => $request->district_id,
                    'street_name' => $request->street_name,
                    'building_no' => $request->building_no,
               ]);

               if ($request->hasFile('privacy_policy')) {
                    $uploadedPrivacy = $request->file('privacy_policy');
                    $filenamePrivacy = $uploadedPrivacy->getClientOriginalExtension();
                    $newFilenamePrivacy = 'file_' . time() . '.' . $filenamePrivacy;
                    $uploadedPrivacy->move(public_path('insurance/' . $insuranceCompany->id . ''), $newFilenamePrivacy);
                    $insuranceCompany->privacy_policy = $newFilenamePrivacy;
                    $insuranceCompany->save();
               }

               $files = [
                    'ownership_document',
                    'municipality_license',
                    'practice_certificate',
                    'company_tax_certificate',
                    'company_stamp',
                    'authorized_signature',
                    'letterhead',
                    'logo',
               ];

               $insurance_company_document = new InsuranceCompanyDocument();
               foreach ($files as $k => $file) {
                    if ($request->hasFile($file)) {
                         $uploadedFile = $request->file($file);
                         $filename = $uploadedFile->getClientOriginalExtension();
                         $newFilename = 'file_' . $k . time() . '.' . $filename;
                         $uploadedFile->move(public_path('insurance/' . $insuranceCompany->id . ''), $newFilename);
                         $insurance_company_document->$file = $newFilename;
                    }
               }

               $insurance_company_document->insurance_id = $insuranceCompany->id;
               $insurance_company_document->save();

               $line_of_business = $request->line_of_business;
               $commisions      = $request->commissions;
               $issuance        = $request->issuance;
               $stamps          = $request->stamps;
               $tax             = $request->tax;
               $cbj             = $request->cbj;
               $sales_tax_on_cbj = $request->sales_tax_on_cbj;

               if (isset($line_of_business)) {
                    foreach ($line_of_business as $value) {
                         $lobId = $value;

                         if (isset($commisions[$lobId])) {
                              $record = new CompanyCommisionBusiness();
                              $record->insurance_company_id = $insuranceCompany->id;
                              $record->line_of_business_id  = $lobId;
                              $record->commision            = $commisions[$lobId];
                              $record->save();
                         }

                         if (isset($issuance[$lobId])) {
                              $new = new InsurancePercentageOfCommision();
                              $new->insurance_company_id = $insuranceCompany->id;
                              $new->line_of_business_id  = $lobId;
                              $new->insurance_fee        = $issuance[$lobId];
                              $new->stamp                = $stamps[$lobId];
                              $new->tax                  = $tax[$lobId];
                              $new->cbj                  = $cbj[$lobId];
                              $new->sales_tax_on_cbj     = $sales_tax_on_cbj[$lobId];
                              $new->save();
                         }
                    }
               }

               return redirect()->route('pages.insurance')->with('success', __('messages.insurance_company.insurance_add'));
          } catch (ValidationException $e) {
               $errors = $e->validator->errors()->first();
               return redirect()->route('pages.insurance')->with('error', $errors);
          }
     }
     public function edit($id)
     {
          $insurance_company = InsuranceCompany::find($id);
          $insurance_company_document = InsuranceCompanyDocument::where('insurance_id', $insurance_company->id)->first();
          $line_of_business = LineOfBusiness::all();
          $company_commission = CompanyCommisionBusiness::with('lineOfBusiness')->where('insurance_company_id', $insurance_company->id)->get();
          $insurance_percentage = InsurancePercentageOfCommision::with('lineOfBusiness')->where('insurance_company_id', $insurance_company->id)->get();

          return response()->json([
               'insurance_company' => $insurance_company,
               'insurance_company_document' => $insurance_company_document,
               'line_of_business' => $line_of_business,
               'company_commission' => $company_commission,
               'insurance_percentage' => $insurance_percentage,
               'public_path' => url('insurance/' . $id . "/"),
               'status' => 200
          ]);
     }

     public function update(Request $request)
     {
          try {
               $request->validate([
                    // 'company_name' => 'required|string|max:100|unique:insurance_companies,company_name,' . $request->insurance_company_id,
                    'company_name'        => 'required|string|max:100|unique:insurance_companies,company_name,' . $request->insurance_company_id . ',id,deleted_at,NULL',
                    'company_national_id' => 'required|string|max:100',
                    // 'register_number' => 'required|string|max:100',
                    'register_number'     => 'required|string|max:100|unique:insurance_companies,register_number,' . $request->insurance_company_id . ',id,deleted_at,NULL',
                    'tax_number' => 'required|string|max:100',
                    // 'email' => 'required|email|unique:insurance_companies,email,' . $request->insurance_company_id,
                    // 'email_1' => 'nullable|email|unique:insurance_companies,email_1,' . $request->insurance_company_id,
                    // 'email_2' => 'nullable|email|unique:insurance_companies,email_2,' . $request->insurance_company_id,
                    // 'claim_email' => 'required|email|unique:insurance_companies,claim_email,' . $request->insurance_company_id,
                    'email'               => 'required|email|unique:insurance_companies,email,' . $request->insurance_company_id . ',id,deleted_at,NULL',
                    'email_1'             => 'nullable|email|unique:insurance_companies,email_1,' . $request->insurance_company_id . ',id,deleted_at,NULL',
                    'email_2'             => 'nullable|email|unique:insurance_companies,email_2,' . $request->insurance_company_id . ',id,deleted_at,NULL',
                    'claim_email'         => 'required|email|unique:insurance_companies,claim_email,' . $request->insurance_company_id . ',id,deleted_at,NULL',
                    'mobile_number' => 'required',
                    'telephone_number' => 'nullable',
                    'joining_date' => 'required|date|date_format:Y-m-d',
                    'country_id' => 'required|integer',
                     'currency_id' => 'required|exists:currencies,id',
                    'city_id' => 'required|integer',
                    'district_id' => 'required|integer',
                    'street_name' => 'required|string|max:100',
                    'building_no' => 'required|string|max:100',
                    'ownership_document' => 'nullable|file|max:10240',
                    'municipality_license' => 'nullable|file|max:10240',
                    'practice_certificate' => 'nullable|file|max:10240',
                    'company_tax_certificate' => 'nullable|file|max:10240',
                    'company_stamp' => 'nullable|file|max:10240',
                    'authorized_signature' => 'nullable|file|max:10240',
                    'letterhead' => 'nullable|file|max:10240',
                    'logo' => 'nullable|file|max:10240',
                    'commissions.*' => 'required_with:line_of_business|numeric|min:0',
                    'issuance.*' => 'required_with:line_of_business|numeric|min:0',
                    'stamps.*' => 'required_with:line_of_business|numeric|min:0',
                    'tax.*' => 'required_with:line_of_business|numeric|min:0',
                    'cbj.*' => 'required_with:line_of_business|numeric|min:0',
                    'sales_tax_on_cbj.*' => 'required_with:line_of_business|numeric|min:0',
               ]);

               $insuranceCompany = InsuranceCompany::findOrFail($request->insurance_company_id);

               if ($request->hasFile('privacy_policy')) {
                    $uploadedPrivacy = $request->file('privacy_policy');
                    $filenamePrivacy = $uploadedPrivacy->getClientOriginalExtension();
                    $newFilenamePrivacy = 'file_' . time() . '.' . $filenamePrivacy;
                    $uploadedPrivacy->move(public_path('insurance/' . $insuranceCompany->id . ''), $newFilenamePrivacy);
                    $insuranceCompany->privacy_policy = $newFilenamePrivacy;
                    $insuranceCompany->save();
               }

               $insuranceCompany->update([
                    'company_name' => $request->company_name,
                    'line_of_business_id' => isset($request->line_of_business) ? json_encode($request->line_of_business) : null,
                    'national_id' => $request->company_national_id,
                    'register_number' => $request->register_number,
                    'tax_number' => $request->tax_number,
                    'email' => $request->email,
                    'email_1' => $request->email_1,
                    'email_2' => $request->email_2,
                    'claim_email' => $request->claim_email,
                    'mobile_number' => $request->mobile_number,
                    'telephone_number' => $request->telephone_number,
                    'joining_date' => $request->joining_date,
                    'currency_id' => $request->currency_id,
                    'country_id' => $request->country_id,
                    'city_id' => $request->city_id,
                    'district_id' => $request->district_id,
                    'street_name' => $request->street_name,
                    'building_no' => $request->building_no,
               ]);

               $files = [
                    'ownership_document',
                    'municipality_license',
                    'practice_certificate',
                    'company_tax_certificate',
                    'company_stamp',
                    'authorized_signature',
                    'letterhead',
                    'logo',
               ];

               $insurance_company_document = InsuranceCompanyDocument::where('insurance_id', $insuranceCompany->id)->first();

               foreach ($files as $k => $file) {
                    if ($request->hasFile($file)) {
                         if (!empty($insurance_company_document->$file)) {
                              $filePath = public_path('insurance/' . $insuranceCompany->id . '/' . $insurance_company_document->$file);
                              if (file_exists($filePath)) {
                                   unlink($filePath);
                              }
                         }
                         $uploadedFile = $request->file($file);
                         $filename = $uploadedFile->getClientOriginalExtension();
                         $newFilename = 'file_' . $k . time() . '.' . $filename;
                         $uploadedFile->move(public_path('insurance/' . $insuranceCompany->id . ''), $newFilename);
                         $insurance_company_document->$file = $newFilename;
                    }
               }

               $insurance_company_document->save();

               $line_of_business = $request->line_of_business;
               $commisions       = $request->commissions;
               $issuance         = $request->issuance;
               $stamps           = $request->stamps;
               $tax              = $request->tax;
               $cbj              = $request->cbj;
               $sales_tax_on_cbj = $request->sales_tax_on_cbj;

               if (isset($line_of_business)) {
                    foreach ($line_of_business as $value) {
                         $lobId = $value;

                         if (isset($commisions[$lobId])) {
                              $check = CompanyCommisionBusiness::where('insurance_company_id', $insuranceCompany->id)
                                   ->where('line_of_business_id', $lobId)->first();

                              if (!empty($check)) {
                                   $check->commision = $commisions[$lobId];
                                   $check->save();
                              } else {
                                   $record = new CompanyCommisionBusiness();
                                   $record->insurance_company_id = $insuranceCompany->id;
                                   $record->line_of_business_id  = $lobId;
                                   $record->commision            = $commisions[$lobId];
                                   $record->save();
                              }
                         }

                         if (isset($issuance[$lobId])) {
                              $check = InsurancePercentageOfCommision::where('insurance_company_id', $insuranceCompany->id)
                                   ->where('line_of_business_id', $lobId)->first();

                              if (!empty($check)) {
                                   $check->insurance_fee    = $issuance[$lobId];
                                   $check->stamp            = $stamps[$lobId];
                                   $check->tax              = $tax[$lobId];
                                   $check->cbj              = $cbj[$lobId];
                                   $check->sales_tax_on_cbj = $sales_tax_on_cbj[$lobId];
                                   $check->save();
                              } else {
                                   $new = new InsurancePercentageOfCommision();
                                   $new->insurance_company_id = $insuranceCompany->id;
                                   $new->line_of_business_id  = $lobId;
                                   $new->insurance_fee        = $issuance[$lobId];
                                   $new->stamp                = $stamps[$lobId];
                                   $new->tax                  = $tax[$lobId];
                                   $new->cbj                  = $cbj[$lobId];
                                   $new->sales_tax_on_cbj     = $sales_tax_on_cbj[$lobId];
                                   $new->save();
                              }
                         }
                    }
               }

               $extraCommission = CompanyCommisionBusiness::where('insurance_company_id', $insuranceCompany->id)
                    ->pluck('line_of_business_id')->toArray();

               if (empty($line_of_business)) {
                    CompanyCommisionBusiness::where('insurance_company_id', $insuranceCompany->id)->delete();
                    InsurancePercentageOfCommision::where('insurance_company_id', $insuranceCompany->id)->delete();
               }

               if (!empty($line_of_business)) {
                    $deletedLineOfBusiness = array_diff($extraCommission, $line_of_business);

                    CompanyCommisionBusiness::where('insurance_company_id', $insuranceCompany->id)
                         ->whereIn('line_of_business_id', $deletedLineOfBusiness)
                         ->delete();

                    InsurancePercentageOfCommision::where('insurance_company_id', $insuranceCompany->id)
                         ->whereIn('line_of_business_id', $deletedLineOfBusiness)
                         ->delete();
               }

               return redirect()->route('pages.insurance')->with('success', __('messages.insurance_company.insurance_update'));
          } catch (ValidationException $e) {
               $errors = $e->validator->errors()->first();
               return redirect()->route('pages.insurance')->with('error', $errors);
          }
     }

     public function destroy(Request $request)
     {
          $insurance_company_document = InsuranceCompanyDocument::where('insurance_id', $request->id)->first();
          $insurance_company_document->delete();

          $insurance_company = InsuranceCompany::find($request->id);
          $insurance_company->delete();

          return redirect()->route('pages.insurance')->with('success', __('messages.insurance_company.insurance_deleted'));
     }

     public function get_line_of_businesses(Request $request)
     {
          $insurance_company = InsuranceCompany::find($request->id);
          $line_of_businesses = LineOfBusiness::whereIn('id', json_decode($insurance_company->line_of_business_id))->get();
          return response()->json([
               'line_of_businesses' => $line_of_businesses,
               'status' => 200
          ]);
     }

     public function check_mobile(Request $request)
     {
          $mobile_no = $request->mobile_no;
          $client_id = $request->client_id;

          if ($client_id) {
               $found = InsuranceCompany::where('mobile_number', $mobile_no)->whereNot('id', $client_id)->first();
          } else {
               $found = InsuranceCompany::where('mobile_number', $mobile_no)->first();
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
               $found = InsuranceCompany::where('email', $email_id)->whereNot('id', $client_id)->first();
          } else {
               $found = InsuranceCompany::where('email', $email_id)->first();
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
