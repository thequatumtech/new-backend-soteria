<div class="policy-card">

    <div class="policy-card-header">
        Office Insurance Details
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

            <div class="col-md-4 detail-item">
                <div class="detail-label">Place of Residence</div>
                <div class="detail-value">
                    {{ $policy_details->place_residence ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- COMPANY INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Company Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Name</div>
                <div class="detail-value">
                    {{ $policy_details->company_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Register National ID</div>
                <div class="detail-value">
                    {{ $policy_details->company_register_national_id ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Register ID</div>
                <div class="detail-value">
                    {{ $policy_details->company_register_id ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Number of Employees</div>
                <div class="detail-value">
                    {{ $policy_details->no_of_employee ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Telephone</div>
                <div class="detail-value">
                    {{ $policy_details->company_telephone ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- OFFICE / PROPERTY INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Office / Property Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Office Type</div>
                <div class="detail-value">
                    {{ $policy_details->office_type ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Office Category</div>
                <div class="detail-value">
                    {{ $policy_details->office_category ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Number of Floors</div>
                <div class="detail-value">
                    {{ $policy_details->no_of_floor ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Number of Rooms</div>
                <div class="detail-value">
                    {{ $policy_details->no_of_room ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Size of Apartment</div>
                <div class="detail-value">
                    {{ $policy_details->size_of_apartment ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Age of Apartment</div>
                <div class="detail-value">
                    {{ $policy_details->age_of_apartment ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Number of Residences</div>
                <div class="detail-value">
                    {{ $policy_details->no_of_residence ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Block No.</div>
                <div class="detail-value">
                    {{ $policy_details->block_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Plate No.</div>
                <div class="detail-value">
                    {{ $policy_details->plate_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Plot No.</div>
                <div class="detail-value">
                    {{ $policy_details->plot_no ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- OFFICE ADDRESS --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Office Address</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Country ID</div>
                <div class="detail-value">
                    {{ getCountryName($policy_details->country_id) ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">City ID</div>
                <div class="detail-value">
                    {{ getCityName($policy_details->city_id) }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">District ID</div>
                <div class="detail-value">
                    {{ getDistrictName($policy_details->district_id) }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Street Name</div>
                <div class="detail-value">
                    {{ $policy_details->street_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Building No.</div>
                <div class="detail-value">
                    {{ $policy_details->building_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Office No.</div>
                <div class="detail-value">
                    {{ $policy_details->office_no ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- COMPANY OWNER INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Company Owner Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Owner Name</div>
                <div class="detail-value">
                    {{ $policy_details->company_owner_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Owner Telephone</div>
                <div class="detail-value">
                    {{ $policy_details->company_owner_telephone ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Partner Company Status</div>
                <div class="detail-value">
                    {{ $policy_details->partner_company_status ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Authorized Insurance Policy Status</div>
                <div class="detail-value">
                    {{ $policy_details->authorized_insurance_police_status ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Authorized Company Register Status</div>
                <div class="detail-value">
                    {{ $policy_details->auth_company_register_status ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- INSURANCE INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Insurance Information</h6>

        <div class="row">

            <div class="col-md-4 detail-value">
                <div class="detail-label">Insurance Limit</div>
                <div class="detail-value">
                    {{ $policy_details->insurance_limit ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Insurance Plan</div>
                <div class="detail-value">
                    {{ $policy_details->insurance_plan ?? '-' }}
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

            <div class="col-md-4 detail-item">
                <div class="detail-label">Protection System</div>
                <div class="detail-value">
                    {{ $policy_details->protection_system ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- POLICY QUESTIONS --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Policy Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Previous Insurance Policy</div>
                <div class="detail-value">
                    {{ $policy_details->provious_insurance_policy ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Insurance Declined Issue Status</div>
                <div class="detail-value">
                    {{ $policy_details->insurance_declined_issue_status ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Claims in Last 5 Years</div>
                <div class="detail-value">
                    {{ $policy_details->claims_5_year_status ?? '-' }}
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
                <div class="detail-label">Insurance Expiry Date</div>
                <div class="detail-value">
                    @if(!empty($policy_details->insurance_expiry_date))
                        {{ \Carbon\Carbon::parse($policy_details->insurance_expiry_date)->format('d-m-Y') }}
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
                    'rent_contract_documents'              => 'Rent Contract',
                    'property_photo_documents'             => 'Property Photos',
                    'contents_documents'                   => 'Contents Documents',
                    'policy_issuer_documents'              => 'Policy Issuer Documents',
                    'company_owner_documents'              => 'Company Owner Documents',
                    'career_municipality_license_documents'=> 'Career Municipality License',
                    'owner_id_documents'                   => 'Owner ID Documents',
                    'practice_documents'                   => 'Practice Documents',
                    'company_tax_certi_documents'          => 'Company Tax Certificate',
                ];

                $hasDocuments = false;
            @endphp

            @foreach($documentFields as $field => $label)

                @if(!empty($policy_details->$field))

                    @php
                        $hasDocuments = true;
                        $document = $policy_details->$field;
                    @endphp

                    {{-- JSON ARRAY DOCUMENTS --}}

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

                        {{-- SINGLE DOCUMENT --}}

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
