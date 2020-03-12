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
        document.getElementById('articleCategories').classList.add('active');
    </script>
    <script type="text/javascript" src="/manage/js/core/articleCategoryController.js"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h4>تعریف دسته کالا</h4>
                        </div>
                        <form class="form-horizontal" method="post" id="form">
                            <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>حوزه کالا</label>
                                            <select class="form-control selectpicker" name="areaId" id="areaId" data-live-search="true"
                                                    onchange="getArticleGroup(this.value,  document.getElementById('token').value, '#groupId')">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($areas as $area)
                                                    <option value="{{ $area->id }}">{{ $area->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('areaId')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>گروه کالا</label>
                                            <select class="form-control selectpicker" name="groupId" id="groupId" data-live-search="true">
                                                <option value="">انتخاب کنید</option>
                                            </select>
                                        </div>
                                        @error('groupId')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>نام دسته کالا</label>
                                            <input type="text" class="form-control" name="nameId" id="nameId">
                                            <input type="text" id="categoryId" name="categoryId" style="display: none">
                                        </div>
                                        @error('nameId')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div class="content-group">
                                            <label> </label>
                                            <?php if ($accessList['insert_12'] == "on"){?>
                                                <div>
                                                    <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary" onclick="$('#startSpinner').click();">ثبت</button>
                                                </div>
                                            <?php }if ($accessList['update_12'] == "on"){?>
                                                <div>
                                                    <div class="col-md-6">
                                                        <button type="button" id="update" style="display: none; width: 100%" class="btn btn-primary" onclick="updateArticleCategory(true, '#categoryId', '#areaId', '#groupId', '#nameId', document.getElementById('token').value)">ویرایش</button>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="button" id="cancel" style="display: none; width: 100%" class="btn btn-danger" onclick="updateArticleCategory(false, '#categoryId', '#areaId', '#groupId', '#nameId', document.getElementById('token').value)">لغو</button>
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
                            <h4>دسته کالا</h4>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">
                                    <h4>جستجو دسته کالا</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>حوزه کالا</label>
                                                <select class="form-control selectpicker" name="searchArea" id="searchArea" data-live-search="true"
                                                        onchange="getArticleGroup(this.value,  document.getElementById('token').value, '#searchGroup')">
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
                                                <select class="form-control selectpicker" name="searchGroup" id="searchGroup" data-live-search="true">
                                                    <option value="">انتخاب کنید</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div>
                                                <label>نام دسته کالا</label>
                                                <input type="text" class="form-control" name="searchName" id="searchName">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div class="content-group">
                                                <label> </label>
                                                <div>
                                                    <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary btn-labeled btn-xlg"
                                                            onclick="searchArticleCategory(
                                                                        document.getElementById('searchArea').value,
                                                                        document.getElementById('searchGroup').value,
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
                                    <h4>لیست دسته کالا</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>حوزه کالا <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>گروه کالا <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>دسته کالا <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <?php if ($accessList['update_12'] == "on"){?>
                                                <th>ویرایش <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <?php }if ($accessList['delete_12'] == "on"){?>
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
                                            @foreach($categories as $category)
                                                <tr>
                                                    <td><?php echo $id++; ?></td>
                                                    <td>{{$category->area->title}}</td>
                                                    <td>{{$category->group->title}}</td>
                                                    <td>{{$category->title}}</td>
                                                    <?php if ($accessList['update_12'] == "on"){?>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                                                onclick="startUpdateArticleCategory({{$category->id}}, document.getElementById('token').value, '#categoryId', '#areaId', '#groupId', '#nameId')">
                                                            <i class="icon-search4"></i>
                                                        </button>
                                                    </td>
                                                    <?php }if ($accessList['delete_12'] == "on"){?>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                                                onclick="deleteArticleCategory({{$category->id}}, document.getElementById('token').value)">
                                                            <i class="icon-trash"></i>
                                                        </button>
                                                    </td>
                                                    <?php }?>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <nav aria-label="Page navigation example">
                                        {!! $categories->render() !!}
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
