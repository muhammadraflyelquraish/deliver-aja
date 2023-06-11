<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>E-Parking | Login</title>

    <link href="{{ asset('build/assets') }}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('build/assets') }}/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="{{ asset('build/assets') }}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="{{ asset('build/assets') }}/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="{{ asset('build/assets') }}/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">

    <link href="{{ asset('build/assets') }}/css/animate.css" rel="stylesheet">
    <link href="{{ asset('build/assets') }}/css/style.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            font-family: 'Rubik', sans-serif;
        }
    </style>

</head>

<body class="gray-bg">

    <div class="passwordBox animated fadeInDown">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox-content">
                    <h2 class="font-bold">DeliverAja</h2>
                    <p>
                        Registrasi
                    </p>
                    <form class="m-t" role="form" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group">
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama">
                            @if($errors->has('name'))
                            <small class="text-danger">{{ $errors->first('name') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Email" name="email" required value="{{ old('email') }}">
                            @if($errors->has('email'))
                            <small class="text-danger">{{ $errors->first('email') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" autocomplete="off" placeholder="Password" name="password" required value="{{ old('password') }}" autocomplete="new-password">
                            @if($errors->has('password'))
                            <small class="text-danger">{{ $errors->first('password') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" autocomplete="off" placeholder="Konfirmasi Password" name="password_confirmation" required autocomplete="new-password">
                            @if($errors->has('password_confirmation'))
                            <small class="text-danger">{{ $errors->first('password_confirmation') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" autocomplete="off" placeholder="Nomor Telephone" name="phone_number" required>
                            @if($errors->has('phone_number'))
                            <small class="text-danger">{{ $errors->first('phone_number') }}</small>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary block full-width m-b ladda-button ladda-button-demo" data-style="zoom-in">Registrasi</button>

                        <p class="text-muted text-center">
                            <small><a href="{{ route('login') }}">Sudah punya akun?</a></small>
                        </p>
                    </form>

                </div>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-12">
                &copy; <small>{{ date('Y') }} &bullet; DeliverAja</small>
            </div>

        </div>
    </div>

    <script src="{{ asset('build/assets') }}/js/jquery-3.1.1.min.js"></script>
    <script src="{{ asset('build/assets') }}/js/plugins/iCheck/icheck.min.js"></script>
    <script src="{{ asset('build/assets') }}/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="{{ asset('build/assets') }}/js/plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Ladda -->
    <script src="{{ asset('build/assets') }}/js/plugins/ladda/spin.min.js"></script>
    <script src="{{ asset('build/assets') }}/js/plugins/ladda/ladda.min.js"></script>
    <script src="{{ asset('build/assets') }}/js/plugins/ladda/ladda.jquery.min.js"></script>
</body>

</html>