<?php

namespace App\Http\Controllers;

use App\Models\AgentCommission;
use App\Models\AgentModel;
use App\Models\AgentSupervisorCommission;
use App\Models\Cities;
use App\Models\Client;
use App\Models\Country;
use App\Models\District;
use App\Models\LineOfBusiness;
use App\Models\Nationality;
use App\Rules\AdultRule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AgentController extends Controller
{
    function index()
    {
        $agentdata = AgentModel::withCount('clients')->orderBy('first_name')->orderBy('father_name')->get();
        $nationalities = Nationality::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        $cities = Cities::orderBy('name')->get();
        $districts = District::orderBy('name')->get();
        $line_of_business = LineOfBusiness::all();
        return view('content.agent.agentadd', compact('agentdata', 'nationalities', 'countries', 'cities', 'districts', 'line_of_business'));
    }

    function AgentCreate(Request $request)
    {
        try {
            $customMessages = [
                'required' => 'This field is required.',
                'email' => 'The email must be a valid email address.',
                'numeric' => 'The value must be a valid number.',
                'unique' => 'This value has already been taken.',
                'date' => 'The value must be a valid date.',
                'max' => 'The value may not be greater than :max kilobytes.',
                'file' => 'The value must be a file.',
                'image' => 'The value must be an image.',
                ];
            $request->validate([
                'first_name' => 'required',
                'father_name' => 'required',
                'grandfather_name' => 'required',
                'surname' => 'required',
                'nationality_id' => 'required',
                'nationalidpassport' => 'required',
                'residence_no' => 'required',
                'birth_date' => ['required', 'date', new AdultRule],
                'gender' => 'required',
                'marital_status' => 'required',
                'agent_email' => ['required', 'email', 'unique:agent,agent_email,NULL,id,deleted_at,NULL'],
                'agent_mobile_no' => ['required', 'numeric', 'unique:agent,agent_mobile_no,NULL,id,deleted_at,NULL'],
                'joining_date' => 'required|date',
                'country_id' => 'required',
                'city_id' => 'required',
                'district_id' => 'required',
                'street_name' => 'required',
                'building_no' => 'required',
                'id_front' => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,pdf'],
                'id_back' => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,pdf'],
                'profile_pic' => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,pdf'],
                'agent_code' => ['required', 'numeric', 'unique:agent,agent_code,NULL,id,deleted_at,NULL'],
            ]/*, $customMessages*/);

            $now = now();
            $agent_id = AgentModel::insertGetId($request->except('_token', 'id_front', 'id_back', 'profile_pic', 'agent_commissions', 'supervisor_commissions'));
            $agent = AgentModel::find($agent_id);
            $agent->created_at = $now;
            $agent->updated_at = $now;
            $agent->save();

            $agent_commission = $request->agent_commissions;
            if (isset($agent_commission) && !empty($agent_commission)) {
                $agent_commission_data = [];
                foreach ($agent_commission as $key => $value) {
                    if ($value) {
                        $agent_commission_data[] = [
                            'agent_id' => $agent_id,
                            'line_of_business_id' => $key,
                            'agent_commission' => $value,
                            'created_at' => $now,
                            'updated_at' => $now
                        ];
                    }
                }
                $agent_commission_model = new AgentCommission();
                $agent_commission_model->insert($agent_commission_data);
            }
            $agent_supervisor_commissions = $request->supervisor_commissions;
            if (isset($agent_supervisor_commissions) && !empty($agent_supervisor_commissions) && $request->supervisor_id) {
                $agent_supervisor_commission_data = [];
                $supervisor_id = $request->supervisor_id??null;
                foreach ($agent_supervisor_commissions as $key => $value) {
                    if ($value) {
                        $agent_supervisor_commission_data[] = [
                            'agent_id' => $agent_id,
                            'supervisor_id' => $supervisor_id,
                            'line_of_business_id' => $key,
                            'supervisor_commission' => $value,
                            'created_at' => $now,
                            'updated_at' => $now
                        ];
                    }
                }
                $agent_supervisor_commission_model = new AgentSupervisorCommission();
                $agent_supervisor_commission_model->insert($agent_supervisor_commission_data);
            }
            $files = [
                'id_front',
                'id_back',
                'profile_pic'
            ];
            foreach ($files as $file) {
                if ($request->hasFile($file)) {
                    $uploadedFile = $request->file($file);
                    $filename = $uploadedFile->getClientOriginalName(); // Original filename
                    $newFilename = "file_" . time() . "." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                    $uploadedFile->move(public_path('uploads/agents/' . $agent_id), $newFilename);

                    $agent->$file = $newFilename; // Store the filename in the database
                    $agent->save();
                }
            }
            return redirect()->route('pages.agent')->with('success', __('messages.agents.agent_add_success'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->first();
            return back()->withInput()->with('error', $errors);
        } catch (\Exception $exception) {
            logger()->error($exception->getTraceAsString());
            logger()->error($exception->getMessage());
            return back()->withInput()->with('error', __('messages.agents.agent_add_error'));
        }
    }

    function AgentEdit($id)
    {
        $agentdata = AgentModel::with(['agent_supervisor_commission','agent_commission'])->find($id);
        $supervisor_name = '';
        if($agentdata->supervisor_id){
            $supervisor_id = $agentdata->supervisor_id;
            $supervisor = AgentModel::find($supervisor_id);
            if($supervisor){
                $supervisor_name = $supervisor->full_name;
            }
        }
        $merge_data = [
            'supervisor_name' => $supervisor_name,
            'nationality' => $agentdata->nationality->name,
            'country' => $agentdata->country->name,
            'city' => $agentdata->city->name,
            'district' => $agentdata->district->name,
            'supervisor_code' => !empty($agentdata->supervisor) ? $agentdata->supervisor->agent_code : '-',
        ];
        $agentdata = array_merge(json_decode(json_encode($agentdata),1),$merge_data);
        $agentdata = array_merge($agentdata,['storage_path'=>asset('uploads/agents/')]);

        // Return the customer data as JSON response
        return response()->json([
            'agent' => $agentdata,
            'status' => 200
        ]);
    }

    function agentUpdate(Request $request)
    {
        try {
            $request->validate([
                'agent_id' => 'required',
                'first_name' => 'required',
                'father_name' => 'required',
                'grandfather_name' => 'required',
                'surname' => 'required',
                'nationality_id' => 'required',
                'nationalidpassport' => 'required',
                'residence_no' => 'required',
                'birth_date' => ['required', 'date', new AdultRule],
                'gender' => 'required',
                'marital_status' => 'required',
                'agent_email' => ['required', 'email', 'unique:agent,agent_email,'.$request->agent_id.',id,deleted_at,NULL'],
                'agent_mobile_no' => ['required', 'numeric', 'unique:agent,agent_mobile_no,'.$request->agent_id.',id,deleted_at,NULL'],
                'joining_date' => 'required|date',
                'country_id' => 'required',
                'city_id' => 'required',
                'district_id' => 'required',
                'street_name' => 'required',
                'building_no' => 'required',
                'id_front' => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,pdf'],
                'id_back' => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,pdf'],
                'profile_pic' => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,pdf'],
                'agent_code' => ['required', 'numeric', 'unique:agent,agent_code,'.$request->agent_id.',id,deleted_at,NULL'],
            ]/*, $customMessages*/);
            $agent = AgentModel::find($request->agent_id);
            if ($agent) {
                $now = now();
                $agent->update($request->except('_token', 'id_front', 'id_back', 'profile_pic', 'agent_commissions', 'supervisor_commissions'));
                if(!$request->supervisor_id){
                    $agent->supervisor_id = null;
                    $agent->save();
                }

                $agent_commissions = $request->agent_commissions;
                if (isset($agent_commissions) && !empty($agent_commissions)) {
                    foreach ($agent_commissions as $key => $value) {
                        if ($value) {
                            $agent_commissions_where = [
                                'agent_id' => $agent->id,
                                'line_of_business_id' => $key
                            ];
                            $agent_commission_found = AgentCommission::where($agent_commissions_where)->first();
                            if(!$agent_commission_found){
                                $agent_commission_found = new AgentCommission();
                                $agent_commission_found->agent_id = $agent->id;
                                $agent_commission_found->line_of_business_id = $key;
                            }
                            $agent_commission_found->agent_commission = $value;
                            $agent_commission_found->save();
                        }
                    }
                }
                $agent_supervisor_commissions = $request->supervisor_commissions;
                if (isset($agent_supervisor_commissions) && !empty($agent_supervisor_commissions) && $request->supervisor_id) {
                    AgentSupervisorCommission::where('agent_id',$agent->id)->where('supervisor_id','!=',$request->supervisor_id)->delete();
                    $supervisor_id = $request->supervisor_id?:null;
                    foreach ($agent_supervisor_commissions as $key => $value) {
                        if ($value) {
                            $agent_supervisor_commission_where = [
                                'agent_id' => $agent->id,
                                'line_of_business_id' => $key,
//                                'supervisor_id' => $supervisor_id
                            ];
                            $supervisor_commission_found = AgentSupervisorCommission::where($agent_supervisor_commission_where)->first();
                            if(!$supervisor_commission_found){
                                $supervisor_commission_found = new AgentSupervisorCommission();
                                $supervisor_commission_found->agent_id = $agent->id;
                                $supervisor_commission_found->line_of_business_id = $key;
                                $supervisor_commission_found->supervisor_id = $supervisor_id;
                            }
                            $supervisor_commission_found->supervisor_commission = $value;
                            $supervisor_commission_found->save();
                        }
                    }
                } else {
                    AgentSupervisorCommission::where('agent_id',$agent->id)->delete();
                }
                $files = [
                    'id_front',
                    'id_back',
                    'profile_pic'
                ];
                foreach ($files as $file) {
                    if ($request->hasFile($file)) {
                        $uploadedFile = $request->file($file);
                        $filename = $uploadedFile->getClientOriginalName(); // Original filename
                        $timestamp = now()->timestamp; // Current timestamp
                        $newFilename = "{$filename}_{$timestamp}.{$uploadedFile->getClientOriginalExtension()}"; // New filename with timestamp
                        $uploadedFile->move(public_path('uploads/agents/' . $agent->id), $newFilename);

                        $agent->$file = $newFilename; // Store the filename in the database
                        $agent->save();
                    }
                }

                return redirect()->back()->with('success', __('messages.agents.agent_edit_success'));
            }
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->first();
            return back()->withInput()->with('error', $errors);
        } catch (\Exception $exception) {
            logger()->error($exception->getTraceAsString());
            logger()->error($exception->getMessage());
            return back()->withInput()->with('error',  __('messages.agents.agent_edit_error'));
        }

    }

    function destoryAgent(Request $request)
    {
        $agent_id = $request->input('delete_agent_id');
        $agent = AgentModel::find($agent_id);
        $supervisors = AgentModel::where('supervisor_id', $agent_id)->get();
        foreach ($supervisors as $supervisor) {
            $supervisor->supervisor_id = null;
            $supervisor->save();
        }
        $agent_code = $agent->agent_code;
        $clients = Client::where('agent_id', $agent_code)->get();
        foreach ($clients as $client) {
            $client->agent_id = null;
            $client->save();
        }
        $agent->delete();
        return redirect()->back()->with('status', 'Agent deleted successfully');
    }

    public function check_mobile(Request $request){
        $mobile_no = $request->mobile_no;
        $client_id = $request->client_id;

        if($client_id){
            $found = AgentModel::where('agent_mobile_no', $mobile_no)->whereNot('id', $client_id)->first();
        } else {
            $found = AgentModel::where('agent_mobile_no', $mobile_no)->first();
        }

        if($found){
            return response()->json([
                'exists' => true,
            ]);
        } else {
            return response()->json([
                'exists' => false,
            ]);
        }
    }

    public function check_email(Request $request){
        $email_id = $request->email_id;
        $client_id = $request->client_id;

        if($client_id){
            $found = AgentModel::where('agent_email', $email_id)->whereNot('id', $client_id)->first();
        } else {
            $found = AgentModel::where('agent_email', $email_id)->first();
        }

        if($found){
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
