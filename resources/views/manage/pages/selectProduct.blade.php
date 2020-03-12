@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('manageBasket').classList.add('active');
        document.getElementById('selectProduct').classList.add('active');
    </script>
    <script type="text/javascript" src="/manage/js/core/selectProductController.js"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">

                        <div class="card-header">
                            <h4>فقط کافیست گزینه مورد نظر را تیک بزنید.</h4>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">
                                    <h4>خدمات و محصولات جدید</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form-horizontal" method="post" enctype="multipart/form-data" id="form">
                                        <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                                        <div class="row">
                                            <div class="col-md-4 col-lg-4 col-sm-4">
                                                <div>
                                                    <label>برند</label>
                                                    <select class="form-control selectpicker" name="searchBrand" id="searchBrand" data-live-search="true"
                                                            onchange="/*getProduct(this.value, 0,  document.getElementById('token').value)*/document.getElementById('save').click();">
                                                        <option value="">انتخاب کنید</option>
                                                        @foreach($brands as $brand)
                                                            @if($selected == $brand->id )
                                                                <option selected value="{{ $brand->id }}">{{ $brand->title }}</option>
                                                            @else
                                                                <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="submit" id="save" style="display: none; width: 100%" class="btn btn-primary" onclick="$('#startSpinner').click();">ثبت</button>
                                        </div>
                                    </form>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>تصویر <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>برند <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>نوع <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>انتخاب</th>
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
                                                    @foreach($results as $result)
                                                        <tr>
                                                            <td><?php echo $id++; ?></td>
                                                            <td>
                                                                <div class="list-unstyled row clearfix aniimated-thumbnials">
                                                                    <a href="{{$result->url_avatar}}" data-sub-html="{{$result->title}}">
                                                                        <img class="img-responsive thumbnail" src="{{$result->url_avatar}}" alt="{{$result->title}}" width="35">
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <td>{{$result->brand->title}}</td>
                                                            <td>{{$result->title}}</td>
                                                            <td>
                                                                @if(Auth::user()->workFieldArticle != null && Auth::user()->workFieldArticle != "")
                                                                    <input type="checkbox" onchange="saveArticle({{$result->id}}, '<?php echo csrf_token(); ?>')">
                                                                @else
                                                                    <input type="checkbox" onchange="saveService({{$result->id}}, '<?php echo csrf_token(); ?>')">
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <nav aria-label="Page navigation example">
                                            {!! $results->appends(['searchBrand' => $selected])->render() !!}
                                        </nav>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
