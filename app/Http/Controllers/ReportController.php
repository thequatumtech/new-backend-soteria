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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SoldPolicyExport;
use App\Exports\SupervisorReportExport;
use App\Exports\CommissionReportExport;
use App\Exports\ClientReportExport;
use App\Exports\ReportArrayExport;
use Illuminate\Support\Str;
use PDF;


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

        if ($request->has('export')) {
            if ($request->get('export') == 'excel') {
                return Excel::download(new SoldPolicyExport($policies), 'sold_policies_report.xlsx');
            } elseif ($request->get('export') == 'pdf') {
                $pdf = PDF::loadView('admin.reports.exports.sold_policy_export', compact('policies'))->setPaper('a4', 'landscape');
                return $pdf->download('sold_policies_report.pdf');
            }
        }

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

        return view('admin.reports.sold_policy_report', compact('policies', 'insuranceCompanies', 'agents', 'policyTypes', 'clients', 'positions', 'district', 'city', 'supervisor', 'ages', 'clients'));
    }

    // Download Report as PDF
    public function downloadSoldPolicyPDF(Request $request)
    {
        try {
            $filters = [
                'insurance_company' => $request->get('insurance_company'),
                'issue_date' => $request->get('issue_date'),
                'expiry_date' => $request->get('expiry_date'),
                'agent_name' => $request->get('agent_name'),
                'client_position' => $request->get('client_position'),
                'client_district' => $request->get('client_district'),
                'policy_type' => $request->get('policy_type'),
                'client_gender' => $request->get('client_gender'),
                'client_name' => $request->get('client_name'),
                'client_city' => $request->get('client_city'),
                'supervisor' => $request->get('supervisor'),
                'client_age' => $request->get('client_age'),
            ];

            $groupBy = $request->get('grouptype', 'insurance_company');
            $policies = PurchasePolicy::getFilterAllPolicy($filters, $groupBy);

            if (empty($policies)) {
                return response()->json(['error' => 'No data to export'], 400);
            }

            $data = [
                'policies' => $policies,
                'filters' => $filters,
                'grouptype' => $groupBy,
                'issue_date' => $request->get('issue_date') ?? '--',
                'expiry_date' => $request->get('expiry_date') ?? '--',
            ];

            $pdf = PDF::loadView('admin.reports.exports.sold_policy_pdf', $data);
            return $pdf->download('Sold_Policy_Report_' . date('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'PDF generation failed: ' . $e->getMessage()], 500);
        }
    }

    // Download Report as Excel
    public function downloadSoldPolicyExcel(Request $request)
    {
        try {
            $filters = [
                'insurance_company' => $request->get('insurance_company'),
                'issue_date' => $request->get('issue_date'),
                'expiry_date' => $request->get('expiry_date'),
                'agent_name' => $request->get('agent_name'),
                'client_position' => $request->get('client_position'),
                'client_district' => $request->get('client_district'),
                'policy_type' => $request->get('policy_type'),
                'client_gender' => $request->get('client_gender'),
                'client_name' => $request->get('client_name'),
                'client_city' => $request->get('client_city'),
                'supervisor' => $request->get('supervisor'),
                'client_age' => $request->get('client_age'),
            ];

            $groupBy = $request->get('grouptype', 'insurance_company');
            $policies = PurchasePolicy::getFilterAllPolicy($filters, $groupBy);

            if (empty($policies)) {
                return response()->json(['error' => 'No data to export'], 400);
            }

            return Excel::download(new \App\Exports\SoldPolicyExport($policies), 'Sold_Policy_Report_' . date('Y-m-d') . '.xlsx');
        } catch (\Exception $e) {
            \Log::error('Excel Export Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Export failed: ' . $e->getMessage()], 500);
        }
    }

    public function downloadReport(Request $request, string $report, string $format)
    {
        $config = $this->getReportDownloadConfig($report);

        if (!$config) {
            abort(404);
        }

        try {
            $reportData = $this->buildReportExportData($request, $report);

            if (empty($reportData['rows'])) {
                return response()->json(['error' => 'No data to export'], 400);
            }

            $filename = Str::slug($config['filename'], '_') . '_' . date('Y-m-d');

            if ($format === 'pdf') {
                $pdf = PDF::loadView('admin.reports.exports.generic_report_pdf', $reportData)
                    ->setPaper('A3', 'landscape');

                return $pdf->download($filename . '.pdf');
            }

            if ($format === 'excel') {
                $excelRows = $this->buildExcelRows($reportData);
                return Excel::download(new ReportArrayExport($excelRows), $filename . '.xlsx');
            }

            abort(404);
        } catch (\Exception $e) {
            \Log::error(sprintf('Report download error [%s][%s]: %s', $report, $format, $e->getMessage()), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Export failed: ' . $e->getMessage()], 500);
        }
    }

    private function getReportDownloadConfig(string $report): ?array
    {
        switch ($report) {
            case 'sold_policy_report':
                return ['filename' => 'Sold_Policy_Report'];
            case 'policy_commission':
                return ['filename' => 'Policy_Commission_Report'];
            case 'client_report':
                return ['filename' => 'Client_Report'];
            case 'policy_renewal_report':
                return ['filename' => 'Policy_Renewal_Report'];
            case 'travel_policies_report':
                return ['filename' => 'Travel_Policies_Report'];
            case 'pets_policies_report':
                return ['filename' => 'Pets_Policies_Report'];
            case 'cancelled_by_admin_report':
                return ['filename' => 'Cancelled_By_Admin_Report'];
            case 'renewed_by_admin_report':
                return ['filename' => 'Renewed_By_Admin_Report'];
            case 'expired_without_renewal_report':
                return ['filename' => 'Expired_Without_Renewal_Report'];
            case 'sold_policies_by_location':
                return ['filename' => 'Sold_Policies_By_Location_Report'];
            case 'claims_report':
                return ['filename' => 'Claims_Report'];
            case 'complaints_report':
                return ['filename' => 'Complaints_Report'];
            case 'supervisor_report':
                return ['filename' => 'Supervisor_Report'];
            default:
                return null;
        }
    }

    private function buildReportExportData(Request $request, string $report): array
    {
        switch ($report) {
            case 'sold_policy_report':
                return $this->buildPurchasePolicyExportData($request, 'Sold Policy Report', [
                    'insurance_company',
                    'issue_date',
                    'expiry_date',
                    'agent_name',
                    'client_position',
                    'client_district',
                    'policy_type',
                    'client_gender',
                    'client_name',
                    'client_city',
                    'supervisor',
                    'client_age',
                ], 'sold_policy_report');
            case 'policy_commission':
                return $this->buildPurchasePolicyExportData($request, 'Policy Commission Report', [
                    'insurance_company',
                    'issue_date',
                    'expiry_date',
                    'agent_name',
                    'policy_type',
                    'policy_plan',
                    'total_commission',
                ], 'policy_commission');
            case 'client_report':
                return $this->buildPurchasePolicyExportData($request, 'Client Report', [
                    'insurance_company',
                    'issue_date',
                    'expiry_date',
                    'agent_name',
                    'client_position',
                    'client_district',
                    'policy_type',
                    'client_gender',
                    'client_city',
                    'client_age',
                    'client_marital',
                ], 'client_report');
            case 'policy_renewal_report':
                return $this->buildPolicyRenewalExportData($request);
            case 'travel_policies_report':
                return $this->buildFilteredExportData($request, 'Travel Policies Report', [
                    'period_of_travel',
                    'destination',
                    'travel_period',
                    'issue_date',
                    'expiry_date',
                    'travel_plan',
                    'insurance_company',
                    'agent_name',
                    'client_age',
                ], function ($filters, $groupBy) {
                    return PurchasePolicy::getFilterTravelPolicyReport($filters, $groupBy);
                }, 'travel_policies_report');
            case 'pets_policies_report':
                return $this->buildFilteredExportData($request, 'Pets Policies Report', [
                    'insurance_company',
                    'issue_date',
                    'expiry_date',
                    'pets_type',
                    'pet_breed',
                    'agent_name',
                    'owner_city',
                    'owner_district',
                    'owner_age',
                ], function ($filters, $groupBy) {
                    return PurchasePolicy::getFilterPetsPolicyReport($filters, $groupBy);
                }, 'pets_policies_report');
            case 'cancelled_by_admin_report':
                return $this->buildPurchasePolicyExportData($request, 'Cancelled by Admin Report', [
                    'insurance_company',
                    'issue_date',
                    'expiry_date',
                    'policy_type',
                    'client_name',
                ], 'cancelled_by_admin_report');
            case 'renewed_by_admin_report':
                return $this->buildPurchasePolicyExportData($request, 'Renewed by Admin Report', [
                    'insurance_company',
                    'issue_date',
                    'expiry_date',
                    'policy_type',
                    'client_name',
                ], 'renewed_by_admin_report');
            case 'expired_without_renewal_report':
                return $this->buildPurchasePolicyExportData($request, 'Expired Without Renewal Report', [
                    'insurance_company',
                    'issue_date',
                    'expiry_date',
                    'agent_name',
                    'client_position',
                    'client_district',
                    'policy_type',
                    'client_gender',
                    'client_city',
                    'supervisor',
                    'client_age',
                    'total_commission',
                ], 'expired_without_renewal_report');
            case 'sold_policies_by_location':
                return $this->buildPurchasePolicyExportData($request, 'Sold Policies By Location Report', [
                    'insurance_company',
                    'issue_date',
                    'expiry_date',
                    'agent_name',
                    'client_position',
                    'client_district',
                    'policy_type',
                    'client_gender',
                    'client_name',
                    'client_city',
                    'supervisor',
                    'client_age',
                ], 'sold_policies_by_location');
            case 'claims_report':
                return $this->buildFilteredExportData($request, 'Claims Report', [
                    'insurance_company',
                    'issue_date',
                    'expiry_date',
                    'policy_type',
                    'client_name',
                    'claim_status',
                ], function ($filters, $groupBy) {
                    return Claim::getClaimsReport($filters, $groupBy);
                }, 'claims_report');
            case 'complaints_report':
                return $this->buildFilteredExportData($request, 'Complaints Report', [
                    'insurance_company',
                    'issue_date',
                    'expiry_date',
                    'policy_type',
                    'client_name',
                    'claim_status',
                ], function ($filters, $groupBy) {
                    return Complaint::getComplaintReport($filters, $groupBy);
                }, 'complaints_report');
            case 'supervisor_report':
                return $this->buildFilteredExportData($request, 'Supervisor Report', [
                    'insurance_company',
                    'issue_date',
                    'expiry_date',
                    'policy_type',
                    'supervisor_name',
                    'agent_name',
                    'supervisor_report',
                ], function ($filters, $groupBy) {
                    return PurchasePolicy::getFilterAllPolicy($filters, $groupBy);
                }, 'supervisor_report');
            default:
                return ['title' => 'Report', 'filters' => [], 'columns' => [], 'rows' => []];
        }
    }

    private function buildPurchasePolicyExportData(Request $request, string $title, array $filterFields, string $report): array
    {
        return $this->buildFilteredExportData($request, $title, $filterFields, function ($filters, $groupBy) {
            return PurchasePolicy::getFilterAllPolicy($filters, $groupBy);
        }, $report);
    }

    private function buildPolicyRenewalExportData(Request $request): array
    {
        $filters = [
            'insurance_company' => $request->get('insurance_company'),
            'issue_date' => $request->get('issue_date'),
            'expiry_date' => $request->get('expiry_date'),
            'agent_name' => $request->get('agent_name'),
            'policy_type' => $request->get('policy_type'),
            'supervisor' => $request->get('supervisor'),
            'renew_status' => $request->get('renew_status'),
            'non_renew_status' => $request->get('non_renew_status'),
        ];

        $groupBy = $request->get('group_by', $request->grouptype);
        $groupedPolicies = PurchasePolicy::getFilterAllPolicy($filters, $groupBy);

        return $this->buildReportPayload($this->getRenewalReportTitle($groupBy), $filters, $groupedPolicies, 'policy_renewal_report');
    }

    private function getRenewalReportTitle(string $groupBy): string
    {
        return 'Policy Renewal Report';
    }

    private function buildFilteredExportData(Request $request, string $title, array $filterFields, callable $queryBuilder, string $report): array
    {
        $filters = [];
        foreach ($filterFields as $field) {
            $filters[$field] = $request->get($field);
        }

        $groupBy = $request->get('group_by', $request->grouptype);
        $policies = $queryBuilder($filters, $groupBy);

        return $this->buildReportPayload($title, $filters, $policies, $report);
    }

    private function buildReportPayload(string $title, array $filters, $data, ?string $report = null): array
    {
        $columns = $this->getReportExportColumns($report);
        $columnLabels = $this->getReportExportColumnLabels($report);
        $groups = [];
        $rows = [];

        if ($this->isGroupedPolicyData($data)) {
            $groups = $this->buildGroupsFromPolicyData($data, $report);
            foreach ($groups as $group) {
                foreach ($group['rows'] as $row) {
                    $rows[] = $row;
                }
            }
        } else {
            $rows = $this->normalizeReportRows($data);
        }

        if (empty($columns) && !empty($rows)) {
            foreach ($rows as $row) {
                $columns = array_unique(array_merge($columns, array_keys((array) $row)));
            }
        }

        return [
            'title' => $title,
            'filters' => $filters,
            'columns' => $columns,
            'column_labels' => $columnLabels,
            'groups' => $groups,
            'rows' => $rows,
            'total_key' => $this->getReportExportTotalKey($report),
            'total_keys' => $this->getReportExportTotalKeys($report),
            'report' => $report,
            'is_grouped' => !empty($groups),
        ];
    }

    private function getReportExportColumns(?string $report): array
    {
        return match ($report) {
            'sold_policy_report' => [
                'policy_no',
                'client_name',
                'company_name',
                'policy_type',
                'agent_name',
                'supervisor_name',
                'inception_date',
                'expiry_date',
                'occupations_name',
                'policy_plan_limit',
                'net_premium',
                'gross_premium',
            ],
            'policy_commission' => [
                'client_name',
                'policy_type',
                'plan_name',
                'commission_percentage',
                'net_premium',
                'commission_amount',
                'company_name',
            ],
            'client_report' => [
                'client_name',
                'age',
                'position',
                'district',
                'city',
                'policy_type',
                'plan_name',
                'gender',
                'marital_status',
                'start_date',
                'expiry_date',
                'gross_premium',
            ],
            'policy_renewal_report' => [
                'policy_type',
                'client_name',
                'agent_name',
                'supervisor_name',
                'expiry_date',
                'gross_premium',
                'company_name',
                'renew_status',
            ],
            'travel_policies_report' => [
                'client_name',
                'period_of_travel',
                'distination',
                'client_age',
                'agent_name',
                'travel_plans_name',
                'net_premium',
                'gross_premium',
                'company_name',
            ],
            'pets_policies_report' => [
                'client_name',
                'owner_age',
                'inception_date',
                'expiry_date',
                'pets_type',
                'pet_breed',
                'owner_city',
                'owner_district',
                'agent_name',
                'net_premium',
                'gross_premium',
            ],
            'cancelled_by_admin_report' => [
                'policy_no',
                'policy_type',
                'client_name',
                'agent_name',
                'supervisor_name',
                'inception_date',
                'expiry_date',
                'policy_plan_limit',
                'net_premium',
                'commission_amount',
                'net_premium_after',
                'company_name',
            ],
            'renewed_by_admin_report' => [
                'policy_no',
                'policy_type',
                'client_name',
                'agent_name',
                'supervisor_name',
                'inception_date',
                'expiry_date',
                'policy_plan_limit',
                'net_premium',
                'commission_amount',
                'net_premium_after',
                'company_name',
            ],
            'expired_without_renewal_report' => [
                'policy_no',
                'policy_type',
                'client_name',
                'agent_name',
                'supervisor_name',
                'inception_date',
                'expiry_date',
                'policy_plan_limit',
                'net_premium',
                'commission_amount',
                'net_premium_after',
            ],
            'sold_policies_by_location' => [
                'policy_no',
                'client_name',
                'company_name',
                'policy_type',
                'inception_date',
                'expiry_date',
                'agent_name',
                'city',
                'district',
                'position',
                'gross_premium',
            ],
            'claims_report' => [
                'policy_no',
                'company_name',
                'client_name',
                'policy_type',
                'status',
                'claim_no',
                'gross_premium',
            ],
            'complaints_report' => [
                'policy_no',
                'company_name',
                'client_name',
                'policy_type',
                'complaint_status',
                'complaint_message',
                'gross_premium',
            ],
            'supervisor_report' => [
                'client_name',
                'policy_type',
                'agent_name',
                'supervisor_name',
                'agent_commission_amount',
                'supervisor_commission_amount',
                'net_premium',
                'supervisor_commission',
                'company_name',
            ],
            default => [],
        };
    }

    private function getReportExportColumnLabels(?string $report): array
    {
        return match ($report) {
            'sold_policy_report' => [
                'policy_no' => 'Policy No',
                'client_name' => 'Client',
                'company_name' => 'Insurance Company',
                'policy_type' => 'Policy Type',
                'agent_name' => 'Agent',
                'supervisor_name' => 'Supervisor',
                'inception_date' => 'Inception Date',
                'expiry_date' => 'Expiry Date',
                'occupations_name' => 'Client Position',
                'policy_plan_limit' => 'Policy Limit',
                'net_premium' => 'Net Premium',
                'gross_premium' => 'Gross Premium',
            ],
            'policy_commission' => [
                'client_name' => 'Client',
                'policy_type' => 'Policy Type',
                'plan_name' => 'Policy Plan',
                'commission_percentage' => 'Agent Commission %',
                'net_premium' => 'Net Premium',
                'commission_amount' => 'Total Commission',
                'company_name' => 'Insurance Company',
            ],
            'client_report' => [
                'client_name' => 'Client',
                'age' => 'Age',
                'position' => 'Client Position',
                'district' => 'District',
                'city' => 'City',
                'policy_type' => 'Policy Type',
                'plan_name' => 'Policy Plan',
                'gender' => 'Gender',
                'marital_status' => 'Marital Status',
                'start_date' => 'Inception Date',
                'expiry_date' => 'Expiry Date',
                'gross_premium' => 'Gross Premium',
            ],
            'policy_renewal_report' => [
                'policy_type' => 'Policy Type',
                'client_name' => 'Client',
                'agent_name' => 'Agent',
                'supervisor_name' => 'Supervisor',
                'expiry_date' => 'Expiry Date',
                'gross_premium' => 'Gross Premium',
                'company_name' => 'Insurance Company',
                'renew_status' => 'Renewed or Non-Renewed ?',
            ],
            'travel_policies_report' => [
                'client_name' => 'Client',
                'period_of_travel' => 'Period of Travel',
                'distination' => 'Destination',
                'client_age' => 'Age',
                'agent_name' => 'Agent',
                'travel_plans_name' => 'Travel Plan',
                'net_premium' => 'Net Premium',
                'gross_premium' => 'Gross Premium',
                'company_name' => 'Insurance Company',
            ],
            'pets_policies_report' => [
                'client_name' => 'Client',
                'owner_age' => 'Owner Age',
                'inception_date' => 'Inception Date',
                'expiry_date' => 'Expiry Date',
                'pets_type' => 'Pet Type',
                'pet_breed' => 'Breed',
                'owner_city' => 'City',
                'owner_district' => 'District',
                'agent_name' => 'Agent',
                'net_premium' => 'Net Premium',
                'gross_premium' => 'Gross Premium',
            ],
            'cancelled_by_admin_report',
            'renewed_by_admin_report' => [
                'policy_no' => 'Policy No',
                'policy_type' => 'Policy Type',
                'client_name' => 'Client',
                'agent_name' => 'Agent',
                'supervisor_name' => 'Supervisor',
                'inception_date' => 'Inception Date',
                'expiry_date' => 'Expiry Date',
                'policy_plan_limit' => 'Policy Limit',
                'net_premium' => 'Net Premium',
                'commission_amount' => 'Commission Amount',
                'net_premium_after' => 'Net Premium After Commission',
                'company_name' => 'Insurance Company',
            ],
            'expired_without_renewal_report' => [
                'policy_no' => 'Policy No',
                'policy_type' => 'Policy Type',
                'client_name' => 'Client',
                'agent_name' => 'Agent',
                'supervisor_name' => 'Supervisor',
                'inception_date' => 'Inception Date',
                'expiry_date' => 'Expiry Date',
                'policy_plan_limit' => 'Policy Limit',
                'net_premium' => 'Net Premium',
                'commission_amount' => 'Commission Amount',
                'net_premium_after' => 'Net Premium After Commission',
            ],
            'sold_policies_by_location' => [
                'policy_no' => 'Policy No',
                'client_name' => 'Client',
                'company_name' => 'Insurance Company',
                'policy_type' => 'Policy Type',
                'inception_date' => 'Inception Date',
                'expiry_date' => 'Expiry Date',
                'agent_name' => 'Agent',
                'city' => 'City',
                'district' => 'District',
                'position' => 'Client Position',
                'gross_premium' => 'Gross Premium',
            ],
            'claims_report' => [
                'policy_no' => 'Policy No',
                'company_name' => 'Insurance Company',
                'client_name' => 'Client',
                'policy_type' => 'Policy Type',
                'status' => 'Claim Status',
                'claim_no' => 'Claim No',
                'gross_premium' => 'Claim Amount',
            ],
            'complaints_report' => [
                'policy_no' => 'Policy No',
                'company_name' => 'Insurance Company',
                'client_name' => 'Client',
                'policy_type' => 'Policy Type',
                'complaint_status' => 'Complaint Status',
                'complaint_message' => 'Complaint Message',
                'gross_premium' => 'Related Premium',
            ],
            'supervisor_report' => [
                'client_name' => 'Client',
                'policy_type' => 'Policy Type',
                'agent_name' => 'Agent',
                'supervisor_name' => 'Supervisor',
                'agent_commission_amount' => 'Agent Commission %',
                'supervisor_commission_amount' => 'Supervisor Override %',
                'net_premium' => 'Net Premium',
                'supervisor_commission' => 'Total Commission',
                'company_name' => 'Insurance Company',
            ],
            default => [],
        };
    }

    private function getReportExportTotalKey(?string $report): ?string
    {
        return match ($report) {
            'policy_commission' => 'commission_amount',
            'sold_policy_report' => 'gross_premium',
            'policy_renewal_report' => 'gross_premium',
            'travel_policies_report' => 'gross_premium',
            'pets_policies_report' => 'gross_premium',
            'claims_report' => 'gross_premium',
            'complaints_report' => 'gross_premium',
            'supervisor_report' => 'supervisor_commission',
            'client_report' => 'gross_premium',
            'sold_policies_by_location' => 'gross_premium',
            default => null,
        };
    }

    private function getReportExportTotalKeys(?string $report): array
    {
        return match ($report) {
            'pets_policies_report' => ['net_premium', 'gross_premium'],
            'travel_policies_report' => ['net_premium', 'gross_premium'],
            default => [],
        };
    }

    private function isGroupedPolicyData($data): bool
    {
        if ($data instanceof \Illuminate\Support\Collection) {
            $data = $data->all();
        }

        if (!is_array($data)) {
            return false;
        }

        foreach ($data as $groupData) {
            return is_array($groupData) && array_key_exists('policies', $groupData);
        }

        return false;
    }

    private function buildGroupsFromPolicyData($data, ?string $report = null): array
    {
        if ($data instanceof \Illuminate\Support\Collection) {
            $data = $data->all();
        }

        if (!is_array($data)) {
            return [];
        }

        $groups = [];

        foreach ($data as $groupName => $groupData) {
            if (!is_array($groupData) || !array_key_exists('policies', $groupData)) {
                continue;
            }

            $rows = [];
            foreach ($groupData['policies'] as $policy) {
                $rows[] = $this->buildReportRowForExport($policy, $report, $groupName);
            }

            $groups[] = [
                'group_name' => $groupName,
                'rows' => $rows,
                'totals' => $groupData['totals'] ?? [],
            ];
        }

        return $groups;
    }

    private function buildReportRowForExport($policy, ?string $report, ?string $groupName): array
    {
        $row = $this->normalizeRowItem($policy, $groupName);

        if ($report === 'supervisor_report') {
            $agentCommission = !empty($row['agent_commission_amount']) ? (float) $row['agent_commission_amount'] : 0;
            $netPremium = !empty($row['net_premium']) ? (float) $row['net_premium'] : 0;
            $row['supervisor_commission'] = $netPremium * ($agentCommission / 100);
        }

        if (in_array($report, ['expired_without_renewal_report', 'cancelled_by_admin_report', 'renewed_by_admin_report'], true)) {
            if (!isset($row['net_premium_after']) && isset($row['net_premium'], $row['commission_amount'])) {
                $row['net_premium_after'] = (float) $row['net_premium'] - (float) $row['commission_amount'];
            }
        }

        if ($report === 'pets_policies_report' && isset($row['pets_type'])) {
            $row['pets_type'] = match ((string) $row['pets_type']) {
                '1' => 'Dog',
                '2' => 'Cat',
                default => $row['pets_type'],
            };
        }

        return $row;
    }

    private function normalizeReportRows($data): array
    {
        if ($data instanceof \Illuminate\Support\Collection) {
            $data = $data->all();
        }

        if (is_object($data)) {
            $data = json_decode(json_encode($data), true);
        }

        if (!is_array($data)) {
            return [];
        }

        $rows = [];

        if ($this->arrayHasStringKeys($data)) {
            foreach ($data as $group => $items) {
                if (is_array($items) || $items instanceof \Illuminate\Support\Collection) {
                    if (is_array($items) && array_key_exists('policies', $items)) {
                        foreach ($items['policies'] as $item) {
                            $rows[] = $this->normalizeRowItem($item, $group);
                        }
                        continue;
                    }

                    foreach ($items as $item) {
                        $rows[] = $this->normalizeRowItem($item, $group);
                    }
                } else {
                    $rows[] = $this->normalizeRowItem($items, $group);
                }
            }
        } else {
            foreach ($data as $item) {
                $rows[] = $this->normalizeRowItem($item);
            }
        }

        return $rows;
    }

    private function normalizeRowItem($item, $group = null): array
    {
        if ($item instanceof \Illuminate\Support\Collection) {
            $item = $item->all();
        }

        if (is_object($item)) {
            $item = json_decode(json_encode($item), true);
        }

        if (!is_array($item)) {
            $item = ['value' => $item];
        }

        if ($group !== null && !array_key_exists('group', $item)) {
            $item['group'] = $group;
        }

        return array_map([$this, 'flattenValue'], $item);
    }

    private function flattenValue($value)
    {
        if (is_null($value)) {
            return '';
        }

        if (is_scalar($value)) {
            return $value;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_array($value) || $value instanceof \Illuminate\Support\Collection) {
            $value = $value instanceof \Illuminate\Support\Collection ? $value->all() : $value;
            $flattened = array_map([$this, 'flattenValue'], $value);
            return implode(', ', array_filter($flattened, fn($item) => $item !== ''));
        }

        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                return (string) $value;
            }

            return json_encode($value);
        }

        return (string) $value;
    }

    private function arrayHasStringKeys(array $data): bool
    {
        foreach ($data as $key => $value) {
            if (!is_int($key)) {
                return true;
            }
        }

        return false;
    }

    private function buildExcelRows(array $reportData): array
    {
        $columns = $reportData['columns'] ?? [];
        $columnLabels = $reportData['column_labels'] ?? [];
        $rows = $reportData['rows'] ?? [];
        $groups = $reportData['groups'] ?? [];

        $excelRows = [];

        if (!empty($reportData['title'])) {
            $excelRows[] = [$reportData['title']];
            $excelRows[] = [];
        }

        if (!empty($reportData['filters'])) {
            foreach ($reportData['filters'] as $label => $value) {
                if ($value !== null && $value !== '') {
                    $excelRows[] = [ucwords(str_replace('_', ' ', $label)) . ':', $value];
                }
            }
            $excelRows[] = [];
        }

        if (!empty($groups)) {
            foreach ($groups as $group) {
                $excelRows[] = [$group['group_name']];
                $excelRows[] = array_map(function ($column) use ($columnLabels) {
                    return $columnLabels[$column] ?? ucwords(str_replace('_', ' ', $column));
                }, $columns);

                foreach ($group['rows'] as $row) {
                    $excelRows[] = array_map(function ($column) use ($row) {
                        return $row[$column] ?? '';
                    }, $columns);
                }

                $totalLabelRow = array_fill(0, count($columns), '');
                $totalLabelRow[0] = 'TOTAL';
                $totalKey = $reportData['total_key'] ?? null;
                $totalKeys = $reportData['total_keys'] ?? [];

                if (!empty($totalKeys)) {
                    foreach ($totalKeys as $key) {
                        $totalIndex = array_search($key, $columns, true);
                        if ($totalIndex !== false && isset($group['totals']['total_' . $key])) {
                            $totalLabelRow[$totalIndex] = $group['totals']['total_' . $key];
                        }
                    }
                } elseif ($totalKey && isset($group['totals']['total_' . $totalKey])) {
                    $totalIndex = array_search($totalKey, $columns, true);
                    if ($totalIndex !== false) {
                        $totalLabelRow[$totalIndex] = $group['totals']['total_' . $totalKey];
                    }
                } elseif (isset($group['totals']['total_gross_premium'])) {
                    $totalGrossIndex = array_search('gross_premium', $columns, true);
                    if ($totalGrossIndex !== false) {
                        $totalLabelRow[$totalGrossIndex] = $group['totals']['total_gross_premium'];
                    }
                }
                $excelRows[] = $totalLabelRow;
                $excelRows[] = [];
            }

            return $excelRows;
        }

        if (!empty($columns)) {
            $excelRows[] = array_map(function ($column) use ($columnLabels) {
                return $columnLabels[$column] ?? ucwords(str_replace('_', ' ', $column));
            }, $columns);
        }

        foreach ($rows as $row) {
            $excelRows[] = array_map(function ($column) use ($row) {
                return $row[$column] ?? '';
            }, $columns);
        }

        return $excelRows;
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
        if ($request->has('export')) {
            if ($request->get('export') == 'excel') {
                return Excel::download(new CommissionReportExport($policies), 'commission_report.xlsx');
            } elseif ($request->get('export') == 'pdf') {
                $pdf = PDF::loadView('admin.reports.exports.commission_report_export', compact('policies'))->setPaper('a4', 'landscape');
                return $pdf->download('commission_report.pdf');
            }
        }

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
        if ($request->has('export')) {
            if ($request->get('export') == 'excel') {
                return Excel::download(new ClientReportExport($policies), 'client_report.xlsx');
            } elseif ($request->get('export') == 'pdf') {
                $pdf = PDF::loadView('admin.reports.exports.client_report_export', compact('policies'))->setPaper('a4', 'landscape');
                return $pdf->download('client_report.pdf');
            }
        }

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
        return view('admin.reports.client_report', compact('policies', 'insuranceCompanies', 'agents', 'policyTypes', 'clients', 'positions', 'district', 'city', 'supervisor', 'ages', 'policy_plan'));
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
        if (!$request->grouptype) {
            $request->grouptype = 'policy_type';
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

        return view('admin.reports.policy_renewal_report', compact('policies', 'insuranceCompanies', 'agents', 'policyTypes', 'clients', 'supervisor'));
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

        return view('admin.reports.travel_policies_report', compact('policies', 'insuranceCompanies', 'agents', 'periods', 'clients', 'countries', 'ages'));
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
        $breed = ClientPetsInsurance::select('breed')->groupBy('breed')->get();
        $positions = DB::table('clients')->select('position')->whereNotNull('position')->groupBy('position')->get();
        $districts = District::all();
        $cities = Cities::all();
        $supervisor = SupervisorModel::all();
        $ages = Ages::all();

        return view('admin.reports.pets_policies_report', compact('policies', 'insuranceCompanies', 'agents', 'clients', 'positions', 'districts', 'cities', 'supervisor', 'ages', 'breed'));
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
        // dd($policies);
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
        $blackList = Client::where('is_blacklisted', 1)->get();
        // dd($blackList);
        return view('admin.reports.cancelled_by_admin_report', compact('policies', 'insuranceCompanies', 'policyTypes', 'blackList'));
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
        $blackList = Client::where('is_blacklisted', 1)->get();
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

        return view('admin.reports.expired_without_renewal_report', compact('policies', 'insuranceCompanies', 'agents', 'policyTypes', 'clients', 'positions', 'district', 'city', 'supervisor', 'ages'));
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
        return view('admin.reports.sold_policies_by_location', compact('policies', 'insuranceCompanies', 'agents', 'policyTypes', 'clients', 'positions', 'district', 'city', 'supervisor', 'ages'));
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
        return view('admin.reports.claims_report', compact('policies', 'insuranceCompanies', 'agents', 'policyTypes', 'clients', 'positions', 'district', 'city', 'supervisor', 'ages'));
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
        if ($request->has('export')) {
            if ($request->get('export') == 'excel') {
                return Excel::download(new SupervisorReportExport($policies), 'supervisor_report.xlsx');
            } elseif ($request->get('export') == 'pdf') {
                $pdf = PDF::loadView('admin.reports.exports.supervisor_report_export', compact('policies'))->setPaper('a4', 'landscape');
                return $pdf->download('supervisor_report.pdf');
            }
        }

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
        return view('admin.reports.supervisor_report', compact('policies', 'insuranceCompanies', 'complaint_statuses', 'policyTypes', 'agents', 'supervisors'));
    }
}
