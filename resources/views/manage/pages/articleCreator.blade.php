<?php

use Illuminate\Support\Facades\Auth;

$accessLevel = Auth::user()->usrRole;
$accessList = json_decode($accessLevel->json, true);
?>

@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('article').classList.add('active');
        document.getElementById('articleCreators').classList.add('active');
    </script>
    <script type="text/javascript" src="/manage/js/core/articleCreatorController.js"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h4>تعریف کالا</h4>
                        </div>
                        <form class="form-horizontal" method="post" enctype="multipart/form-data" id="form">
                            <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <label>لوگو کالا</label>
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
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>حوزه کالا</label>
                                            <select class="form-control selectpicker" name="area" id="area" data-live-search="true"
                                                    onchange="getArticleGroupCreator(this.value,  document.getElementById('token').value, '#group')">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($areas as $area)
                                                    <option value="{{ $area->id }}">{{ $area->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('area')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>برند کالا</label>
                                            <select class="form-control selectpicker" name="brand" id="brand" data-live-search="true">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('brand')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>گروه کالا</label>
                                            <select class="form-control selectpicker" name="group" id="group" data-live-search="true"
                                                    onchange="getArticleCategories(this.value,  document.getElementById('token').value, '#category')">
                                                <option value="">انتخاب کنید</option>
                                            </select>
                                        </div>
                                        @error('group')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>دسته کالا</label>
                                            <select class="form-control selectpicker" name="category" id="category" data-live-search="true">
                                                <option value="">انتخاب کنید</option>
                                            </select>
                                        </div>
                                        @error('category')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>نام کالا</label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}">
                                            <input type="text" id="articleId" name="articleId" style="display: none">
                                        </div>
                                        @error('name')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div class="content-group">
                                            <label> </label>
                                            <?php if ($accessList['insert_13'] == "on"){?>
                                                <div>
                                                    <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary" onclick="$('#startSpinner').click();">ثبت</button>
                                                </div>
                                            <?php }if ($accessList['update_13'] == "on"){?>
                                                <div>
                                                    <div class="col-md-6">
                                                        <button type="button" id="update" style="display: none; width: 100%" class="btn btn-primary" onclick="updateArticleCreator(true, '#articleId', '#name', '#avatar', '#brand', '#area', '#group', '#category', document.getElementById('token').value)">ویرایش</button>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="button" id="cancel" style="display: none; width: 100%" class="btn btn-danger" onclick="updateArticleCreator(false, '#articleId', '#name', '#avatar', '#brand', '#area', '#group', '#category', document.getElementById('token').value)">لغو</button>
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
                            <h4>کالاها</h4>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">
                                    <h4>جستجو کالاها</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>حوزه کالا</label>
                                                <select class="form-control selectpicker" name="searchArea" id="searchArea" data-live-search="true"
                                                        onchange="getArticleGroupCreator(this.value,  document.getElementById('token').value, '#searchGroup')">
                                                    <option value="">انتخاب کنید</option>
                                                    @foreach($areas as $area)
                                                        <option value="{{ $area->id }}">{{ $area->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>برند کالا</label>
                                                <select class="form-control selectpicker" name="searchBrand" id="searchBrand" data-live-search="true">
                                                    <option value="">انتخاب کنید</option>
                                                    @foreach($brands as $brand)
                                                        <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>گروه کالا</label>
                                                <select class="form-control selectpicker" name="searchGroup" id="searchGroup" data-live-search="true"
                                                        onchange="getArticleCategories(this.value,  document.getElementById('token').value, '#searchCategory')">
                                                    <option value="">انتخاب کنید</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>دسته کالا</label>
                                                <select class="form-control selectpicker" name="searchCategory" id="searchCategory" data-live-search="true">
                                                    <option value="">انتخاب کنید</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>نام کالا</label>
                                                <input type="text" class="form-control" name="searchName" id="searchName">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div class="content-group">
                                                <label> </label>
                                                <div>
                                                    <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary btn-labeled btn-xlg"
                                                            onclick="searchArticleCreator(
                                                                        document.getElementById('searchBrand').value,
                                                                        document.getElementById('searchArea').value,
                                                                        document.getElementById('searchGroup').value,
                                                                        document.getElementById('searchCategory').value,
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
                                    <h4>لیست کالاها</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>لوگو <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>کالا <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>برند <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>حوزه <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>گروه <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>دسته <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                @if(Auth::user()->userRole != 3)
                                                    <th>کاربر ثبت کننده <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                @endif
                                                <?php if ($accessList['update_13'] == "on"){?>
                                                <th>ویرایش <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>فعال/غیرفعال <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <?php }if ($accessList['delete_13'] == "on"){?>
                                                <th>حذف</th>
                                                <?php }?>
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
                                            @foreach($articles as $article)
                                                <tr>
                                                    <td><?php echo $id++; ?></td>
                                                    <td>
                                                        <div class="list-unstyled row clearfix aniimated-thumbnials">
                                                            <a href="{{$article->url_avatar}}" data-sub-html="{{$article->title}}">
                                                                <img class="img-responsive thumbnail" src="{{$article->url_avatar}}" alt="{{$article->title}}" width="35">
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>{{$article->title}}</td>
                                                    <td>{{$article->brand->title}}</td>
                                                    <td>{{$article->area->title}}</td>
                                                    <td>{{$article->group->title}}</td>
                                                    <td>{{$article->category->title}}</td>
                                                    @if(Auth::user()->userRole != 3)
                                                        @if($article->user != null)
                                                            <td>{{$article->user->email}}</td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    @endif
                                                    <?php if ($accessList['update_13'] == "on"){?>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                                                onclick="startUpdateArticleCreator({{$article->id}}, document.getElementById('token').value, '#articleId', '#name', '#thumb-output', '#brand', '#area', '#group', '#category')">
                                                            <i class="icon-search4"></i>
                                                        </button>
                                                    </td>
                                                    @if($article->active)
                                                        <td id="{{$article->id}}">
                                                            <button type="button" class="btn btn-success btn-icon btn-rounded"
                                                                    onclick="activeArticle({{$article->id}}, document.getElementById('token').value)">
                                                                <i class="icon-user-check"></i>
                                                            </button>
                                                        </td>
                                                    @else
                                                        <td id="{{$article->id}}">
                                                            <button type="button" class="btn-secondary btn-icon btn-rounded"
                                                                    onclick="activeArticle({{$article->id}}, document.getElementById('token').value)">
                                                                <i class="icon-user-block"></i>
                                                            </button>
                                                        </td>
                                                    @endif
                                                    <?php }if ($accessList['delete_13'] == "on"){?>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                                                onclick="deleteArticleCreator({{$article->id}}, document.getElementById('token').value)">
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
                                        {!! $articles->render() !!}
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
