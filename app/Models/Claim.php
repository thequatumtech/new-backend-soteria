<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
class Claim extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = ['client_id','policy_id','insurance_company_id','claim_no','policy_type','effective_date','expiry_date','status','notify_client','notify_insurance_company','claim_note','attachments'];

    public static function getAllClaims($user_id)
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
        $policy = Claim::select('claims.*','insurance_companies.company_name as company_name')
            ->leftJoin('insurance_companies','claims.insurance_company_id','insurance_companies.id')
            ->where('claims.client_id', $user_id)
            ->get();

        // Add policy name based on policy_type
        foreach ($policy as $p) {
            $p->policy_type = $policyTypes[$p->policy_type] ?? 'Unknown Policy Type';
        }
        return $policy;
    }
    public static function getClaimsReport($filters = [], $groupBy = 'insurance_company')
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
        $policies = Claim::select('claims.*','purchase_policy.gross_premium','purchase_policy.policy_no','clients.first_name as client_name','insurance_companies.company_name as company_name')
            ->leftJoin('insurance_companies','claims.insurance_company_id','insurance_companies.id')
            ->leftJoin('purchase_policy', 'claims.policy_id', '=', 'purchase_policy.id')
            ->leftJoin('clients', 'purchase_policy.client_id', '=', 'clients.id');
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
            if (!empty($filters['policy_type'])) {
                $policies->where('purchase_policy.policy_type', $filters['policy_type']);
            }
            if (!empty($filters['claim_status'])) {
                $policies->where('claims.status', $filters['claim_status']);
            }
            $policies = $policies->get();

            $groupedPolicies = [];

            foreach ($policies as $policy) {
                $policy->policy_type_no = $policy->policy_type ?? 0;
                $policy->policy_type = $policyTypes[$policy->policy_type] ?? 'Unknown Policy Type';

                // Dynamic grouping based on the selected criterion
                switch ($groupBy) {
                    case 'policy_type':
                        $groupKey = $policy->policy_type ?? 'Unknown Policy Type';
                        break;
                    case 'client_name':
                        $groupKey = $policy->client_name ?? 'Unknown Client';
                        break;
                    case 'claim_status':
                        $groupKey = $policy->status ?? 'Unknown Claim Status';
                        break;
                    default:  // Default is grouping by insurance company
                        $groupKey = $policy->company_name ?? 'Unknown Insurance Company';
                        break;
                }

                if (!isset($groupedPolicies[$groupKey])) {
                    $groupedPolicies[$groupKey] = [
                        'policies' => [],
                        'totals' => [
                            'total_claim_amount' => 0,
                        ]
                    ];
                }

                // Add policy to group
                $groupedPolicies[$groupKey]['policies'][] = $policy;

                // Accumulate totals
                $groupedPolicies[$groupKey]['totals']['total_claim_amount'] += $policy->gross_premium;
            }

            return $groupedPolicies;
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function insurance_company()
    {
        return $this->belongsTo(InsuranceCompany::class, 'insurance_company_id');
    }

}
