<?php

use Illuminate\Support\Facades\Auth;

$accessLevel = Auth::user()->usrRole;
$accessList = json_decode($accessLevel->json, true);
?>

@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('service').classList.add('active');
        document.getElementById('serviceBrands').classList.add('active');
    </script>
    <script type="text/javascript" src="/manage/js/core/serviceBrandController.js"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h4>تعریف برند خدمات</h4>
                        </div>
                        <form class="form-horizontal" method="post" enctype="multipart/form-data" id="form">
                            <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <label>لوگو برند خدمات</label>
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
                                    <div class="col-md-8 col-lg-8 col-sm-8">
                                        <div>
                                            <label>نام برند خدمات</label>
                                            <input type="text" class="form-control" name="name" id="name">
                                            <input type="text" id="brandId" name="brandId" style="display: none">
                                        </div>
                                        @error('name')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div class="content-group">
                                            <label> </label>
                                            <?php if ($accessList['insert_16'] == "on"){?>
                                                <div>
                                                    <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary" onclick="$('#startSpinner').click();">ثبت</button>
                                                </div>
                                            <?php } if ($accessList['update_16'] == "on"){?>
                                                <div>
                                                    <div class="col-md-6">
                                                        <button type="button" id="update" style="display: none; width: 100%" class="btn btn-primary" onclick="updateServiceBrand(true, '#brandId', '#name', '#avatar', document.getElementById('token').value)">ویرایش</button>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="button" id="cancel" style="display: none; width: 100%" class="btn btn-danger" onclick="updateServiceBrand(false, '#brandId', '#name', '#avatar', document.getElementById('token').value)">لغو</button>
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
                            <h4>برند خدمات</h4>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">
                                    <h4>جستجو برند خدمات</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8 col-lg-8 col-sm-8">
                                            <div>
                                                <label>نام برند خدمات</label>
                                                <input type="text" class="form-control" name="searchName" id="searchName">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div class="content-group">
                                                <label> </label>
                                                <div>
                                                    <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary btn-labeled btn-xlg"
                                                            onclick="searchServiceBrand(
                                                                        document.getElementById('searchName').value,
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
                                    <h4>لیست برند خدمات</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>لوگو <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>برند <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <?php if ($accessList['update_16'] == "on"){?>
                                                <th>ویرایش <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <?php } if ($accessList['delete_16'] == "on"){?>
                                                <th>حذف</th>
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
                                            @foreach($brands as $brand)
                                                <tr>
                                                    <td><?php echo $id++; ?></td>
                                                    <td>
                                                        <div class="list-unstyled row clearfix aniimated-thumbnials">
                                                            <a href="{{$brand->url_avatar}}" data-sub-html="{{$brand->title}}">
                                                                <img class="img-responsive thumbnail" src="{{$brand->url_avatar}}" alt="{{$brand->title}}" width="35">
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>{{$brand->title}}</td>
                                                    <?php if ($accessList['update_16'] == "on"){?>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                                                onclick="startUpdateServiceBrand({{$brand->id}}, document.getElementById('token').value, '#brandId', '#name', '#thumb-output')">
                                                            <i class="icon-search4"></i>
                                                        </button>
                                                    </td>
                                                    <?php } if ($accessList['delete_16'] == "on"){?>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                                                onclick="deleteServiceBrand({{$brand->id}}, document.getElementById('token').value)">
                                                            <i class="icon-trash"></i>
                                                        </button>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <nav aria-label="Page navigation example">
                                        {!! $brands->render() !!}
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
