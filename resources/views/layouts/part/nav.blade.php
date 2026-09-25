<nav class="d-flex justify-content-between align-items-center px-2 px-md-4" style="padding-bottom: 0.75rem; padding-top: 0.75rem;">
    <a id="show-sidebar" class="btn btn-sm btn-dark" href="javascript:void(0)">
        <i class="fas fa-bars"></i>
    </a>
    <div class="title">
        {{__('messages.sidebar_titles.main_page')}}
    </div>
    <div class="d-flex gap-3">
        <div class="dropdown">
            <button class="btn border border-2 circle position-relative" id="notificationBtn" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{asset('img/notification.png')}}" alt="" class="img-fluid">
                <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none;">
                    0
                </span>
            </button>

            <div class="dropdown-menu dropdown-menu-end p-0" style="width: 340px; max-height: 420px; overflow-y: auto;" aria-labelledby="notificationBtn">
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <strong>Notifications</strong>
                    <button id="markAllReadBtn" class="btn btn-sm btn-link p-0">Mark all read</button>
                </div>
                <div id="notifList">
                    <div class="text-center text-muted p-3">Loading...</div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const badge = document.getElementById('notifBadge');
                const list = document.getElementById('notifList');
                const dropdownBtn = document.getElementById('notificationBtn');
                const markAllBtn = document.getElementById('markAllReadBtn');

                function fetchUnreadCount() {
                    fetch('/admin/notifications/unread-count', {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.unread_count > 0) {
                            badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                            badge.style.display = 'inline-block';
                        } else {
                            badge.style.display = 'none';
                        }
                    });
                }

                function fetchNotifications() {
                    list.innerHTML = '<div class="text-center text-muted p-3">Loading...</div>';

                    fetch('/admin/notifications', {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        const notifications = data.data || [];

                        if (notifications.length === 0) {
                            list.innerHTML = '<div class="text-center text-muted p-3">No notifications</div>';
                            return;
                        }

                        list.innerHTML = notifications.map(n => `
                            <div class="notif-item px-3 py-2 border-bottom ${n.read_at ? '' : 'bg-light'}" 
                                data-id="${n.id}" style="cursor:pointer;">
                                <div class="fw-semibold small">${n.title}</div>
                                <div class="small text-muted">${n.body}</div>
                                <div class="small text-muted">${new Date(n.created_at).toLocaleString()}</div>
                            </div>
                        `).join('');

                        document.querySelectorAll('.notif-item').forEach(item => {
                            item.addEventListener('click', function () {
                                const id = this.getAttribute('data-id');
                                markAsRead(id, this);
                            });
                        });
                    });
                }

                function markAsRead(id, el) {
                    fetch(`/admin/notifications/${id}/read`, {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(() => {
                        if (el) el.classList.remove('bg-light');
                        fetchUnreadCount();
                    });
                }

                markAllBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    fetch('/admin/notifications/read-all', {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(() => {
                        fetchUnreadCount();
                        fetchNotifications();
                    });
                });

                dropdownBtn.addEventListener('click', fetchNotifications);

                fetchUnreadCount();
                setInterval(fetchUnreadCount, 15000); // 15s poll for badge count
            });
        </script>
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
            <p>{{__('messages.sidebar_titles.welcome')}}, {{ !empty(trim(auth()->guard('admin')->user()->full_name)) ? auth()->user()->full_name : 'Admin'  }}</p>
            <a href="javascript:void(0);" class="logoutbtn">{{__('messages.sidebar_titles.logout')}}</a>
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
