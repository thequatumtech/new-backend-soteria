<div class="policy-card">

    <div class="policy-card-header">
        Critical Illness Insurance Details
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
                <div class="detail-label">Occupancy / Work</div>
                <div class="detail-value">
                    {{ $policy_details->occupancy_work ?? '-' }}
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
        {{-- RESIDENCE INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Residence Information</h6>

        <div class="row">

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

        </div>


        {{-- ========================= --}}
        {{-- EMPLOYMENT INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Employment Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Name</div>
                <div class="detail-value">
                    {{ $policy_details->company_name ?? '-' }}
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
                <div class="detail-label">Company Contact</div>
                <div class="detail-value">
                    {{ $policy_details->company_contact ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- PHYSICAL INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Physical Information</h6>

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

        </div>


        {{-- ========================= --}}
        {{-- MEDICAL INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Medical Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Chronic Diseases</div>
                <div class="detail-value">
                    {{ $policy_details->chronic_diseases_id ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Previous Operation</div>
                <div class="detail-value">
                    {{ $policy_details->previous_operation ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
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

            <div class="col-md-6 detail-item">
                <div class="detail-label">Previous Insurance Policy</div>
                <div class="detail-value">
                    {{ $policy_details->previous_insurance_policy ?? '-' }}
                </div>
            </div>

            <div class="col-md-6 detail-item">
                <div class="detail-label">Previous Insurance Policy Details</div>
                <div class="detail-value">
                    {{ $policy_details->previous_insurance_policy_details ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- INSURANCE INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Insurance Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Insurance Amount</div>
                <div class="detail-value">
                    {{ $policy_details->insurance_amount ?? '-' }}
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

        </div>


        {{-- ========================= --}}
        {{-- POLICY DATES --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Policy Dates</h6>

        <div class="row">

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

        </div>


        {{-- ========================= --}}
        {{-- DOCUMENTS --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Documents</h6>

        <div class="d-flex flex-wrap gap-2">

            @if(!empty($policy_details->passport_id_documents))

                <a href="{{  asset($policy_details->passport_id_documents) }}"
                   target="_blank"
                   class="btn btn-outline-primary document-btn">
                    View Passport / ID Documents
                </a>

            @endif

            @if(!empty($policy_details->insured_documents))

                <a href="{{  asset($policy_details->insured_documents )}}"
                   target="_blank"
                   class="btn btn-outline-primary document-btn">
                    View Insured Documents
                </a>

            @endif

            @if(
                    empty($policy_details->passport_id_documents) &&
                    empty($policy_details->insured_documents)
                )

                    <span class="text-muted">
                        No documents available.
                    </span>

            @endif

        </div>

    </div>

</div>
