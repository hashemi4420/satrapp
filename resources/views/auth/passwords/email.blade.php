<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="theme-color" content="#09d16e"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>بازیابی رمزعبور</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="/manage/css/app.min.css">
    <link rel="stylesheet" href="/manage/bundles/bootstrap-social/bootstrap-social.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="/manage/css/style.css">
    <link rel="stylesheet" href="/manage/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="/manage/css/custom.css">
    <link rel='shortcut icon' type='image/x-icon' href='/manage/img/favicon.png' />
    <link href="/manage/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="loader"></div>
<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>بازیابی رمزعبور</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">ایمیل</label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" tabindex="1"
                                                   placeholder="نام کاربری" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
                                            @error('email')
                                            <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                                بازیابی
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- General JS Scripts -->
<script src="/manage/js/app.min.js"></script>
<!-- JS Libraies -->
<!-- Page Specific JS File -->
<!-- Template JS File -->
<script src="/manage/js/scripts.js"></script>
<!-- Custom JS File -->
<script src="/manage/js/custom.js"></script>
</body>

</html>