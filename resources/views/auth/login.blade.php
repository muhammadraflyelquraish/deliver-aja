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
                        Sign in today for more expirience.
                    </p>
                    <form class="m-t" role="form" id="loginForm" method="POST">
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Email" name="email" required autofocus>
                            <small class="text-danger" id="email_error"></small>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" autocomplete="off" placeholder="Password" name="password" required>
                            <small class="text-danger" id="password_error"></small>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-offset-2 col-lg-10">
                                <div class="i-checks"><label> <input type="checkbox" name="remember"><i></i> Ingat saya? </label></div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b ladda-button ladda-button-demo" data-style="zoom-in">Login</button>

                        <p class="text-muted text-center">
                            <small>Belum punya akun?</small>
                        </p>
                        <a class="btn btn-sm btn-white btn-block" href="{{ route('register') }}">Daftar Sekarang</a>
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
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
            });

            let ladda = $('.ladda-button-demo').ladda();

            $("#loginForm").validate({
                rules: {
                    email: 'required',
                    password: 'required',
                },
                messages: {
                    email: "Email tidak boleh kosong",
                    password: "Password tidak boleh kosong",
                },
                success: function(messages) {
                    $(messages).remove();
                },
                errorPlacement: function(error, element) {
                    let name = element.attr("name")
                    $("#" + name + "_error").text(error.text())
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: "{{ route('login') }}",
                        type: "POST",
                        data: $(form).serialize(),
                        dataType: 'json',
                        success: function(res) {
                            window.location.href = "{{ route('dashboard') }}"
                        },
                        error: function(res) {
                            $('input[name="password"]').val(null)
                            swal({
                                title: 'Login Gagal',
                                text: 'username atau password salah!',
                            });
                        }
                    })
                }
            });
        });
    </script>


</body>

</html>