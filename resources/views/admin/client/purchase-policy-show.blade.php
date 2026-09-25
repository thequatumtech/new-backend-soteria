@extends('layouts.mainlayout')

@section('style')
    <style>
        .policy-page {
            font-family: 'Nunito', sans-serif;
        }

        .policy-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .08);
            margin-bottom: 20px;
        }

        .policy-card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            font-size: 18px;
            font-weight: 700;
            color: #333;
            background: #fafafa;
            border-radius: 10px 10px 0 0;
        }

        .policy-card-body {
            padding: 20px;
        }

        .detail-item {
            margin-bottom: 18px;
        }

        .detail-label {
            font-size: 13px;
            color: #777;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .detail-value {
            font-size: 15px;
            font-weight: 700;
            color: #222;
            word-break: break-word;
        }

        .policy-number {
            font-size: 22px;
            font-weight: 800;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .document-btn {
            min-width: 140px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .back-btn {
            margin-right: 8px;
        }

        .premium-box {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            background: #fafafa;
        }

        .premium-total {
            font-size: 20px;
            font-weight: 800;
        }

        .detail-table th {
            width: 35%;
            background: #f8f8f8;
            font-weight: 700;
        }

        .detail-table td,
        .detail-table th {
            padding: 12px 15px;
            vertical-align: middle;
        }
    </style>
@endsection


@section('content')

    <main class="flex-grow-1 pt-5 policy-page">

        <div class="container-fluid">

            {{-- Header --}}
            <div class="header d-flex justify-content-between align-items-center p-3 py-2">

                <div>
                    <div class="text-white group-title fw-bold">
                        {{__('messages.clients.policy_details')}}
                    </div>
                </div>

                <div>
                    <a href="{{ url()->previous() }}" class="btn btn-light back-btn">
                        <i class="fa fa-arrow-left me-1"></i>{{__('messages.clients.back')}}
                    </a>

                    @if(!empty($policy->draft_pdf_url))
                        <a href="{{ $policy->draft_pdf_url }}" target="_blank" class="btn btn-secondary">
                            View Draft PDF
                        </a>
                    @endif

                    @if(!empty($policy->final_pdf_url))
                        <a href="{{ $policy->final_pdf_url }}" target="_blank" class="btn btn-success">
                            View Final PDF
                        </a>
                    @endif
                </div>

            </div>


            <div class="body bg-white py-4 px-4">

                {{-- ========================================= --}}
                {{-- POLICY SUMMARY --}}
                {{-- ========================================= --}}

                <div class="policy-card">

                    <div class="policy-card-header">
                        {{__('messages.clients.policy_summary')}}
                    </div>

                    <div class="policy-card-body">

                        <div class="row">

                            <div class="col-md-3 detail-item">
                                <div class="detail-label">
                                    Policy Number
                                </div>

                                <div class="detail-value policy-number">
                                    {{ $policy->policy_no ?? '-' }}
                                </div>
                            </div>


                            <div class="col-md-3 detail-item">

                                <div class="detail-label">
                                    Policy Type
                                </div>

                                <div class="detail-value">
                                    {{ $policy->policy_type_name ?? '-' }}
                                </div>

                            </div>


                            <div class="col-md-3 detail-item">

                                <div class="detail-label">
                                    Insurance Company
                                </div>

                                <div class="detail-value">
                                    {{ $policy->insurance_company->company_name ?? '-' }}
                                </div>

                            </div>


                            <div class="col-md-3 detail-item">

                                <div class="detail-label">
                                    Plan
                                </div>

                                <div class="detail-value">
                                    {{ $policy->plan_name ?? '-' }}
                                </div>

                            </div>


                            <div class="col-md-3 detail-item">

                                <div class="detail-label">
                                    Inception Date
                                </div>

                                <div class="detail-value">

                                    {{ $policy->inception_date
    ? \Carbon\Carbon::parse($policy->inception_date)->format('d-m-Y')
    : '-' }}

                                </div>

                            </div>


                            <div class="col-md-3 detail-item">

                                <div class="detail-label">
                                    Expiry Date
                                </div>

                                <div class="detail-value">

                                    {{ $policy->expiry_date
    ? \Carbon\Carbon::parse($policy->expiry_date)->format('d-m-Y')
    : '-' }}

                                </div>

                            </div>


                            <div class="col-md-3 detail-item">

                                <div class="detail-label">
                                    Payment Status
                                </div>

                                <div class="detail-value">

                                    @if($policy->payment_status == 1)

                                        <span class="badge bg-success badge-status">
                                            Paid
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark badge-status">
                                            Pending
                                        </span>

                                    @endif

                                </div>

                            </div>


                            <div class="col-md-3 detail-item">

                                <div class="detail-label">
                                    Policy ID
                                </div>

                                <div class="detail-value">
                                    {{ $policy->id }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ========================================= --}}
                {{-- CLIENT INFORMATION --}}
                {{-- ========================================= --}}

                <div class="policy-card">

                    <div class="policy-card-header">
                        Client Information
                    </div>

                    <div class="policy-card-body">

                        <div class="row">

                            <div class="col-md-4 detail-item">

                                <div class="detail-label">
                                    Client Name
                                </div>

                                <div class="detail-value">
                                    {{ $policy->client->first_name ?? '' }}
                                    {{ $policy->client->father_name ?? '' }}
                                    {{ $policy->client->grandfather_name ?? '' }}
                                    {{ $policy->client->surname ?? '' }}
                                </div>

                            </div>


                            <div class="col-md-4 detail-item">

                                <div class="detail-label">
                                    Client ID
                                </div>

                                <div class="detail-value">
                                    {{ $policy->client->id ?? '-' }}
                                </div>

                            </div>


                            <div class="col-md-4 detail-item">

                                <div class="detail-label">
                                    Date of Birth
                                </div>

                                <div class="detail-value">

                                    {{ !empty($policy->client->birth_date)
    ? \Carbon\Carbon::parse($policy->client->birth_date)->format('d-m-Y')
    : '-' }}

                                </div>

                            </div>


                            <div class="col-md-4 detail-item">

                                <div class="detail-label">
                                    Mobile
                                </div>

                                <div class="detail-value">
                                    {{ $policy->client->mobile_no ?? '-' }}
                                </div>

                            </div>


                            <div class="col-md-4 detail-item">

                                <div class="detail-label">
                                    Email
                                </div>

                                <div class="detail-value">
                                    {{ $policy->client->email_id ?? '-' }}
                                </div>

                            </div>


                            <div class="col-md-4 detail-item">

                                <div class="detail-label">
                                    Gender
                                </div>

                                <div class="detail-value">

                                    @if(isset($policy->client->gender))

                                        @if($policy->client->gender == 1)
                                            Male
                                        @elseif($policy->client->gender == 2)
                                            Female
                                        @else
                                            {{ $policy->client->gender }}
                                        @endif

                                    @else
                                        -
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ========================================= --}}
                {{-- AGENT --}}
                {{-- ========================================= --}}

                @if($policy->agent)

                    <div class="policy-card">

                        <div class="policy-card-header">
                            Agent Information
                        </div>

                        <div class="policy-card-body">

                            <div class="row">

                                <div class="col-md-4 detail-item">

                                    <div class="detail-label">
                                        Agent Name
                                    </div>

                                    <div class="detail-value">

                                        {{ $policy->agent->first_name ?? '' }}
                                        {{ $policy->agent->last_name ?? '' }}

                                    </div>

                                </div>


                                <div class="col-md-4 detail-item">

                                    <div class="detail-label">
                                        Agent ID
                                    </div>

                                    <div class="detail-value">
                                        {{ $policy->agent->id ?? '-' }}
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- ========================================= --}}
                {{-- PLAN / COVERAGE --}}
                {{-- ========================================= --}}

                <div class="policy-card">

                    <div class="policy-card-header">
                        Plan & Coverage
                    </div>

                    <div class="policy-card-body">

                        <div class="row">

                            <div class="col-md-4 detail-item">

                                <div class="detail-label">
                                    Plan ID
                                </div>

                                <div class="detail-value">
                                    {{ $policy->plan_id ?? '-' }}
                                </div>

                            </div>


                            <div class="col-md-4 detail-item">

                                <div class="detail-label">
                                    Plan Name
                                </div>

                                <div class="detail-value">
                                    {{ $policy->plan_name ?? '-' }}
                                </div>

                            </div>


                            <div class="col-md-4 detail-item">

                                <div class="detail-label">
                                    Policy Plan Limit
                                </div>

                                <div class="detail-value">

                                    @if($policy->policy_plan_limit !== null)
                                        {{ number_format((float) $policy->policy_plan_limit, 2) }}
                                    @else
                                        -
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ========================================= --}}
                {{-- PREMIUM INFORMATION --}}
                {{-- ========================================= --}}

                <div class="policy-card">

                    <div class="policy-card-header">
                        Premium & Financial Details
                    </div>

                    <div class="policy-card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <div class="premium-box">

                                    <div class="detail-label">
                                        Net Premium
                                    </div>

                                    <div class="detail-value">
                                        {{ number_format((float) ($policy->net_premium ?? 0), 2) }}
                                    </div>

                                </div>
                            </div>


                            <div class="col-md-4 mb-3">
                                <div class="premium-box">

                                    <div class="detail-label">
                                        Fees
                                    </div>

                                    <div class="detail-value">
                                        {{ number_format((float) ($policy->fees ?? 0), 2) }}
                                    </div>

                                </div>
                            </div>


                            <div class="col-md-4 mb-3">
                                <div class="premium-box">

                                    <div class="detail-label">
                                        Stamps
                                    </div>

                                    <div class="detail-value">
                                        {{ number_format((float) ($policy->stamps ?? 0), 2) }}
                                    </div>

                                </div>
                            </div>


                            <div class="col-md-4 mb-3">
                                <div class="premium-box">

                                    <div class="detail-label">
                                        Sales Tax
                                    </div>

                                    <div class="detail-value">
                                        {{ number_format((float) ($policy->sales_tax ?? 0), 2) }}
                                    </div>

                                </div>
                            </div>


                            <div class="col-md-4 mb-3">
                                <div class="premium-box">

                                    <div class="detail-label">
                                        CBJ
                                    </div>

                                    <div class="detail-value">
                                        {{ number_format((float) ($policy->cbj ?? 0), 2) }}
                                    </div>

                                </div>
                            </div>


                            <div class="col-md-4 mb-3">
                                <div class="premium-box">

                                    <div class="detail-label">
                                        Sales Tax CBJ
                                    </div>

                                    <div class="detail-value">
                                        {{ number_format((float) ($policy->sales_tax_cbj ?? 0), 2) }}
                                    </div>

                                </div>
                            </div>


                            <div class="col-md-4 mb-3">
                                <div class="premium-box">

                                    <div class="detail-label">
                                        Gross Premium
                                    </div>

                                    <div class="premium-total">
                                        {{ number_format((float) ($policy->gross_premium ?? 0), 2) }}
                                    </div>

                                </div>
                            </div>


                            <div class="col-md-4 mb-3">
                                <div class="premium-box">

                                    <div class="detail-label">
                                        Commission %
                                    </div>

                                    <div class="detail-value">
                                        {{ $policy->commission_percentage ?? 0 }}%
                                    </div>

                                </div>
                            </div>


                            <div class="col-md-4 mb-3">
                                <div class="premium-box">

                                    <div class="detail-label">
                                        Commission Amount
                                    </div>

                                    <div class="detail-value">
                                        {{ number_format((float) ($policy->commission_amount ?? 0), 2) }}
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

                </div>


            {{-- ========================================= --}}
            {{-- POLICY SPECIFIC DETAILS --}}
            {{-- ========================================= --}}

            @include($policyView, [
                'policy' => $policy,
                'policy_details' => $policy_details
            ])



            </div>

        </div>

    </main>

@endsection
