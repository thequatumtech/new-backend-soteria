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

    .form-group {
        margin-bottom: 1.25rem;
    }

    .old-value {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 4px;
    }

    input[type="date"] {
        border: 1px solid #ced4da;
        background-color: #fff;
        color: #495057;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-weight: 600;
    }

    .text-end button,
    .text-end a {
        margin-left: 5px;
    }
</style>
@endsection

@section('content')
@php
$today = date('Y-m-d');
$previousUrl = url()->previous();
@endphp

<main class="flex-grow-1 pt-5">
    <div class="container">
        <h2 class="mb-4">{{ __('messages.renewal_section.renew_policy')}}</h2>

        @if(session('success'))
        <div id="successMessage" class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="card p-4 shadow-sm">
            <form action="{{ route('renew_policy.update', $policy->id) }}" method="POST" id="renewForm">
                @csrf

                <input type="hidden" name="redirect_to" value="{{ $previousUrl }}">

                {{-- Disabled fields --}}
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="fw-bold">{{ __('messages.renewal_section.policy_number')}}</label>
                        <input type="text" value="{{ $policy->policy_no }}" disabled>
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="fw-bold">{{ __('messages.renewal_section.client_namee')}}</label>
                        <input type="text" value="{{ $policy->client->full_name }}" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="fw-bold">{{ __('messages.renewal_section.plan_name')}}</label>
                        <input type="text" value="{{ $policy->plan_name ?? 'N/A' }}" disabled>
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="fw-bold">{{ __('messages.renewal_section.policy_limit')}}</label>
                        <input type="number" value="{{ $policy->policy_plan_limit ?? 0 }}" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="fw-bold">{{ __('messages.renewal_section.net_premium')}}</label>
                        <input type="number" value="{{ $policy->net_premium ?? 0 }}" disabled>
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="fw-bold">{{ __('messages.renewal_section.fees')}}</label>
                        <input type="number" value="{{ $policy->fees ?? 0 }}" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="fw-bold">{{ __('messages.renewal_section.payment_status')}}</label>
                        <input type="text" value="{{ $policy->payment_status == 1 ? 'Paid' : 'Unpaid' }}" disabled>
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="fw-bold">{{ __('messages.renewal_section.insurance_company')}}</label>
                        <input type="text" value="{{ $policy->insurance_company->company_name ?? 'N/A' }}" disabled>
                    </div>
                </div>

                {{-- Editable dates --}}
                <div class="row mt-3">
                    <div class="col-md-6 form-group">
                        <label class="fw-bold">{{ __('messages.renewal_section.inception_date')}}</label>
                        <div class="old-value">{{ __('messages.renewal_section.old')}} {{ $policy->inception_date }}</div>
                        <input type="date" name="inception_date"
                            value="{{ old('inception_date', $policy->inception_date ?? date('Y-m-d')) }}"
                            required
                            min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="fw-bold">{{ __('messages.renewal_section.expiry_date')}}</label>
                        <div class="old-value">{{ __('messages.renewal_section.old')}} {{ $policy->expiry_date }}</div>
                        <input type="date" name="expiry_date"
                            value="{{ old('expiry_date', $policy->expiry_date ?? date('Y-m-d')) }}"
                            required
                            min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">{{ __('messages.renewal_section.save')}}</button>
                    <a href="#" id="cancelBtn" class="btn btn-secondary">{{ __('messages.renewal_section.cancel')}}</a>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const successMsg = document.getElementById('successMessage');
        if (successMsg) {
            setTimeout(() => {
                successMsg.style.transition = "opacity 0.5s";
                successMsg.style.opacity = '0';
                setTimeout(() => successMsg.remove(), 500);
            }, 5000);
        }

        const cancelBtn = document.getElementById('cancelBtn');
        const redirectUrl = "{{ $previousUrl }}";

        cancelBtn.addEventListener("click", function(e) {
            e.preventDefault();
            window.location.href = redirectUrl;
        });
    });
</script>
@endsection