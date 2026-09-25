<div class="policy-card">

    <div class="policy-card-header">
        Home Insurance Details
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
                    {{ $policy_details->gender ?? '-' }}
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
                    {{ $policy_details->place_of_residence ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Occupancy / Type of Work</div>
                <div class="detail-value">
                    {{  $policy->client?->occupation?->name ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- HOME INFORMATION --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Home Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Home Type</div>
                <div class="detail-value">
                    {{ $policy_details->home_type ?? '-' }}
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
                <div class="detail-label">Age of Building / Villa</div>
                <div class="detail-value">
                    {{ !empty($policy_details->home_age) ? $policy_details->home_age : '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Size of Apartment in Sqm</div>
                <div class="detail-value">
                    {{ $policy_details->size_of_apartment ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Number of Residence</div>
                <div class="detail-value">
                    {{ $policy_details->no_of_residence ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Home Category</div>
                <div class="detail-value">
                    {{ $policy_details->home_category ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- PROPERTY ADDRESS --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Property Address</h6>

        <div class="row">

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
                <div class="detail-label">Company City ID</div>
                <div class="detail-value">
                    {{ $policy_details->city_id_2 ?? '-' }}
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

        </div>


        {{-- ========================= --}}
        {{-- PREVIOUS INSURANCE --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Previous Insurance Information</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Previous Policy</div>
                <div class="detail-value">
                    {{ $policy_details->previous_policy ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Company Declined to Issue</div>
                <div class="detail-value">
                    {{ $policy_details->company_declined_to_issue ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Claims / Accidents in Past</div>
                <div class="detail-value">
                    {{ $policy_details->claims_accidents_past ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- PROPERTY SECURITY --}}
        {{-- ========================= --}}

        <h6 class="section-title mt-4">Property Security & Coverage</h6>

        <div class="row">

            <div class="col-md-4 detail-item">
                <div class="detail-label">Protection System</div>
                <div class="detail-value">
                    {{ $policy_details->protection_system ?? '-' }}
                </div>
            </div>

            <div class="col-md-4 detail-item">
                <div class="detail-label">Insurance Limit</div>
                <div class="detail-value">
                    {{ $policy_details->insurance_limit ?? '-' }}
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
                    {{ $policy_details->plan_name ?? '-' }}
                </div>
            </div>

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
                <div class="detail-label">Payment Status</div>
                <div class="detail-value">
                    {{ $policy_details->payment_status ?? '-' }}
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- DOCUMENTS --}}
        {{-- ========================= --}}

        {{-- ========================= --}}
{{-- DOCUMENTS --}}
{{-- ========================= --}}

<h6 class="section-title mt-4">Property Documents</h6>

<div class="d-flex flex-wrap gap-2">

    @php
// Helper to turn "a.pdf,https://.../b.pdf" into clean, usable links
$buildDocLinks = function ($value) {
    if (empty($value)) {
        return [];
    }

    return collect(explode(',', $value))
        ->map(fn($path) => trim($path))
        ->filter() // remove empty entries from stray commas
        ->map(function ($path) {
            return str_starts_with($path, 'http://') || str_starts_with($path, 'https://')
                ? $path
                : asset($path);
        })
        ->values()
        ->toArray();
};

$rentContracts = $buildDocLinks($policy_details->rent_contract ?? null);
$propertyDocuments = $buildDocLinks($policy_details->property_document ?? null);
$contentDocuments = $buildDocLinks($policy_details->content_document ?? null);
    @endphp

    @forelse ($rentContracts as $index => $link)
        <a href="{{ $link }}" target="_blank" class="btn btn-outline-primary document-btn">
            View Rent Contract {{ count($rentContracts) > 1 ? '#' . ($index + 1) : '' }}
        </a>
    @empty
    @endforelse

    @forelse ($propertyDocuments as $index => $link)
        <a href="{{ $link }}" target="_blank" class="btn btn-outline-primary document-btn">
            View Property Document {{ count($propertyDocuments) > 1 ? '#' . ($index + 1) : '' }}
        </a>
    @empty
    @endforelse

    @forelse ($contentDocuments as $index => $link)
        <a href="{{ $link }}" target="_blank" class="btn btn-outline-primary document-btn">
            View Content Document {{ count($contentDocuments) > 1 ? '#' . ($index + 1) : '' }}
        </a>
    @empty
    @endforelse

    @if (empty($rentContracts) && empty($propertyDocuments) && empty($contentDocuments))
        <span class="text-muted">
            No property documents available.
        </span>
    @endif

</div>

    </div>

</div>
