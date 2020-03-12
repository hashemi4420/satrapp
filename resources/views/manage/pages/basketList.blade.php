@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('manageBasket').classList.add('active');
        document.getElementById('basketList').classList.add('active');
    </script>
    <script type="text/javascript" src="/manage/js/core/basketListController.js"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h4></h4>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">
                                    <h4>لیست خدمات و محصولات فعلی</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form-horizontal" method="post" id="form">
                                        <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>تصویر <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>برند <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>نوع <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>وضعیت (فعال / غیر فعال) <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>درصد تخفیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>قیمت(تومان) <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>حداقل خرید(تومان) <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>حذف</th>
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
                                                @if(Auth::user()->workFieldArticle != null && Auth::user()->workFieldArticle != "")
                                                    @foreach($articles as $article)
                                                        <tr>
                                                            <td><?php echo $id++; ?></td>
                                                            <td>
                                                                <div class="list-unstyled row clearfix aniimated-thumbnials">
                                                                    <a href="{{$article->article->url_avatar}}" data-sub-html="{{$article->article->title}}">
                                                                        <img class="img-responsive thumbnail" src="{{$article->article->url_avatar}}" alt="{{$article->article->title}}" width="35">
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <td>{{$article->article->brand->title}}</td>
                                                            <td>{{$article->article->title}}</td>
                                                            <td>
                                                            @if($article->active)
                                                                <input type="checkbox" checked onchange="updateArticle({{$article->id}}, document.getElementById('token').value)"/>
                                                            @else
                                                                <input type="checkbox" onchange="updateArticle({{$article->id}}, document.getElementById('token').value)"/>
                                                            @endif
                                                            </td>

                                                            <td>
                                                                <input type="tel" step="any" class="form-control" value="{{$article->status}}" onkeyup="validate_int(this.value, this.id)" name="darsad{{$article->id}}" id="darsat{{$article->id}}">
                                                            </td>
                                                            <td>
                                                                <input type="tel" step="any" class="form-control" value="{{$article->price}}" onkeyup="validate_int(this.value, this.id)" name="pey{{$article->id}}" id="pey{{$article->id}}">
                                                            </td>
                                                            <td>
                                                                <input type="tel" step="any" class="form-control" value="{{$article->atless}}" onkeyup="validate_int(this.value, this.id)" name="atless{{$article->id}}" id="atless{{$article->id}}">
                                                            </td>

                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                                                        onclick="deleteArticle({{$article->id}}, document.getElementById('token').value)">
                                                                    <i class="icon-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                @if(Auth::user()->workFieldService != null && Auth::user()->workFieldService != "")
                                                    @foreach($services as $service)
                                                        <tr>
                                                            <td><?php echo $id++; ?></td>
                                                            <td>
                                                                <div class="list-unstyled row clearfix aniimated-thumbnials">
                                                                    <a href="{{$service->service->url_avatar}}" data-sub-html="{{$service->service->title}}">
                                                                        <img class="img-responsive thumbnail" src="{{$service->service->url_avatar}}" alt="{{$service->service->title}}" width="35">
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <td>{{$service->service->brand->title}}</td>
                                                            <td>{{$service->service->title}}</td>
                                                            <td>
                                                                @if($service->active)
                                                                    <input type="checkbox" checked onchange="updateService({{$service->id}}, document.getElementById('token').value)"/>
                                                                @else
                                                                    <input type="checkbox" onchange="updateService({{$service->id}}, document.getElementById('token').value)"/>
                                                                @endif
                                                            </td>

                                                            <td>
                                                                <input type="tel" step="any" class="form-control" value="{{$service->status}}" onkeyup="validate_int(this.value, this.id)" name="darsad{{$service->id}}" id="darsat{{$service->id}}">
                                                            </td>
                                                            <td>
                                                                <input type="tel" step="any" class="form-control" value="{{$service->price}}" onkeyup="validate_int(this.value, this.id)" name="pey{{$service->id}}" id="pey{{$service->id}}">
                                                            </td>
                                                            <td>
                                                                <input type="tel" step="any" class="form-control" value="{{$service->atless}}" onkeyup="validate_int(this.value, this.id)" name="atless{{$service->id}}" id="atless{{$service->id}}">
                                                            </td>

                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                                                        onclick="deleteService({{$service->id}}, document.getElementById('token').value)">
                                                                    <i class="icon-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <nav aria-label="Page navigation example">
                                                    @if(Auth::user()->workFieldArticle != null && Auth::user()->workFieldArticle != "")
                                                        {!! $articles->render() !!}
                                                    @endif

                                                    @if(Auth::user()->workFieldService != null && Auth::user()->workFieldService != "")
                                                        {!! $services->render() !!}
                                                    @endif
                                                </nav>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 15px">
                                            <div class="col-md-3">
                                                <button type="submit" id="save" name="save" value="save" style="display: block; width: 100%" class="btn btn-primary" onclick="$('#startSpinner').click();">اعمال تغییرات و بازگشت به صفحه اصلی</button>
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
