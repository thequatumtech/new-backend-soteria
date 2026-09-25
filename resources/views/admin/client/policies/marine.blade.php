<div class="policy-card">

    <div class="policy-card-header">
        Marine Insurance Details
    </div>

    <div class="policy-card-body">

        {{-- ========================= --}}
        {{-- PERSONAL INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title">Personal Information</h6>

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
                <div class="detail-label">Nationality</div>
                <div class="detail-value">
                    {{ $policy_details->nationality ?? '-' }}
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
        {{-- COMPANY INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Company Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Status</div>
                <div class="detail-value">
                    {{ $policy_details->company_status ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Name</div>
                <div class="detail-value">
                    {{ $policy_details->company_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Registration National ID</div>
                <div class="detail-value">
                    {{ $policy_details->company_reg_notional_id ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Registration No.</div>
                <div class="detail-value">
                    {{ $policy_details->company_reg_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Country ID</div>
                <div class="detail-value">
                    {{ $policy_details->company_country_id ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company City ID</div>
                <div class="detail-value">
                    {{ $policy_details->company_city_id ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company District ID</div>
                <div class="detail-value">
                    {{ $policy_details->company_district_id ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Street Name</div>
                <div class="detail-value">
                    {{ $policy_details->company_street_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Building No.</div>
                <div class="detail-value">
                    {{ $policy_details->company_building_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Office No.</div>
                <div class="detail-value">
                    {{ $policy_details->company_office_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Contact</div>
                <div class="detail-value">
                    {{ $policy_details->company_contact ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- COMPANY OWNER INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Company Owner Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Owner First Name</div>
                <div class="detail-value">
                    {{ $policy_details->owner_first_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Owner Last Name</div>
                <div class="detail-value">
                    {{ $policy_details->owner_last_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Owner Third Name</div>
                <div class="detail-value">
                    {{ $policy_details->owner_third_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Owner Family Name</div>
                <div class="detail-value">
                    {{ $policy_details->owner_family_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Owner Contact</div>
                <div class="detail-value">
                    {{ $policy_details->company_owner_contact ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Partner Status</div>
                <div class="detail-value">
                    {{ $policy_details->company_partner_status ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Authorized Status</div>
                <div class="detail-value">
                    {{ $policy_details->company_authorized_status ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Authorized Positions</div>
                <div class="detail-value">
                    {{ $policy_details->authorized_positions ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Register Status</div>
                <div class="detail-value">
                    {{ $policy_details->company_register_status ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- SHIPPING / TRANSPORTATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Shipping & Transportation</h6>

        <div class="row">

        <div class="col-md-4 detail-item">
            <div class="detail-label">Voyage From</div>
            <div class="detail-value">
                {{ getCountryName($policy_details->vayage_from_id) }}
            </div>
        </div>

        <div class="col-md-4 detail-item">
            <div class="detail-label">Through Country</div>
            <div class="detail-value">
                {{ getCountryName($policy_details->through_country_id) }}
            </div>
        </div>

        <div class="col-md-4 detail-item">
            <div class="detail-label">Destination Country</div>
            <div class="detail-value">
                {{ getCountryName($policy_details->destination_country_id) }}
            </div>
        </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Type of Transportation</div>
                <div class="detail-value">
                    {{ $policy_details->type_of_transportation ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Type of Cover</div>
                <div class="detail-value">
                    {{ $policy_details->type_of_cover ?? '-' }}
                </div>
            </div>

        <div class="col-md-4 detail-item">
            <div class="detail-label">Item Category</div>
            <div class="detail-value">
                {{ getInsuredItemCategoryName($policy_details->item_category_id) }}
            </div>
        </div>

        <div class="col-md-4 detail-item">
            <div class="detail-label">Item Subcategory</div>
            <div class="detail-value">
                {{ getInsuredItemSubCategoryName($policy_details->item_subcategory_id) }}
            </div>
        </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Bill No.</div>
                <div class="detail-value">
                    {{ $policy_details->bill_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Trans-shipped Through Third Country</div>
                <div class="detail-value">
                    {{ $policy_details->trans_shipped_third_country ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Dangerous Activities</div>
                <div class="detail-value">
                    {{ $policy_details->dangerous_activities ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- INSURANCE INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Insurance Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Insurance Limit</div>
                <div class="detail-value">
                    {{ $policy_details->insurance_limit ?? '-' }}
                </div>
            </div>

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
                <div class="detail-label">Insurance Company</div>
                <div class="detail-value">
                    {{ $policy_details->company_name ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- POLICY QUESTIONS --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Policy Information</h6>

    <div class="row">

        <div class="col-md-4 detail-item">
            <div class="detail-label">Existing Policy Status</div>
            <div class="detail-value">
                {{ getYesNoStatus($policy_details->existing_policy_status) }}
            </div>
        </div>

        <div class="col-md-8 detail-item">
            <div class="detail-label">Existing Policy Description</div>
            <div class="detail-value">
                {{ $policy_details->existing_policy_desc ?? '-' }}
            </div>
        </div>

        <div class="col-md-4 detail-item">
            <div class="detail-label">Declined Insurance Status</div>
            <div class="detail-value">
                {{ getYesNoStatus($policy_details->declined_insurance_status) }}
            </div>
        </div>

        <div class="col-md-8 detail-item">
            <div class="detail-label">Declined Insurance Description</div>
            <div class="detail-value">
                {{ $policy_details->declined_insurance_desc ?? '-' }}
            </div>
        </div>

        <div class="col-md-4 detail-item">
            <div class="detail-label">Claims / Accident Status</div>
            <div class="detail-value">
                {{ getYesNoStatus($policy_details->claims_accident_status) }}
            </div>
        </div>

        <div class="col-md-8 detail-item">
            <div class="detail-label">Claims / Accident Description</div>
            <div class="detail-value">
                {{ $policy_details->claims_accident_desc ?? '-' }}
            </div>
        </div>

    </div>


        {{-- ========================= --}}
        {{-- POLICY DATES --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Policy Dates</h6>

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

            <div class="col-md-4 detail-item">
                <div class="detail-label">Policy Number</div>
                <div class="detail-value">
                    {{ $policy_details->police_no ?? '-' }}
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
                <div class="detail-label">CBJ Sales Tax</div>
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
                <div class="detail-label">Commission</div>
                <div class="detail-value">
                    {{ $policy_details->commission_amount ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Commission Percentage</div>
                <div class="detail-value">
                    {{ $policy_details->commission_percentage ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- DOCUMENTS --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Policy Documents</h6>

        <div class="d-flex flex-wrap gap-2">

            @php
$documentFields = [
    'register_document' => 'Register Document',
    'billing_of_landing_doc' => 'Bill of Lading',
    'copy_of_invoice_doc' => 'Copy of Invoice',
    'insured_id_doc' => 'Insured ID',
    'policy_issuer_doc' => 'Policy Issuer Document',
    'company_reg_owner_doc' => 'Company Registration / Owner Document',
    'career_municipality_license_doc' => 'Career Municipality License',
    'company_tax_certificate_doc' => 'Company Tax Certificate',
    'practice_certificate_doc' => 'Practice Certificate',
];

$hasDocuments = false;
            @endphp

            @foreach($documentFields as $field => $label)

                @if(!empty($policy_details->$field))

                    @php
        $hasDocuments = true;
        $document = $policy_details->$field;
                    @endphp

                    @if(is_string($document) && str_starts_with($document, '['))

                        @php $decoded = json_decode($document, true); @endphp

                        @if(is_array($decoded))

                            @foreach($decoded as $doc)
                                @if(!empty($doc))
                                    <a href="{{ asset($doc) }}" target="_blank" class="btn btn-outline-primary document-btn">
                                        View {{ $label }}
                                    </a>
                                @endif
                            @endforeach

                        @endif

                    @else

                        <a href="{{ asset($document) }}" target="_blank" class="btn btn-outline-primary document-btn">
                            View {{ $label }}
                        </a>

                    @endif

                @endif

            @endforeach

            @if(!$hasDocuments)
                <span class="text-muted">No policy documents available.</span>
            @endif

        </div>

    </div>

</div>
