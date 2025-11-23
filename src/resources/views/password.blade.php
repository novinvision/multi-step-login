@extends('multi-step-login::layouts.authenticate')

@section('content')
    <div class="p-3 px-lg-5">
        <div class="text-center mb-3">
            <i class="fa-solid fa-lock fa-5x"></i>
        </div>
        <h1 class="text-center h3 fw-bolder">{{trans('novinvision.multi-step-login::multi-step-login.password-title')}}</h1>
        <div class="text-body-secondary text-center fanum">{{trans('novinvision.multi-step-login::multi-step-login.password-step-description', ['username' => $username])}}</div>
        <form action="" method="post" class="my-3" id="multi-step-login">
            @csrf
            <div class="my-3 my-lg-4">
                <div class="row g-3">
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
                    <div class="col-12 text-center">
                        <div class="bg-body-secondary rounded-4 p-3">
                            <div class="row g-3">
                                <?php if (!empty($username)): ?>
                                <input name="username" value="{{old('username', $username)}}" type="hidden">
                                <?php else: ?>
                                <div class="col-12">
                                    <label for="username" class="mb-2">{{trans('novinvision.multi-step-login::multi-step-login.enter-username')}}</label>
                                    <input
                                        name="username"
                                        value="{{old('username', $username)}}"
                                        placeholder="{{trans('novinvision.multi-step-login::multi-step-login.enter-mobile-or-username')}}"
                                        class="form-control text-center"
                                        {{isset($username) && $username ? 'disabled' : ''}}
                                        type="text">
                                </div>
                                <?php endif; ?>
                                <div class="col-12">
                                    <label for="password" class="mb-2">{{trans('novinvision.multi-step-login::multi-step-login.password')}}</label>
                                    <input
                                        name="password"
                                        value="{{old('password')}}"
                                        placeholder="{{trans('novinvision.multi-step-login::multi-step-login.password-placeholder')}}"
                                        class="form-control text-center"
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
            <div class="mt-3 text-center">
                <a href="{{route('login')}}" class="btn btn-link">
                    <i class="fa-solid fa-user align-middle me-1"></i>
                    {{trans('novinvision.multi-step-login::multi-step-login.change-username')}}
                </a>
                <a href="{{route('forget-password')}}" class="btn btn-link">
                    <i class="fa-solid fa-lock-open align-middle me-1"></i>
                    {{trans('novinvision.multi-step-login::multi-step-login.forget-password')}}
                </a>
            </div>
        </form>
    </div>
@endsection
