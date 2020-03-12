<?php

use Illuminate\Support\Facades\Auth;

$workFieldArticle = json_decode(Auth::user()->workFieldArticle, true);
$workFieldService = json_decode(Auth::user()->workFieldService, true);
$workLocal = json_decode(Auth::user()->workLocal, true);
if($workFieldArticle == null){
    $workFieldArticle = array();
}
if($workFieldService == null){
    $workFieldService = array();
}
if($workLocal == null){
    $workLocal = array();
}

?>

@extends('manage.master')

@section('bottomHead')

@endsection

@section('content')
    <section class="section">

        @if(Auth::user()->userRole ==3)
            @if( ((Auth::user()->workFieldArticle == "") || (Auth::user()->workFieldService == "")) &&  (Auth::user()->workLocal == ""))
                <div class="alert alert-warning alert-has-icon">
                    <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                    <div class="alert-body">
                        <div class="alert-title">هشدار</div>
                        حوزه کاری و مناطق تحت پوشش را مشخص نمایید.
                    </div>
                </div>
            @endif
        @endif

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h4>
                                پروفایل {{Auth::user()->name}} {{Auth::user()->family}}
                            </h4>
                        </div>
                        <form class="form-horizontal" method="post" enctype="multipart/form-data" id="form">
                            <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <label>لوگو برند کالا</label>
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
                                        @if(Auth::user()->url_avatar)
                                            <img alt="{{Auth::user()->name}}" src="{{Auth::user()->url_avatar}}" id="thumb-output">
                                        @else
                                            <img alt="تصویر" src="/manage/img/products/product-5.png" id="thumb-output">
                                        @endif

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>نام</label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{Auth::user()->name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>فامیلی</label>
                                            <input type="text" class="form-control" name="family" id="family" value="{{Auth::user()->family}}">
                                        </div>
                                    </div>
                                    @if(Auth::user()->userRole ==3)
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>نام شرکت/فروشگاه</label>
                                                <input type="text" class="form-control" name="company" id="company" value="{{Auth::user()->title_company}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>استان</label>
                                                <select class="form-control selectpicker" name="state[]" id="state" data-live-search="true" multiple>
                                                    @foreach($states as $state)
                                                        @if(in_array($state->id, $workLocal))
                                                            <option selected value="{{ $state->id }}">{{ $state->title }}</option>
                                                        @else
                                                            <option value="{{ $state->id }}">{{ $state->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('state')
                                            <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>حوزه کاری کالا</label>
                                                <select class="form-control selectpicker" name="article[]" id="article" data-live-search="true" multiple>
                                                    @foreach($articles as $article)
                                                        @if(in_array($article->id, $workFieldArticle))
                                                            <option selected value="{{ $article->id }}">{{ $article->title }}</option>
                                                        @else
                                                            <option value="{{ $article->id }}">{{ $article->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('article')
                                            <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>حوزه کاری خدمات</label>
                                                <select class="form-control selectpicker" name="service[]" id="service" data-live-search="true" multiple>
                                                    @foreach($services as $service)
                                                        @if(in_array($service->id, $workFieldService))
                                                            <option selected value="{{ $service->id }}">{{ $service->title }}</option>
                                                        @else
                                                            <option value="{{ $service->id }}">{{ $service->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('service')
                                            <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div class="content-group">
                                            <label> </label>
                                            <div>
                                                <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary" onclick="$('#startSpinner').click();">ثبت</button>
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
