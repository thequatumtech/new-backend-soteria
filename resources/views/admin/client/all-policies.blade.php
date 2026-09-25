@extends('layouts.mainlayout')
@section('style')
<style>
    input {
        border: none;
        font-family: 'Nunito';
        width: 100%;
        height: 2.5rem;
        font-weight: 600;
    }

    input:focus-visible {
        outline: none;
    }

    .input-group {
        font-weight: 600;
        font-family: 'Nunito';
    }

    .shadow1 {
        box-shadow: 4px 4px 13px -3px #00000040;
    }

    /* Style for modal content */
    .view-modal-content {
        background-color: #fefefe;
        /*margin: 5% auto;*/
        margin-left: 5%;
        /*padding: 20px;*/
        padding-top: 20px;
        /*border: 1px solid #888;*/
        width: 80%;
    }

    /* Override default error styles */
    .error {
        color: inherit;
        /* Set the color to inherit */
    }
</style>
@endsection

@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">
                {{ __('messages.clients.client') }} - {{$client->full_name}}
            </div>

            <a href="{{ route('client') }}" class="btn btn-light btn-sm">
                <i class="fa fa-arrow-left me-1"></i>
                {{__('messages.clients.back')}}
            </a>
        </div>

        {{-- Policies --}}
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">

            <table id="example" class="table table-striped" style="width:100%">

                <thead>
                    <tr>
                        <th class="no-search">#</th>
                        <th>{{__('messages.clients.policy_no')}}</th>
                        <th>{{__('messages.clients.policy_type')}}</th>
                        <th>{{__('messages.clients.insurance_company')}}</th>
                        <th>{{__('messages.clients.plan')}}</th>


                        <th class="no-order no-search">{{__('messages.clients.final_pdf')}}</th>
                        <th class="no-order no-search">{{__('messages.clients.action')}}</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($purchased_policy as $key => $policy)

                    <tr>

                        <td>
                            {{ $key + 1 }}
                        </td>

                        <td>
                            {{ $policy->policy_no ?? '-' }}
                        </td>

                        <td>
                            {{ $policy->policy_type ?? '-' }}
                        </td>

                        <td>
                            {{ $policy->insurance_company->company_name ?? '-' }}
                        </td>

                        <td>
                            {{ $policy->plan_name ?? '-' }}
                        </td>


                        {{-- Final PDF --}}
                        <td>
                            @if(!empty($policy->final_pdf_url))

                            <a href="{{ $policy->final_pdf_url }}" target="_blank" class="btn btn-sm btn-outline-success">
                                <img src="{{asset('img/icon-eye.png')}}" alt="">
                            </a>

                            @else

                            <span class="text-muted">{{__('messages.clients.no_pdf')}}</span>

                            @endif
                        </td>


                        {{-- Action --}}
                        <td>
                            <a href="{{ route('purchase-policy.show', $policy->id) }}" class="btn btn-sm btn-primary">
                                <img src="{{ asset('img/icon-eye.png') }}" alt="View">
                            </a>
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>
    </div>
    <input type="hidden" id="selected_client_id">
    <input type="hidden" id="selected_client_names">
</main>

<!-- END Send Message-->
<div class="toast-container position-fixed bottom-0 start-50 translate-middle-x w-100">
    <div class="p-3 col-12 col-lg-8 mx-auto">
        <div class="toast align-items-center text-bg-danger border-0 col-12 col-lg-8 w-100" data-bs-delay="3000"
            role="alert" aria-live="assertive" aria-atomic="true" id="clientSelectToast">
            <div class="d-flex">
                <div class="toast-body px-5 fw-bold py-3" style="font-size: 1.375rem;">
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>


<script src="{{asset('js/all-policies.js?v=1.3')}}">
</script>
@endsection