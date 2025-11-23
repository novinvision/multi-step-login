@extends('multi-step-login::layouts.authenticate')

@section('content')
    <div class="p-3 px-lg-5">
        <div class="text-center mb-3">
            <i class="fa-solid fa-badge-check fa-5x"></i>
        </div>
        <h1 class="text-center h3 fw-bolder">{{trans('novinvision.multi-step-login::multi-step-login.verify-title' ,['username' => $username,'field' => trans("novinvision.multi-step-login::multi-step-login.{$field}")])}}</h1>
        <div
            class="text-body-secondary text-center">{{trans('novinvision.multi-step-login::multi-step-login.verify-description' ,['username' => $username,'field' => trans("novinvision.multi-step-login::multi-step-login.{$field}")])}}</div>
        <form action="" method="post" class="my-3" id="multi-step-login">
            @csrf
            <div class="my-3 my-lg-4">
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
                            <label for="code" class="mb-2">{{trans('novinvision.multi-step-login::multi-step-login.verify-code')}}</label>
                            <input
                                name="code"
                                value="{{old('code')}}"
                                placeholder="{{str_repeat('_', config("multi-step-login.verify_code_len.{$field}", 5))}}"
                                data-length-submit="{{config("multi-step-login.verify_code_len.{$field}")}}"
                                maxlength="{{config("multi-step-login.verify_code_len.{$field}", 30)}}"
                                data-has-otp="true"
                                class="form-control otp-code-control text-center"
                                autocomplete="off"
                                required
                                type="tel">
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary px-4">
                            {{trans('novinvision.multi-step-login::multi-step-login.submit-verify-code')}}
                            <i class="fa-solid fa-angle-left align-middle ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mt-3 text-center">
                <a href="{{route('verify.resend', ['field' => $field])}}" class="btn btn-link"
                   data-count-down-text="{{trans('novinvision.multi-step-login::multi-step-login.verify-code-resend-countdown')}}"
                   data-count-down-finish-text="{{trans('novinvision.multi-step-login::multi-step-login.verify-code-resend')}}"
                   data-count-down="{{$countdown ?? config("multi-step-login.verify_resend_seconds.{$field}")}}">
                    @if($countdown > 0)
                        {{trans('novinvision.multi-step-login::multi-step-login.verify-code-resend-countdown', ['sec'=> 60])}}
                    @else
                        {{trans('novinvision.multi-step-login::multi-step-login.verify-code-resend')}}
                    @endif
                </a>
                @if($modifyRoute = config("multi-step-login.verify_modify_route_name"))
                    <a href="{{route($modifyRoute)}}" class="btn btn-link">
                        <i class="fa-solid fa-pen-to-square align-middle me-1"></i>
                        {{trans('novinvision.multi-step-login::multi-step-login.verify-code-edit-profile')}}
                    </a>
                @endif
            </div>
        </form>
    </div>
@endsection
