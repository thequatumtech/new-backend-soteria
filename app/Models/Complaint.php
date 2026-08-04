<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $fillable = [
        'client_id',
        'complaint_number',
        'complaint_date',
        'complaint_status_id',
        'insurance_company_id',
        'complaint_message',
        'line_of_business_id',
        'purchase_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id','id');
    }

    public function line_of_business()
    {
        return $this->belongsTo(LineOfBusiness::class, 'line_of_business_id','id');
    }

    public function insurance_company()
    {
        return $this->belongsTo(InsuranceCompany::class, 'insurance_company_id','id');
    }

    public function status()
    {
        return $this->hasOne(ComplaintStatus::class, 'id', 'complaint_status_id');
    }
    public static function getComplaintReport($filters = [], $groupBy = 'insurance_company')
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
        $policies = Complaint::select('complaints.*','purchase_policy.gross_premium','purchase_policy.policy_no','clients.first_name as client_name','insurance_companies.company_name as company_name','complaint_statuses.name as complaint_status')
            ->leftJoin('insurance_companies','complaints.insurance_company_id','insurance_companies.id')
            ->leftJoin('purchase_policy', 'complaints.purchase_id', '=', 'purchase_policy.id')
            ->leftJoin('complaint_statuses', 'complaints.complaint_status_id', '=', 'complaint_statuses.id')
            ->leftJoin('clients', 'complaints.client_id', '=', 'clients.id');
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
            if (!empty($filters['complaint_status'])) {
                $policies->where('complaints.complaint_status_id', $filters['complaint_status']);
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
                    case 'complaint_status':
                        $groupKey = $policy->complaint_status ?? 'Unknown complaint Status';
                        break;
                    default:  // Default is grouping by insurance company
                        $groupKey = $policy->company_name ?? 'Unknown Insurance Company';
                        break;
                }

                if (!isset($groupedPolicies[$groupKey])) {
                    $groupedPolicies[$groupKey] = [
                        'policies' => [],
                        'totals' => [
                            'total_complaint_amount' => 0,
                        ]
                    ];
                }

                // Add policy to group
                $groupedPolicies[$groupKey]['policies'][] = $policy;

                // Accumulate totals
                $groupedPolicies[$groupKey]['totals']['total_complaint_amount'] += $policy->gross_premium;
            }

            return $groupedPolicies;
    }
    
}
