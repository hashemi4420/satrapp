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
        document.getElementById('users').classList.add('active');
    </script>
    <script type="text/javascript" src="/manage/js/core/userController.js"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h4>تعریف کاربر</h4>
                        </div>
                        <form class="form-horizontal" method="post" enctype="multipart/form-data" id="form">
                            <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <label>لوگو کاربر</label>
                                        <div>
                                            <input type="file" class="file-styled" id="avatar" name="avatar">
                                            <span class="help-block">حداکثر سایز مجاز 2 مگابایت می باشد.</span>
                                        </div>
                                        @error('avatar')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5"></div>
                                    <div class="col-md-3 col-lg-3 col-sm-3 logo">
                                        <img alt="تصویر" src="/manage/img/products/product-5.png" id="thumb-output">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                        <div>
                                            <label>نام</label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}">
                                            <input type="text" id="userId" name="userId" style="display: none">
                                        </div>
                                        @error('name')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                        <div>
                                            <label>نام خانوادگی</label>
                                            <input type="text" class="form-control" name="family" id="family" value="{{old('family')}}">
                                        </div>
                                        @error('family')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                        <div>
                                            <label>شماره همراه</label>
                                            <input type="tel" step="any" class="form-control" onkeyup="validate_int(this.value, this.id)"
                                                   value="{{old('phone')}}" name="phone" id="phone" maxlength="11" style="direction: ltr;text-align: right;">
                                        </div>
                                        @error('phone')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                        <div>
                                            <label>ایمیل</label>
                                            <input type="text" class="form-control" name="email" id="email" value="{{old('email')}}">
                                        </div>
                                        @error('email')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                        <div class="content-group">
                                            <label>کلمه عبور</label>
                                            <input type="password" class="form-control" name="password" id="password" value="{{old('password')}}">
                                        </div>
                                        @error('password')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                        <div>
                                            <label>برند کالا</label>
                                            <select class="form-control selectpicker" name="articleBrand_id" id="articleBrand_id" data-live-search="true" onchange="disableService(this.value)">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($articleBrands as $articleBrand)
                                                    <option value="{{ $articleBrand->id }}">{{ $articleBrand->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('articleBrand_id')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                        <div class="content-group">
                                            <label>برند خدمات</label>
                                            <select class="form-control selectpicker" name="serviceBrand_id" id="serviceBrand_id" data-live-search="true" onchange="disableArticle(this.value)">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($serviceBrands as $serviceBrand)
                                                    <option value="{{ $serviceBrand->id }}">{{ $serviceBrand->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('serviceBrand_id')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                        <div class="content-group">
                                            <label>نقش کاربری</label>
                                            <select class="form-control selectpicker" name="userRole" id="userRole" data-live-search="true">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($accessLevels as $accessLevel)
                                                    <option value="{{ $accessLevel->id }}">{{ $accessLevel->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('userRole')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                        <div class="content-group">
                                            <label> </label>
                                            <?php
                                                if($accessList['insert_1'] == "on"){
                                            ?>
                                                <div>
                                                    <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary" onclick="$('#startSpinner').click();">ثبت</button>
                                                </div>
                                            <?php
                                                }
                                                if($accessList['update_1'] == "on") {
                                            ?>
                                                <div>
                                                    <div class="col-md-6">
                                                        <button type="button" id="update" style="display: none; width: 100%" class="btn btn-primary" onclick="updateUser(true, '#userId', '#name', '#family', '#phone', '#email', '#password', '#articleBrand_id', '#serviceBrand_id', '#userRole', '#avatar', document.getElementById('token').value)">ویرایش</button>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="button" id="cancel" style="display: none; width: 100%" class="btn btn-danger" onclick="updateUser(false, '#userId', '#name', '#family', '#phone', '#email', '#password', '#articleBrand_id', '#serviceBrand_id', '#userRole', '#avatar', document.getElementById('token').value)">لغو</button>
                                                    </div>
                                                </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>کاربران</h4>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">
                                    <h4>جستجو کاربران</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>ایمیل کاربر</label>
                                                <input type="text" class="form-control" name="searchName" id="searchName">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>شماره همراه</label>
                                                <input type="tel" step="any" class="form-control" onkeyup="validate_int(this.value, this.id)" name="searchPhone" id="searchPhone" maxlength="11" style="direction: ltr;text-align: center;">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div class="content-group">
                                                <label> </label>
                                                <div>
                                                    <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary btn-labeled btn-xlg"
                                                            onclick="searchUser(
                                                                        document.getElementById('searchName').value,
                                                                        document.getElementById('searchPhone').value,
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
                                    <h4>لیست کاربران</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr><th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>نام <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>نام خانوادگی <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>همراه <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>email <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>نقش کاربری <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>برند کالا <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>برند خدمات <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <?php
                                                if($accessList['update_1'] == "on") {
                                                ?>
                                                <th>ویرایش <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>فعال/غیرفعال <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>تغییر کلمه عبور <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <?php } ?>
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
                                            @foreach($users as $user)
                                                <tr>
                                                    <td><?php echo $id++; ?></td>
                                                    <td>{{$user->name}}</td>
                                                    <td>{{$user->family}}</td>
                                                    <td>{{$user->phone}}</td>
                                                    <td>{{$user->email}}</td>
                                                    <td>{{$user->usrRole->title}}</td>
                                                    @if($user->articleBrand_id != null)
                                                        <td>
                                                            {{$user->articleBrand->title}}
                                                        </td>
                                                    @else
                                                        <td style="color: red">
                                                            ندارد
                                                        </td>
                                                    @endif
                                                    @if($user->serviceBrand_id != null)
                                                        <td>
                                                            {{$user->serviceBrand->title}}
                                                        </td>
                                                    @else
                                                        <td style="color: red">
                                                            ندارد
                                                        </td>
                                                    @endif
                                                    <?php
                                                    if($accessList['update_1'] == "on") { ?>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                                                onclick="startUpdateUser({{$user->id}}, document.getElementById('token').value, '#userId', '#name', '#family', '#phone', '#email', '#password', '#articleBrand_id', '#serviceBrand_id', '#userRole', '#thumb-output')">
                                                            <i class="icon-search4"></i>
                                                        </button>
                                                    </td>
                                                    @if($user->active)
                                                        <td id="{{$user->id}}">
                                                            <button type="button" class="btn btn-success btn-icon btn-rounded"
                                                                    onclick="activeUser({{$user->id}}, document.getElementById('token').value)">
                                                                <i class="icon-user-check"></i>
                                                            </button>
                                                        </td>
                                                    @else
                                                        <td id="{{$user->id}}">
                                                            <button type="button" class="btn-secondary btn-icon btn-rounded"
                                                                    onclick="activeUser({{$user->id}}, document.getElementById('token').value)">
                                                                <i class="icon-user-block"></i>
                                                            </button>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <button type="button" class="btn btn-info btn-icon btn-rounded"
                                                                onclick="changePass({{$user->id}}, document.getElementById('token').value)">
                                                            <i class=" icon-shield2"></i>
                                                        </button>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <nav aria-label="Page navigation example">
                                        {!! $users->render() !!}
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function () {
            $('#avatar').on('change', function () {
                if (window.File && window.FileReader && window.FileList && window.Blob){
                    $('#thumb-output').html("");
                    var data = $(this)[0].files;

                    $.each(data, function (index, file) {
                        if ('/(\.|\/(gif|jpe?g|png)$/i.test(file.type)'){
                            var fRead = new FileReader();
                            fRead.onload = (function (file) {
                                return function (e) {
                                    // var img = $('<img/>').addClass('thumb').attr('src', e.target.result);
                                    $('#thumb-output').attr('src', e.target.result);
                                };
                            })(file);
                            fRead.readAsDataURL(file);
                        } else {

                        }
                    });
                }
            });
        });
    </script>
@endsection
