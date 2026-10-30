<div class="policy-card">

    <div class="policy-card-header">
        Life Insurance Details
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
                <div class="detail-label">Marital Status</div>
                <div class="detail-value">
                    {{ $policy_details->marital_status ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Place of Residence</div>
                <div class="detail-value">
                    {{ $policy_details->place_residence ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Occupation / Work</div>
                <div class="detail-value">
                    {{ $policy_details->occupancy_work ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">American Nationality Status</div>
                <div class="detail-value">
                    {{ getYesNoStatus($policy_details->american_notionality_status ?? null) }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- BENEFICIARY INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Beneficiary Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Beneficiary First Name</div>
                <div class="detail-value">
                    {{ $policy_details->beneficiary_first_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Beneficiary Last Name</div>
                <div class="detail-value">
                    {{ $policy_details->beneficiary_last_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Beneficiary Third Name</div>
                <div class="detail-value">
                    {{ $policy_details->beneficiary_third_name ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- RESIDENTIAL ADDRESS --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Residential Address</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Country ID</div>
                <div class="detail-value">
                    {{ getCountryName($policy_details->country_id) ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">City</div>
                <div class="detail-value">
                    {{ getCityName($policy_details->city_id) }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">District</div>
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

        </div>


        {{-- shown only when the client entered employment details (not shown for unemployed) --}}
        @if(($policy_details->employee_status ?? null) == 1 || !empty($policy_details->employer_company_name) || !empty($policy_details->position) || !empty($policy_details->work_nature) || !empty($policy_details->employee_city_id) || !empty($policy_details->employee_district_id) || !empty($policy_details->employee_street_name) || !empty($policy_details->employee_building_no) || !empty($policy_details->company_contact))

        {{-- ========================= --}}
        {{-- EMPLOYMENT INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Employment Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Employee Status</div>
                <div class="detail-value">
                    {{ getYesNoStatus($policy_details->employee_status ?? null) }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Name</div>
                <div class="detail-value">
                    {{ $policy_details->employer_company_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Position</div>
                <div class="detail-value">
                    {{ $policy_details->position ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Nature of Work</div>
                <div class="detail-value">
                    {{ $policy_details->work_nature ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Employee City</div>
                <div class="detail-value">
                    {{ getCityName($policy_details->employee_city_id ?? null) }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Employee District</div>
                <div class="detail-value">
                    {{ getDistrictName($policy_details->employee_district_id ?? null) }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Employee Street Name</div>
                <div class="detail-value">
                    {{ $policy_details->employee_street_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Employee Building No.</div>
                <div class="detail-value">
                    {{ $policy_details->employee_building_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Contact</div>
                <div class="detail-value">
                    {{ $policy_details->company_contact ?? '-' }}
                </div>
            </div>

        </div>
        @endif



        {{-- ========================= --}}
        {{-- HEALTH INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Health Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Height</div>
                <div class="detail-value">
                    {{ $policy_details->height ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Weight</div>
                <div class="detail-value">
                    {{ $policy_details->wight ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Chronic Diseases</div>
                <div class="detail-value">
                    {{ getChronicDiseaseName($policy_details->chronic_diseases_id ?? null) }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Previous Operation</div>
                <div class="detail-value">
                    {{ getYesNoStatus($policy_details->previous_operation ?? null) }}
                </div>
            </div>

            <div class="col-md-8 detail-item">
                <div class="detail-label">Operation Details</div>
                <div class="detail-value">
                    {{ $policy_details->operation_details ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- PREVIOUS INSURANCE --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Previous Insurance Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Declined Policy</div>
                <div class="detail-value">
                    {{ getYesNoStatus($policy_details->company_declined_policy ?? null) }}
                </div>
            </div>

            <div class="col-md-8 detail-item">
                <div class="detail-label">Declined Policy Details</div>
                <div class="detail-value">
                    {{ $policy_details->declined_policy_details ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Existing Life Insurance</div>
                <div class="detail-value">
                    {{ getYesNoStatus($policy_details->exiting_life_insur ?? null) }}
                </div>
            </div>

            <div class="col-md-8 detail-item">
                <div class="detail-label">Existing Life Insurance Details</div>
                <div class="detail-value">
                    {{ $policy_details->exiting_life_insur_details ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- INSURANCE INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Insurance Information</h6>

        <div class="row">


            <div class="col-md-4 detail-item">
                <div class="detail-label">Plan Name</div>
                <div class="detail-value">
                    {{ $policy_details->plan_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Insurance Amount</div>
                <div class="detail-value">
                    {{ $policy_details->insurance_amount ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Insurance Period</div>
                <div class="detail-value">
                        {{-- Life insurance_period is a number of years, not an insurance_periods id --}}
                        {{ !empty($policy_details->insurance_period) ? (int) $policy_details->insurance_period . ' ' . ((int) $policy_details->insurance_period === 1 ? 'Year' : 'Years') : '-' }}

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

                    @if(isset($policy->payment_status))

                        @if($policy->payment_status == 1)

                            <span class="badge bg-success badge-status">
                                Paid
                            </span>

                        @elseif($policy->payment_status == 0)

                            <span class="badge bg-warning text-dark badge-status">
                                Pending
                            </span>

                        @else

                            {{ $policy->payment_status }}

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

        </div>


        {{-- ========================= --}}
        {{-- DOCUMENTS --}}
        {{-- ========================= --}}

        @include('admin.client.policies.partials.documents', [
            'documentFields' => ['Photo' => $policy_details->photo_documents ?? null, 'Insured Document' => $policy_details->insured_documents ?? null, 'Family Book' => $policy_details->family_book_documents ?? null],
        ])

    </div>

</div>
