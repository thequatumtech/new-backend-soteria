<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Cities;
use App\Models\Country;
use App\Models\District;
use App\Models\Nationality;
use App\Models\Occupations;
use App\Rules\AdultRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SubAdminController extends Controller
{

    public function index(Request $request)
    {
        $admins = Admin::orderBy('admin_id')->get();
        return view('admin.sub-admin.index', compact('admins'));
    }

    public function add(Request $request)
    {
        $last_admin = Admin::orderByDesc('admin_id')->first();
        $next_admin_id = $last_admin ? $last_admin->admin_id + 1 : 1;
        $nationalities = Nationality::all();
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $occupations = Occupations::all();
        return view('admin.sub-admin.add', compact('nationalities', 'countries', 'cities', 'districts', 'occupations', 'next_admin_id'));
    }

    public function create(Request $request)
    {
        $sub_admin_id = null;
        if ($request->filled('sub_admin_id')) {
            $sub_admin_id = $request->sub_admin_id;
        }
        try {
            $request->validate([
                'first_name' => 'required',
                'second_name' => 'required',
                'third_name' => 'required',
                'last_name' => 'required',
                'language' => 'required',
                'nationality_id' => 'required',
                'national_id_no' => 'required',
                'residence_id_no' => 'required',
                'birth_date' => ['required', 'date', new AdultRule],
                'gender' => 'required',
                'email' => ['required', 'email', 'unique:admins,email,' . $sub_admin_id . ',id,deleted_at,NULL'],
                'mobile_no' => ['required', 'numeric', 'unique:admins,mobile_no,' . $sub_admin_id . ',id,deleted_at,NULL'],
                'country_id' => 'required|numeric',
                'city_id' => 'required|numeric',
                'district_id' => 'required|numeric',
                'street_name' => 'required',
                'building_no' => 'required',
                'company_name' => 'required',
                'occupation_id' => 'required|numeric',
                'work_nature' => 'required',
                'company_city_id' => 'required|numeric',
                'company_district_id' => 'required|numeric',
                'company_street_name' => 'required',
                'company_building_no' => 'required',
                'company_contact_no' => 'required',
                'id_front' => [$sub_admin_id ? 'nullable' : 'required', 'file', 'max:10240', 'image'],
                'id_back' => [$sub_admin_id ? 'nullable' : 'required', 'file', 'max:10240', 'image'],
                'profile_pic' => [$sub_admin_id ? 'nullable' : 'required', 'file', 'max:10240', 'image'],
                'admin_id' => ['nullable', 'numeric', 'unique:admins,admin_id,' . $sub_admin_id . ',id,deleted_at,NULL'],
                'password' => 'required_without:sub_admin_id'
            ]);

            if (!$request->filled('sub_admin_id')) {
                $admin = new Admin();
            } else {
                $admin = Admin::find($request->sub_admin_id);
            }
            $admin->first_name = $request->first_name;
            $admin->second_name = $request->second_name;
            $admin->third_name = $request->third_name;
            $admin->last_name = $request->last_name;
            $admin->language = $request->language;
            $admin->nationality_id = $request->nationality_id;
            $admin->national_id_no = $request->national_id_no;
            $admin->residence_id_no = $request->residence_id_no;
            $admin->birth_date = $request->birth_date;
            $admin->gender = $request->gender;
            $admin->email = $request->email;
            $admin->mobile_no = $request->mobile_no;
            $admin->country_id = $request->country_id;
            $admin->residing_country_id = $request->residing_country_id;
            $admin->city_id = $request->city_id;
            $admin->district_id = $request->district_id;
            $admin->street_name = $request->street_name;
            $admin->building_no = $request->building_no;
            $admin->company_name = $request->company_name;
            $admin->occupation_id = $request->occupation_id;
            $admin->work_nature = $request->work_nature;
            $admin->company_city_id = $request->company_city_id;
            $admin->company_district_id = $request->company_district_id;
            $admin->company_street_name = $request->company_street_name;
            $admin->company_building_no = $request->company_building_no;
            $admin->company_contact_no = $request->company_contact_no;
            $admin->admin_id = $request->admin_id;
            if($request->has('authorized_routes')) {
                $admin->authorized_routes = json_encode($request->authorized_routes);
            }
            if($request->password) {
                $admin->password = Hash::make($request->password);
            }
            $admin->save();

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
                    $uploadedFile->move(public_path('uploads/admins/' . $admin->id), $newFilename);

                    $admin->$file = $newFilename; // Store the filename in the database
                    $admin->save();
                }
            }
            if ($sub_admin_id) {
                $msg = __('messages.sub_admins.edit_success');
            } else {
                $msg = __('messages.sub_admins.add_success');
            }
            return redirect()->route('sub_admin')->with('success', $msg);
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->first();
            return back()->withInput()->with('error', $errors);
        } catch (\Exception $e) {
            error_log($e->getTraceAsString());
            error_log($e->getMessage());
            if ($sub_admin_id) {
                $msg = __('messages.sub_admins.edit_error');
            } else {
                $msg = __('messages.sub_admins.add_error');
            }
            return back()->withInput()->with('error', $msg);
        }
    }

    public function edit(Request $request, $id)
    {
        $admin = Admin::find($id);
        if ($admin) {
            $nationalities = Nationality::all();
            $countries = Country::all();
            $cities = Cities::all();
            $districts = District::all();
            $occupations = Occupations::all();
            $authorized_routes = json_decode($admin->authorized_routes);
            return view('admin.sub-admin.add', compact('admin', 'nationalities', 'countries', 'cities', 'districts', 'occupations','authorized_routes'));
        } else {
            return back()->with('error', __('messages.sub_admins.not_found'));
        }
    }

    public function delete(Request $request)
    {
        $sub_admin_id = $request->delete_sub_admin_id;
        $sub_admin = Admin::find($sub_admin_id);
        $sub_admin->delete();
        return back()->with('status', __('messages.sub_admins.delete_success'));
    }

//TODO admin authorization

}
