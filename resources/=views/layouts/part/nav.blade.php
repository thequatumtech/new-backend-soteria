<nav class="d-flex justify-content-between align-items-center px-2 px-md-4" style="padding-bottom: 0.75rem; padding-top: 0.75rem;">
    <a id="show-sidebar" class="btn btn-sm btn-dark" href="javascript:void(0)">
        <i class="fas fa-bars"></i>
    </a>
    <div class="title">
        Dashboard/Main Page
    </div>
    <div class="d-flex gap-3">
        <div>
            <button class="btn border border-2 circle">
                <img src="{{asset('img/notification.png')}}" alt="" class="img-fluid">
            </button>
        </div>
        <div>
{{--            <button class="btn border profile circle"></button>--}}
            <a href="javascript:void(0);" class="profile-btn">
                @if(auth()->user()->profile_pic)
                    <img class="border circle"
                         src="{{asset('uploads/admins').'/'.auth()->id().'/'.auth()->user()->profile_pic}}" alt="profile">
                @else
                    <img class="border circle" src="{{asset('img/myAvatar.png')}}" alt="profile">
                @endif
            </a>
        </div>
        <div>
            @if(auth()->guard('admin')->check())
            <p>Welcome, {{ !empty(trim(auth()->guard('admin')->user()->full_name)) ? auth()->user()->full_name : 'Admin'  }}</p>
            <a href="javascript:void(0);" class="logoutbtn">Logout</a>
            @endif
        </div>
    </div>
</nav>
<!-- START LOGOUT -->
<div class="modal fade " id="LogoutModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">{{__('messages.titles.logout')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <div class="modal-body">
                <div class="row p-3" style="color: #92959A;">
                    <div class="container">
                        <div class="row pt-5">
                            <div class="col-12 col-lg-8">
                                <h4>{{__('messages.titles.logout_confirmation')}}</h4>
                            </div>
                            <div class="col-12 col-lg-2">
                                <div class="pt-4 " style="border: none;">
                                    <a href="{{route('adminLogout')}}" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">{{__('messages.titles.yes')}}</a>
                                </div>
                            </div>
                            <div class="col-12 col-lg-2">
                                <div class="pt-4 " style="border: none;">
                                    <a data-bs-dismiss="modal" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">{{__('messages.titles.no')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END LOGOUT -->
