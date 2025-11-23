@extends('multi-step-login::layouts.authenticate')

@section('content')
    <div class="p-3 px-lg-5">
        <div class="text-center mb-3">
            <i class="fa-solid fa-circle-user fa-5x"></i>
        </div>
        <h1 class="text-center h3 fw-bolder">{{trans('novinvision.multi-step-login::multi-step-login.login-title')}}</h1>
        <div class="text-body-secondary text-center">{{trans('novinvision.multi-step-login::multi-step-login.first-step-description')}}</div>
        <form action="" method="post" class="my-3" id="multi-step-login">
            @csrf
            <div class="my-3 my-lg-5">
                <div class="row g-3 g-lg-4">
                    @if (isset($errors) && $errors->any())
                        <div class="col-12">
                            <div class="alert alert-danger my-0">
                                <ul class="my-0 list-unstyled p-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <div class="col-12 text-center">
                        <div class="bg-body-secondary rounded-4 p-3">
                            <label for="username" class="mb-2">{{trans('novinvision.multi-step-login::multi-step-login.enter-username')}}</label>
                            <input
                                name="username"
                                value="{{old('username')}}"
                                placeholder="{{trans('novinvision.multi-step-login::multi-step-login.enter-mobile-or-username')}}"
                                class="form-control text-center"
                                type="text">
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary px-4">
                            {{trans('novinvision.multi-step-login::multi-step-login.go-to-next-step')}}
                            <i class="fa-solid fa-angle-left align-middle ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{route('register')}}" class="btn btn-link">
                    <i class="fa-solid fa-octagon-plus align-middle me-1"></i>
                    {{trans('novinvision.multi-step-login::multi-step-login.register')}}
                </a>
                <a href="{{route('forget-password')}}" class="btn btn-link">
                    <i class="fa-solid fa-lock-open align-middle me-1"></i>
                    {{trans('novinvision.multi-step-login::multi-step-login.forget-password')}}
                </a>
            </div>
        </form>
    </div>
@endsection
