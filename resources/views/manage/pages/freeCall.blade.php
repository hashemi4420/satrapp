@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('manageCall').classList.add('active');
        document.getElementById('freeCall').classList.add('active');
    </script>
    <script type="text/javascript" src="/manage/js/core/freeCallController.js"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h4>فعال سازی تماس رایگان</h4>
                            <div class="pretty p-switch p-fill">
                                @if($freeCall == null || $freeCall->active == 0)
                                    <input type="checkbox" onchange="changeActivity(document.getElementById('token').value)">
                                @else
                                    <input type="checkbox" checked onchange="changeActivity(document.getElementById('token').value)">
                                @endif
                                <div class="state p-success">
                                    <label></label>
                                </div>
                            </div>
                        </div>
                        <div class="card-header">
                            <div style="padding: 10px 25px;">
                                <div class="row">
                                    <h5>اعتبار فعلی: {{$freeCall->pey}} تومان</h5>
                                </div>
                                <div class="row" style="margin-top: 10px">
                                    <h5>حداقل "اعتبار فعلی" باید ۲۰ هزار تومان باشد.</h5>
                                </div>
                                <div class="row">
                                    <button type="button" class="rollButton" onclick="document.getElementById('collapse').click()"> مطالعه قوانین</button>
                                    <a class="collapsed" id="collapse" style="display: none" data-toggle="collapse" href="#collapseExample">قوانین</a>
                                    <div class="collapse" id="collapseExample" style="">
                                        <p>
                                        <div style="margin-top: 10px">
                                            <h5>قوانین:</h5>
                                            <h5>
                                                <div style="width: 20px; position: absolute;">
                                                    <i class="glyph-icon flaticon-checked-1" style="color: #08d06d;"></i>
                                                </div>
                                                <div style="margin-right: 30px;">
                                                    <span style="line-height: 20px;">
                                                        حداقل "اعتبار فعلی" باید ۲۰ هزار تومان باشد
                                                    </span>
                                                </div>
                                            </h5>
                                            <h5>
                                                <div style="width: 20px; position: absolute;">
                                                    <i class="glyph-icon flaticon-checked-1" style="color: #08d06d;"></i>
                                                </div>
                                                <div style="margin-right: 30px;">
                                                    <span style="line-height: 20px;">
                                                        مبلغ ۱۵ هزار تومان از "اعتبار فعلی" به عنوان ودیعه می باشد و قابل استفاده نیست.
                                                    </span>
                                                </div>
                                            </h5>
                                            <h5>
                                                <div style="width: 20px; position: absolute;">
                                                    <i class="glyph-icon flaticon-checked-1" style="color: #08d06d;"></i>
                                                </div>
                                                <div style="margin-right: 30px;">
                                                    <span style="line-height: 20px;">
                                                        تا لحظه ای که مخاطب، در حالت انتظار می باشد هذ ثانیه ۲ تومان محاسبه می شود.
                                                    </span>
                                                </div>
                                            </h5>
                                            <h5>
                                                <div style="width: 20px; position: absolute;">
                                                    <i class="glyph-icon flaticon-checked-1" style="color: #08d06d;"></i>
                                                </div>
                                                <div style="margin-right: 30px;">
                                                    <span style="line-height: 20px;">
                                                        وقتی پاسخ مخاطب را بدهید و مکالمه نمایید، مجموعا هر ثانیه مکالمه ۴ تومان محاسبه می شود.
                                                    </span>
                                                </div>
                                            </h5>
                                        </div>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <form class="form-horizontal" method="post" id="form">
                            <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th style="width: 120px;">مبلغ شارژ <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                            <th>انتخاب</th>
                                        </tr>
                                        </thead>
                                        <tbody id="dataTable">
                                        <tr>
                                            <td>۳۰ هزار تومان</td>
                                            <td>
                                                <div class="pretty p-icon p-round">
                                                    <input type="radio" name="icon" onclick="changeResult(30000, '#result');">
                                                    <div class="state p-success-o">
                                                        <i class="icon material-icons"></i>
                                                        <label></label><i class="icon material-icons">done</i><label></label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>۵۰ هزار تومان</td>
                                            <td>
                                                <div class="pretty p-icon p-round">
                                                    <input type="radio" name="icon" onclick="changeResult(50000, '#result');">
                                                    <div class="state p-success-o">
                                                        <i class="icon material-icons"></i>
                                                        <label></label><i class="icon material-icons">done</i><label></label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>۱۰۰ هزار تومان</td>
                                            <td>
                                                <div class="pretty p-icon p-round">
                                                    <input type="radio" name="icon" onclick="changeResult(100000, '#result');">
                                                    <div class="state p-success-o">
                                                        <i class="icon material-icons"></i>
                                                        <label></label><i class="icon material-icons">done</i><label></label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>۲۰۰ هزار تومان</td>
                                            <td>
                                                <div class="pretty p-icon p-round">
                                                    <input type="radio" name="icon" onclick="changeResult(200000, '#result');">
                                                    <div class="state p-success-o">
                                                        <i class="icon material-icons"></i>
                                                        <label></label><i class="icon material-icons">done</i><label></label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>۵۰۰ هزار تومان</td>
                                            <td>
                                                <div class="pretty p-icon p-round">
                                                    <input type="radio" name="icon" onclick="changeResult(500000, '#result');">
                                                    <div class="state p-success-o">
                                                        <i class="icon material-icons"></i>
                                                        <label></label><i class="icon material-icons">done</i><label></label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>۱ میلیون تومان</td>
                                            <td>
                                                <div class="pretty p-icon p-round">
                                                    <input type="radio" name="icon" onclick="changeResult(1000000, '#result');">
                                                    <div class="state p-success-o">
                                                        <i class="icon material-icons"></i>
                                                        <label></label><i class="icon material-icons">done</i><label></label>
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
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div class="content-group">
                                            <label> </label>
                                            <div>
                                                <button type="button" style="display: block; width: 100%" class="btn btn-primary" onclick="save('#result', document.getElementById('token').value);">پرداخت</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
