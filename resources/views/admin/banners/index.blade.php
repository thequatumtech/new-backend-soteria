@extends('layouts.mainlayout')
@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">Banners</div>
            <button data-bs-toggle="modal" data-bs-target="#addage" class="btn pe-0">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </button>
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
            <table id="example" class="table table-striped " style="width:100%">
                <thead>
                    <tr>

                        <th class="no-show">Sr.</th>
                        <th width="40%"> Banner Title & Link</th>
                        <th width="20%"> Image</th>
                        <th width="20%"> Status</th>
                        <th class="no-order" width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $key => $banner)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <div>{{ $banner->title }}</div>
                            <div class="text-muted">{{ $banner->redirect_url }}</div>
                        </td>
                       <td>
                            @if($banner->image)
                                @php
                                    $extension = strtolower(pathinfo($banner->image, PATHINFO_EXTENSION));
                                    $videoExtensions = ['mp4', 'webm', 'ogg', 'mov'];
                                @endphp

                                @if(in_array($extension, $videoExtensions))
                                    <video width="120" height="80" controls>
                                        <source src="{{ asset($banner->image) }}" type="video/{{ $extension }}">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <img src="{{ asset($banner->image) }}" width="120">
                                @endif
                            @else
                                No Media
                            @endif
                        </td>
                        <td>
                            @if($banner->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                <button class="btn p-0 m-0 editbtn" value="{{$banner->id}}" data-val="{{$banner->title}}" data-link="{{ $banner->redirect_url }}" data-image="{{ asset($banner->image) }}" data-status="{{ $banner->is_active }}" data-bs-toggle="modal" data-bs-target="#editModal">
                                    <img src="{{asset('img/icon-edit.png')}}" alt="">
                                </button>
                                <button class="btn p-0 m-0 deletebtn" value="{{$banner->id}}"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end mt-3">
                {{ $banners->links() }}
            </div>
        </div>
    </div>
</main>
<div class="modal fade " id="addage" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">Add Banner</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <form action="{{route('banner.create')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-4 pt-2">
                                    <div>Banner Title</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="name" style="border: none;" maxlength="50" required>
                                    </div>
                                </div>
                               <div class="col-12 col-lg-4 pt-2">
                                    <div>Banner Upload</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input
                                            type="file"
                                            name="image"
                                            style="border: none;"
                                            accept="image/*,video/*"
                                            required
                                        >
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 pt-2">
                                    <div> Banner Link</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" id="add_link" name="link" style="border: none;" maxlength="200" required>
                                    </div>
                                    <div id="add_link_error" class="text-danger mt-1" style="display:none; font-size:12px;">
                                        Link must start with http:// or https://
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;"> {{__('messages.age.add')}} </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<div class="modal fade " id="editModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> Edit Banner</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <form action="{{route('banner.update')}}" method="post" enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-2">
                                    <div>Banner Title</div>
                                    <input type="hidden" id="cd_id" name="cd_id" style="border: none;" value="">
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" id="name_edit" name="name" style="border: none;" maxlength="50" required>
                                    </div>
                                </div>
                               {{-- <div class="col-12 col-lg-4 pt-2">
                                    <div>Banner Upload</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input
                                            type="file"
                                            name="image"
                                            style="border: none;"
                                            accept="image/*,video/*"
                                            required
                                        >
                                    </div>
                                </div> --}}
                                <div class="col-12 col-lg-4 pt-2">
                                    <div>Banner Upload</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input
                                            type="file"
                                            id="image_edit_input"
                                            name="image"
                                            style="border: none;"
                                            accept="image/*,video/*"
                                        >

                                        <div class="mt-2">
                                            <img
                                                id="banner_edit"
                                                src=""
                                                width="120"
                                                style="display:none;"
                                            >

                                            <video
                                                id="banner_video_edit"
                                                width="200"
                                                controls
                                                style="display:none;"
                                            >
                                                <source id="banner_video_source" src="">
                                            </video>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Status </div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <select id="status_edit" name="status" class="form-control" style="border: none;">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Banner Link </div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" id="link_edit" name="link" style="border: none;" maxlength="200" required>
                                    </div>
                                    <div id="edit_link_error" class="text-danger mt-1" style="display:none; font-size:12px;">
                                        Link must start with http:// or https://
                                    </div>
                                </div>
                            </div>

                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;"> {{__('messages.age.update')}} </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> {{__('messages.currency.delete_currency')}} </h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <form action="{{route('banner.destroy')}}" method="post">

                @csrf
                @method('delete')
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9">
                                    <h4> {{__('messages.table_headers.confirm_to_delete_data')}} </h4>
                                    <input type="hidden" id="deleteing_id" name="banner_id">
                                </div>
                            </div>
                            <div class="col-12 col-lg-3">
                                <div class="pt-4 " style="border: none;">
                                    <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;"> {{__('messages.table_headers.yes_delete')}} </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    function isValidUrl(url) {
        return url.startsWith('http://') || url.startsWith('https://');
    }

    $('#add_link').on('input', function() {
        var val = $(this).val().trim();
        if (val !== '' && !isValidUrl(val)) {
            $('#add_link_error').show();
        } else {
            $('#add_link_error').hide();
        }
    });

    $('#addage').find('form').on('submit', function(e) {
        var link = $('#add_link').val().trim();
        if (link !== '' && !isValidUrl(link)) {
            e.preventDefault();
            $('#add_link_error').show();
            return false;
        }
    });

    $('#link_edit').on('input', function() {
        var val = $(this).val().trim();
        if (val !== '' && !isValidUrl(val)) {
            $('#edit_link_error').show();
        } else {
            $('#edit_link_error').hide();
        }
    });

    $('#editModal').find('form').on('submit', function(e) {
        var link = $('#link_edit').val().trim();
        if (link !== '' && !isValidUrl(link)) {
            e.preventDefault();
            $('#edit_link_error').show();
            return false;
        }
    });

    // $('#image_edit_input').on('change', function() {
    //     var file = this.files[0];
    //     if (file) {
    //         var reader = new FileReader();
    //         reader.onload = function(e) {
    //             $('#banner_edit').attr('src', e.target.result).show();
    //         };
    //         reader.readAsDataURL(file);
    //     }
    // });

    $('#image_edit_input').on('change', function() {
    var file = this.files[0];

        if (!file) {
            return;
        }

        $('#banner_edit').hide();
        $('#banner_video_edit').hide();

        if (file.type.startsWith('video/')) {
            var videoUrl = URL.createObjectURL(file);

            $('#banner_video_source').attr('src', videoUrl);
            $('#banner_video_edit').show();

            $('#banner_video_edit')[0].load();
        } else if (file.type.startsWith('image/')) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#banner_edit').attr('src', e.target.result).show();
            };

            reader.readAsDataURL(file);
        }
    });
    // $(document).on('click', '.editbtn', function() {
    //     var cd_id = $(this).val();
    //     var name = $(this).data('val');
    //     var image = $(this).data('image');
    //     var status = $(this).data('status') || 0;
    //     var link = $(this).data('link') || '';

    //     $("#cd_id").val(cd_id);
    //     $("#name_edit").val(name);
    //     $("#link_edit").val(link);
    //     $('#edit_link_error').hide();

    //     if (image) {
    //         $("#banner_edit").attr('src', image).show();
    //     } else {
    //         $("#banner_edit").hide();
    //     }

    //     $("#status_edit").val(status).trigger('change');
    //     $('#editModal').modal('show');
    // });

    $(document).on('click', '.editbtn', function() {
    var cd_id = $(this).val();
    var name = $(this).data('val');
    var image = $(this).data('image');
    var status = $(this).data('status') || 0;
    var link = $(this).data('link') || '';

    $("#cd_id").val(cd_id);
    $("#name_edit").val(name);
    $("#link_edit").val(link);
    $('#edit_link_error').hide();

    // Reset the file input so a stale selection isn't carried over
    $('#image_edit_input').val('');

    // Hide both previews first
    $('#banner_edit').hide();
    $('#banner_video_edit').hide();

    if (image) {
        var ext = image.split('.').pop().toLowerCase().split('?')[0];
        var videoExtensions = ['mp4', 'webm', 'ogg', 'mov'];

        if (videoExtensions.includes(ext)) {
            $('#banner_video_source').attr('src', image);
            $('#banner_video_edit').show();
            $('#banner_video_edit')[0].load();
        } else {
            $('#banner_edit').attr('src', image).show();
        }
    }

    $("#status_edit").val(status).trigger('change');
    $('#editModal').modal('show');
});
    $(document).on('click', '.deletebtn', function() {
        var banner_id = $(this).val();
        $('#DeleteModal').modal('show');
        $('#deleteing_id').val(banner_id);
    });
</script>
@endsection