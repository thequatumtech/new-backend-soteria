<div class="policy-card">

    <div class="policy-card-header">
        Family Medical Insurance Details
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
                <div class="detail-label">Occupation / Work</div>
                <div class="detail-value">
                    {{ $policy_details->occupancy_work ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- RESIDENCE ADDRESS --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Residence Address</h6>

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
                    {{ $policy_details->company_company_contact ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- EXISTING INSURANCE --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Existing Insurance Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Existing Policy Status</div>
                <div class="detail-value">
                    {{ $policy_details->existing_policy_status ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Existing Policy Company</div>
                <div class="detail-value">
                    {{ $policy_details->existing_policy_company_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Existing Policy Expiry Date</div>
                <div class="detail-value">
                    @if(!empty($policy_details->existing_policy_expiry_date))
                        {{ \Carbon\Carbon::parse($policy_details->existing_policy_expiry_date)->format('d-m-Y') }}
                    @else
                        -
                    @endif
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- MEDICAL INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Medical Information</h6>

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
        {{-- PREGNANCY & ACTIVITIES --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Pregnancy & Dangerous Activities</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Pregnancy Status</div>
                <div class="detail-value">
                    {{ $policy_details->pregnant_status ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Pregnancy Month</div>
                <div class="detail-value">
                    {{ $policy_details->pregnant_month ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Dangerous Activity Status</div>
                <div class="detail-value">
                    {{ $policy_details->dangerous_status ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Dangerous Activity</div>
                <div class="detail-value">
                    {{ $policy_details->dangerous_id ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- INSURANCE INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Insurance Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Insurance Type</div>
                <div class="detail-value">
                    {{ $policy_details->insurance_type ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Insurance Class</div>
                <div class="detail-value">
                    {{ $policy_details->insurance_class ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Insurance Limit</div>
                <div class="detail-value">
                    {{ $policy_details->insurance_limit ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Insurance Type Status</div>
                <div class="detail-value">
                    {{ $policy_details->insurance_type_status ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Plan ID</div>
                <div class="detail-value">
                    {{ $policy_details->plan_id ?? '-' }}
                </div>
            </div>

            @if(isset($policy_details->plan_name))
                <div class="col-md-4 detail-item">
                    <div class="detail-label">Plan Name</div>
                    <div class="detail-value">
                        {{ $policy_details->plan_name }}
                    </div>
                </div>
            @endif

            @if(isset($policy_details->insurance_company_name))
                <div class="col-md-4 detail-item">
                    <div class="detail-label">Insurance Company</div>
                    <div class="detail-value">
                        {{ $policy_details->insurance_company_name }}
                    </div>
                </div>
            @endif

        </div>


        {{-- ========================= --}}
        {{-- DEDUCTIBLES --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Deductibles & Visits</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Inpatient Deductible</div>
                <div class="detail-value">
                    {{ getInpatientDeductibleName($policy_details->inpatient_deductible_id) }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Outpatient Deductible</div>
                <div class="detail-value">
                    {{ getOutpatientDeductibleName($policy_details->outpatient_deductible_id) }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Number of Visits</div>
                <div class="detail-value">
                    {{ $policy_details->no_of_visits_id ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- POLICY INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Policy Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Policy No.</div>
                <div class="detail-value">
                    {{ $policy_details->police_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Effective Date</div>
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
                <div class="detail-label">Payment Status</div>
                <div class="detail-value">
                    @if(isset($policy_details->payment_status))
                        @if($policy_details->payment_status == 1)
                            Paid
                        @elseif($policy_details->payment_status == 0)
                            Pending
                        @else
                            {{ $policy_details->payment_status }}
                        @endif
                    @else
                        -
                    @endif
                </div>
            </div>

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

        <h6 class="section-title mt-4">Documents</h6>

        <div class="d-flex flex-wrap gap-2">

            @if(!empty($policy_details->passport_front_id))
            <a href="{{ asset($policy_details->passport_front_id) }}" target="_blank"
                    class="btn btn-outline-primary document-btn">
                    View Passport Front
                </a>
            @endif

            @if(!empty($policy_details->passport_back_id))
            <a href="{{ asset($policy_details->passport_back_id) }}" target="_blank"
                    class="btn btn-outline-primary document-btn">
                    View Passport Back
                </a>
            @endif

            @if(!empty($policy_details->family_book_documents))
            <a href="{{ asset($policy_details->family_book_documents) }}" target="_blank"
                    class="btn btn-outline-primary document-btn">
                    View Family Book
                </a>
            @endif

            @if(!empty($policy_details->personal_picture_documents))
            <a href="{{ asset($policy_details->personal_picture_documents) }}" target="_blank"
                    class="btn btn-outline-primary document-btn">
                    View Personal Picture
                </a>
            @endif

            @if(!empty($policy_details->other_documents))
            <a href="{{ asset($policy_details->other_documents) }}" target="_blank"
                    class="btn btn-outline-primary document-btn">
                    View Other Documents
                </a>
            @endif

            @if(!empty($policy_details->existing_policy_card))
            <a href="{{ asset($policy_details->existing_policy_card) }}" target="_blank"
                    class="btn btn-outline-primary document-btn">
                    View Existing Policy Card
                </a>
            @endif

            @if(
    empty($policy_details->passport_front_id) &&
    empty($policy_details->passport_back_id) &&
    empty($policy_details->family_book_documents) &&
    empty($policy_details->personal_picture_documents) &&
    empty($policy_details->other_documents) &&
    empty($policy_details->existing_policy_card)
)
                <span class="text-muted">
                    No documents available.
                </span>
            @endif

        </div>


        {{-- ========================= --}}
        {{-- FAMILY MEMBERS --}}
        {{-- ========================= --}}

        @if(!empty($family_members) && count($family_members))

            <h6 class="section-title mt-4">Family Members</h6>

            <div class="table-responsive">

                <table class="table table-bordered detail-table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Relation</th>
                            <th>Nationality</th>
                            <th>Birth Date</th>
                            <th>Gender</th>
                            <th>Marital Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($family_members as $index => $member)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $member->first_name ?? '-' }}
                                </td>

                                <td>
                                    {{ $member->last_name ?? '-' }}
                                </td>

                                <td>
                                    {{ $member->relation ?? '-' }}
                                </td>

                                <td>
                                    {{ $member->nationality ?? '-' }}
                                </td>

                                <td>
                                    @if(!empty($member->birth_date))
                                        {{ \Carbon\Carbon::parse($member->birth_date)->format('d-m-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if(isset($member->gender))
                                        @if($member->gender == 1)
                                            Male
                                        @elseif($member->gender == 2)
                                            Female
                                        @else
                                            {{ $member->gender }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    {{ $member->marital_status ?? '-' }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</div>
