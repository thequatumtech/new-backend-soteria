@extends('layouts.mainlayout')
@section('style')
<link rel="stylesheet" href="{{asset('css/agent.css')}}">
<style>
    tr {
        cursor: pointer;
    }

    td {
        word-wrap: break-word;
        word-break: break-word;
    }
</style>
@endsection

@section('content')
<main class="flex-grow-1 pt-3">
    <div class="container-fluid" style="box-shadow: 1px 7px 21px -9px rgba(0, 0, 0, 0.25); border-radius: 10px 10px 0px 0px;">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">Renewal Section</div>
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3">
            <div class="d-flex gap-3 flex-wrap justify-content-center">
                <div class="d-flex align-items-center gap-3 box flex-grow-1 w-auto p-3">
                    <img src="{{asset('img/icon-search.png')}}" alt="">
                    <input type="text" class="border-0 w-100" placeholder="Search Plans by name, date, company etc.">
                </div>
                <div class="box p-3 d-flex align-items-center gap-2 text-dark" style="min-width: max-content;">
                    <img src="{{asset('img/icon-filter.png')}}" alt="">
                    Filters
                </div>
                <div class="" style="min-width: fit-content; ">
                    <button class="btn h-100 px-4" style="border-radius: 5px;
                                border: 0.5px solid #FFE7A6;
                                background: #FFE7A6;font-size: 1.25rem;" data-bs-target="#writeMsg" data-bs-toggle="modal">Notify All</button>
                </div>
            </div>
            <div class="table-responsive pt-3 rounded">
                <table class="table table-striped table-bordered border rounded rounded-2">
                    <thead class="text-white rounded" style="background-color: #6D7986;">
                        <tr class="rounded">
                            <th scope="col">#No.</th>
                            <th scope="col">
                                Issue Date
                            </th>
                            <th scope="col">
                                Ending Date
                            </th>
                            <th scope="col">
                                User Name
                            </th>
                            <th scope="col">
                                Company
                            </th>
                            <th scope="col">
                                Insurance Name
                            </th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">#1</td>
                            <td>21/12/2021</td>
                            <td>21/12/2021</td>
                            <td>Mansi</td>
                            <td>LIC</td>
                            <td>Life Insurance</td>
                            <td>
                                <a href="" data-bs-toggle="modal" data-bs-target="#writeMsg" style="color: #EB8F00 !important; font-size: 1.125rem;">Notify</a>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">#2</td>
                            <td>21/12/2021</td>
                            <td>21/12/2021</td>
                            <td>Mansi</td>
                            <td>LIC</td>
                            <td>Life Insurance</td>
                            <td>
                                <a href="" data-bs-toggle="modal" data-bs-target="#writeMsg" style="color: #EB8F00 !important; font-size: 1.125rem;">Notify</a>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">#3</td>
                            <td>21/12/2021</td>
                            <td>21/12/2021</td>
                            <td>Mansi</td>
                            <td>LIC</td>
                            <td>Life Insurance</td>
                            <td>
                                <a href="" data-bs-toggle="modal" data-bs-target="#writeMsg" style="color: #EB8F00 !important; font-size: 1.125rem;">Notify</a>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">#4</td>
                            <td>21/12/2021</td>
                            <td>21/12/2021</td>
                            <td>Mansi</td>
                            <td>LIC</td>
                            <td>Life Insurance</td>
                            <td>
                                <a href="" data-bs-toggle="modal" data-bs-target="#writeMsg" style="color: #EB8F00 !important; font-size: 1.125rem;">Notify</a>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">#5</td>
                            <td>21/12/2021</td>
                            <td>21/12/2021</td>
                            <td>Mansi</td>
                            <td>LIC</td>
                            <td>Life Insurance</td>
                            <td>
                                <a href="" data-bs-toggle="modal" data-bs-target="#writeMsg" style="color: #EB8F00 !important; font-size: 1.125rem;">Notify</a>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">#6</td>
                            <td>21/12/2021</td>
                            <td>21/12/2021</td>
                            <td>Mansi</td>
                            <td>LIC</td>
                            <td>Life Insurance</td>
                            <td>
                                <a href="" data-bs-toggle="modal" data-bs-target="#writeMsg" style="color: #EB8F00 !important; font-size: 1.125rem;">Notify</a>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">#7</td>
                            <td>21/12/2021</td>
                            <td>21/12/2021</td>
                            <td>Mansi</td>
                            <td>LIC</td>
                            <td>Life Insurance</td>
                            <td>
                                <a href="" data-bs-toggle="modal" data-bs-target="#writeMsg" style="color: #EB8F00 !important; font-size: 1.125rem;">Notify</a>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">#8</td>
                            <td>21/12/2021</td>
                            <td>21/12/2021</td>
                            <td>Mansi</td>
                            <td>LIC</td>
                            <td>Life Insurance</td>
                            <td>
                                <a href="" data-bs-toggle="modal" data-bs-target="#writeMsg" style="color: #EB8F00 !important; font-size: 1.125rem;">Notify</a>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">#9</td>
                            <td>21/12/2021</td>
                            <td>21/12/2021</td>
                            <td>Mansi</td>
                            <td>LIC</td>
                            <td>Life Insurance</td>
                            <td>
                                <a href="" data-bs-toggle="modal" data-bs-target="#writeMsg" style="color: #EB8F00 !important; font-size: 1.125rem;">Notify</a>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">#10</td>
                            <td>21/12/2021</td>
                            <td>21/12/2021</td>
                            <td>Mansi</td>
                            <td>LIC</td>
                            <td>Life Insurance</td>
                            <td>
                                <a href="" data-bs-toggle="modal" data-bs-target="#writeMsg" style="color: #EB8F00 !important; font-size: 1.125rem;">Notify</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<div class="modal fade" id="writeMsg" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0">
            <div class="modal-body">
                <div class="row g-0 p-3">
                    <div class="container f-1">
                        <div class="row g-0">
                            <div class="fw-bold" style="font-size: 1.875rem;">Write Message!</div>
                        </div>
                        <div class="row g-0 pt-4">
                            <div class="text-black-50" style="font-size: 1.25rem;"> Message Here </div>
                            <div class="pt-4">
                                <input class="border-0 border-bottom border-3 w-100" type="text" style="outline: none; font-size: 1.25rem;">
                            </div>
                        </div>
                        <div class="row g-0" style="padding-top: 2.25rem;">
                            <div class="col-12 col-lg-8">
                                <div class="pt-2">
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="pt-2">
                                    <button type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2 fw-bold" style="background-color: #EF7C00; font-size: 1.25rem;" data-bs-target="#areyousure" data-bs-toggle="modal"> Send </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="areyousure" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-body">
                <div class="row g-0 p-3">
                    <div class="container f-1">
                        <div class="row g-0 p-5">
                            <img src="{{asset('img/icon-notify.png')}}" class="mx-auto d-inline-block h-50 w-50">
                        </div>
                        <div class="row g-0">
                            <div class="text-center" style="font-size: 1.875rem; font-weight:700; font-family:'Nunito'"> Are you Sure ? </div>
                        </div>
                        <div class="row g-0 pt-3">
                            <div class="text-center" style="font-size: 1.25rem; font-family:'Nunito'">
                                User will get notification for insurance renewal!
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-md-row gap-3 pt-5">
                            <div class="w-100">
                                <button type="reset" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #939EAA;" data-bs-toggle="modal" data-bs-target="#writeMsg"> Back </button>
                            </div>
                            <div class="w-100">
                                <button type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;" data-bs-dismiss="modal"> Yes I'm Sure </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
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
@endsection