<div class="policy-card">

    <div class="policy-card-header">
        Motor Insurance Details
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
                <div class="detail-label">National ID Number</div>
                <div class="detail-value">
                    {{ $policy_details->national_id_number ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Residency Number</div>
                <div class="detail-value">
                    {{ $policy_details->residency_number ?? '-' }}
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

        </div>


        {{-- ========================= --}}
        {{-- CONTACT / ADDRESS INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Contact & Address Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Mobile No.</div>
                <div class="detail-value">
                    {{ $policy_details->user_mobile_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Occupancy</div>
                <div class="detail-value">
                    {{ $policy_details->occupancy ?? '-' }}
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
                <div class="detail-label">Work Nature</div>
                <div class="detail-value">
                    {{ $policy_details->work_nature ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Contact No.</div>
                <div class="detail-value">
                    {{ $policy_details->company_contact_no ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- DRIVER / CLAIM INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Driver & Claims Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">No Accident in Last 3 Years</div>
                <div class="detail-value">
                    {{ $policy_details->no_accident_3_year ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">No Ticket in Last 12 Months</div>
                <div class="detail-value">
                    {{ $policy_details->no_ticket_12_month ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">No Points in Last 12 Months</div>
                <div class="detail-value">
                    {{ $policy_details->no_point_12_month ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- VEHICLE INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Vehicle Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Vehicle No.</div>
                <div class="detail-value">
                    {{ $policy_details->vahicle_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Obtain Vehicle Information</div>
                <div class="detail-value">
                    {{ $policy_details->obtain_vahicle_info ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Vehicle Type</div>
                <div class="detail-value">
                    {{ get_vehicle_type_name($policy_details->vahicle_type_id) }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Vehicle Brand</div>
                <div class="detail-value">
                    {{ get_vehicle_brand_name($policy_details->vahicle_brand_id) }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Vehicle Category</div>
                <div class="detail-value">
                    {{ get_vehicle_category_name($policy_details->vahicle_category_id) }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Vehicle Color</div>
                <div class="detail-value">
                    {{ get_vehicle_color_name($policy_details->vahicle_color_id) }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Vehicle Registration No.</div>
                <div class="detail-value">
                    {{ $policy_details->vahicle_register_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Engine No.</div>
                <div class="detail-value">
                    {{ $policy_details->engine_no ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Chassis No.</div>
                <div class="detail-value">
                    {{ $policy_details->chassis_no ?? '-' }}
                </div>
            </div>

        <div class="col-md-4 detail-item">
            <div class="detail-label">Engine Type</div>
            <div class="detail-value">
                {{ get_engine_type_name($policy_details->engine_type_id) }}
            </div>
        </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Engine Capacity</div>
                <div class="detail-value">
                    {{ $policy_details->engine_capacity ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Manufacturing Date</div>
                <div class="detail-value">
                    @if(!empty($policy_details->vehicle_manufacturing_date))
                        {{ \Carbon\Carbon::parse($policy_details->vehicle_manufacturing_date)->format('d-m-Y') }}
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Vehicle Value</div>
                <div class="detail-value">
                    {{ $policy_details->vehicle_value ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Insurance Type</div>
                <div class="detail-value">
                    {{ $policy_details->insurance_type ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- POLICY INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Policy Information</h6>

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
                <div class="detail-label">Insurance Company</div>
                <div class="detail-value">
                    {{ $policy_details->company_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Policy Type</div>
                <div class="detail-value">
                    {{ $policy_details->policy_type ?? '-' }}
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
        {{-- POLICY DOCUMENTS --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Policy Documents</h6>

        <div class="d-flex flex-wrap gap-2">

            @php
$documentFields = [
    'residence_id_front' => 'Residence ID Front',
    'residence_id_back' => 'Residence ID Back',
    'vehicle_license_front' => 'Vehicle License Front',
    'vehicle_license_back' => 'Vehicle License Back',
    'vehicle_photo_front' => 'Vehicle Photo Front',
    'vehicle_photo_back' => 'Vehicle Photo Back',
    'vehicle_photo_right' => 'Vehicle Photo Right',
    'vehicle_photo_left' => 'Vehicle Photo Left',
    'carseer_documents' => 'Carseer Documents',
    'autoscore_documents' => 'Autoscore Documents',
    'customs_declaration' => 'Customs Declaration',
];

$hasDocuments = false;
            @endphp

            @foreach($documentFields as $field => $label)

                @if(!empty($policy_details->$field))

                    @php
        $hasDocuments = true;
        $document = $policy_details->$field;
                    @endphp

                    {{-- JSON array documents --}}
                    @if(is_string($document) && str_starts_with(trim($document), '['))

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

                        {{-- Single document --}}
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
