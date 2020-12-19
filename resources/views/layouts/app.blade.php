<!DOCTYPE html>
<html style="font-size: 16px">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css">

{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">--}}

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Miriam+Libre:400,700|Merriweather" rel="stylesheet">

    <!-- Theme style -->
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/AdminLTE.min.css">--}}
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/skins/_all-skins.min.css">--}}

    <link rel="stylesheet" href="{{url('assets/css/bootstrap-datepicker3.css')}}">
    <link rel="stylesheet" href="{{url('assets/css/theme.css')}}">

    @yield('css')
    <style>
        header .desc {
            color: white;
            margin: 20px;
        }
        header .desc .desc__title1 {
            font-size: 25px;
            font-family: "NotoSans";
            font-weight: bold;
            margin-bottom: 5px;
        }
        header .desc .desc__title__main {
            font-size: 16px;
            font-weight: normal;
            margin: 0px;
        }
    </style>

</head>

<body>

<header class="py-5" style="background-color: #337ab7">
    <div class="container mx-auto px-5 lg:max-w-screen">
        <div class="flex items-center flex-col lg:flex-row">
            <a href="/" class="flex items-center no-underline text-brand">
                <img src="{{ asset('assets/images/quoc_huy.png') }}" style="width: 60px">
            </a>

            <div class="desc text-center sm:text-left">
                <div class="desc__title1 uppercase">Thái Nguyên</div>
                <div class="desc__title__main uppercase">Phần mềm quản lý tài liệu số</div>
            </div>

            <div class="lg:ml-auto mt-10 lg:mt-0 flex items-center">
{{--                <a href="/" class="no-underline hover:underline uppercase">Home</a>--}}
{{--                <a href="/releases" class="ml-5 no-underline hover:underline uppercase">Releases</a>--}}
{{--                <a href="/forge" class="ml-5 no-underline hover:underline uppercase">Forge</a>--}}
                <a href="{{ route('logout') }}" class="ml-5 no-underline hover:underline uppercase" style="color: white"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Đăng xuất
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</header>
<div class="container mx-auto px-5 pt-10 lg:max-w-screen-sm">
    @yield('content')
</div>

<div class="border-t border-lighter mt-20">
    <div class="container mx-auto px-5 lg:max-w-screen">
        <div class="text-muted py-10 text-center">
            Thái Nguyên 2020
        </div>
    </div>
</div>


<!-- jQuery 3.1.1 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/js/adminlte.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>--}}

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="{{url('assets/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{url('assets/js/bootstrap-datepicker.vi.min.js')}}"></script>

@stack('scripts')
</body>
</html>
