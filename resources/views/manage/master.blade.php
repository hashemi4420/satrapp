<?php

use App\Form;
use Illuminate\Support\Facades\Auth;

$accessLevel = Auth::user()->usrRole;

$accessList = json_decode($accessLevel->json, true);
$form = Form::all();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="theme-color" content="#09d16e"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>سامانه تماس تجاری</title>
    <link href="/manage/css/satrapp.css" rel="stylesheet" type="text/css">
    <link href="/manage/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/manage/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="/manage/css/sweetalert2.css" rel="stylesheet" type="text/css">
    <link href="/manage/css/flaticon.css" rel="stylesheet" type="text/css">
{{--    <script type="text/javascript" src="/manage/js/core/libraries/bootstrap.min.js"></script>--}}
    <!-- General CSS Files -->
    <link rel="stylesheet" href="/manage/css/app.min.css">
    <link rel="stylesheet" href="/manage/bundles/jqvmap/dist/jqvmap.min.css">
    <link rel="stylesheet" href="/manage/bundles/weather-icon/css/weather-icons.min.css">
    <link rel="stylesheet" href="/manage/bundles/weather-icon/css/weather-icons-wind.min.css">
    <link rel="stylesheet" href="/manage/bundles/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="/manage/css/custom.css">
    <link rel="stylesheet" href="/manage/css/pretty-checkbox.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="/manage/css/style.css">
    <link href="/manage/bundles/lightgallery/dist/css/lightgallery.css" rel="stylesheet">
{{--    <link rel="stylesheet" href="/manage/css/components.css">--}}
    <!-- Custom style CSS -->



    <link href="/manage/css/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/manage/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="/manage/js/sweetalert2.all.min.js"></script>
    <script type="text/javascript" src="/manage/js/core/manageController.js"></script>
    {{--<script type="text/javascript" src="/manage/assets/js/jquery.js"></script>--}}

    <link rel='shortcut icon' type='image/x-icon' href='/manage/img/favicon.png' />
    @yield('topHead')
</head>

<body class="{{Auth::user()->theme}}">
<div class="loader"></div>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            <div class="form-inline mr-auto">
                <ul class="navbar-nav mr-3">
                    <li><a id="sidebarMenu" href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
                    <li id="mobileShow"><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                            <i data-feather="maximize"></i>
                        </a></li>
                    <li style="margin-right: 10px;"> <!--id="mobileShow"-->
                        <div class="selectgroup layout-color w-50" style="height: 100%">
                            <label class="selectgroup-item">
                                @if(Auth::user()->theme == 'light light-sidebar theme-white' || Auth::user()->theme == 'light light-sidebar theme-white sidebar-gone')
                                    <div class="pretty p-switch p-fill">
                                        <input type="checkbox" onchange="changeTheme({{Auth::user()->id}}, 'dark dark-sidebar theme-black', document.getElementById('tkn').value)">
                                        <div class="state p-success">
                                            <label>حالت تاریک</label>
                                        </div>
                                    </div>
                                @else
                                    <div class="pretty p-switch p-fill">
                                        <input type="checkbox" checked onchange="changeTheme({{Auth::user()->id}}, 'light light-sidebar theme-white', document.getElementById('tkn').value)">
                                        <div class="state p-success">
                                            <label>حالت تاریک</label>
                                        </div>
                                    </div>
                                @endif
                            </label>
                        </div>
                    </li>
                </ul>
            </div>
            <ul class="navbar-nav navbar-right">
                <form class="form-horizontal" method="post">
                    <input type="hidden" id="tkn" name="_token" value="<?php echo csrf_token(); ?>">
                </form>
                @if(Auth::check())
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            @if(Auth::user()->url_avatar)
                                <img alt="{{Auth::user()->name}}" src="{{Auth::user()->url_avatar}}" class="user-img-radious-style">
                            @else
                                <img alt="{{Auth::user()->name}}" src="/manage/img/products/product-5.png" class="user-img-radious-style">
                            @endif
                            <span class="d-sm-none d-lg-inline-block"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pullDown">
                            <!--<div class="dropdown-title">سلام {{Auth::user()->name}} {{Auth::user()->family}}</div>
                            <!--a href="/manage/profile" class="dropdown-item has-icon">
                                <i class="far fa-user"></i>
                                مشخصات
                            </a-->
                            <a href="#" onclick="changePassUser({{Auth::user()->id}}, document.getElementById('tkn').value)" class="dropdown-item has-icon">
                                <i class="fas fa-cog"></i>
                                تغییر کلمه عبور
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="/logout" class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i>
                                خروج
                            </a>
                        </div>
                    </li>
                @endif
            </ul>
        </nav>
        <div class="main-sidebar sidebar-style-2">
            <aside id="sidebar-wrapper">
                <div class="sidebar-brand">
                    <a href="#">
                        <img alt="تصویر" src="/manage/img/logo.png" class="header-logo" style="border-radius:50%; height: 50px !important;">
                    </a>
                </div>
                <div class="sidebar-user">

                </div>
                <ul class="sidebar-menu">
                    <li id="index" class="active">
                        <a class="nav-link" href="/manage/index"><i class="flaticon-home"></i><span>صفحه اصلی</span></a>
                    </li>
                    @if(Auth::user()->userRole != 3)
                    <?php if($accessList['read_1'] == "on" || $accessList['read_2'] == "on" || $accessList['read_3'] == "on" ||
                        $accessList['read_4'] == "on" || $accessList['read_5'] == "on" || $accessList['read_35'] == "on" ||
                        $accessList['read_36'] == "on" || $accessList['read_37'] == "on" || $accessList['read_38'] == "on" ||
                        $accessList['read_39'] == "on"){ ?>
                        <li id="manage" class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i data-feather="users"></i><span>مدیریت</span></a>
                            <ul class="dropdown-menu">
                                <?php if($accessList['read_1'] == "on"){ ?>
                                    <li id="users"><a class="nav-link" href="<?php echo $form[0]->url; ?>"><?php echo $form[0]->title; ?></a></li>
                                <?php } if($accessList['read_2'] == "on"){ ?>
                                    <li id="businessNumber"><a class="nav-link" href="<?php echo $form[1]->url; ?>"><?php echo $form[1]->title; ?></a></li>
                                <?php } if($accessList['read_3'] == "on"){ ?>
                                    <li id="log"><a class="nav-link" href="<?php echo $form[2]->url; ?>"><?php echo $form[2]->title; ?></a></li>
                                <?php }if($accessList['read_4'] == "on"){ ?>
                                    <li id="forms"><a class="nav-link" href="<?php echo $form[3]->url; ?>"><?php echo $form[3]->title; ?></a></li>
                                <?php }if($accessList['read_5'] == "on"){ ?>
                                    <li id="accessLevel"><a class="nav-link" href="<?php echo $form[4]->url; ?>"><?php echo $form[4]->title; ?></a></li>
                                <?php } if($accessList['read_35'] == "on"){ ?>
                                    <li id="reseller"><a class="nav-link" href="<?php echo $form[34]->url; ?>"><?php echo $form[34]->title; ?></a></li>
                                <?php } if($accessList['read_36'] == "on"){ ?>
                                    <li id="contact"><a class="nav-link" href="<?php echo $form[35]->url; ?>"><?php echo $form[35]->title; ?></a></li>
                                <?php } if($accessList['read_37'] == "on"){ ?>
                                    <li id="question"><a class="nav-link" href="<?php echo $form[36]->url; ?>"><?php echo $form[36]->title; ?></a></li>
                                <?php } if($accessList['read_38'] == "on"){ ?>
                                    <li id="report"><a class="nav-link" href="<?php echo $form[37]->url; ?>"><?php echo $form[37]->title; ?></a></li>
                                <?php } if($accessList['read_39'] == "on"){ ?>
                                    <li id="appUser"><a class="nav-link" href="<?php echo $form[38]->url; ?>"><?php echo $form[38]->title; ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php }
                        if($accessList['read_6'] == "on" || $accessList['read_7'] == "on" || $accessList['read_8'] == "on"){
                    ?>
                        <li id="baseTable" class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i data-feather="database"></i><span>جداول پایه</span></a>
                            <ul class="dropdown-menu">
                                <?php if($accessList['read_6'] == "on"){ ?>
                                    <li id="states"><a class="nav-link" href="<?php echo $form[5]->url; ?>"><?php echo $form[5]->title; ?></a></li>
                                <?php } if($accessList['read_7'] == "on"){ ?>
                                    <li id="cities"><a class="nav-link" href="<?php echo $form[6]->url; ?>"><?php echo $form[6]->title; ?></a></li>
                                <?php } if($accessList['read_8'] == "on"){ ?>
                                    <li id="districts"><a class="nav-link" href="<?php echo $form[7]->url; ?>"><?php echo $form[7]->title; ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php }
                        if($accessList['read_9'] == "on" || $accessList['read_10'] == "on" || $accessList['read_11'] == "on" || $accessList['read_12'] == "on" ||
                            $accessList['read_13'] == "on" || $accessList['read_14'] == "on"){
                    ?>
                        <li id="article" class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i data-feather="archive"></i><span>جداول کالا</span></a>
                            <ul class="dropdown-menu">
                                <?php if($accessList['read_9'] == "on"){ ?>
                                    <li id="articleBrands"><a class="nav-link" href="<?php echo $form[8]->url; ?>"><?php echo $form[8]->title; ?></a></li>
                                <?php } if($accessList['read_10'] == "on"){ ?>
                                    <li id="articleAreas"><a class="nav-link" href="<?php echo $form[9]->url; ?>"><?php echo $form[9]->title; ?></a></li>
                                <?php } if($accessList['read_11'] == "on"){ ?>
                                    <li id="articleGroups"><a class="nav-link" href="<?php echo $form[10]->url; ?>"><?php echo $form[10]->title; ?></a></li>
                                <?php } if($accessList['read_12'] == "on"){ ?>
                                    <li id="articleCategories"><a class="nav-link" href="<?php echo $form[11]->url; ?>"><?php echo $form[11]->title; ?></a></li>
                                <?php } if($accessList['read_13'] == "on"){ ?>
                                    <li id="articleCreators"><a class="nav-link" href="<?php echo $form[12]->url; ?>"><?php echo $form[12]->title; ?></a></li>
                                <?php } if($accessList['read_14'] == "on"){ ?>
                                    <li id="articleProvider"><a class="nav-link" href="<?php echo $form[13]->url; ?>"><?php echo $form[13]->title; ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php }
                    if($accessList['read_16'] == "on" || $accessList['read_17'] == "on" || $accessList['read_18'] == "on" ||
                        $accessList['read_19'] == "on" || $accessList['read_20'] == "on" || $accessList['read_21'] == "on"){
                    ?>
                        <li id="service" class="dropdown">
                            <a href="#" class="nav-link has-dropdown"><i data-feather="check-square"></i><span>جداول خدمات</span></a>
                            <ul class="dropdown-menu">
                                <?php if($accessList['read_16'] == "on"){ ?>
                                    <li id="serviceBrands"><a class="nav-link" href="<?php echo $form[15]->url; ?>"><?php echo $form[15]->title; ?></a></li>
                                <?php } if($accessList['read_17'] == "on"){ ?>
                                    <li id="serviceArea"><a class="nav-link" href="<?php echo $form[16]->url; ?>"><?php echo $form[16]->title; ?></a></li>
                                <?php } if($accessList['read_18'] == "on"){ ?>
                                    <li id="serviceGroups"><a class="nav-link" href="<?php echo $form[17]->url; ?>"><?php echo $form[17]->title; ?></a></li>
                                <?php } if($accessList['read_19'] == "on"){ ?>
                                    <li id="serviceCategories"><a class="nav-link" href="<?php echo $form[18]->url; ?>"><?php echo $form[18]->title; ?></a></li>
                                <?php } if($accessList['read_20'] == "on"){ ?>
                                    <li id="serviceCreators"><a class="nav-link" href="<?php echo $form[19]->url; ?>"><?php echo $form[19]->title; ?></a></li>
                                <?php } if($accessList['read_21'] == "on"){ ?>
                                    <li id="serviceProvider"><a class="nav-link" href="<?php echo $form[20]->url; ?>"><?php echo $form[20]->title; ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    @endif
                    @if(Auth::user()->userRole == 3)
                        <?php if($accessList['read_32'] == "on"){ ?>
                            <li id="aboutUs">
                                <a class="nav-link" href="<?php echo $form[31]->url; ?>"><i class="flaticon-folder-14"></i><span><?php echo $form[31]->title; ?></span></a>
                            </li>
                        <?php }
                            if($accessList['read_22'] == "on" || $accessList['read_23'] == "on" || $accessList['read_24'] == "on"){
                         ?>
                            <li id="manageBasket" class="dropdown">
                                <a href="#" class="nav-link has-dropdown"><i class="flaticon-list"></i><span>لیست خدمات و محصولات</span></a>
                                <ul class="dropdown-menu">
                                    <?php if($accessList['read_22'] == "on"){ ?>
                                        <li id="basketList"><a class="nav-link" href="<?php echo $form[21]->url; ?>"><?php echo $form[21]->title; ?></a></li>
                                    <?php } if($accessList['read_23'] == "on"){ ?>
                                        <li id="selectProduct"><a class="nav-link" href="<?php echo $form[22]->url; ?>"><?php echo $form[22]->title; ?></a></li>
                                    <?php } if($accessList['read_24'] == "on"){ ?>
                                        <li id="newProduct"><a class="nav-link" href="<?php echo $form[23]->url; ?>"><?php echo $form[23]->title; ?></a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php }
                        if($accessList['read_25'] == "on"){ ?>
                            <li id="covered">
                                <a class="nav-link" href="<?php echo $form[24]->url; ?>"><i class="flaticon-placeholder"></i><span><?php echo $form[24]->title; ?></span></a>
                            </li>
                        <?php }
                            if($accessList['read_26'] == "on" || $accessList['read_33'] == "on" || $accessList['read_34'] == "on" ||
                            $accessList['read_27'] == "on" || $accessList['read_28'] == "on"){
                        ?>
                            <li id="manageCall" class="dropdown">
                                <a href="#" class="nav-link has-dropdown"><i data-feather="phone-call"></i><span>مدیریت تماس ها</span></a>
                                <ul class="dropdown-menu">
                                    <?php if($accessList['read_26'] == "on"){ ?>
                                        <li id="freeCall"><a class="nav-link" href="<?php echo $form[25]->url; ?>"><?php echo $form[25]->title; ?></a></li>
                                    <?php } if($accessList['read_33'] == "on"){ ?>
                                        <li id="numberAnswer"><a class="nav-link" href="<?php echo $form[32]->url; ?>"><?php echo $form[32]->title; ?></a></li>
                                    <?php } if($accessList['read_34'] == "on"){ ?>
                                        <li id="timeAnswer"><a class="nav-link" href="<?php echo $form[33]->url; ?>"><?php echo $form[33]->title; ?></a></li>
                                    <?php } if($accessList['read_27'] == "on"){ ?>
                                        <li id="callList"><a class="nav-link" href="<?php echo $form[26]->url; ?>"><?php echo $form[26]->title; ?></a></li>
                                    <?php } if($accessList['read_28'] == "on"){ ?>
                                        <li id="freeCallForCustomer"><a class="nav-link" href="<?php echo $form[27]->url; ?>"><?php echo $form[27]->title; ?></a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php }
                            if($accessList['read_29'] == "on" || $accessList['read_30'] == "on"){
                        ?>
                            <li id="manageBrand" class="dropdown">
                                <a href="#" class="nav-link has-dropdown"><i data-feather="trending-up"></i><span>ارتقاء لوگو</span></a>
                                <ul class="dropdown-menu">
                                    <?php if($accessList['read_29'] == "on"){ ?>
                                        <li id="upLogo"><a class="nav-link" href="<?php echo $form[28]->url; ?>"><?php echo $form[28]->title; ?></a></li>
                                    <?php } if($accessList['read_30'] == "on"){ ?>
                                        <li id="upLogoSpetial"><a class="nav-link" href="<?php echo $form[29]->url; ?>"><?php echo $form[29]->title; ?></a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php }
                            if($accessList['read_31'] == "on"){
                        ?>
                            <li id="transAction">
                                <a class="nav-link" href="<?php echo $form[30]->url; ?>"><i data-feather="dollar-sign"></i><span><?php echo $form[30]->title; ?></span></a>
                            </li>
                        <?php } ?>
                    @endif

                </ul>
            </aside>
        </div>
        <!-- Main Content -->
        <div class="main-content">

            @yield('content')

        </div>
        <footer class="main-footer">
            <div class="footer-left">
                حق چاپ © 2019 <div class="bullet"></div>طراحی توسط <a href="#">مهدی هاشمی</a>
            </div>
            <div class="footer-right">
            </div>
        </footer>
    </div>
</div>

<!-- General JS Scripts -->
<script src="/manage/js/app.min.js"></script>
<!-- JS Libraies -->
{{--<script src="/manage/bundles/echart/echarts.js"></script>--}}
{{--<script src="/manage/bundles/chartjs/chart.min.js"></script>--}}
<!-- Page Specific JS File -->
{{--<script src="/manage/js/page/index.js"></script>--}}
<!-- Template JS File -->
<script src="/manage/js/scripts.js"></script>
<!-- Custom JS File -->
<script src="/manage/js/custom.js"></script>
<script src="/manage/js/cleave.min.js"></script>
<script src="/manage/js/cleave-phone.i18n.js"></script>
<script src="/manage/bundles/lightgallery/dist/js/lightgallery-all.js"></script>
<script src="/manage/js/light-gallery.js"></script>
{{--<script src="/manage/js/cleave-phone-type-formatter-ir.js"></script>--}}
{{--<script src="/manage/js/core/mask.js"></script>--}}
<script src="/manage/js/masking-input.js"></script>
<script src="/manage/js/core/mask.js"></script>
<script type="text/javascript" src="/manage/js/plugins/bootstrap-select/bootstrap-select.min.js"></script>
@yield('bottomHead')
</body>


</html>
