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
        document.getElementById('accessLevel').classList.add('active');
    </script>
    <script type="text/javascript" src="/manage/js/core/accessLevelController.js"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h4>تعریف حوزه کالا</h4>
                        </div>
                        <form class="form-horizontal" method="post" id="form">
                            <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="card-body">

                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <div>
                                        <label>نام سطح دسترسی</label>
                                        <input type="text" class="form-control" name="name" id="name">
                                        <input type="text" id="accessId" name="accessId" style="display: none">
                                    </div>
                                    @error('name')
                                    <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>فرم <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                            <th>دسترسی مشاهده <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                            <th>دسترسی ایجاد <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                            <th>دسترسی ویرایش <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                            <th>دسترسی حذف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                            <th>دسترسی تایید</th>
                                        </tr>
                                        </thead>
                                        <tbody id="formTable">
                                        @foreach($forms as $form)
                                            <tr>
                                                <td>{{$form->title}}</td>
                                                <td align="center">
                                                    <input type="checkbox" style="position: fixed !important;" class="custom-control-input" id="read_{{$form->id}}" name="read_{{$form->id}}">
                                                    <label class="custom-control-label" for="read_{{$form->id}}"></label>
                                                </td>
                                                <td align="center">
                                                    <input type="checkbox" style="position: fixed !important;" class="custom-control-input" id="insert_{{$form->id}}" name="insert_{{$form->id}}">
                                                    <label class="custom-control-label" for="insert_{{$form->id}}"></label>
                                                </td>
                                                <td align="center">
                                                    <input type="checkbox" style="position: fixed !important;" class="custom-control-input" id="update_{{$form->id}}" name="update_{{$form->id}}">
                                                    <label class="custom-control-label" for="update_{{$form->id}}"></label>
                                                </td>
                                                <td align="center">
                                                    <input type="checkbox" style="position: fixed !important;" class="custom-control-input" id="delete_{{$form->id}}" name="delete_{{$form->id}}">
                                                    <label class="custom-control-label" for="delete_{{$form->id}}"></label>
                                                </td>
                                                <td align="center">
                                                    <input type="checkbox" style="position: fixed !important;" class="custom-control-input" id="access_{{$form->id}}" name="access_{{$form->id}}">
                                                    <label class="custom-control-label" for="access_{{$form->id}}"></label>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-4 col-lg-4 col-sm-4">
                                    <div class="content-group">
                                        <label> </label>
                                        <?php if($accessList['insert_5'] == "on") {?>
                                        <div>
                                            <button type="submit" id="save" name="save" value="save" style="display: block; width: 100%" class="btn btn-primary" onclick="$('#startSpinner').click();">ثبت</button>
                                        </div>
                                        <?php } ?>
                                        <?php if($accessList['update_5'] == "on") {?>
                                        <div>
                                            <button type="submit" id="edit" name="edit" value="edit" style="display: none; width: 100%" class="btn btn-primary" onclick="$('#startSpinner').click();">ویرایش</button>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>سطح دسترسی</h4>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">
                                    <h4>لیست سطح دسترسی</h4>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>سطح دسترسی <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                    <th>مشاهده</th>
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
                                                @foreach($accessLevels as $accessLevel)
                                                    <tr>
                                                        <td><?php echo $id++; ?></td>
                                                        <td>{{$accessLevel->title}}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                                                    onclick="startUpdateAccessLevel({{$accessLevel->id}}, document.getElementById('token').value, '#accessId', '#name', '#formTable')">
                                                                <i class="icon-search4"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row" align="center">
                                        {!! $accessLevels->render() !!}
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
