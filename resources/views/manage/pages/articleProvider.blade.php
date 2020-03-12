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
        document.getElementById('articleProvider').classList.add('active');
    </script>
    <script type="text/javascript" src="/manage/js/core/articleProvider.js"></script>
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
                        <form class="form-horizontal" method="post" id="form">
                            <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="card-body">
                                <div class="row">
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
                                            <label>حوزه کالا</label>
                                            <select class="form-control selectpicker" name="area" id="area" data-live-search="true"
                                                    onchange="getArticleGroupProvider(this.value,  document.getElementById('token').value, '#group')">
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
                                            <label>گروه کالا</label>
                                            <select class="form-control selectpicker" name="group" id="group" data-live-search="true"
                                                    onchange="getArticleCategoryProvider(this.value,  document.getElementById('token').value, '#category')">
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
                                            <select class="form-control selectpicker" name="category" id="category" data-live-search="true"
                                                    onchange="getArticleProvider(document.getElementById('brand').value,
                                                                                    document.getElementById('area').value,
                                                                                    document.getElementById('group').value,
                                                                                    this.value,  document.getElementById('token').value, '#articlee')">
                                                <option value="">انتخاب کنید</option>
                                            </select>
                                        </div>
                                        @error('category')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>کالا</label>
                                            <select class="form-control selectpicker" name="article" id="articlee" data-live-search="true">
                                                <option value="">انتخاب کنید</option>
                                            </select>
                                        </div>
                                        @error('article')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>قیمت(تومان)</label>
                                            <input type="text" class="form-control" name="price" id="price">
                                            <input type="text" id="providerId" name="providerId" style="display: none">
                                        </div>
                                        @error('price')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-8 col-lg-8 col-sm-8">
                                        <div>
                                            <label>توضیحات</label>
                                            <textarea class="form-control" name="status" id="status" rows="5" style="height: 100px !important;"></textarea>
                                        </div>
                                        @error('status')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div class="content-group">
                                            <label> </label>
                                            <?php if ($accessList['insert_14'] == "on"){?>
                                            <div>
                                                <button type="submit" id="save" style="display: block; width: 100%; margin-top: 55px" class="btn btn-primary" onclick="$('#startSpinner').click();">ثبت</button>
                                            </div>
                                            <?php } if ($accessList['update_14'] == "on"){?>
                                            <div>
                                                <div class="col-md-6">
                                                    <button type="button" id="update" style="display: none; width: 100%; margin-top: 55px" class="btn btn-primary" onclick="updateArticleProvider(true, '#providerId', '#article', '#price', '#status', document.getElementById('token').value)">ویرایش</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="button" id="cancel" style="display: none; width: 100%; margin-top: 55px" class="btn btn-danger" onclick="updateArticleProvider(false, '#providerId', '#article', '#price', '#status', document.getElementById('token').value)">لغو</button>
                                                </div>
                                            </div>
                                            <?php } ?>
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
                                                <label>حوزه کالا</label>
                                                <select class="form-control selectpicker" name="searchArea" id="searchArea" data-live-search="true"
                                                        onchange="getArticleGroupProvider(this.value,  document.getElementById('token').value, '#searchGroup')">
                                                    <option value="">انتخاب کنید</option>
                                                    @foreach($areas as $area)
                                                        <option value="{{ $area->id }}">{{ $area->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>گروه کالا</label>
                                                <select class="form-control selectpicker" name="searchGroup" id="searchGroup" data-live-search="true"
                                                        onchange="getArticleCategoryProvider(this.value,  document.getElementById('token').value, '#searchCategory')">
                                                    <option value="">انتخاب کنید</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>دسته کالا</label>
                                                <select class="form-control selectpicker" name="searchCategory" id="searchCategory" data-live-search="true"
                                                        onchange="getArticleProvider(document.getElementById('searchBrand').value,
                                                                                    document.getElementById('searchArea').value,
                                                                                    document.getElementById('searchGroup').value,
                                                                                    this.value,  document.getElementById('token').value, '#searchArticle')">
                                                    <option value="">انتخاب کنید</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>کالا</label>
                                                <select class="form-control selectpicker" name="searchArticle" id="searchArticle" data-live-search="true">
                                                    <option value="">انتخاب کنید</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>قیمت(تومان)</label>
                                                <input type="text" class="form-control" name="searchPrice" id="searchPrice">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div class="content-group">
                                                <label> </label>
                                                <div>
                                                    <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary btn-labeled btn-xlg"
                                                            onclick="searchArticleProvider(
                                                                        document.getElementById('searchBrand').value,
                                                                        document.getElementById('searchArea').value,
                                                                        document.getElementById('searchGroup').value,
                                                                        document.getElementById('searchCategory').value,
                                                                        document.getElementById('searchArticle').value,
                                                                        document.getElementById('searchPrice').value,
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
                                                <th>قیمت(تومان) <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                @if(Auth::user()->userRole != 3)
                                                    <th>کاربر ثبت کننده <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                @endif
                                                <?php if ($accessList['update_14'] == "on"){?>
                                                <th>ویرایش <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <?php }?>
                                                <th>فعال/غیرفعال</th>
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
                                                    <td><div class="list-unstyled row clearfix aniimated-thumbnials">
                                                            <a href="{{$article->article->url_avatar}}" data-sub-html="{{$article->article->title}}">
                                                                <img class="img-responsive thumbnail" src="{{$article->article->url_avatar}}" alt="{{$article->article->title}}" width="35">
                                                            </a>
                                                        </div>
                                                    <td>{{$article->article->title}}</td>
                                                    <td>{{$article->article->brand->title}}</td>
                                                    <td>{{$article->article->area->title}}</td>
                                                    <td>{{$article->article->group->title}}</td>
                                                    <td>{{$article->article->category->title}}</td>
                                                    <td>{{$article->price}}</td>
                                                    @if(Auth::user()->userRole != 3)
                                                        @if($article->user != null)
                                                            <td>{{$article->email}}</td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    @endif
                                                    <?php if ($accessList['update_14'] == "on"){?>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                                                onclick="startUpdateArticleProvider({{$article->id}}, document.getElementById('token').value, '#providerId', '#brand', '#area', '#group', '#category', '#article', '#price', '#status')">
                                                            <i class="icon-search4"></i>
                                                        </button>
                                                    </td>
                                                    <?php } ?>
                                                    @if($article->active)
                                                        <td>
                                                            <button type="button" class="btn btn-success btn-icon btn-rounded"
                                                                    onclick="activeArticleProvider({{$article->id}}, document.getElementById('token').value)">
                                                                <i class="icon-user-check"></i>
                                                            </button>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <button type="button" class="btn-secondary btn-icon btn-rounded"
                                                                    onclick="activeArticleProvider({{$article->id}}, document.getElementById('token').value)">
                                                                <i class="icon-user-block"></i>
                                                            </button>
                                                        </td>
                                                    @endif
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
@endsection
