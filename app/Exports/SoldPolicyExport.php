<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SoldPolicyExport implements FromArray, ShouldAutoSize
{
    protected $policies;

    public function __construct($policies)
    {
        $this->policies = $policies;
    }

    public function array(): array
    {
        $rows = [];
        
        // Add main headers
        $rows[] = [
            'Policy No',
            'Client Name',
            'Insurance Company',
            'Policy Type',
            'Agent',
            'Supervisor',
            'Effective Date',
            'Expiry Date',
            'Position',
            'Policy Limit',
            'Net Premium',
            'Gross Premium',
        ];

        // Build the data from grouped policies
        foreach ($this->policies as $companyName => $data) {
            // Add empty row for separation
            $rows[] = [];
            
            // Add company name row
            $rows[] = ['Company: ' . $companyName];
            
            // Add policies for this company
            if (isset($data['policies']) && is_iterable($data['policies'])) {
                foreach ($data['policies'] as $policy) {
                    $rows[] = [
                        is_object($policy) ? ($policy->policy_no ?? '--') : ($policy['policy_no'] ?? '--'),
                        is_object($policy) ? ($policy->client_name ?? '--') : ($policy['client_name'] ?? '--'),
                        is_object($policy) ? ($policy->company_name ?? '--') : ($policy['company_name'] ?? '--'),
                        is_object($policy) ? ($policy->policy_type ?? '--') : ($policy['policy_type'] ?? '--'),
                        is_object($policy) ? ($policy->agent_name ?? '--') : ($policy['agent_name'] ?? '--'),
                        is_object($policy) ? ($policy->supervisor_name ?? '--') : ($policy['supervisor_name'] ?? '--'),
                        is_object($policy) ? ($policy->inception_date ?? '--') : ($policy['inception_date'] ?? '--'),
                        is_object($policy) ? ($policy->expiry_date ?? '--') : ($policy['expiry_date'] ?? '--'),
                        is_object($policy) ? ($policy->occupations_name ?? '--') : ($policy['occupations_name'] ?? '--'),
                        is_object($policy) ? ($policy->policy_plan_limit ?? '--') : ($policy['policy_plan_limit'] ?? '--'),
                        is_object($policy) ? ($policy->net_premium ?? 0) : ($policy['net_premium'] ?? 0),
                        is_object($policy) ? ($policy->gross_premium ?? 0) : ($policy['gross_premium'] ?? 0),
                    ];
                }
            }

            // Add totals row for this company
            if (isset($data['totals'])) {
                $rows[] = [
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    'TOTAL',
                    $data['totals']['total_net_premium'] ?? 0,
                    $data['totals']['total_gross_premium'] ?? 0,
                ];
            }
        }

        return $rows;
    }
}
