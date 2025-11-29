@extends('multi-step-login::layouts.authenticate')

@section('content')
    <div class="p-3 px-lg-5">
        <div class="text-center mb-3">
            <i class="fa-solid fa-lock fa-5x"></i>
        </div>
        <h1 class="text-center h3 fw-bolder">{{trans('novinvision.multi-step-login::multi-step-login.change_password_title')}}</h1>
        <div class="text-body-secondary text-center">{{trans('novinvision.multi-step-login::multi-step-login.change_password_description', ['name' => ($user->name ?? '')])}}</div>
        <form action="" method="post" class="my-3" id="multi-step-login">
            @csrf
            <div class="my-3 mb-lg-5">
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
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="password" class="mb-2">{{trans('novinvision.multi-step-login::multi-step-login.password')}}</label>
                                    <input
                                        name="password"
                                        value="{{old('password')}}"
                                        placeholder="{{trans('novinvision.multi-step-login::multi-step-login.password')}}"
                                        class="form-control text-center"
                                        autocomplete="new-password"
                                        type="password">
                                </div>
                                <div class="col-12">
                                    <label for="password_confirm" class="mb-2">{{trans('novinvision.multi-step-login::multi-step-login.password_confirm')}}</label>
                                    <input
                                        name="password_confirm"
                                        value="{{old('password_confirm')}}"
                                        placeholder="{{trans('novinvision.multi-step-login::multi-step-login.password_confirm')}}"
                                        class="form-control text-center"
                                        autocomplete="new-password"
                                        type="password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary px-4">
                            {{trans('novinvision.multi-step-login::multi-step-login.submit-to-login')}}
                            <i class="fa-solid fa-angle-left align-middle ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
