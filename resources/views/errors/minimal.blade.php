<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="/manage/css/app.min.css">
    <link rel="stylesheet" href="/manage/css/style.css">
    <link rel="stylesheet" href="/manage/css/components.css">
    <link rel="stylesheet" href="/manage/css/custom.css">
    <link rel='shortcut icon' type='image/x-icon' href='/manage/img/favicon.png' />
</head>

<body>
<div class="loader"></div>
<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="page-error">
                <div class="page-inner">
                    <h1>@yield('code')</h1>
                    <div class="page-description">@yield('message')</div>
                    <div class="page-search">
                        <form action="/login">
                            <div class="form-group floating-addon floating-addon-not-append">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-lg" style="width: 100%" type="submit">
                                        ورود به سامانه
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="/manage/js/app.min.js"></script>
<script src="/manage/js/scripts.js"></script>
<script src="/manage/js/custom.js"></script>
</body>
</html>
