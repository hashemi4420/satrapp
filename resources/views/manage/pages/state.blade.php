<?php

use Illuminate\Support\Facades\Auth;

$accessLevel = Auth::user()->usrRole;
$accessList = json_decode($accessLevel->json, true);
?>

@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('baseTable').classList.add('active');
        document.getElementById('states').classList.add('active');
    </script>
    <script type="text/javascript" src="/manage/js/core/stateController.js"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h4>تعریف استان</h4>
                        </div>
                        <form class="form-horizontal" method="post" id="form">
                            <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8 col-lg-8 col-sm-8">
                                        <div>
                                            <label>نام استان</label>
                                            <input type="text" class="form-control" name="name" id="name">
                                            <input type="text" id="stateId" name="stateId" style="display: none">
                                        </div>
                                        @error('name')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div class="content-group">
                                            <label> </label>
                                            <?php if($accessList['insert_6'] == "on") {?>
                                                <div>
                                                    <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary" onclick="$('#startSpinner').click();">ثبت</button>
                                                </div>
                                            <?php } if($accessList['update_6'] == "on") {?>
                                                <div>
                                                    <div class="col-md-6">
                                                        <button type="button" id="update" style="display: none; width: 100%" class="btn btn-primary" onclick="updateState(true, '#stateId', '#name', document.getElementById('token').value)">ویرایش</button>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="button" id="cancel" style="display: none; width: 100%" class="btn btn-danger" onclick="updateState(false, '#stateId', '#name', document.getElementById('token').value)">لغو</button>
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
                            <h4>استانها</h4>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">
                                    <h4>جستجو استانها</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8 col-lg-8 col-sm-8">
                                            <div>
                                                <label>استان</label>
                                                <input type="text" class="form-control" name="searchName" id="searchName">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div class="content-group">
                                                <label> </label>
                                                <div>
                                                    <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary btn-labeled btn-xlg"
                                                            onclick="searchState(
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
                                    <h4>لیست استانها</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>استان <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <?php if($accessList['update_6'] == "on") {?>
                                                <th>ویرایش <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <?php }if($accessList['delete_6'] == "on") {?>
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
                                            @foreach($states as $state)
                                                <tr>
                                                    <td><?php echo $id++; ?></td>
                                                    <td>{{$state->title}}</td>
                                                    <?php if($accessList['update_6'] == "on") {?>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                                                onclick="startUpdateState({{$state->id}}, document.getElementById('token').value, '#stateId', '#name')">
                                                            <i class="icon-search4"></i>
                                                        </button>
                                                    </td>
                                                    <?php }if($accessList['delete_6'] == "on") {?>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                                                onclick="deleteState({{$state->id}}, document.getElementById('token').value)">
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
                                        {!! $states->render() !!}
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
