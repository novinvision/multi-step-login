@extends('multi-step-login::layouts.authenticate')

@section('content')
    <div class="p-3">
        <div class="text-center mb-3">
            <i class="fa-solid fa-circle-user fa-5x"></i>
        </div>
        <h1 class="text-center h3 fw-bolder">{{trans('novinvision.multi-step-login::multi-step-login.register-title')}}</h1>
        <div class="text-body-secondary text-center">{{trans('novinvision.multi-step-login::multi-step-login.register-step-description')}}</div>
        <form action="" method="post" id="multi-step-login">
            @csrf
            <div class="my-3">
                @if (isset($errors) && $errors->any())
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <ul class="my-0 list-unstyled p-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="bg-body-secondary rounded p-3">
                    <div class="row g-3">
                        @foreach(config('multi-step-login.register_columns') as $key => $field)
                            <div class="{{$field['row-class'] ?? 'col-12 col-sm-6'}}">
                                <label for="{{$key}}" class="mb-2">
                                    {{trans("novinvision.multi-step-login::multi-step-login.register-{$key}")}}
                                </label>
                                <input
                                    name="{{$key}}"
                                    value="{{old($key, $oldData[$key] ?? null)}}"
                                    placeholder="{{$field['placeholder'] ?? 'وارد کنید...'}}"
                                    class="form-control fanum"
                                    type="{{$field['type'] ?? 'text'}}">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="bg-body-secondary rounded p-3 my-3">
                    <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label for="{{$key}}" class="mb-2">
                                    {{trans("novinvision.multi-step-login::multi-step-login.password")}}
                                </label>
                                <input
                                    name="password"
                                    value="{{old('password')}}"
                                    placeholder="گذرواژه دلخواه وارد کنید"
                                    class="form-control fanum"
                                    type="password">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="{{$key}}" class="mb-2">
                                    {{trans("novinvision.multi-step-login::multi-step-login.password_confirm")}}
                                </label>
                                <input
                                    name="password_confirm"
                                    value="{{old('password_confirm')}}"
                                    placeholder="گذرواژه دلخواه وارد کنید"
                                    class="form-control fanum"
                                    type="password">
                            </div>
                    </div>
                </div>
                <div class="text-center my-3 my-lg-4">
                    <button type="submit" class="btn btn-primary px-4">
                        {{trans('novinvision.multi-step-login::multi-step-login.go-to-next-step')}}
                        <i class="fa-solid fa-angle-left align-middle ms-1"></i>
                    </button>
                </div>
            </div>
            <div class="mt-3 text-center">
                <a href="{{route('login')}}" class="btn btn-link">
                    <i class="fa-solid fa-user align-middle me-1"></i>
                    {{trans('novinvision.multi-step-login::multi-step-login.login')}}
                </a>
                <a href="{{route('forget-password')}}" class="btn btn-link">
                    <i class="fa-solid fa-lock-open align-middle me-1"></i>
                    {{trans('novinvision.multi-step-login::multi-step-login.forget-password')}}
                </a>
            </div>
        </form>
    </div>
@endsection
