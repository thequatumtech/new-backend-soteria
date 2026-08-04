@extends('layouts.mainlayout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/agent.css')}}">
    <link rel="stylesheet" href="{{asset('css/supervisor.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <main class="flex-grow-1 pt-5">
        <div class="container-fluid">
            <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
                <div class="text-white group-title fw-bold">{{__('messages.page_titles.manage_social_media')}}</div>
                {{--
                            <button data-bs-target="#addnewsupervisor" data-bs-toggle="modal" class="btn pe-0">
                                <img src="{{asset('img/icon-add.png')}}" alt="">
                            </button>
                --}}
            </div>
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3">
                <form action="{{route('social_media.create')}}" method="post">
                    @csrf
                    <div class="modal-body px-1 px-md-3">
                        <div class="container d-flex flex-column gap-4" style="color: #92959A;">
                            <div class="row g-0 gap-4">
                                <div class="col-12 col-lg input-active">
                                    <div>{{__('messages.titles.platform')}}</div>
                                </div>
                                <div class="col-12 col-lg input-active">
                                    <div>{{__('messages.titles.url')}}</div>
                                </div>
                            </div>
                            <div class="social_media_input">
                            @forelse($social_media_data as $single)
                                <div class="row g-0 gap-4" id="div_{{$loop->index}}">
                                    <div class="col-12 col-lg input-active">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input class="form-control" type="text" name="social_media[{{$loop->index}}][platform]" id="social_media[{{$loop->index}}][platform]" value="{{$single->platform}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg d-flex flex-column input-active">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input class="form-control" type="url" name="social_media[{{$loop->index}}][url]" id="social_media[{{$loop->index}}][url]" value="{{$single->url}}">
                                        </div>
                                    </div>
                                    <div class="col-1 d-flex flex-column">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <button class="btn pe-0 align-left delete-btn" type="button" data-id="{{$loop->index}}">
                                                <img src="{{asset('img/icon-delete.png')}}" alt="">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="row g-0 gap-4" id="div_0">
                                    <div class="col-12 col-lg input-active">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input class="form-control" type="text" name="social_media[0][platform]" id="social_media[0][platform]" placeholder="{{__('messages.placeholders.platform')}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg d-flex flex-column input-active">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input class="form-control" type="url" name="social_media[0][url]" id="social_media[0][url]"  placeholder="{{__('messages.placeholders.url')}}">
                                        </div>
                                    </div>
                                    <div class="col-1 d-flex flex-column">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <button class="btn pe-0 align-left delete-btn" type="button" data-id="0">
                                                <img src="{{asset('img/icon-delete.png')}}" alt="">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                            </div>
                            <div class="row g-0 gap-4">
                                <button class="btn pe-0 align-center btn-custom" type="button" id="add_more">
                                    <img src="{{asset('img/icon-add.png')}}" alt="">{{__('messages.titles.add_more')}}
                                </button>
                            </div>
                            <div class="col-12 col-lg-3 ms-auto">
                                <button data-bs-target="#notif" data-bs-toggle="modal" type="submit"
                                        class="btn rounded-1 w-100 text-white opacity-50 p-2"
                                        style="background-color: #EF7C00;">{{__('messages.titles.save')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            let initial_count = parseInt('{{$social_media_data->count()}}');
            if(initial_count == 0){
                initial_count = 1;
            }
            $(document).on('click', '.delete-btn', function () {
                // alert($(this).data('id'));
                $('#div_'+$(this).data('id')).remove();
            });
            $(document).on('click', '#add_more', function () {
                $('.social_media_input').append(
                    `<div class="row g-0 gap-4" id="div_`+initial_count+`">
                        <div class="col-12 col-lg input-active">
                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                <input class="form-control" type="text" name="social_media[`+initial_count+`][platform]" id="social_media[`+initial_count+`][platform]" placeholder="`+`{{__('messages.placeholders.platform')}}`+`">
                            </div>
                        </div>
                        <div class="col-12 col-lg d-flex flex-column input-active">
                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                <input class="form-control" type="url" name="social_media[`+initial_count+`][url]" id="social_media[`+initial_count+`][url]" placeholder="`+`{{__('messages.placeholders.url')}}`+`">
                            </div>
                        </div>
                        <div class="col-1 d-flex flex-column">
                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                <button class="btn pe-0 align-left delete-btn" type="button" data-id="`+initial_count+`">
                                    <img src="{{asset('img/icon-delete.png')}}" alt="">
                                </button>
                            </div>
                        </div>

                    </div>`
                );
                initial_count++;
            });
        });
    </script>
    <script>
        const toastTrigger = document.getElementById('successToastBtn')
        const toastLiveExample = document.getElementById('successToast')
        if (toastTrigger) {
            toastTrigger.addEventListener('click', () => {
                const toast = new bootstrap.Toast(toastLiveExample)

                toast.show()
            })
        }
    </script>
    <script>
    </script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
@endsection