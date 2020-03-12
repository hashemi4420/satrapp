<?php

use Illuminate\Support\Facades\Auth;

$accessLevel = Auth::user()->usrRole;
$accessList = json_decode($accessLevel->json, true);

?>


@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('manage').classList.add('active');
        document.getElementById('log').classList.add('active');
    </script>
    <script type="text/javascript" src="/manage/js/core/logHistoryController.js"></script>
@endsection

@section('content')
    <form class="form-horizontal" method="post" id="form">
        <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">
    </form>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h4>رویدادنگاری</h4>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">
                                    <h4>جستجو رویدادها</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>رویداد</label>
                                                <select class="form-control selectpicker" name="logh" id="logh" data-live-search="true">
                                                    <option value="">انتخاب کنید</option>
                                                    <option value="1">مشاهده فرم</option>
                                                    <option value="2">جستجو</option>
                                                    <option value="3">ویرایش اطلاعات</option>
                                                    <option value="4">ایجاد اطلاعات</option>
                                                    <option value="5">تایید اطلاعات</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>فرم</label>
                                                <select class="form-control selectpicker" name="formh" id="formh" data-live-search="true">
                                                    <option value="">انتخاب کنید</option>
                                                    @foreach($forms as $form)
                                                        <option value="{{ $form->id }}">{{ $form->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>کاربر</label>
                                                <input type="text" class="form-control" name="userh" id="userh">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div class="content-group">
                                                <label> </label>
                                                <div>
                                                    <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary btn-labeled btn-xlg"
                                                            onclick="searchLog(
                                                                        document.getElementById('logh').value,
                                                                        document.getElementById('formh').value,
                                                                        document.getElementById('userh').value,
                                                                        document.getElementById('token').value
                                                                    )">جستجو<i class="icon-search4"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4>لیست رویداد ها</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>کاربر <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>فرم <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>رویداد</th>
                                            </tr>
                                            </thead>
                                            <tbody id="dataTable">
                                            <?php
                                            $id = "";
                                            if(isset($_GET['page']))
                                                $id = $_GET['page'];
                                            if($id == "") {
                                                $id = 1;
                                            }
                                            else {
                                                $id = ($id * 20) - 19;
                                            }
                                            ?>
                                            @foreach($logHistories as $logHistory)
                                                <tr>
                                                    <td><?php echo $id++; ?></td>
                                                    <td>{{$logHistory->user->email}}</td>
                                                    <td>{{$logHistory->form->title}}</td>
                                                    <td>{{$logHistory->action}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <nav aria-label="Page navigation example">
                                        {!! $logHistories->render() !!}
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
