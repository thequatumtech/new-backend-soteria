@extends('layouts.mainlayout')
@section('style')
<link rel="stylesheet" href="{{asset('css/agent.css')}}">

@endsection

@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="row p-3 pt-0 g-0">
            <a href="{{route('privacy.form')}}" class="col-12 col-md-6 col-lg-3 pb-5 text-decoration-none">
                <div class="p-3 insurances d-flex flex-column gap-3 align-items-center">
                    <div class="insurance-container bg-white d-flex justify-content-center align-items-center">
                        <img src="{{asset ('img/card-3.png')}}" alt="">
                    </div>
                    <div class="text-center insurance-title">
                        Individual Medical Insurance
                    </div>
                </div>
            </a>
            <a href="{{route('privacy.form')}}" class="col-12 col-md-6 col-lg-3 pb-5 text-decoration-none">
                <div class="p-3 insurances d-flex flex-column gap-3 align-items-center">
                    <div class="insurance-container bg-white d-flex justify-content-center align-items-center">
                        <img src="{{asset ('img/card-4.png')}}" alt="">
                    </div>
                    <div class="text-center insurance-title">
                        Life Insurance
                    </div>
                </div>
            </a>
            <a href="{{route('privacy.form')}}" class="col-12 col-md-6 col-lg-3 pb-5 text-decoration-none">
                <div class="p-3 insurances d-flex flex-column gap-3 align-items-center">
                    <div class="insurance-container bg-white d-flex justify-content-center align-items-center">
                        <img src="{{asset ('img/card-5.png')}}" alt="">
                    </div>
                    <div class="text-center insurance-title">
                        Home Insurance
                    </div>
                </div>
            </a>
            <a href="{{route('privacy.form')}}" class="col-12 col-md-6 col-lg-3 pb-5 text-decoration-none">
                <div class="p-3 insurances d-flex flex-column gap-3 align-items-center">
                    <div class="insurance-container bg-white d-flex justify-content-center align-items-center">
                        <img src="{{asset ('img/card-6.png')}}" alt="">
                    </div>
                    <div class="text-center insurance-title">
                        Personal Accident Insurance
                    </div>
                </div>
            </a>
            <a href="{{route('privacy.form')}}" class="col-12 col-md-6 col-lg-3 pb-5 text-decoration-none">
                <div class="p-3 insurances d-flex flex-column gap-3 align-items-center">
                    <div class="insurance-container bg-white d-flex justify-content-center align-items-center">
                        <img src="{{asset ('img/card-7.png')}}" alt="">
                    </div>
                    <div class="text-center insurance-title">
                        Travel Insurance
                    </div>
                </div>
            </a>
            <a href="{{route('privacy.form')}}" class="col-12 col-md-6 col-lg-3 pb-5 text-decoration-none">
                <div class="p-3 insurances d-flex flex-column gap-3 align-items-center">
                    <div class="insurance-container bg-white d-flex justify-content-center align-items-center">
                        <img src="{{asset ('img/card-8.png')}}" alt="">
                    </div>
                    <div class="text-center insurance-title">
                        Marine Insurance
                    </div>
                </div>
            </a>
            <a href="{{route('privacy.form')}}" class="col-12 col-md-6 col-lg-3 pb-5 text-decoration-none">
                <div class="p-3 insurances d-flex flex-column gap-3 align-items-center">
                    <div class="insurance-container bg-white d-flex justify-content-center align-items-center">
                        <img src="{{asset ('img/card-9.png')}}" alt="">
                    </div>
                    <div class="text-center insurance-title">
                        Automotive Insurance
                    </div>
                </div>
            </a>
            <a href="{{route('privacy.form')}}" class="col-12 col-md-6 col-lg-3 pb-5 text-decoration-none">
                <div class="p-3 insurances d-flex flex-column gap-3 align-items-center">
                    <div class="insurance-container bg-white d-flex justify-content-center align-items-center">
                        <img src="{{asset ('img/card-10.png')}}" alt="">
                    </div>
                    <div class="text-center insurance-title">
                        Office Insurance
                    </div>
                </div>
            </a>
            <a href="{{route('privacy.form')}}" class="col-12 col-md-6 col-lg-3 pb-5 text-decoration-none">
                <div class="p-3 insurances d-flex flex-column gap-3 align-items-center">
                    <div class="insurance-container bg-white d-flex justify-content-center align-items-center">
                        <img src="{{asset ('img/card-11.png')}}" alt="">
                    </div>
                    <div class="text-center insurance-title">
                        Critical Illness Insurance
                    </div>
                </div>
            </a>
            <a href="{{route('privacy.form')}}" class="col-12 col-md-6 col-lg-3 pb-5 text-decoration-none">
                <div class="p-3 insurances d-flex flex-column gap-3 align-items-center">
                    <div class="insurance-container bg-white d-flex justify-content-center align-items-center">
                        <img src="{{asset ('img/card-12.png')}}" alt="">
                    </div>
                    <div class="text-center insurance-title">
                        Pet Insurance
                    </div>
                </div>
            </a>
        </div>
    </div>
</main>
@endsection