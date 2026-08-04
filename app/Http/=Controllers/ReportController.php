<?php

namespace App\Http\Controllers;

use App\Models\ClientPetsInsurance;
use App\Models\Country;
use App\Models\InsuranceCompany;
use App\Models\PurchasePolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\AgentModel;
use App\Models\Ages;
use App\Models\Client;
use App\Models\District;
use App\Models\Cities;
use App\Models\Claim;
use App\Models\ClientTravelInsurance;
use App\Models\Complaint;
use App\Models\ComplaintStatus;
use App\Models\InsurancePeriod;
use App\Models\InsurancePlanModels\TravelPlan;
use App\Models\Occupations;
use App\Models\SupervisorModel;
use DB;
use phpseclib3\System\SSH\Agent;

class ReportController extends Controller
{
    public function sold_policy_report(Request $request)
    {
        $filters = [
            'insurance_company' => request()->get('insurance_company'),
            'issue_date' => request()->get('issue_date'),
            'expiry_date' => request()->get('expiry_date'),
            'agent_name' => request()->get('agent_name'),
            'client_position' => request()->get('client_position'),
            'client_district' => request()->get('client_district'),
            'policy_type' => request()->get('policy_type'),
            'client_gender' => request()->get('client_gender'),
            'client_name' => request()->get('client_name'),
            'client_city' => request()->get('client_city'),
            'supervisor' => request()->get('supervisor'),
            'client_age' => request()->get('client_age'),
        ];
        $groupBy = request()->get('group_by', $request->grouptype);
        $policies = PurchasePolicy::getFilterAllPolicy($filters, $groupBy);
        // Fetch necessary data for the dropdowns
        $insuranceCompanies = InsuranceCompany::all();
        $agents = AgentModel::get(); // Fetch only needed fields
        $clients = Client::get();
        
        // Predefined insurance types for the dropdown
        $policyTypes = [
            ['id' => 1,  'name' => 'Home Insurance'],
            ['id' => 2,  'name' => 'Office Insurance'],
            ['id' => 3,  'name' => 'Life Insurance'],
            ['id' => 4,  'name' => 'Critical Illness Insurance'],
            ['id' => 5,  'name' => 'Personal Accident Insurance'],
            ['id' => 6,  'name' => 'Individual Medical Insurance'],
            ['id' => 7,  'name' => 'Family Medical Insurance'],
            ['id' => 8,  'name' => 'Pets Insurance'],
            ['id' => 9,  'name' => 'Dental Insurance'],
            ['id' => 10, 'name' => 'Travel Insurance'],
            ['id' => 11, 'name' => 'Marine Insurance'],
            ['id' => 12, 'name' => 'Motor Insurance']
        ];
        // $positions = DB::table('clients')->select('position')->whereNotNull('position')->groupBy('position')->get();
        $positions = Occupations::get();
        $district = District::all();
        $city = Cities::all();
        $supervisor = SupervisorModel::all();
        $ages = Ages::all();
        $clients = Client::all();
        
        return view('admin.reports.sold_policy_report', compact('policies', 'insuranceCompanies', 'agents', 'policyTypes', 'clients', 'positions','district','city','supervisor','ages','clients'));
    }
    public function policy_commission(Request $request)
    {
        $filters = [
            'insurance_company' => $request->get('insurance_company'),
            'issue_date' => $request->get('issue_date'),
            'expiry_date' => $request->get('expiry_date'),
            'agent_name' => $request->get('agent_name'),
            'policy_type' => $request->get('policy_type'),
            'policy_plan' => $request->get('policy_plan'),
            'total_commission' => $request->get('total_commission'),

        ];
        $groupBy = $request->get('group_by', $request->grouptype);
        // Retrieve filtered and grouped policies
        $policies = PurchasePolicy::getFilterAllPolicy($filters, $groupBy);
    
        // Fetch necessary data for dropdowns
        $insuranceCompanies = InsuranceCompany::all();
        $agents = AgentModel::select('id', 'first_name')->get();  // Fetch only needed fields
        $clients = Client::select('id', 'first_name')->get();  // Fetch only necessary fields
        $policyTypes = $this->getPolicyTypes();
        $positions = DB::table('clients')->select('position')->distinct()->whereNotNull('position')->get();
        $districts = District::all();
        $cities = Cities::all();
        $supervisors = SupervisorModel::all();
        $ages = Ages::all();
    
        return view('admin.reports.policy_commission', compact('policies', 'insuranceCompanies', 'agents', 'policyTypes', 'clients', 'positions', 'districts', 'cities', 'supervisors', 'ages'));
    }
    public function client_report(Request $request)
    {
       $filters = [
            'insurance_company' => $request->get('insurance_company'),
            'issue_date' => $request->get('issue_date'),
            'expiry_date' => $request->get('expiry_date'),
            'agent_name' => $request->get('agent_name'),
            'client_position' => $request->get('client_position'),
            'client_district' => $request->get('client_district'),
            'policy_type' => $request->get('policy_type'),
            'client_gender' => $request->get('client_gender'),
            'client_city' => $request->get('client_city'),
            'client_age' => $request->get('client_age'),
            'client_marital' => $request->get('client_marital'),
        ];
        $groupBy = $request->get('group_by', $request->grouptype);
    
        // Retrieve filtered and grouped policies
        $policies = PurchasePolicy::getFilterAllPolicy($filters, $groupBy);
        
        // Fetch necessary data for the dropdowns
        $insuranceCompanies = InsuranceCompany::all();
        $agents = AgentModel::get(); // Fetch only needed fields
        $clients = Client::get();
        
        // Predefined insurance types for the dropdown
        $policyTypes = [
            ['id' => 1,  'name' => 'Home Insurance'],
            ['id' => 2,  'name' => 'Office Insurance'],
            ['id' => 3,  'name' => 'Life Insurance'],
            ['id' => 4,  'name' => 'Critical Illness Insurance'],
            ['id' => 5,  'name' => 'Personal Accident Insurance'],
            ['id' => 6,  'name' => 'Individual Medical Insurance'],
            ['id' => 7,  'name' => 'Family Medical Insurance'],
            ['id' => 8,  'name' => 'Pets Insurance'],
            ['id' => 9,  'name' => 'Dental Insurance'],
            ['id' => 10, 'name' => 'Travel Insurance'],
            ['id' => 11, 'name' => 'Marine Insurance'],
            ['id' => 12, 'name' => 'Motor Insurance']
        ];
        $positions = Occupations::get();
        $district = District::all();
        $city = Cities::all();
        $supervisor = SupervisorModel::all();
        $ages = Ages::all();
        $policy_plan = PurchasePolicy::select('plan_name')
                    ->whereNotNull('plan_name') // Exclude null values
                    ->groupBy('plan_name')
                    ->get();
        return view('admin.reports.client_report', compact('policies', 'insuranceCompanies', 'agents', 'policyTypes', 'clients', 'positions','district','city','supervisor','ages','policy_plan'));
    }
    private function getPolicyTypes()
    {
        return [
            ['id' => 1,  'name' => 'Home Insurance'],
            ['id' => 2,  'name' => 'Office Insurance'],
            ['id' => 3,  'name' => 'Life Insurance'],
            ['id' => 4,  'name' => 'Critical Illness Insurance'],
            ['id' => 5,  'name' => 'Personal Accident Insurance'],
            ['id' => 6,  'name' => 'Individual Medical Insurance'],
            ['id' => 7,  'name' => 'Family Medical Insurance'],
            ['id' => 8,  'name' => 'Pets Insurance'],
            ['id' => 9,  'name' => 'Dental Insurance'],
            ['id' => 10, 'name' => 'Travel Insurance'],
            ['id' => 11, 'name' => 'Marine Insurance'],
            ['id' => 12, 'name' => 'Motor Insurance'],
        ];
    }
    
    public function policy_renewal_report(Request $request)
    {
        $filters = [
            'insurance_company' => $request->get('insurance_company'),
            'issue_date' => $request->get('issue_date'),
            'expiry_date' => $request->get('expiry_date'),
            'agent_name' => $request->get('agent_name'),
            'policy_type' => $request->get('policy_type'),
            'supervisor' => $request->get('supervisor'),
            'renew_status' => $request->get('renew_status'),
        ];
        if(!$request->grouptype)
        {
            $request->grouptype='policy_type';
        }
        $groupBy = $request->get('group_by', $request->grouptype);
    
        // Retrieve filtered and grouped policies
        $policies = PurchasePolicy::getFilterAllPolicy($filters, $groupBy);
        
        // Fetch necessary data for the dropdowns
        $insuranceCompanies = InsuranceCompany::all();
        $agents = AgentModel::get(); // Fetch only needed fields
        $clients = Client::get();
        
        // Predefined insurance types for the dropdown
        $policyTypes = [
            ['id' => 1,  'name' => 'Home Insurance'],
            ['id' => 2,  'name' => 'Office Insurance'],
            ['id' => 3,  'name' => 'Life Insurance'],
            ['id' => 4,  'name' => 'Critical Illness Insurance'],
            ['id' => 5,  'name' => 'Personal Accident Insurance'],
            ['id' => 6,  'name' => 'Individual Medical Insurance'],
            ['id' => 7,  'name' => 'Family Medical Insurance'],
            ['id' => 8,  'name' => 'Pets Insurance'],
            ['id' => 9,  'name' => 'Dental Insurance'],
            ['id' => 10, 'name' => 'Travel Insurance'],
            ['id' => 11, 'name' => 'Marine Insurance'],
            ['id' => 12, 'name' => 'Motor Insurance']
        ];
        $district = District::all();
        $city = Cities::all();
        $supervisor = SupervisorModel::all();
        $ages = Ages::all();
        
        return view('admin.reports.policy_renewal_report', compact('policies', 'insuranceCompanies', 'agents', 'policyTypes', 'clients','supervisor'));
    }
    public function travel_policies_report(Request $request)
    {
        $filters = [
            'period_of_travel' => $request->input('period_of_travel'),
            'destination'       => $request->input('destination'),
            'travel_period'     => $request->input('travel_period'),
            'issue_date' => $request->get('issue_date'),
            'expiry_date' => $request->get('expiry_date'),
            'travel_plan'         => $request->input('travel_plan'),
            'insurance_company' => $request->input('insurance_company'),
            'agent_name'         => $request->input('agent_name'),
            'client_age'        => $request->input('client_age'),
        ];
        $groupBy = $request->get('group_by', $request->grouptype);
    
        // Retrieve filtered and grouped policies
        $policies = PurchasePolicy::getFilterTravelPolicyReport($filters, $groupBy);
        $insuranceCompanies = InsuranceCompany::all();
        $agents = AgentModel::get(); // Fetch only needed fields
        $clients = Client::get();
        // Predefined insurance types for the dropdown
        $periods = InsurancePeriod::all();
        $countries = Country::all();
        $ages = Ages::all();
        
        return view('admin.reports.travel_policies_report', compact('policies', 'insuranceCompanies', 'agents', 'periods', 'clients', 'countries','ages'));
    }
    public function pets_policies_report(Request $request)
    {
       $filters = [
            'insurance_company' => $request->get('insurance_company'),
            'issue_date' => $request->get('issue_date'),
            'expiry_date' => $request->get('expiry_date'),
            'pets_type' => $request->get('pets_type'),
            'pet_breed' => $request->get('pet_breed'),
            'agent_name' => $request->get('agent_name'),
            'owner_city' => $request->get('owner_city'),
            'owner_district' => $request->get('owner_district'),
            'owner_age' => $request->get('owner_age'),
        ];
        $groupBy = $request->get('group_by', $request->grouptype);
    
        // Retrieve filtered and grouped policies
        $policies = PurchasePolicy::getFilterPetsPolicyReport($filters, $groupBy);
        
        // Fetch necessary data for the dropdowns
        $insuranceCompanies = InsuranceCompany::all();
        $agents = AgentModel::get(); // Fetch only needed fields
        $clients = Client::get();
        $breed=ClientPetsInsurance::select('breed')->groupBy('breed')->get();
        $positions = DB::table('clients')->select('position')->whereNotNull('position')->groupBy('position')->get();
        $districts = District::all();
        $cities = Cities::all();
        $supervisor = SupervisorModel::all();
        $ages = Ages::all();
        
        return view('admin.reports.pets_policies_report', compact('policies', 'insuranceCompanies', 'agents', 'clients', 'positions','districts','cities','supervisor','ages','breed'));
    }
    public function cancelled_by_admin_report(Request $request)
    {
       $filters = [
            'insurance_company' => $request->get('insurance_company'),
            'issue_date' => $request->get('issue_date'),
            'expiry_date' => $request->get('expiry_date'),
            'policy_type' => $request->get('policy_type'),
            'client_name' => $request->get('client_name'),
        ];
        $groupBy = $request->get('group_by', $request->grouptype);
    
        // Retrieve filtered and grouped policies
        $policies = PurchasePolicy::getFilterAllPolicy($filters, $groupBy);
        // Predefined insurance types for the dropdown
        $policyTypes = [
            ['id' => 1,  'name' => 'Home Insurance'],
            ['id' => 2,  'name' => 'Office Insurance'],
            ['id' => 3,  'name' => 'Life Insurance'],
            ['id' => 4,  'name' => 'Critical Illness Insurance'],
            ['id' => 5,  'name' => 'Personal Accident Insurance'],
            ['id' => 6,  'name' => 'Individual Medical Insurance'],
            ['id' => 7,  'name' => 'Family Medical Insurance'],
            ['id' => 8,  'name' => 'Pets Insurance'],
            ['id' => 9,  'name' => 'Dental Insurance'],
            ['id' => 10, 'name' => 'Travel Insurance'],
            ['id' => 11, 'name' => 'Marine Insurance'],
            ['id' => 12, 'name' => 'Motor Insurance']
        ];
        $insuranceCompanies = InsuranceCompany::all();
        $blackList = Client::where('is_blacklisted',1)->get();
        return view('admin.reports.cancelled_by_admin_report', compact('policies', 'insuranceCompanies','policyTypes', 'blackList'));
    }
    public function renewed_by_admin_report(Request $request)
    {
        $filters = [
            'insurance_company' => $request->get('insurance_company'),
            'issue_date' => $request->get('issue_date'),
            'expiry_date' => $request->get('expiry_date'),
            'policy_type' => $request->get('policy_type'),
            'client_name' => $request->get('client_name'),
        ];
        $groupBy = $request->get('group_by', $request->grouptype);
    
        // Retrieve filtered and grouped policies
        $policies = PurchasePolicy::getFilterAllPolicy($filters, $groupBy);
        $agents = AgentModel::get(); // Fetch only needed fields
        $policyTypes = [
            ['id' => 1,  'name' => 'Home Insurance'],
            ['id' => 2,  'name' => 'Office Insurance'],
            ['id' => 3,  'name' => 'Life Insurance'],
            ['id' => 4,  'name' => 'Critical Illness Insurance'],
            ['id' => 5,  'name' => 'Personal Accident Insurance'],
            ['id' => 6,  'name' => 'Individual Medical Insurance'],
            ['id' => 7,  'name' => 'Family Medical Insurance'],
            ['id' => 8,  'name' => 'Pets Insurance'],
            ['id' => 9,  'name' => 'Dental Insurance'],
            ['id' => 10, 'name' => 'Travel Insurance'],
            ['id' => 11, 'name' => 'Marine Insurance'],
            ['id' => 12, 'name' => 'Motor Insurance']
        ];
        $insuranceCompanies = InsuranceCompany::all();
        $blackList = Client::where('is_blacklisted',1)->get();
        return view('admin.reports.renewed_by_admin_report', compact('policies', 'insuranceCompanies', 'agents', 'policyTypes'));
    }
    public function expired_without_renewal_report(Request $request)
    {
       $filters = [
            'insurance_company' => $request->get('insurance_company'),
            'issue_date' => $request->get('issue_date'),
            'expiry_date' => $request->get('expiry_date'),
            'agent_name' => $request->get('agent_name'),
            'client_position' => $request->get('client_position'),
            'client_district' => $request->get('client_district'),
            'policy_type' => $request->get('policy_type'),
            'client_gender' => $request->get('client_gender'),
            'client_city' => $request->get('client_city'),
            'supervisor' => $request->get('supervisor'),
            'client_age' => $request->get('client_age'),
            'total_commission' => $request->get('total_commission'),

        ];
        $groupBy = $request->get('group_by', $request->grouptype);
        // Retrieve filtered and grouped policies
        $policies = PurchasePolicy::getFilterAllPolicy($filters, $groupBy);
        
        // Fetch necessary data for the dropdowns
        $insuranceCompanies = InsuranceCompany::all();
        $agents = AgentModel::get(); // Fetch only needed fields
        $clients = Client::get();
        
        $policyTypes = [
            ['id' => 1,  'name' => 'Home Insurance'],
            ['id' => 2,  'name' => 'Office Insurance'],
            ['id' => 3,  'name' => 'Life Insurance'],
            ['id' => 4,  'name' => 'Critical Illness Insurance'],
            ['id' => 5,  'name' => 'Personal Accident Insurance'],
            ['id' => 6,  'name' => 'Individual Medical Insurance'],
            ['id' => 7,  'name' => 'Family Medical Insurance'],
            ['id' => 8,  'name' => 'Pets Insurance'],
            ['id' => 9,  'name' => 'Dental Insurance'],
            ['id' => 10, 'name' => 'Travel Insurance'],
            ['id' => 11, 'name' => 'Marine Insurance'],
            ['id' => 12, 'name' => 'Motor Insurance']
        ];
        $positions = DB::table('clients')->select('position')->whereNotNull('position')->groupBy('position')->get();
        $district = District::all();
        $city = Cities::all();
        $supervisor = SupervisorModel::all();
        $ages = Ages::all();
        
        return view('admin.reports.expired_without_renewal_report', compact('policies', 'insuranceCompanies', 'agents', 'policyTypes', 'clients', 'positions','district','city','supervisor','ages'));
    }
    public function sold_policies_by_location(Request $request)
    {
        $filters = [
            'insurance_company' => request()->get('insurance_company'),
            'issue_date' => request()->get('issue_date'),
            'expiry_date' => request()->get('expiry_date'),
            'agent_name' => request()->get('agent_name'),
            'client_position' => request()->get('client_position'),
            'client_district' => request()->get('client_district'),
            'policy_type' => request()->get('policy_type'),
            'client_gender' => request()->get('client_gender'),
            'client_name' => request()->get('client_name'),
            'client_city' => request()->get('client_city'),
            'supervisor' => request()->get('supervisor'),
            'client_age' => request()->get('client_age'),
        ];
        $groupBy = request()->get('group_by', $request->grouptype);
        $policies = PurchasePolicy::getFilterAllPolicy($filters, $groupBy);
        // Fetch necessary data for the dropdowns
        $insuranceCompanies = InsuranceCompany::all();
        $agents = AgentModel::get(); // Fetch only needed fields
        $clients = Client::get();
        
        // Predefined insurance types for the dropdown
        $policyTypes = [
            ['id' => 1,  'name' => 'Home Insurance'],
            ['id' => 2,  'name' => 'Office Insurance'],
            ['id' => 3,  'name' => 'Life Insurance'],
            ['id' => 4,  'name' => 'Critical Illness Insurance'],
            ['id' => 5,  'name' => 'Personal Accident Insurance'],
            ['id' => 6,  'name' => 'Individual Medical Insurance'],
            ['id' => 7,  'name' => 'Family Medical Insurance'],
            ['id' => 8,  'name' => 'Pets Insurance'],
            ['id' => 9,  'name' => 'Dental Insurance'],
            ['id' => 10, 'name' => 'Travel Insurance'],
            ['id' => 11, 'name' => 'Marine Insurance'],
            ['id' => 12, 'name' => 'Motor Insurance']
        ];
        $positions = Occupations::get();
        $district = District::all();
        $city = Cities::all();
        $supervisor = SupervisorModel::all();
        $ages = Ages::all();
        $clients = Client::all();
        return view('admin.reports.sold_policies_by_location', compact('policies', 'insuranceCompanies', 'agents', 'policyTypes', 'clients', 'positions','district','city','supervisor','ages'));
    }
    public function claims_report(Request $request)
    {
        $filters = [
            'insurance_company' => request()->get('insurance_company'),
            'issue_date' => request()->get('issue_date'),
            'expiry_date' => request()->get('expiry_date'),
            'policy_type' => request()->get('policy_type'),
            'client_name' => request()->get('client_name'),
            'claim_status' => request()->get('claim_status'),
        ];
        $groupBy = request()->get('group_by', $request->grouptype);
        $policies = Claim::getClaimsReport($filters, $groupBy);
        
        $insuranceCompanies = InsuranceCompany::all();
        $agents = AgentModel::get();
        $clients = Client::get();
        
        // Predefined insurance types for the dropdown
        $policyTypes = [
            ['id' => 1,  'name' => 'Home Insurance'],
            ['id' => 2,  'name' => 'Office Insurance'],
            ['id' => 3,  'name' => 'Life Insurance'],
            ['id' => 4,  'name' => 'Critical Illness Insurance'],
            ['id' => 5,  'name' => 'Personal Accident Insurance'],
            ['id' => 6,  'name' => 'Individual Medical Insurance'],
            ['id' => 7,  'name' => 'Family Medical Insurance'],
            ['id' => 8,  'name' => 'Pets Insurance'],
            ['id' => 9,  'name' => 'Dental Insurance'],
            ['id' => 10, 'name' => 'Travel Insurance'],
            ['id' => 11, 'name' => 'Marine Insurance'],
            ['id' => 12, 'name' => 'Motor Insurance']
        ];
        $positions = Occupations::get();
        $district = District::all();
        $city = Cities::all();
        $supervisor = SupervisorModel::all();
        $ages = Ages::all();
        $clients = Client::all();
        return view('admin.reports.claims_report', compact('policies', 'insuranceCompanies', 'agents', 'policyTypes', 'clients', 'positions','district','city','supervisor','ages'));
    }
    public function complaints_report(Request $request)
    {
        $filters = [
            'insurance_company' => request()->get('insurance_company'),
            'issue_date' => request()->get('issue_date'),
            'expiry_date' => request()->get('expiry_date'),
            'policy_type' => request()->get('policy_type'),
            'client_name' => request()->get('client_name'),
            'claim_status' => request()->get('claim_status'),
        ];
        $groupBy = request()->get('group_by', $request->grouptype);
        $policies = Complaint::getComplaintReport($filters, $groupBy);
        
        $insuranceCompanies = InsuranceCompany::all();
        $clients = Client::get();
        
        // Predefined insurance types for the dropdown
        $policyTypes = [
            ['id' => 1,  'name' => 'Home Insurance'],
            ['id' => 2,  'name' => 'Office Insurance'],
            ['id' => 3,  'name' => 'Life Insurance'],
            ['id' => 4,  'name' => 'Critical Illness Insurance'],
            ['id' => 5,  'name' => 'Personal Accident Insurance'],
            ['id' => 6,  'name' => 'Individual Medical Insurance'],
            ['id' => 7,  'name' => 'Family Medical Insurance'],
            ['id' => 8,  'name' => 'Pets Insurance'],
            ['id' => 9,  'name' => 'Dental Insurance'],
            ['id' => 10, 'name' => 'Travel Insurance'],
            ['id' => 11, 'name' => 'Marine Insurance'],
            ['id' => 12, 'name' => 'Motor Insurance']
        ];
        $complaint_statuses = ComplaintStatus::get();
        $clients = Client::all();
        return view('admin.reports.complaints_report', compact('policies', 'insuranceCompanies', 'complaint_statuses', 'policyTypes', 'clients'));
    }
    public function supervisor_report(Request $request)
    {
        $filters = [
            'insurance_company' => request()->get('insurance_company'),
            'issue_date' => request()->get('issue_date'),
            'expiry_date' => request()->get('expiry_date'),
            'policy_type' => request()->get('policy_type'),
            'supervisor_name' => request()->get('supervisor_name'),
            'agent_name' => request()->get('agent_name'),
            'supervisor_report' => 1,
        ];
        $groupBy = request()->get('group_by', $request->grouptype);
        $policies = PurchasePolicy::getFilterAllPolicy($filters, $groupBy);
        
        $insuranceCompanies = InsuranceCompany::all();
        
        $policyTypes = [
            ['id' => 1,  'name' => 'Home Insurance'],
            ['id' => 2,  'name' => 'Office Insurance'],
            ['id' => 3,  'name' => 'Life Insurance'],
            ['id' => 4,  'name' => 'Critical Illness Insurance'],
            ['id' => 5,  'name' => 'Personal Accident Insurance'],
            ['id' => 6,  'name' => 'Individual Medical Insurance'],
            ['id' => 7,  'name' => 'Family Medical Insurance'],
            ['id' => 8,  'name' => 'Pets Insurance'],
            ['id' => 9,  'name' => 'Dental Insurance'],
            ['id' => 10, 'name' => 'Travel Insurance'],
            ['id' => 11, 'name' => 'Marine Insurance'],
            ['id' => 12, 'name' => 'Motor Insurance']
        ];
        $complaint_statuses = ComplaintStatus::get();
        $agents = AgentModel::all();
        $supervisors = AgentModel::getSupervisor();
        return view('admin.reports.supervisor_report', compact('policies', 'insuranceCompanies', 'complaint_statuses', 'policyTypes', 'agents','supervisors'));
    }
}
