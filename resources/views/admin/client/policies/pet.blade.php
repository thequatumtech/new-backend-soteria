<div class="policy-card">

    <div class="policy-card-header">
        Pet Insurance Details
    </div>

    <div class="policy-card-body">

        {{-- ========================= --}}
        {{-- OWNER INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title">Owner Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">First Name</div>
                <div class="detail-value">
                    {{ $policy_details->first_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Last Name</div>
                <div class="detail-value">
                    {{ $policy_details->last_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Third Name</div>
                <div class="detail-value">
                    {{ $policy_details->third_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Family Name</div>
                <div class="detail-value">
                    {{ $policy_details->family_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Nationality No.</div>
                <div class="detail-value">
                    {{ $policy_details->nationality_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">ID / Residence No.</div>
                <div class="detail-value">
                    {{ $policy_details->id_residence_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Birth Date</div>
                <div class="detail-value">
                    @if(!empty($policy_details->birth_date))
                        {{ \Carbon\Carbon::parse($policy_details->birth_date)->format('d-m-Y') }}
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Gender</div>
                <div class="detail-value">

                    @if(isset($policy_details->gender))

                        @if($policy_details->gender == 1)
                            Male
                        @elseif($policy_details->gender == 2)
                            Female
                        @else
                            {{ $policy_details->gender }}
                        @endif

                    @else
                        -
                    @endif

                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- PET INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Pet Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Pet Type</div>
                <div class="detail-value">
                    {{ $policy_details->pets_type ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Pet Name</div>
                <div class="detail-value">
                    {{ $policy_details->pets_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Type of Pet</div>
                <div class="detail-value">
                    {{ $policy_details->type_of_pets ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Breed</div>
                <div class="detail-value">
                    {{ $policy_details->breed ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Pet Date of Birth</div>
                <div class="detail-value">
                    @if(!empty($policy_details->pets_dob))
                        {{ \Carbon\Carbon::parse($policy_details->pets_dob)->format('d-m-Y') }}
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Pet Gender</div>
                <div class="detail-value">

                    @if(isset($policy_details->gender))

                        @if($policy_details->gender == 1)
                            Male
                        @elseif($policy_details->gender == 2)
                            Female
                        @else
                            {{ $policy_details->gender }}
                        @endif

                    @else
                        -
                    @endif

                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Existing Condition Status</div>
                <div class="detail-value">
                    {{ $policy_details->pets_existing_condition_status ?? '-' }}
                </div>
            </div>

            <div class="col-md-8 detail-item">
                <div class="detail-label">Existing Condition</div>
                <div class="detail-value">
                    {{ $policy_details->pets_existing_condition ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- INSURANCE INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Insurance Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Plan ID</div>
                <div class="detail-value">
                    {{ $policy_details->plan_id ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Plan Name</div>
                <div class="detail-value">
                    {{ $policy_details->plan_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Insurance Limit</div>
                <div class="detail-value">
                    {{ $policy_details->insurance_limit ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Insurance Company ID</div>
                <div class="detail-value">
                    {{ getInsuranceCompanyName($policy_details->insurance_company_id) }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- POLICY INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Policy Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Effective Date</div>
                <div class="detail-value">
                    @if(!empty($policy_details->effective_date))
                        {{ \Carbon\Carbon::parse($policy_details->effective_date)->format('d-m-Y') }}
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Inception Date</div>
                <div class="detail-value">
                    @if(!empty($policy_details->inception_date))
                        {{ \Carbon\Carbon::parse($policy_details->inception_date)->format('d-m-Y') }}
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Expiry Date</div>
                <div class="detail-value">
                    @if(!empty($policy_details->expiry_date))
                        {{ \Carbon\Carbon::parse($policy_details->expiry_date)->format('d-m-Y') }}
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Policy Number</div>
                <div class="detail-value">
                    {{ $policy_details->police_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Payment Status</div>
                <div class="detail-value">

                    @if(isset($policy_details->payment_status))

                        @if($policy_details->payment_status == 1)

                            <span class="badge bg-success badge-status">
                                Paid
                            </span>

                        @elseif($policy_details->payment_status == 0)

                            <span class="badge bg-warning text-dark badge-status">
                                Pending
                            </span>

                        @else

                            {{ $policy_details->payment_status }}

                        @endif

                    @else
                        -
                    @endif

                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- PREMIUM INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Premium Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Net Premium</div>
                <div class="detail-value">
                    {{ $policy_details->net_premium ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Fees</div>
                <div class="detail-value">
                    {{ $policy_details->fees ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Stamps</div>
                <div class="detail-value">
                    {{ $policy_details->stamps ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Sales Tax</div>
                <div class="detail-value">
                    {{ $policy_details->sales_tax ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">CBJ</div>
                <div class="detail-value">
                    {{ $policy_details->cbj ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Sales Tax CBJ</div>
                <div class="detail-value">
                    {{ $policy_details->sales_tax_cbj ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Gross Premium</div>
                <div class="detail-value">
                    {{ $policy_details->gross_premium ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Commission Percentage</div>
                <div class="detail-value">
                    {{ $policy_details->commission_percentage ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Commission Amount</div>
                <div class="detail-value">
                    {{ $policy_details->commission_amount ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- PET DOCUMENTS --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Pet Documents</h6>

        <div class="d-flex flex-wrap gap-2">

            @php
$petDocuments = [
    'vaccine_document' => $policy_details->vaccine_document ?? null,
    'pets_picture' => $policy_details->pets_picture ?? null,
    'pets_passport' => $policy_details->pets_passport ?? null,
    'personal_picture_documents' => $policy_details->personal_picture_documents ?? null,
    'pets_permit' => $policy_details->pets_permit ?? null,
];

$hasDocuments = false;
            @endphp

            @foreach($petDocuments as $key => $document)

                @if(!empty($document))

                    @php $hasDocuments = true; @endphp

                    <a href="{{ asset($document) }}" target="_blank" class="btn btn-outline-primary document-btn">
                        View {{ ucwords(str_replace('_', ' ', $key)) }}
                    </a>

                @endif

            @endforeach

            @if(!$hasDocuments)
                <span class="text-muted">No pet documents available.</span>
            @endif

        </div>


        {{-- ========================= --}}
        {{-- POLICY PDF --}}
        {{-- ========================= --}}

        @if(!empty($policy_details->policy_pdf_url))

            <h6 class="section-title mt-4">Policy PDF</h6>

            <div class="d-flex flex-wrap gap-2">

                <a href="{{ asset($policy_details->policy_pdf_url) }}" target="_blank" class="btn btn-outline-danger document-btn">
                    View Policy PDF
                </a>

            </div>

        @endif

    </div>

</div>
