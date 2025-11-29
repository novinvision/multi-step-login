@extends('multi-step-login::layouts.authenticate')

@section('content')
    <div class="p-3 px-lg-5">
        <div class="text-center mb-3">
            <i class="fa-solid fa-lock fa-5x"></i>
        </div>
        <h1 class="text-center h3 fw-bolder">{{trans('novinvision.multi-step-login::multi-step-login.forget_password_sent_title', ['username' => $username])}}</h1>
        <div
            class="text-body-secondary text-center">{{trans('novinvision.multi-step-login::multi-step-login.forget_password_sent_description', ['username' => $username])}}</div>
        <div class="my-3 mb-lg-5">
            <div class="col-12 text-center">
                <a href="{{url('/')}}" type="submit" class="btn btn-primary px-4">
                    {{trans('novinvision.multi-step-login::multi-step-login.back-to-home')}}
                    <i class="fa-solid fa-angle-left align-middle ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="mt-3 text-center">
        <a href="{{route('register')}}" class="btn btn-link">
            <i class="fa-solid fa-octagon-plus align-middle me-1"></i>
            {{trans('novinvision.multi-step-login::multi-step-login.register')}}
        </a>
        <a href="{{route('login')}}" class="btn btn-link">
            <i class="fa-solid fa-lock-open align-middle me-1"></i>
            {{trans('novinvision.multi-step-login::multi-step-login.login')}}
        </a>
    </div>
@endsection
