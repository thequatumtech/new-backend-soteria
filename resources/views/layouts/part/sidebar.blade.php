<!-- Left navbar -->
@php
$currentRouteName = Route::currentRouteName();
$admin = auth()->user();
$is_super_admin = $admin->is_super_admin;
$authorized_routes = json_decode($admin->authorized_routes, 1);
@endphp
<nav id="sidebar" class="sidebar-wrapper chiller-theme">
    <div class="sidebar-content">
        <div class="sidebar-brand">
            <a class="none-hover" href="{{ route('pages.index') }}">
                <img src="{{ asset('img/DashboardSotariaLogo.png') }}" alt="" class="img-fluid"
                    style="padding-top: 0.3rem; padding-bottom: 0.3rem;">
            </a>
            <div id="close-sidebar">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul>
                <li class="{{$currentRouteName == 'pages.index' ? 'active-main' : '' }}">
                    <a href="{{ route('pages.index') }}">
                        <i class="fa fa-tachometer-alt"></i>
                        <span>{{__('messages.sidebar_titles.dashboard')}}</span>
                    </a>
                </li>
                @if($is_super_admin == 1 || in_array('sub_admin',$authorized_routes))
                <li class=" {{in_array($currentRouteName, ['sub_admin','sub_admin.add','sub_admin.edit']) ? 'active-main' : '' }} ">
                    <a href="{{ route('sub_admin') }}">
                        <i class="fa fa-users"></i>
                        <span>{{__('messages.sidebar_titles.sub_admin')}}</span>
                    </a>
                </li>
                @endif

                @if($is_super_admin == 1 || in_array('client',$authorized_routes))
                <li class=" {{in_array($currentRouteName, ['client','client.add','client.edit']) ? 'active-main' : '' }} ">
                    <a href="{{ route('client') }}">
                        <i class="fa fa-users"></i>
                        <span>{{__('messages.sidebar_titles.clients')}}</span>
                    </a>
                </li>
                @endif

                @if($is_super_admin == 1 || in_array('pages.agent',$authorized_routes))
                <li class="{{$currentRouteName == 'pages.agent' ? 'active-main' : '' }}">
                    <a href="{{ route('pages.agent') }}">
                        <i class="fa fa-user"></i>
                        <span>{{__('messages.sidebar_titles.agents')}}</span>
                    </a>
                </li>
                @endif

                @if($is_super_admin == 1 || in_array('pages.insurance',$authorized_routes))
                <li class="{{$currentRouteName == 'pages.insurance' ? 'active-main' : '' }}">
                    <a href="{{ route('pages.insurance') }}">
                        <i class="fa fa-building"></i>
                        <span>{{__('messages.sidebar_titles.insurance')}}</span>
                    </a>
                </li>
                @endif

                @if($is_super_admin == 1 || in_array('insurance_plans',$authorized_routes))
                <li class="sidebar-dropdown {{in_array($currentRouteName, [
                'home_plan.add_home_plan',
                'home_plan.home_plan',
                'home_plan.edit',
                'office_plan.office_plan',
                'office_plan.add_office_plan',
                'office_plan.edit',
                'life_plan.life_plan',
                'life_plan.add_life_plan',
                'life_plan.edit',
                'critical_illness_plan.critical_illness_plan',
                'critical_illness_plan.add_critical_illness_plan',
                'critical_illness_plan.edit',
                'personal_accident_plan.personal_accident_plan',
                'personal_accident_plan.add_personal_accident_plan',
                'personal_accident_plan.edit',
                'in_patient_plan.in_patient_plan',
                'in_patient_plan.add_in_patient_plan',
                'in_patient_plan.edit',
                'in_out_patient_plan.in_out_patient_plan',
                'in_out_patient_plan.add_in_out_patient_plan',
                'in_out_patient_plan.edit',
                'travel_plan.travel_plan',
                'travel_plan.add_travel_plan',
                'travel_plan.edit',
                'marine_plan.marine_plan',
                'marine_plan.add_marine_plan',
                'marine_plan.edit',
                'dental_plan.dental_plan',
                'dental_plan.add_dental_plan',
                'dental_plan.edit',
                'pet_plan.pet_plan',
                'pet_plan.add_pet_plan',
                'pet_plan.edit',
            ]) ? 'active-main active' : '' }} ">
                    <a href="javascript:void(0)">
                        <i class="far fa-gem"></i>
                        <span>{{__('messages.sidebar_titles.insurance_plans')}}</span>
                    </a>
                    <div class="sidebar-submenu" style="{{in_array($currentRouteName, [
                    'home_plan.add_home_plan',
                    'home_plan.home_plan',
                    'home_plan.edit',
                    'office_plan.office_plan',
                    'office_plan.add_office_plan',
                    'office_plan.edit',
                    'life_plan.life_plan',
                    'life_plan.add_life_plan',
                    'life_plan.edit',
                    'critical_illness_plan.critical_illness_plan',
                    'critical_illness_plan.add_critical_illness_plan',
                    'critical_illness_plan.edit',
                    'personal_accident_plan.personal_accident_plan',
                    'personal_accident_plan.add_personal_accident_plan',
                    'personal_accident_plan.edit',
                    'in_patient_plan.in_patient_plan',
                    'in_patient_plan.add_in_patient_plan',
                    'in_patient_plan.edit',
                    'in_out_patient_plan.in_out_patient_plan',
                    'in_out_patient_plan.add_in_out_patient_plan',
                    'in_out_patient_plan.edit',
                    'travel_plan.travel_plan',
                    'travel_plan.add_travel_plan',
                    'travel_plan.edit',
                    'marine_plan.marine_plan',
                    'marine_plan.add_marine_plan',
                    'marine_plan.edit',
                    'dental_plan.dental_plan',
                    'dental_plan.add_dental_plan',
                    'dental_plan.edit',
                    'pet_plan.pet_plan',
                    'pet_plan.add_pet_plan',
                    'pet_plan.edit',
                ]) ? 'display:block;' : '' }}">
                        <ul>
                            <li class="{{in_array($currentRouteName, [
                                'home_plan.add_home_plan',
                                'home_plan.home_plan',
                                'home_plan.edit'
                                ]) ? 'active-main' : '' }} ">
                                <a href="{{ route('home_plan.home_plan') }}">{{__('messages.sidebar_titles.home_plans')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, [
                                'office_plan.office_plan',
                                'office_plan.add_office_plan',
                                'office_plan.edit',
                                ]) ? 'active-main' : '' }} ">
                                <a href="{{ route('office_plan.office_plan') }}">{{__('messages.sidebar_titles.office_plans')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, [
                                'life_plan.life_plan',
                                'life_plan.add_life_plan',
                                'life_plan.edit',
                                ]) ? 'active-main' : '' }} ">
                                <a href="{{ route('life_plan.life_plan') }}">{{__('messages.sidebar_titles.life_plans')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, [
                                'critical_illness_plan.critical_illness_plan',
                                'critical_illness_plan.add_critical_illness_plan',
                                'critical_illness_plan.edit',
                                ]) ? 'active-main' : '' }} ">
                                <a href="{{ route('critical_illness_plan.critical_illness_plan') }}">{{__('messages.sidebar_titles.critical_illness_plan')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, [
                                'personal_accident_plan.personal_accident_plan',
                                'personal_accident_plan.add_personal_accident_plan',
                                'personal_accident_plan.edit',
                                ]) ? 'active-main' : '' }} ">
                                <a href="{{ route('personal_accident_plan.personal_accident_plan') }}">{{__('messages.sidebar_titles.personal_accident_plan')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, [
                                'in_patient_plan.in_patient_plan',
                                'in_patient_plan.add_in_patient_plan',
                                'in_patient_plan.edit',
                                ]) ? 'active-main' : '' }} ">
                                <a href="{{ route('in_patient_plan.in_patient_plan') }}">{{__('messages.sidebar_titles.in_patient_plan')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, [
                                'in_out_patient_plan.in_out_patient_plan',
                                'in_out_patient_plan.add_in_out_patient_plan',
                                'in_out_patient_plan.edit',
                                ]) ? 'active-main' : '' }} ">
                                <a href="{{ route('in_out_patient_plan.in_out_patient_plan') }}">{{__('messages.sidebar_titles.in_out_patient_plan')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, [
                                'travel_plan.travel_plan',
                                'travel_plan.add_travel_plan',
                                'travel_plan.edit',
                                ]) ? 'active-main' : '' }} ">
                                <a href="{{ route('travel_plan.travel_plan') }}">{{__('messages.sidebar_titles.travel_plan')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, [
                                'marine_plan.marine_plan',
                                'marine_plan.add_marine_plan',
                                'marine_plan.edit',
                                ]) ? 'active-main' : '' }} ">
                                <a href="{{ route('marine_plan.marine_plan') }}">{{__('messages.sidebar_titles.marine_plan')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, [
                                'dental_plan.dental_plan',
                                'dental_plan.add_dental_plan',
                                'dental_plan.edit',
                                ]) ? 'active-main' : '' }} ">
                                <a href="{{ route('dental_plan.dental_plan') }}">{{__('messages.sidebar_titles.dental_plan')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, [
                                'pet_plan.pet_plan',
                                'pet_plan.add_pet_plan',
                                'pet_plan.edit',
                                ]) ? 'active-main' : '' }} ">
                                <a href="{{ route('pet_plan.pet_plan') }}">{{__('messages.sidebar_titles.pet_plan')}}</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-dropdown {{in_array($currentRouteName, ['motor_plan.motor_plan_comprehensive','motor_plan.add_motor_plan_comprehensive','motor_plan.add_motor_plan_compulsory_3_months','motor_plan.motor_plan_compulsory_3_months','motor_plan.add_motor_plan_compulsory_6_months','motor_plan.motor_plan_compulsory_6_months','motor_plan.add_motor_plan_compulsory_9_months','motor_plan.motor_plan_compulsory_9_months','motor_plan.add_motor_plan_compulsory_12_months','motor_plan.motor_plan_compulsory_12_months','motor_plan.add_motor_plan_total_loss','motor_plan.motor_plan_total_loss','motor_plan.edit']) ? 'active-main active' : '' }} ">
                    <a href="javascript:void(0)">
                        <i class="far fa-gem"></i>
                        <span>{{__('messages.sidebar_titles.motor_plans')}}</span>
                    </a>
                    <div class="sidebar-submenu" style="{{in_array($currentRouteName, ['motor_plan.motor_plan_comprehensive','motor_plan.add_motor_plan_comprehensive','motor_plan.add_motor_plan_compulsory_3_months','motor_plan.motor_plan_compulsory_3_months','motor_plan.add_motor_plan_compulsory_6_months','motor_plan.motor_plan_compulsory_6_months','motor_plan.add_motor_plan_compulsory_9_months','motor_plan.motor_plan_compulsory_9_months','motor_plan.add_motor_plan_compulsory_12_months','motor_plan.motor_plan_compulsory_12_months','motor_plan.add_motor_plan_total_loss','motor_plan.motor_plan_total_loss','motor_plan.edit']) ? 'display:block;' : '' }} ">
                        <ul>
                            <li class="{{in_array($currentRouteName, ['motor_plan.motor_plan_comprehensive','motor_plan.add_motor_plan_comprehensive']) ? 'active-main' : '' }}">
                                <a href="{{ route('motor_plan.motor_plan_comprehensive') }}">{{__('messages.sidebar_titles.comprehensive')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, ['motor_plan.motor_plan_compulsory_3_months','motor_plan.add_motor_plan_compulsory_3_months']) ? 'active-main' : '' }}">
                                <a href="{{ route('motor_plan.motor_plan_compulsory_3_months') }}">{{__('messages.sidebar_titles.compulsory_3_months')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, ['motor_plan.motor_plan_compulsory_6_months','motor_plan.add_motor_plan_compulsory_6_months']) ? 'active-main' : '' }}">
                                <a href="{{ route('motor_plan.motor_plan_compulsory_6_months') }}">{{__('messages.sidebar_titles.compulsory_6_months')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, ['motor_plan.motor_plan_compulsory_9_months','motor_plan.add_motor_plan_compulsory_9_months']) ? 'active-main' : '' }}">
                                <a href="{{ route('motor_plan.motor_plan_compulsory_9_months') }}">{{__('messages.sidebar_titles.compulsory_9_months')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, ['motor_plan.motor_plan_compulsory_12_months','motor_plan.add_motor_plan_compulsory_12_months']) ? 'active-main' : '' }}">
                                <a href="{{ route('motor_plan.motor_plan_compulsory_12_months') }}">{{__('messages.sidebar_titles.compulsory_12_months')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, ['motor_plan.motor_plan_total_loss','motor_plan.add_motor_plan_total_loss']) ? 'active-main' : '' }}">
                                <a href="{{ route('motor_plan.motor_plan_total_loss') }}">{{__('messages.sidebar_titles.total_loss')}}</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                @if($is_super_admin == 1 || in_array('coupons',$authorized_routes))
                <li class="{{in_array($currentRouteName, ['coupons','coupons.add','coupons.edit']) ? 'active-main' : '' }}">
                    <a href="{{ route('coupons') }}">
                        <i class="fa fa-share-square"></i>
                        <span>{{__('messages.sidebar_titles.coupons')}}</span>
                    </a>
                </li>
                @endif


                <!-- <li class="sidebar-dropdown {{in_array($currentRouteName, ['active_policies','expired_policies']) ? 'active-main active' : '' }} ">
                    <a href="javascript:void(0)">
                        <i class="far fa-gem"></i>
                        <span>{{__('messages.sidebar_titles.renewal_section')}}</span>
                    </a>
                    <div class="sidebar-submenu" style="{{in_array($currentRouteName, ['active_policies','expired_policies']) ? 'display:block;' : '' }} ">
                        <ul>

                            <li class="{{in_array($currentRouteName, ['active_policies']) ? 'active-main' : '' }}">
                                <a href="{{ route('active_policies') }}">{{__('messages.sidebar_titles.active_policies')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName, ['expired_policies']) ? 'active-main' : '' }}">
                                <a href="{{ route('expired_policies') }}">{{__('messages.sidebar_titles.expired_policies')}}</a>
                            </li>
                        </ul>
                    </div>
                </li> -->

                @if(
    $is_super_admin == 1 ||
    in_array('active_policies', $authorized_routes) ||
    in_array('expired_policies', $authorized_routes)
)
    <li class="sidebar-dropdown {{in_array($currentRouteName, ['active_policies','expired_policies']) ? 'active-main active' : ''}}">
        <a href="javascript:void(0)">
            <i class="far fa-gem"></i>
            <span>{{__('messages.sidebar_titles.renewal_section')}}</span>
        </a>

        <div class="sidebar-submenu" style="{{in_array($currentRouteName, ['active_policies','expired_policies']) ? 'display:block;' : ''}}">
            <ul>

                @if(is_admin_authorized('active_policies'))
                    <li class="{{ $currentRouteName == 'active_policies' ? 'active-main' : '' }}">
                        <a href="{{ route('active_policies') }}">
                            {{__('messages.sidebar_titles.active_policies')}}
                        </a>
                    </li>
                @endif

                @if(is_admin_authorized('expired_policies'))
                    <li class="{{ $currentRouteName == 'expired_policies' ? 'active-main' : '' }}">
                        <a href="{{ route('expired_policies') }}">
                            {{__('messages.sidebar_titles.expired_policies')}}
                        </a>
                    </li>
                @endif

            </ul>
        </div>
    </li>
@endif

                @if($is_super_admin == 1 || in_array('claims',$authorized_routes))
                <li class="{{in_array($currentRouteName, ['claims','claims.add','claims.view']) ? 'active-main' : '' }}">
                    <a href="{{ route('claims') }}">
                        <i class="fa fa-share-square"></i>
                        <span>{{__('messages.sidebar_titles.claims')}}</span>
                    </a>
                </li>
                @endif

                @if($is_super_admin == 1 || in_array('complaints',$authorized_routes) || in_array('complaint_emails',$authorized_routes))
                <li class="sidebar-dropdown {{in_array($currentRouteName, ['complaints','complaint_emails']) ? 'active-main active' : '' }} ">
                    <a href="javascript:void(0)">
                        <i class="far fa-gem"></i>
                        <span>{{__('messages.sidebar_titles.manage_complaints')}}</span>
                    </a>
                    <div class="sidebar-submenu" style="{{in_array($currentRouteName, ['complaint_emails','complaints']) ? 'display:block;' : '' }} ">
                        <ul>
                            @if(is_admin_authorized('complaint_emails'))
                            <li class="{{in_array($currentRouteName, ['complaint_emails']) ? 'active-main' : '' }}">
                                <a href="{{ route('complaint_emails') }}">{{__('messages.sidebar_titles.complaint_emails')}}</a>
                            </li>
                            @endif
                            @if(is_admin_authorized('complaints'))
                            <li class="{{in_array($currentRouteName, ['complaints']) ? 'active-main' : '' }}">
                                <a href="{{ route('complaints') }}">{{__('messages.sidebar_titles.complaints')}}</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if($is_super_admin == 1 || in_array('pages.terms-and-conditions',$authorized_routes))
                <li class="{{$currentRouteName == 'pages.terms-and-conditions' ? 'active-main' : '' }}">
                    <a href="{{ route('pages.terms-and-conditions') }}">
                        <i class="fa fa-user"></i>
                        <span>{{__('messages.sidebar_titles.terms-and-conditions')}}</span>
                    </a>
                </li>
                @endif

                @if($is_super_admin == 1 || in_array('social_media.list',$authorized_routes))
                <li class="{{$currentRouteName == 'social_media.list' ? 'active-main' : '' }}">
                    <a href="{{ route('social_media.list') }}">
                        <i class="fa fa-share-square"></i>
                        <span>{{__('messages.sidebar_titles.social_platforms')}}</span>
                    </a>
                </li>
                @endif

                @if($is_super_admin == 1 || in_array('pages.contact-us',$authorized_routes))
                <li class="{{$currentRouteName == 'pages.contact-us' ? 'active-main' : '' }}">
                    <a href="{{ route('pages.new-chat') }}">
                        <i class="fa fa-phone"></i>
                        <span>{{__('messages.sidebar_titles.contact_messages')}}</span>
                    </a>
                </li>
                @endif
                {{-- @if($is_super_admin == 1 || in_array('pages.new-chat',$authorized_routes)) --}}
                {{-- <li class="{{$currentRouteName == 'pages.new-chat' ? 'active-main' : '' }}">
                    <a href="{{ route('pages.new-chat') }}">
                        <i class="fa fa-phone"></i>
                        <span>new chat</span>
                        <span>{{__('messages.sidebar_titles.new_chat')}}</span>
                    </a>
                </li> --}}
                {{-- @endif --}}
                {{-- @if($is_super_admin == 1 || in_array('pages.contactus',$authorized_routes))
                <li class="{{$currentRouteName == 'pages.contactus' ? 'active-main' : '' }}">
                    <a href="{{ route('pages.contactus') }}">
                        <i class="fa fa-phone"></i>
                        <span>{{__('messages.sidebar_titles.contact_us')}}</span>
                    </a>
                </li>
                @endif --}}
                @if($is_super_admin == 1 || in_array('black_list',$authorized_routes))
                <li class=" {{in_array($currentRouteName, ['black_list','black_list.add','black_list.edit']) ? 'active-main' : '' }} ">
                    <a href="{{ route('black_list') }}">
                        <i class="fa fa-users"></i>
                        <span>{{__('messages.sidebar_titles.black_list')}}</span>
                    </a>
                </li>
                @endif

                    @php
                    $report_routes = [
                        'sold_policy_report.list',
                        'supervisor_report.list',
                        'policy_commission.list',
                        'client_report.list',
                        'policy_renewal_report.list',
                        'travel_policies_report.list',
                        'pets_policies_report.list',
                        'cancelled_by_admin_report.list',
                        'renewed_by_admin_report.list',
                        'expired_without_renewal_report.list',
                        'sold_policies_by_location.list',
                        'claims_report.list',
                        'complaints_report.list',
                    ];
                    $is_authorized_for_any_report = false;
                    foreach($report_routes as $r) {
                        if(is_admin_authorized($r)) {
                            $is_authorized_for_any_report = true;
                            break;
                        }
                    }
                @endphp
                @if($is_super_admin == 1 || $is_authorized_for_any_report)
                <!-- <li class="sidebar-dropdown {{in_array($currentRouteName, ['sold_policy_report.list']) ? 'active-main active' : '' }} "> -->
            <li class="sidebar-dropdown {{in_array($currentRouteName, $report_routes) ? 'active-main active' : '' }} ">
                <a href="javascript:void(0)">
                        <i class="far fa-gem"></i>
                        <span>{{__('messages.reports.reports')}}</span>
                    </a>
                    <!-- <div class="sidebar-submenu" style="{{in_array($currentRouteName, ['sold_policy_report.list','policy_commission.list','policy_renewal_report.list','travel_policies_report.list','pets_policies_report.list','cancelled_by_admin_report.list','renewed_by_admin_report.list','expired_without_renewal_report.list','client_report.list','sold_policies_by_location.list','claims_report.list','complaints_report.list','supervisor_report.list']) ? 'display:block;' : '' }} "> -->
                       <div class="sidebar-submenu" style="{{in_array($currentRouteName, $report_routes) ? 'display:block;' : '' }} ">
                        <ul>
                              @if(is_admin_authorized('sold_policy_report.list'))
                            <li class="{{in_array($currentRouteName, ['sold_policy_report.list']) ? 'active-main' : '' }}">
                                <a href="{{ route('sold_policy_report.list') }}">{{__('messages.reports.sold_policy_report')}}</a>
                            </li>
                            @endif
                            @if(is_admin_authorized('supervisor_report.list'))
                            <li class="{{in_array($currentRouteName, ['supervisor_report.list']) ? 'active-main' : '' }}">
                                <a href="{{ route('supervisor_report.list') }}">{{__('messages.reports.supervisor_report')}}</a>
                            </li>
                            @endif
                            @if(is_admin_authorized('policy_commission.list'))
                            <li class="{{in_array($currentRouteName, ['policy_commission.list']) ? 'active-main' : '' }}">
                                <a href="{{ route('policy_commission.list') }}">{{__('messages.reports.commission')}}</a>
                            </li>
                            @endif
                            @if(is_admin_authorized('client_report.list'))
                            <li class="{{in_array($currentRouteName, ['client_report.list']) ? 'active-main' : '' }}">
                                <a href="{{ route('client_report.list') }}">{{__('messages.reports.client_reports')}}</a>
                            </li>
                             @endif
                            @if(is_admin_authorized('policy_renewal_report.list'))
                            <li class="{{in_array($currentRouteName, ['policy_renewal_report.list']) ? 'active-main' : '' }}">
                                <a href="{{ route('policy_renewal_report.list') }}">{{__('messages.reports.renewal')}}</a>
                            </li>
                             @endif
                            @if(is_admin_authorized('travel_policies_report.list'))
                            <li class="{{in_array($currentRouteName, ['travel_policies_report.list']) ? 'active-main' : '' }}">
                                <a href="{{ route('travel_policies_report.list') }}">{{__('messages.reports.travel_policies')}}</a>
                            </li>
                               @endif
                            @if(is_admin_authorized('pets_policies_report.list'))
                            <li class="{{in_array($currentRouteName, ['pets_policies_report.list']) ? 'active-main' : '' }}">
                                <a href="{{ route('pets_policies_report.list') }}">{{__('messages.reports.pets_policies')}}</a>
                            </li>
                              @endif
                            @if(is_admin_authorized('cancelled_by_admin_report.list'))
                            <li class="{{in_array($currentRouteName, ['cancelled_by_admin_report.list']) ? 'active-main' : '' }}">
                                <a href="{{ route('cancelled_by_admin_report.list') }}">{{__('messages.reports.cancelled_by_admin')}}</a>
                            </li>
                              @endif
                            @if(is_admin_authorized('renewed_by_admin_report.list'))
                            <li class="{{in_array($currentRouteName, ['renewed_by_admin_report.list']) ? 'active-main' : '' }}">
                                <a href="{{ route('renewed_by_admin_report.list') }}">{{__('messages.reports.renewed_by_admin')}}</a>
                            </li>
                              @endif
                            @if(is_admin_authorized('expired_without_renewal_report.list'))
                            <li class="{{in_array($currentRouteName, ['expired_without_renewal_report.list']) ? 'active-main' : '' }}">
                                <a href="{{ route('expired_without_renewal_report.list') }}">{{__('messages.reports.expired_without_renewal')}}</a>
                            </li>
                            @endif
                            @if(is_admin_authorized('sold_policies_by_location.list'))
                            <li class="{{in_array($currentRouteName, ['sold_policies_by_location.list']) ? 'active-main' : '' }}">
                                <a href="{{ route('sold_policies_by_location.list') }}">{{__('messages.sidebar_titles.sold_policies_by_location')}}</a>
                            </li>
                            @endif
                            @if(is_admin_authorized('claims_report.list'))
                            <li class="{{in_array($currentRouteName, ['claims_report.list']) ? 'active-main' : '' }}">
                                <a href="{{ route('claims_report.list') }}">{{__('messages.sidebar_titles.claims_report')}}</a>
                            </li>
                             @endif
                            @if(is_admin_authorized('complaints_report.list'))
                            <li class="{{in_array($currentRouteName, ['complaints_report.list']) ? 'active-main' : '' }}">
                                <a href="{{ route('complaints_report.list') }}">{{__('messages.sidebar_titles.complaints_report')}}</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if($is_super_admin == 1 || in_array('admin_basic',$authorized_routes))
                <li class="sidebar-dropdown {{in_array($currentRouteName, ['ages.list','chronic_disease.list','claim_status.list','complaint_status.list','dangerous_activities.list','engine_capacity.list','engine_type.list','insurance_period.list','medical_network.list','motor_plan.list','protection_system.list','in_patient_deductible.list','out_patient_deductible.list','no_of_visits.list','country.list','district.list','occupations.list','language.list','nationality.list','currency.list','geographical_area.list','claim_deductible.list','cities.list','pages.vehicle-brand','pages.vehicle-categories','pages.vehicle-color','pages.vehicle-type','type_of_covers.list','insured-items-categories.list','insured_item_sub_categories.list','banner.list']) ? 'active-main active' : '' }} ">
                    <a href="javascript:void(0)">
                        <i class="far fa-gem"></i>
                        <span>{{__('messages.sidebar_titles.admin_basic')}}</span>
                    </a>
                    <div class="sidebar-submenu" style="{{in_array($currentRouteName, ['ages.list','chronic_disease.list','claim_status.list','complaint_status.list','dangerous_activities.list','engine_capacity.list','engine_type.list','insurance_period.list','medical_network.list','motor_plan.list','protection_system.list','in_patient_deductible.list','out_patient_deductible.list','no_of_visits.list','country.list','district.list','occupations.list','language.list','nationality.list','currency.list','geographical_area.list','claim_deductible.list','cities.list','pages.vehicle-brand','pages.vehicle-categories','pages.vehicle-color','pages.vehicle-type','type_of_covers.list','insured-items-categories.list','insured_item_sub_categories.list','banner.list']) ? 'display:block;' : '' }}">
                        <ul>
                            <li class="{{in_array($currentRouteName,['banner.list']) ? 'active-main' : ''}}">
                                <a href="{{ route('banner.list') }}">{{__('messages.sidebar_titles.banner_management')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName,['ages.list']) ? 'active-main' : ''}}">
                                <a href="{{ route('ages.list') }}">{{__('messages.sidebar_titles.age')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName,['chronic_disease.list']) ? 'active-main' : ''}}">
                                <a href="{{ route('chronic_disease.list') }}">{{__('messages.sidebar_titles.chronic_disease')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName,['claim_status.list']) ? 'active-main' : ''}}">
                                <a href="{{ route('claim_status.list') }}">{{__('messages.sidebar_titles.claim_status')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName,['complaint_status.list']) ? 'active-main' : ''}}">
                                <a href="{{ route('complaint_status.list') }}">{{__('messages.sidebar_titles.complaint_status')}}</a>
                            </li>
                            <li class="{{in_array($currentRouteName,['dangerous_activities.list']) ? 'active-main' : ''}}">
                                <a href="{{ route('dangerous_activities.list') }}">{{__('messages.sidebar_titles.dangerous_activities')}}</a>
                            </li>
                            {{-- <li class="{{in_array($currentRouteName,['engine_capacity.list']) ? 'active-main' : ''}}">
                            <a href="{{ route('engine_capacity.list') }}">{{__('messages.sidebar_titles.engine_capacity')}}</a>
                </li> --}}
                @php
                $motorRoutes = ['engine_capacity.list', 'engine_type.list', 'motor_plan.list','pages.vehicle-categories','pages.vehicle-brand','pages.vehicle-color','pages.vehicle-type'];
                $marineRoutes = ['type_of_covers.list','insured-items-categories.list','insured_item_sub_categories.list'];
                @endphp

                {{-- MOTOR --}}
                <li class="inner-dropdown {{ in_array($currentRouteName, $motorRoutes) ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        {{ __('messages.sidebar_titles.motor') }} <i class="fa fa-angle-right toggle-arrow {{ in_array($currentRouteName, $motorRoutes) ? 'rotate-90' : '' }}"></i>
                    </a>
                    <ul class="inner-submenu" style="display: {{ in_array($currentRouteName, $motorRoutes) ? 'block' : 'none' }};">
                        <li class="{{ $currentRouteName == 'engine_capacity.list' ? 'active-sub' : '' }}">
                            <a href="{{ route('engine_capacity.list') }}">{{ __('messages.sidebar_titles.engine_capacity') }}</a>
                        </li>
                        <li class="{{ $currentRouteName == 'engine_type.list' ? 'active-sub' : '' }}">
                            <a href="{{ route('engine_type.list') }}">{{ __('messages.sidebar_titles.engine_type') }}</a>
                        </li>
                        <li class="{{ $currentRouteName == 'motor_plan.list' ? 'active-sub' : '' }}">
                            <a href="{{ route('motor_plan.list') }}">{{ __('messages.sidebar_titles.motor_plan') }}</a>
                        </li>
                        <li class="{{ $currentRouteName == 'pages.vehicle-brand' ? 'active-sub' : '' }}">
                            <a href="{{ route('pages.vehicle-brand') }}">{{ __('messages.sidebar_titles.vehicle_brand') }}</a>
                        </li>
                        <li class="{{ $currentRouteName == 'pages.vehicle-categories' ? 'active-sub' : '' }}">
                            <a href="{{ route('pages.vehicle-categories') }}">{{ __('messages.sidebar_titles.vehicle_category') }}</a>
                        </li>
                        <li class="{{ $currentRouteName == 'pages.vehicle-color' ? 'active-sub' : '' }}">
                            <a href="{{ route('pages.vehicle-color') }}">{{ __('messages.sidebar_titles.vehicle_color') }}</a>
                        </li>
                        <li class="{{ $currentRouteName == 'pages.vehicle-type' ? 'active-sub' : '' }}">
                            <a href="{{ route('pages.vehicle-type') }}">{{ __('messages.sidebar_titles.vehicle_type') }}</a>
                        </li>
                        <li class="{{$currentRouteName=='claim_deductible.list' ? 'active-sub' : ''}}">
                            <a href="{{ route('claim_deductible.list') }}">{{__('messages.sidebar_titles.claim_deductible')}}</a>
                        </li>
                    </ul>
                </li>

                {{-- PERSONAL --}}
                <li class="inner-dropdown {{ $currentRouteName == 'insurance_period.list' ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        {{ __('messages.sidebar_titles.personal') }} <i class="fa fa-angle-right toggle-arrow {{ $currentRouteName == 'insurance_period.list' ? 'rotate-90' : '' }}" style="float:right;"></i>
                    </a>
                    <ul class="inner-submenu" style="display: {{ $currentRouteName == 'insurance_period.list' ? 'block' : 'none' }};">
                        <li class="{{ $currentRouteName == 'insurance_period.list' ? 'active-sub' : '' }}">
                            <a href="{{ route('insurance_period.list') }}">{{ __('messages.sidebar_titles.insurance_period') }}</a>
                        </li>
                    </ul>
                </li>

                {{-- MEDICAL --}}
                <li class="inner-dropdown {{ $currentRouteName == 'medical_network.list' ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        {{ __('messages.sidebar_titles.medical') }} <i class="fa fa-angle-right toggle-arrow {{ $currentRouteName == 'medical_network.list' ? 'rotate-90' : '' }}" style="float:right;"></i>
                    </a>
                    <ul class="inner-submenu" style="display: {{ $currentRouteName == 'medical_network.list' ? 'block' : 'none' }};">
                        <li class="{{ $currentRouteName == 'medical_network.list' ? 'active-sub' : '' }}">
                            <a href="{{ route('medical_network.list') }}">{{ __('messages.sidebar_titles.medical_network') }}</a>
                        </li>
                        <li class="{{$currentRouteName=='in_patient_deductible.list' ? 'active-sub' : ''}}">
                            <a href="{{ route('in_patient_deductible.list') }}">{{__('messages.sidebar_titles.in_patient_deductible')}}</a>
                        </li>
                        <li class="{{$currentRouteName=='out_patient_deductible.list' ? 'active-sub' : ''}}">
                            <a href="{{ route('out_patient_deductible.list') }}">{{__('messages.sidebar_titles.out_patient_deductible')}}</a>
                        </li>
                        <li class="{{$currentRouteName=='no_of_visits.list' ? 'active-sub' : ''}}">
                            <a href="{{ route('no_of_visits.list') }}">{{__('messages.sidebar_titles.no_of_visits')}}</a>
                        </li>
                    </ul>
                </li>

                {{-- HOME & OFFICE --}}
                <li class="inner-dropdown {{ $currentRouteName == 'protection_system.list' ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        {{ __('messages.sidebar_titles.homeandoffice') }} <i class="fa fa-angle-right toggle-arrow {{ $currentRouteName == 'protection_system.list' ? 'rotate-90' : '' }}" style="float:right;"></i>
                    </a>
                    <ul class="inner-submenu" style="display: {{ $currentRouteName == 'protection_system.list' ? 'block' : 'none' }};">
                        <li class="{{ $currentRouteName == 'protection_system.list' ? 'active-sub' : '' }}">
                            <a href="{{ route('protection_system.list') }}">{{ __('messages.sidebar_titles.protection_system') }}</a>
                        </li>
                    </ul>
                </li>

                {{-- TRAVEL --}}
                <li class="inner-dropdown {{ $currentRouteName == 'geographical_area.list' ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        {{ __('messages.sidebar_titles.travel') }} <i class="fa fa-angle-right toggle-arrow {{ $currentRouteName == 'geographical_area.list' ? 'rotate-90' : '' }}" style="float:right;"></i>
                    </a>
                    <ul class="inner-submenu" style="display: {{ $currentRouteName == 'geographical_area.list' ? 'block' : 'none' }};">
                        <li class="{{ $currentRouteName == 'geographical_area.list' ? 'active-sub' : '' }}">
                            <a href="{{ route('geographical_area.list') }}">{{ __('messages.sidebar_titles.geographical_area') }}</a>
                        </li>
                    </ul>
                </li>

                {{-- MARINE --}}
                <li class="inner-dropdown {{ in_array($currentRouteName, $marineRoutes) ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        {{ __('messages.sidebar_titles.marine') }} <i class="fa fa-angle-right toggle-arrow {{ in_array($currentRouteName, $marineRoutes) ? 'rotate-90' : '' }}" style="float:right;"></i>
                    </a>
                    <ul class="inner-submenu" style="display: {{ in_array($currentRouteName, $marineRoutes) ? 'block' : 'none' }};">
                        <li class="{{ $currentRouteName == 'type_of_covers.list' ? 'active-sub' : '' }}">
                            <a href="{{ route('type_of_covers.list') }}">{{ __('messages.sidebar_titles.type_of_cover') }}</a>
                        </li>
                        <li class="{{ $currentRouteName == 'insured-items-categories.list' ? 'active-sub' : '' }}">
                            <a href="{{ route('insured-items-categories.list') }}">{{ __('messages.sidebar_titles.insured_item_category') }}</a>
                        </li>
                        <li class="{{ $currentRouteName == 'insured_item_sub_categories.list' ? 'active-sub' : '' }}">
                            <a href="{{ route('insured_item_sub_categories.list') }}">{{ __('messages.sidebar_titles.insured_item_sub_category') }}</a>
                        </li>
                    </ul>
                </li>

                {{-- PETS --}}
                @php $petRoutes = ['pet_breed.list']; @endphp
                <li class="inner-dropdown {{ in_array($currentRouteName, $petRoutes) ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        {{ __('messages.sidebar_titles.pets') }} <i class="fa fa-angle-right toggle-arrow {{ in_array($currentRouteName, $petRoutes) ? 'rotate-90' : '' }}" style="float:right;"></i>
                    </a>
                    <ul class="inner-submenu" style="display: {{ in_array($currentRouteName, $petRoutes) ? 'block' : 'none' }};">
                        <li class="{{ $currentRouteName == 'pet_breed.list' ? 'active-sub' : '' }}">
                            <a href="{{ route('pet_breed.list') }}">{{ __('messages.sidebar_titles.pet_breeds') }}</a>
                        </li>
                    </ul>
                </li>
                {{-- <li class="{{in_array($currentRouteName,['engine_type.list']) ? 'active-main' : ''}}">
                <a href="{{ route('engine_type.list') }}">{{__('messages.sidebar_titles.engine_type')}}</a>
                </li> --}}
                {{-- <li class="{{in_array($currentRouteName,['insurance_period.list']) ? 'active-main' : ''}}">
                <a href="{{ route('insurance_period.list') }}">{{__('messages.sidebar_titles.insurance_period')}}</a>
                </li> --}}
                {{-- <li class="{{in_array($currentRouteName,['medical_network.list']) ? 'active-main' : ''}}">
                <a href="{{ route('medical_network.list') }}">{{__('messages.sidebar_titles.medical_network')}}</a>
                </li> --}}
                {{-- <li class="{{in_array($currentRouteName,['motor_plan.list']) ? 'active-main' : ''}}">
                <a href="{{ route('motor_plan.list') }}">{{__('messages.sidebar_titles.motor_plan')}}</a>
                </li> --}}

                {{-- <li class="{{in_array($currentRouteName,['protection_system.list']) ? 'active-main' : ''}}">
                <a href="{{ route('protection_system.list') }}">{{__('messages.sidebar_titles.protection_system')}}</a>
                </li> --}}
                <li class="{{in_array($currentRouteName,['cities.list']) ? 'active-main' : ''}}">
                    <a href="{{ route('cities.list') }}">{{__('messages.sidebar_titles.cities')}}</a>
                </li>
                <li class="{{in_array($currentRouteName,['country.list']) ? 'active-main' : ''}}">
                    <a href="{{ route('country.list') }}">{{__('messages.sidebar_titles.country')}}</a>
                </li>
                <li class="{{in_array($currentRouteName,['district.list']) ? 'active-main' : ''}}">
                    <a href="{{ route('district.list') }}">{{__('messages.sidebar_titles.district')}}</a>
                </li>
                <li class="{{in_array($currentRouteName,['occupations.list']) ? 'active-main' : ''}}">
                    <a href="{{ route('occupations.list') }}">{{__('messages.sidebar_titles.occupations')}}</a>
                </li>
                <li class="{{in_array($currentRouteName,['language.list']) ? 'active-main' : ''}}">
                    <a href="{{ route('language.list') }}">{{__('messages.sidebar_titles.language')}}</a>
                </li>

                <li class="{{in_array($currentRouteName,['nationality.list']) ? 'active-main' : ''}}">
                    <a href="{{ route('nationality.list') }}">{{__('messages.sidebar_titles.nationality')}}</a>
                </li>
                <li class="{{in_array($currentRouteName,['currency.list']) ? 'active-main' : ''}}">
                    <a href="{{ route('currency.list') }}">{{__('messages.sidebar_titles.currency')}}</a>
                </li>
                 <li class="{{in_array($currentRouteName,['geographical_area.list']) ? 'active-main' : ''}}">
                <a href="{{ route('geographical_area.list') }}">{{__('messages.sidebar_titles.geographical_area')}}</a>
                </li>
            {{-- <li class="{{in_array($currentRouteName,['type_of_covers.list']) ? 'active-main' : ''}}">
                <a href="{{ route('type_of_covers.list') }}">{{__('messages.sidebar_titles.type_of_cover')}}</a>
                </li>
                <li class="{{in_array($currentRouteName,['insured-items-categories.list']) ? 'active-main' : ''}}">
                    <a href="{{ route('insured-items-categories.list') }}">{{__('messages.sidebar_titles.insured_item_category')}}</a>
                </li>
                <li class="{{in_array($currentRouteName,['insured_item_sub_categories.list']) ? 'active-main' : ''}}">
                    <a href="{{ route('insured_item_sub_categories.list') }}">{{__('messages.sidebar_titles.insured_item_sub_category')}}</a>
                </li> --}}
            </ul>
        </div>
        </li>
        @endif
        </ul>
    </div>
    <!-- sidebar-menu  -->
    </div>
</nav>
