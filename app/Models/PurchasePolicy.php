<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\InsuranceCompany;
use App\Models\ClientHomeInsurance;
use App\Models\InsurancePlanModels\HomePlan;
use App\Models\InsurancePlanModels\CriticalIllnessPlan;
use App\Models\CriticalIllnessInsurance;
use App\Models\InsurancePlanModels\PetPlan;
use App\Models\ClientPetsInsurance;
use App\Models\InsurancePlanModels\MarinePlan;
use App\Models\ClientMarineInsurance;
use App\Models\InsurancePlanModels\DentalPlan;
use App\Models\ClientDentalsInsurance;
use App\Models\InsurancePlanModels\TravelPlan;
use App\Models\ClientTravelInsurance;
use App\Models\AgentModel as Agent;
use App\Models\ClientOfficeInsurance;
use App\Models\ClientLifeInsurance;
use App\Models\ClientPersonalAccidentInsurance;
use App\Models\ClientFamilyMedicalInsurance;
use App\Models\InsurancePlanModels\OfficePlan;
use App\Models\InsurancePlanModels\LifePlan;
use App\Models\InsurancePlanModels\PersonalAccidentPlan;
use App\Models\InsurancePlanModels\InPatientPlan;
use App\Models\InsurancePlanModels\InOutPatientPlan;
use App\Models\InsurancePlanModels\MotorInsurancePlan;
use App\Models\IndividualPlanModel;

use Illuminate\Database\Eloquent\SoftDeletes;
class PurchasePolicy extends Model
{
    use HasFactory;
    use softDeletes;
    protected $table = 'purchase_policy';
    protected $appends = ['full_name'];
    protected $fillable = [
        'client_id',
        'policy_id',
        'plan_id',
        'policy_no',
        'policy_type',
        'inception_date',
        'expiry_date',
        'payment_status',
        'net_premium',
        'fees',
        'stamps',
        'sales_tax',
        'cbj',
        'sales_tax_cbj',
        'gross_premium',
        'commission_percentage',
        'commission_amount',
        'policy_pdf_url'
    ];
    public function getFullNameAttribute() // notice that the attribute name is in CamelCase.
    {
        return $this->first_name . ' ' . $this->father_name . ' ' . $this->grandfather_name . ' ' . $this->surname;
    }
    public static function savePurchasePolicy($request)
    {
        $policy = new PurchasePolicy();
        $policy->client_id = $request['client_id'];
        $policy->policy_id = $request['policy_id'];
        $policy->plan_id = $request['plan_id'];
        $policy->insurance_company_id = $request['insurance_company_id'];
        $policy->policy_no = $request['police_no'];
        $policy->policy_type = $request['policy_type'];
        $policy->inception_date = $request['inception_date'];
        $policy->expiry_date = $request['expiry_date'];
        $policy->payment_status = 0;
        $policy->save();
        return $policy;
    }
    public static function updatePurchasePolicy($request)
    {
        $policy = PurchasePolicy::find($request['purchase_id']);
        $policy->plan_id = $request['plan_id'];
        $policy->plan_name = $request['plan_name'] ?? '';
        // $policy->policy_plan_limit=$request['policy_plan_limit'] ?? 0;
        $policy->policy_plan_limit = isset($request['policy_plan_limit'])
            ? (int)str_replace(',', '', $request['policy_plan_limit'])
            : 0;

        $policy->insurance_company_id = $request['insurance_company_id'];
        $policy->inception_date = $request['inception_date'];
        $policy->expiry_date = $request['expiry_date'];
        $policy->net_premium = $request['net_premium'] ?? 0;
        $policy->fees = $request['fees'] ?? 0;
        $policy->stamps = $request['stamps'] ?? 0;
        $policy->sales_tax = $request['sales_tax'] ?? 0;
        $policy->cbj = $request['cbj'] ?? 0;
        $policy->sales_tax_cbj = $request['sales_tax_cbj'] ?? 0;
        $policy->gross_premium = $request['gross_premium'] ?? 0;
        $policy->commission_percentage = $request['commission_percentage'] ?? 0;
        $policy->commission_amount = $request['commission_amount'] ?? 0;
        $policy->policy_pdf_url = $request['policy_pdf_url'] ?? '';
        $policy->payment_status = 0;
        $policy->save();
        return $policy;
    }
    // public static function getAllPolicy($user_id)
    // {
    //     $policyTypes = [
    //         1  => 'Home Insurance',
    //         2  => 'Office Insurance',
    //         3  => 'Life Insurance',
    //         4  => 'Critical Illness Insurance',
    //         5  => 'Personal Accident Insurance',
    //         6  => 'Individual Medical Insurance',
    //         7  => 'Family Medical Insurance',
    //         8  => 'Pet Insurance',
    //         9  => 'Dental Insurance',
    //         10 => 'Travel Insurance',
    //         11 => 'Marine Insurance',
    //         12 => 'Motor Insurance'
    //     ];

    //     $policyPdf = [
    //         1  => 'home_policy',
    //         2  => 'office_policy',
    //         3  => 'life_policy',
    //         4  => 'critical_illness_policy',
    //         5  => 'personal_accident_policy',
    //         6  => 'individual_medical_policy',
    //         7  => 'family_medical_policy',
    //         8  => 'pets_policy',
    //         9  => 'dental_policy',
    //         10 => 'travel_policy',
    //         11 => 'marine_policy',
    //         12 => 'motor_policy'
    //     ];

    //     $policy = PurchasePolicy::select(
    //         'purchase_policy.id',
    //         'purchase_policy.client_id',
    //         'purchase_policy.policy_id',
    //         'purchase_policy.plan_id',
    //         'purchase_policy.insurance_company_id',
    //         'purchase_policy.policy_no',
    //         'purchase_policy.policy_type',
    //         'purchase_policy.inception_date',
    //         'purchase_policy.expiry_date',
    //         'purchase_policy.payment_status',
    //         'purchase_policy.net_premium',
    //         'purchase_policy.fees',
    //         'purchase_policy.stamps',
    //         'purchase_policy.sales_tax',
    //         'purchase_policy.cbj',
    //         'purchase_policy.sales_tax_cbj',
    //         'purchase_policy.gross_premium',
    //         'purchase_policy.commission_percentage',
    //         'purchase_policy.commission_amount',
    //         'insurance_companies.company_name',
    //         'clients.first_name',
    //         'clients.father_name',
    //         'clients.grandfather_name',
    //         'clients.surname'
    //     )
    //     ->leftJoin('insurance_companies', 'purchase_policy.insurance_company_id', '=', 'insurance_companies.id')
    //     ->leftJoin('clients', 'purchase_policy.client_id', '=', 'clients.id')
    //     ->where('purchase_policy.client_id', $user_id)
    //     ->where('purchase_policy.payment_status', 1)
    //     ->get();

    //     foreach ($policy as $p) {
    //         $p->policy_type_no = $p->policy_type ?? 0;
    //         $p->policy_type    = $policyTypes[$p->policy_type] ?? 'Unknown Policy Type';

    //         $folder = $policyPdf[$p->policy_type_no] ?? 'policy';
    //         $p->pdf_url = url('insurance_pdfs/' . $folder . '/policy_' . $p->id . '.pdf');
    //     }

    //     return $policy;
    // }
    public static function getAllPolicy($user_id)
{
    $policyTypes = [
        1  => 'Home Insurance',
        2  => 'Office Insurance',
        3  => 'Life Insurance',
        4  => 'Critical Illness Insurance',
        5  => 'Personal Accident Insurance',
        6  => 'Individual Medical Insurance',
        7  => 'Family Medical Insurance',
        8  => 'Pet Insurance',
        9  => 'Dental Insurance',
        10 => 'Travel Insurance',
        11 => 'Marine Insurance',
        12 => 'Motor Insurance'
    ];

    $policy = PurchasePolicy::select(
        'purchase_policy.id',
        'purchase_policy.client_id',
        'purchase_policy.policy_id',
        'purchase_policy.plan_id',
        'purchase_policy.insurance_company_id',
        'purchase_policy.policy_no',
        'purchase_policy.policy_type',
        'purchase_policy.plan_name',
        'purchase_policy.policy_plan_limit',
        'purchase_policy.inception_date',
        'purchase_policy.expiry_date',
        'purchase_policy.payment_status',
        'purchase_policy.net_premium',
        'purchase_policy.fees',
        'purchase_policy.stamps',
        'purchase_policy.sales_tax',
        'purchase_policy.cbj',
        'purchase_policy.sales_tax_cbj',
        'purchase_policy.gross_premium',
        'purchase_policy.commission_percentage',
        'purchase_policy.commission_amount',
        'purchase_policy.policy_pdf_url',
        'insurance_companies.company_name',
        'clients.first_name',
        'clients.father_name',
        'clients.grandfather_name',
        'clients.surname'
    )
    ->leftJoin('insurance_companies', 'purchase_policy.insurance_company_id', '=', 'insurance_companies.id')
    ->leftJoin('clients', 'purchase_policy.client_id', '=', 'clients.id')
    ->where('purchase_policy.client_id', $user_id)
    ->where('purchase_policy.payment_status', 1)
    ->get();

    foreach ($policy as $p) {
        $p->policy_type_no = $p->policy_type ?? 0;
        $p->policy_type    = $policyTypes[$p->policy_type] ?? 'Unknown Policy Type';

        // Build the correct public URL from the actual stored path
        $p->pdf_url = self::buildPdfUrl($p->policy_pdf_url);

        // Hide the raw filesystem path from the response
        $p->makeHidden('policy_pdf_url');
    }

    return $policy;
}

private static function buildPdfUrl($storedPath)
{
    if (!$storedPath) {
        return null;
    }

    // Already a full URL? just return it
    if (filter_var($storedPath, FILTER_VALIDATE_URL)) {
        return $storedPath;
    }

    // If it's an absolute filesystem path, extract from "insurance_pdfs/" onward
    if (str_contains($storedPath, 'insurance_pdfs/')) {
        $relative = 'insurance_pdfs/' . explode('insurance_pdfs/', $storedPath, 2)[1];
        return url($relative);
    }

    // Otherwise treat it as already relative
    return url($storedPath);
}
    public static function getPolicyCompanyByType($request)
    {
        $policy = PurchasePolicy::select('purchase_policy.*', 'insurance_companies.company_name')
            ->leftJoin('insurance_companies', 'purchase_policy.insurance_company_id', '=', 'insurance_companies.id')
            ->where('purchase_policy.client_id', $request->user_id)
            ->where('purchase_policy.policy_type', $request->insurance_type_id)
            ->where('purchase_policy.payment_status', 1)
            ->get();
        return $policy;
    }

    public function client()
    {
        // return $this->belongsTo(Client::class,'id','client_id');
        return $this->belongsTo(Client::class, 'client_id', 'id')->withTrashed();
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
    public function insurance_company()
    {
        return $this->belongsTo(InsuranceCompany::class, 'insurance_company_id', 'id');
    }
    public function client_home_insurance()
    {
        return $this->belongsTo(ClientHomeInsurance::class, 'policy_id');
    }
    public function critical_illness_plan()
    {
        return $this->belongsTo(CriticalIllnessPlan::class, 'plan_id');
    }
    public function client_critical_illness_insurance()
    {
        return $this->belongsTo(CriticalIllnessInsurance::class, 'policy_id');
    }
    public function pet_plan()
    {
        return $this->belongsTo(PetPlan::class, 'plan_id');
    }
    public function client_pet_insurance()
    {
        return $this->belongsTo(ClientPetsInsurance::class, 'policy_id');
    }
    public function home_plan()
    {
        return $this->belongsTo(HomePlan::class, 'plan_id');
    }
    public function marine_plan()
    {
        return $this->belongsTo(MarinePlan::class, 'plan_id');
    }
    public function client_marine_insurance()
    {
        return $this->belongsTo(ClientMarineInsurance::class, 'policy_id');
    }
    public function dental_plan()
    {
        return $this->belongsTo(DentalPlan::class, 'plan_id');
    }
    public function client_dental_insurance()
    {
        return $this->belongsTo(ClientDentalsInsurance::class, 'policy_id');
    }
    public function travel_plan()
    {
        return $this->belongsTo(TravelPlan::class, 'plan_id');
    }
    public function client_travel_insurance()
    {
        return $this->belongsTo(ClientTravelInsurance::class, 'policy_id');
    }
    public function office_plan()
    {
        return $this->belongsTo(OfficePlan::class, 'plan_id');
    }
    public function client_office_insurance()
    {
        return $this->belongsTo(ClientOfficeInsurance::class, 'policy_id');
    }

    public function life_plan()
    {
        return $this->belongsTo(LifePlan::class, 'plan_id');
    }
    public function client_life_insurance()
    {
        return $this->belongsTo(ClientLifeInsurance::class, 'policy_id');
    }

    public function personal_accident_plan()
    {
        return $this->belongsTo(PersonalAccidentPlan::class, 'plan_id');
    }
    public function client_personal_accident_insurance()
    {
        return $this->belongsTo(ClientPersonalAccidentInsurance::class, 'policy_id');
    }

    public function client_family_medical_insurance()
    {
        return $this->belongsTo(ClientFamilyMedicalInsurance::class, 'policy_id');
    }

    public function in_patient_plan()
    {
        return $this->belongsTo(InPatientPlan::class, 'plan_id');
    }

    public function in_out_patient_plan()
    {
        return $this->belongsTo(InOutPatientPlan::class, 'plan_id');
    }

    public function individual_medical_plan()
    {
        return $this->belongsTo(InPatientPlan::class, 'plan_id');
    }

    public function getIndividualMedicalPlanAttribute()
    {
        $plan = InPatientPlan::find($this->plan_id);
        if (!$plan) {
            $plan = InOutPatientPlan::find($this->plan_id);
        }
        if (!$plan) {
            $plan = IndividualPlanModel::find($this->plan_id);
        }
        return $plan;
    }

    public function family_medical_plan()
    {
        return $this->belongsTo(InPatientPlan::class, 'plan_id');
    }

    public function getFamilyMedicalPlanAttribute()
    {
        $plan = InPatientPlan::find($this->plan_id);
        if (!$plan) {
            $plan = InOutPatientPlan::find($this->plan_id);
        }
        return $plan;
    }

    public function motor_plan()
    {
        return $this->belongsTo(MotorInsurancePlan::class, 'plan_id');
    }
    public function client_motor_insurance()
    {
        return $this->belongsTo(ClientMotorInsurance::class, 'policy_id');
    }

    public function getDetails()
    {
        return self::getCombinedPolicyDetails($this);
    }

    public static function getCombinedPolicyDetails($purchasePolicy)
    {
        if (!$purchasePolicy) {
            return null;
        }

        $policyType = (int) $purchasePolicy->policy_type;
        $policyId   = $purchasePolicy->policy_id;

        switch ($policyType) {
            case 1:
                return ClientHomeInsurance::getHomeInsuranceDetails($policyId);
            case 2:
                return ClientOfficeInsurance::getOfficeInsuranceDetails($policyId);
            case 3:
                return ClientLifeInsurance::getLifeInsuranceDetails($policyId);
            case 4:
                return CriticalIllnessInsurance::getCriticalIllnessInsuranceDetails($policyId);
            case 5:
                return ClientPersonalAccidentInsurance::getClientPersonalAccidentInsuranceDetails($policyId);
            case 6:
            case 7:
                $clientMedical = ClientFamilyMedicalInsurance::find($policyId);
                if ($clientMedical) {
                    $dataArr = [
                        'policy_id'      => $policyId,
                        'birth_date'     => $clientMedical->birth_date ?? optional($purchasePolicy->client)->birth_date,
                        'gender'         => ($clientMedical->gender == 1 || strtolower($clientMedical->gender ?? '') === 'male') ? 'male' : 'female',
                        'insurance_type' => $clientMedical->insurance_type ?? 1,
                    ];
                    return ClientFamilyMedicalInsurance::getClientFamilyMedicalInsuranceDetails($dataArr);
                }
                return null;
            case 8:
                return ClientPetsInsurance::getPetsInsuranceDetails($policyId);
            case 9:
                return ClientDentalsInsurance::getDentalsInsuranceDetails($policyId);
            case 10:
                return ClientTravelInsurance::getTravelsInsuranceDetails($policyId);
            case 11:
                return ClientMarineInsurance::getMarineInsuranceDetails($policyId);
            case 12:
                return ClientMotorInsurance::getMotorInsuranceDetails($policyId);
            default:
                return null;
        }
    }
    public static function getFilterAllPolicy($filters = [], $groupBy = 'insurance_company')
    {
        $policyTypes = [
            1  => 'Home Insurance',
            2  => 'Office Insurance',
            3  => 'Life Insurance',
            4  => 'Critical Illness Insurance',
            5  => 'Personal Accident Insurance',
            6  => 'Individual Medical Insurance',
            7  => 'Family Medical Insurance',
            8  => 'Pet Insurance',
            9  => 'Dental Insurance',
            10 => 'Travel Insurance',
            11 => 'Marine Insurance',
            12 => 'Motor Insurance'
        ];

        $policyPdf = [
            1  => 'home_policy',
            2  => 'office_policy',
            3  => 'life_policy',
            4  => 'critical_illness_policy',
            5  => 'personal_accident_policy',
            6  => 'individual_medical_policy',
            7  => 'family_medical_policy',
            8  => 'pets_policy',
            9  => 'dental_policy',
            10 => 'travel_policy',
            11 => 'marine_policy',
            12 => 'motor_policy'
        ];

        $policies = PurchasePolicy::select(
            'purchase_policy.*',
            'insurance_companies.company_name',
            'agent.first_name as agent_name',
            'supervisors.first_name as supervisor_name',
            'clients.first_name as client_name',
            'clients.id as client_id',
            'occupations.name as occupations_name',
            'districts.name as district',
            'clients.black_list_reason',
            'cities.name as city',
            DB::raw('
                CASE
                    WHEN clients.is_blacklisted = 1 THEN "Yes"
                    WHEN clients.is_blacklisted = 2 THEN "No"
                    ELSE "Unknown"
                END as is_blacklisted
            '),
            DB::raw('
                CASE
                    WHEN clients.marital_status = 1 THEN "Single"
                    WHEN clients.marital_status = 2 THEN "Married"
                    WHEN clients.marital_status = 3 THEN "Divorced"
                    WHEN clients.marital_status = 4 THEN "Widowed"
                    ELSE "Unknown"
                END as marital_status
            '),
            DB::raw('
                CASE
                    WHEN clients.gender = 1 THEN "Male"
                    WHEN clients.gender = 2 THEN "Female"
                    ELSE "Unknown"
                END as gender
            '),
            DB::raw('
                CASE
                    WHEN purchase_policy.expiry_date >= CURDATE() THEN "Renewed"
                    WHEN purchase_policy.expiry_date < CURDATE() THEN "Non-Renewed"
                    ELSE "Unknown"
                END as renew_status
            '),
            DB::raw(
                '
                IFNULL(
                    (SELECT SUM(agent_commissions.agent_commission)
                     FROM agent_commissions
                     WHERE agent_commissions.agent_id = agent.id
                       AND agent_commissions.line_of_business_id = purchase_policy.policy_type),
                    0
                ) as agent_commission_amount'
            ),
            DB::raw(
                '
                IFNULL(
                    (SELECT SUM(agent_supervisor_commissions.supervisor_commission)
                     FROM agent_supervisor_commissions
                     WHERE agent_supervisor_commissions.agent_id = agent.id
                     AND agent_supervisor_commissions.supervisor_id = agent.id
                       AND agent_supervisor_commissions.line_of_business_id = purchase_policy.policy_type),
                    0
                ) as supervisor_commission_amount'
            ),

        )
            ->leftJoin('insurance_companies', 'purchase_policy.insurance_company_id', '=', 'insurance_companies.id')
            ->leftJoin('clients', 'purchase_policy.client_id', '=', 'clients.id')
            ->leftJoin('agent', 'clients.agent_id', '=', 'agent.id')
            ->leftJoin('agent as supervisors', 'agent.supervisor_id', '=', 'supervisors.id')
            ->leftJoin('line_of_businesses', 'purchase_policy.policy_type', '=', 'line_of_businesses.new_id')
            ->leftJoin('districts', 'clients.district_id', '=', 'districts.id')
            ->leftJoin('occupations', 'clients.occupation_id', '=', 'occupations.id')
            ->leftJoin('cities', 'clients.city_id', '=', 'cities.id')
            ->where('purchase_policy.payment_status', 1);
        // Apply filters based on the form inputs
        if (!empty($filters['insurance_company'])) {
            $policies->where('purchase_policy.insurance_company_id', $filters['insurance_company']);
        }
        if (!empty($filters['supervisor_report']) && $filters['supervisor_report'] == 1) {
            $policies->whereNotNull('agent.supervisor_id');
        }
        if (!empty($filters['issue_date'])) {
            $policies->whereDate('purchase_policy.inception_date', '>=', $filters['issue_date']);
        }
        if (!empty($filters['expiry_date'])) {
            $policies->whereDate('purchase_policy.expiry_date', '<=', $filters['expiry_date']);
        }
        if (!empty($filters['agent_name'])) {
            $policies->where('clients.agent_id', $filters['agent_name']);
        }
        if (!empty($filters['client_position'])) {
            $policies->where('clients.occupation_id', $filters['client_position']);
        }
        if (!empty($filters['client_marital'])) {
            $policies->where('clients.marital_status', $filters['client_marital']);
        }
        if (!empty($filters['client_district'])) {
            $policies->where('clients.district_id', $filters['client_district']);
        }
        if (!empty($filters['policy_type'])) {
            $policies->where('purchase_policy.policy_type', $filters['policy_type']);
        }
        if (!empty($filters['client_gender'])) {
            $policies->where('clients.gender', $filters['client_gender']);
        }
        if (!empty($filters['client_name'])) {
            $policies->where('clients.id', $filters['client_name']);
        }
        if (!empty($filters['client_city'])) {
            $policies->where('clients.city_id', $filters['client_city']);
        }
        if (!empty($filters['supervisor'])) {
            $policies->where('supervisor.id', $filters['supervisor']);
        }
        if (!empty($filters['supervisor_name'])) {
            $policies->where('agent.supervisor_id', $filters['supervisor_name']);
        }
        if (!empty($filters['policy_plan'])) {
            $policies->where('purchase_policy.plan_name', $filters['policy_plan']);
        }
        if (!empty($filters['client_age'])) {
            $policies->whereRaw('TIMESTAMPDIFF(YEAR, clients.birth_date, CURDATE()) = ?', [$filters['client_age']]);
        }
        if (!empty($filters['total_commission'])) {
            $policies->where('purchase_policy.commission_amount', $filters['total_commission']);
        }
        if (!empty($filters['renew_status']) || !empty($filters['non_renew_status'])) {
            if ($filters['renew_status'] == 1) {
                $policies->whereDate('purchase_policy.expiry_date', '>=', date('Y-m-d'));
            } else if ($filters['non_renew_status'] == 2) {
                $policies->whereDate('purchase_policy.expiry_date', '<=', date('Y-m-d'));
            }
        }
        if (!empty($groupBy == 'blacklisted')) {
            $policies->where('clients.is_blacklisted', 1);
        }
        $policies = $policies->get();

        // Group policies by the selected criterion (insurance company, agent, policy type, etc.)
        $groupedPolicies = [];

        foreach ($policies as $policy) {
            $policy->policy_type_no = $policy->policy_type ?? 0;
            $policy->policy_type = $policyTypes[$policy->policy_type] ?? 'Unknown Policy Type';
            $policy->pdf_url = url('insurance_pdfs/' . $policyPdf[$policy->policy_type_no] . '/policy_' . $policy->id . '.pdf') ?? 'Unknown Policy Type';

            // Dynamic grouping based on the selected criterion
            switch ($groupBy) {
                case 'agent_name':
                    $groupKey = $policy->agent_name ?? 'Unknown Agent';
                    break;
                case 'supervisor_name':
                    $groupKey = $policy->supervisor_name ?? 'Unknown Supervisor';
                    break;
                case 'agent_line_of_business':
                    $groupKey = $policy->agent_line_of_businesses ?? 'Unknown Agent Line Of Business';
                    break;
                case 'supervisor_line_of_businesses':
                    $groupKey = $policy->supervisor_line_of_businesses ?? 'Unknown Supervisor Line Of Business';
                    break;
                case 'supervisor_by_agent':
                    $groupKey = $policy->supervisor_by_agent ?? 'Unknown Supervisor By Agent';
                    break;
                case 'policy_plan':
                    $groupKey = $policy->policy_plan ?? 'Unknown Policy Plan';
                    break;
                case 'policy_type':
                    $groupKey = $policy->policy_type ?? 'Unknown Policy Type';
                    break;
                case 'client_name':
                    $groupKey = $policy->client_name ?? 'Unknown Client';
                    break;
                case 'gender':
                    $groupKey = $policy->gender ?? 'Unknown gender';
                    break;
                case 'position':
                    $groupKey = $policy->occupations_name ?? 'Unknown position';
                    break;
                case 'city':
                    $groupKey = $policy->city ?? 'Unknown city';
                    break;
                case 'district':
                    $groupKey = $policy->district ?? 'Unknown district';
                    break;
                case 'marital_status':
                    $groupKey = $policy->marital_status ?? 'Unknown marital';
                    break;
                case 'age':
                    $groupKey = $policy->age ?? 'Unknown age';
                    break;
                case 'date':
                    $groupKey = date('Y-m-d', strtotime($policy->issue_date)) ?? 'Unknow Date';
                    break;
                case 'blacklisted':
                    $groupKey = $policy->client_name ?? 'Unknown Client';
                    break;
                default:  // Default is grouping by insurance company
                    $groupKey = $policy->company_name ?? 'Unknown Insurance Company';
                    break;
            }

            if (!isset($groupedPolicies[$groupKey])) {
                $groupedPolicies[$groupKey] = [
                    'policies' => [],
                    'totals' => [
                        'policy_limit' => 0,
                        'net_premium_before' => 0,
                        'commission' => 0,
                        'net_premium_after' => 0,
                        'total_gross_premium' => 0,
                        'total_net_premium' => 0,
                    ]
                ];
            }

            // Add policy to group
            $groupedPolicies[$groupKey]['policies'][] = $policy;

            // Accumulate totals
            // $groupedPolicies[$groupKey]['totals']['policy_limit'] += $policy->policy_limit;
            // $groupedPolicies[$groupKey]['totals']['net_premium_before'] += $policy->net_premium_before;
            // $groupedPolicies[$groupKey]['totals']['commission'] += $policy->commission;
            // $groupedPolicies[$groupKey]['totals']['net_premium_after'] += $policy->net_premium_after;
            // $groupedPolicies[$groupKey]['totals']['total_net_premium'] += $policy->net_premium;
            // $groupedPolicies[$groupKey]['totals']['total_gross_premium'] += $policy->gross_premium;
        
          // Accumulate totals
            $groupedPolicies[$groupKey]['totals']['total_net_premium'] += (float)($policy->net_premium ?? 0);
            $groupedPolicies[$groupKey]['totals']['total_gross_premium'] += (float)($policy->gross_premium ?? 0);
     
            }

        return $groupedPolicies;
    }
    public static function getFilterTravelPolicyReport($filters = [], $groupBy = 'insurance_company')
    {
        $policies = PurchasePolicy::select(
            'purchase_policy.*',
            'insurance_companies.company_name',
            'agent.first_name as agent_name',
            'travel_plans.plan_name as travel_plans_name',
            'clients.first_name as client_name',
            'countries.name as distination',
            DB::raw('TIMESTAMPDIFF(YEAR, clients.birth_date, CURDATE()) as client_age'),
            DB::raw('CONCAT(COALESCE(client_travel_insurances.travel_days, DATEDIFF(purchase_policy.expiry_date, purchase_policy.inception_date)), " Days") as period_of_travel')
        )
            ->leftJoin('insurance_companies', 'purchase_policy.insurance_company_id', '=', 'insurance_companies.id')
            ->leftJoin('clients', 'purchase_policy.client_id', '=', 'clients.id')
            ->leftJoin('client_travel_insurances', 'client_travel_insurances.id', '=', 'purchase_policy.policy_id')
            ->leftJoin('countries', 'client_travel_insurances.destination_country_id', '=', 'countries.id')
            ->leftJoin('agent', 'clients.agent_id', '=', 'agent.id')
            ->leftJoin('travel_plans', 'purchase_policy.plan_id', '=', 'travel_plans.id')
            ->where('purchase_policy.policy_type', 10); // Only fetch travel insurance policies

        // Apply filters based on the form inputs
        if (!empty($filters['insurance_company'])) {
            $policies->where('purchase_policy.insurance_company_id', $filters['insurance_company']);
        }
        if (!empty($filters['issue_date'])) {
            $policies->whereDate('purchase_policy.inception_date', '>=', $filters['issue_date']);
        }
        if (!empty($filters['expiry_date'])) {
            $policies->whereDate('purchase_policy.expiry_date', '<=', $filters['expiry_date']);
        }
        if (!empty($filters['agent_name'])) {
            $policies->where('clients.agent_id', $filters['agent_name']);
        }
        if (!empty($filters['plan_type'])) {
            $policies->where('travel_plans.plan_name', $filters['plan_type']);
        }
        if (!empty($filters['client_age'])) {
            $policies->whereRaw('TIMESTAMPDIFF(YEAR, clients.birth_date, CURDATE()) = ?', [$filters['client_age']]);
        }

        // Get policies
        $policies = $policies->get();

        // Group policies by the selected criterion (insurance company, agent, etc.)
        $groupedPolicies = [];

        foreach ($policies as $policy) {
            switch ($groupBy) {
                case 'period_of_travel':
                    $groupKey = $policy->period_of_travel ?? 'Unknown Period of Travel';
                    break;
                case 'destination':
                    $groupKey = $policy->distination ?? 'Unknown Destination';
                    break;
                case 'client_age':
                    $groupKey = $policy->client_age ?? 'Unknown Client Age';
                    break;
                case 'agent_name':
                    $groupKey = $policy->agent_name ?? 'Unknown Agent Name';
                    break;
                case 'insurance_company':
                    $groupKey = $policy->company_name ?? 'Unknown Insurance Company';
                    break;
                case 'travel_plan':
                    $groupKey = $policy->travel_plans_name ?? 'Unknown Travel Plan';
                    break;
                default:  // Default is grouping by insurance company
                    $groupKey = $policy->company_name ?? 'Unknown Insurance Company';
                    break;
            }

            if (!isset($groupedPolicies[$groupKey])) {
                $groupedPolicies[$groupKey] = [
                    'policies' => [],
                    'totals' => [
                        'policy_limit' => 0,
                        'net_premium_before' => 0,
                        'commission' => 0,
                        'net_premium_after' => 0,
                        'total_net_premium' => 0,
                        'total_gross_premium' => 0,
                    ]
                ];
            }

            // Add policy to the group
            $groupedPolicies[$groupKey]['policies'][] = $policy;

            // Accumulate totals
            $groupedPolicies[$groupKey]['totals']['policy_limit'] += $policy->policy_limit;
            $groupedPolicies[$groupKey]['totals']['net_premium_before'] += $policy->net_premium_before;
            $groupedPolicies[$groupKey]['totals']['commission'] += $policy->commission;
            $groupedPolicies[$groupKey]['totals']['net_premium_after'] += $policy->net_premium_after;
            $groupedPolicies[$groupKey]['totals']['total_net_premium'] += $policy->net_premium;
            $groupedPolicies[$groupKey]['totals']['total_gross_premium'] += $policy->gross_premium;
        }

        return $groupedPolicies;
    }
    public static function getFilterPetsPolicyReport($filters = [], $groupBy = 'insurance_company')
    {
        $petTypes = [
            1 => 'Dog',
            2 => 'Cat'
        ];
        $policies = PurchasePolicy::select(
            'purchase_policy.*',
            'insurance_companies.company_name',
            'agent.first_name as agent_name',
            'clients.first_name as client_name',
            'client_pets_insurances.pets_type',
            'client_pets_insurances.breed as pet_breed',
            'cities.name as owner_city',
            'districts.name as owner_district',
            DB::raw('TIMESTAMPDIFF(YEAR, clients.birth_date, CURDATE()) as owner_age'),
        )
            ->leftJoin('insurance_companies', 'purchase_policy.insurance_company_id', '=', 'insurance_companies.id')
            ->leftJoin('clients', 'purchase_policy.client_id', '=', 'clients.id')
            ->leftJoin('cities', 'clients.city_id', '=', 'cities.id')
            ->leftJoin('districts', 'clients.district_id', '=', 'districts.id')
            ->leftJoin('agent', 'clients.agent_id', '=', 'agent.id')
            ->leftJoin('client_pets_insurances', 'purchase_policy.plan_id', '=', 'client_pets_insurances.id')
            ->where('purchase_policy.policy_type', 8); // Only fetch pet insurance policies

        // Apply filters based on the form inputs
        if (!empty($filters['insurance_company'])) {
            $policies->where('purchase_policy.insurance_company_id', $filters['insurance_company']);
        }
        if (!empty($filters['issue_date'])) {
            $policies->whereDate('purchase_policy.inception_date', '>=', $filters['issue_date']);
        }
        if (!empty($filters['expiry_date'])) {
            $policies->whereDate('purchase_policy.expiry_date', '<=', $filters['expiry_date']);
        }
        if (!empty($filters['agent_name'])) {
            $policies->where('clients.agent_id', $filters['agent_name']);
        }
        if (!empty($filters['pets_type'])) {
            // Map pets_type to 'Dog' or 'Cat' based on the input
            $petTypeName = $petTypes[$filters['pets_type']] ?? null;
            $policies->where('client_pets_insurances.pets_type', $petTypeName);
        }
        if (!empty($filters['pet_breed'])) {
            $policies->where('client_pets_insurances.breed', $filters['pet_breed']);
        }
        if (!empty($filters['owner_city'])) {
            $policies->where('clients.city_id', $filters['owner_city']);
        }
        if (!empty($filters['owner_district'])) {
            $policies->where('clients.district_id', $filters['owner_district']);
        }
        if (!empty($filters['owner_age'])) {
            $policies->whereRaw('TIMESTAMPDIFF(YEAR, clients.birth_date, CURDATE()) = ?', [$filters['owner_age']]);
        }
        // Get policies
        $policies = $policies->get();

        // Group policies by the selected criterion (insurance company, pet type, etc.)
        $groupedPolicies = [];

        foreach ($policies as $policy) {
            switch ($groupBy) {
                case 'insurance_company':
                    $groupKey = $policy->company_name ?? 'Unknown Insurance Company';
                    break;
                case 'pets_type':
                    $groupKey = $petTypes[$policy->pets_type] ?? 'Unknown Pet Type';
                    break;
                case 'pet_breed':
                    $groupKey = $policy->pet_breed ?? 'Unknown Pet Breed';
                    break;
                case 'agent_name':
                    $groupKey = $policy->agent_name ?? 'Unknown Agent Name';
                    break;
                case 'owner_city':
                    $groupKey = $policy->owner_city ?? 'Unknown Agent Name';
                    break;
                case 'owner_district':
                    $groupKey = $policy->owner_district ?? 'Unknown Agent Name';
                    break;
                case 'owner_age':
                    $groupKey = $policy->owner_age ?? 'Unknown Agent Name';
                    break;
                default:  // Default is grouping by insurance company
                    $groupKey = $policy->company_name ?? 'Unknown Insurance Company';
                    break;
            }

            if (!isset($groupedPolicies[$groupKey])) {
                $groupedPolicies[$groupKey] = [
                    'policies' => [],
                    'totals' => [
                        'total_net_premium' => 0,
                        'total_gross_premium' => 0,
                    ]
                ];
            }

            // Add policy to the group
            $groupedPolicies[$groupKey]['policies'][] = $policy;

            // Accumulate totals
            $groupedPolicies[$groupKey]['totals']['total_net_premium'] += $policy->net_premium;
            $groupedPolicies[$groupKey]['totals']['total_gross_premium'] += $policy->gross_premium;
        }

        return $groupedPolicies;
    }
}
