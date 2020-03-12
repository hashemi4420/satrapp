@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('manageBrand').classList.add('active');
        document.getElementById('upLogo').classList.add('active');
    </script>
    <script type="text/javascript" src="/manage/js/core/upLogoController.js"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-body">

                            <div class="card">
                                <div class="card-header">
                                    <h4>ارتقاء لوگو</h4>
                                </div>
                                <div class="card-header">
                                    <div style="padding: 10px 25px;">
                                        <div class="row">
                                            <h5>اعتبار فعلی: {{Auth::user()->cash}} تومان</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form class="form-horizontal" method="post" enctype="multipart/form-data" id="form">
                                        <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>صفحه <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>قیمت (تومان) <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>انتخاب</th>
                                                </tr>
                                                </thead>
                                                <tbody id="dataTable">
                                                <tr>
                                                    <td>صفحه اصلی ردیف "تامین کنندگان"</td>
                                                    <td>۱۰۰۰۰</td>
                                                    <td>
                                                        <div class="pretty p-default">
                                                            <input type="checkbox" name="reseller" id="reseller" onchange="changeResult(10000,'#number', '#result',this.checked)">
                                                            <div class="state p-primary">
                                                                <label></label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>صفحه دوم "همه تامین کنندگان"</td>
                                                    <td>۵۰۰۰</td>
                                                    <td>
                                                        <div class="pretty p-default">
                                                            <input type="checkbox" name="allReseller" id="allReseller" onchange="changeResult(5000,'#number','#result',this.checked)">
                                                            <div class="state p-primary">
                                                                <label></label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>صفحه اصلی ردیف"تماس های رایگان"</td>
                                                    <td>۱۰۰۰۰</td>
                                                    <td>
                                                        <div class="pretty p-default">
                                                            <input type="checkbox" name="freeCall" id="freeCall" onchange="changeResult(10000,'#number','#result',this.checked)">
                                                            <div class="state p-primary">
                                                                <label></label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>صفحه دوم "همه تماس های رایگان"</td>
                                                    <td>۵۰۰۰</td>
                                                    <td>
                                                        <div class="pretty p-default">
                                                            <input type="checkbox" name="allFreeCall" id="allFreeCall" onchange="changeResult(5000,'#number','#result',this.checked)">
                                                            <div class="state p-primary">
                                                                <label></label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>صفحه اصلی ردیف"پیشنهاد"</td>
                                                    <td>۱۰۰۰۰</td>
                                                    <td>
                                                        <div class="pretty p-default">
                                                            <input type="checkbox" name="sagest" id="sagest" onchange="changeResult(10000,'#number','#result',this.checked)">
                                                            <div class="state p-primary">
                                                                <label></label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>صفحه دوم"همه پیشنهادها"</td>
                                                    <td>۵۰۰۰</td>
                                                    <td>
                                                        <div class="pretty p-default">
                                                            <input type="checkbox" name="allSagest" id="allSagest" onchange="changeResult(5000,'#number','#result',this.checked)">
                                                            <div class="state p-primary">
                                                                <label></label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <label>مجموع: </label>
                                        <label id="result">۰</label>
                                        <label>تومان</label>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div>
                                                    <input type="text" value="0" id="number" name="number" style="display: none">
                                                </div>

                                                @if($error != '')
                                                <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $error }}</span>
                                                @endif

                                                @if($success != '')
                                                    <span class="help-block text-success"><i class="icon-cancel-circle2 position-left"></i>{{ $success }}</span>
                                                @endif
                                            </div>

                                            <div class="col-md-3">
                                                <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary" onclick="$('#startSpinner').click();">ثبت</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
