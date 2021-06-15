<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> Login </title>

    <link href="{{ asset('/')}}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('/')}}/assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="{{ asset('/')}}/assets/css/animate.css" rel="stylesheet">
    <link href="{{ asset('/')}}/assets/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div><br><br><br>
           
            <form method="POST" action="{{ route('login') }}">
                        @csrf
                <div class="form-group">

                    <input id="user_name" type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name" value="{{ old('user_name') }}" placeholder="Username" required autofocus>

                                @error('user_name')
                                    <span style="color: red" class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                </div>
                <div class="form-group">

                     <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">

                                @error('password')
                                    <span style="color: red" class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">{{ __('Login') }}</button>

            </form>
            <p class="m-t"> <small>Innovation IT &copy; 2020</small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="{{ asset('/')}}/assets/js/jquery-3.1.1.min.js"></script>
    <script src="{{ asset('/')}}/assets/js/bootstrap.min.js"></script>

</body>

</html>
