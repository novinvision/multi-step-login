<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{trans('novinvision.multi-step-login::multi-step-login.page_title')}} - {{config('app.name')}}</title>
    @if(in_array(app()->getLocale(), ['fa', 'ar']))
        <link rel="stylesheet" href="{{asset('css/bootstrap.rtl.min.css')}}">
    @else()
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    @endif

    <link rel="stylesheet" href="{{asset('css/fonts-iranyekan.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
    <link rel="stylesheet" href="{{asset('css/multi-step-login.css')}}">

    <script src="{{asset('js/jquery-3.7.1.slim.min.js')}}"></script>
    <script src="{{asset('js/multi-step-login.js')}}"></script>
</head>
<body class="bg-body-tertiary fanum">
<div class="container py-3">
    <div class="row">
        <div class="col-12 col-sm-11 col-md-9 col-lg-6 col-xl-5 mx-auto">
            <div class="auth-logo text-center">
                @if($logo = config('multi-step-login.logo'))
                    <a href="/" class="d-inline-block">
                        <img
                            src="{{$logo}}"
                            alt="{{config('app.name')}}"
                            class="img-fluid">
                    </a>
                @else
                    <div class="h1">
                        <a href="/" class="d-inline-block">
                            {{config('app.name')}}
                        </a>
                    </div>
                @endif
            </div>
            <div class="card shadow border-0 my-3 my-lg-4">
                <div class="card-body py-3 py-lg-5">
                    @yield('content')
                </div>
            </div>
            <div class="text-center">
                <a href="/" class="btn btn-link text-body-secondary">{{trans('novinvision.multi-step-login::multi-step-login.back-to-home')}}</a>
            </div>
        </div>
    </div>
</div>
@stack('scripts')
</body>
</html>
