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
        document.getElementById('serviceArea').classList.add('active');
    </script>
    <script type="text/javascript" src="/manage/js/core/serviceAreaController.js"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h4>تعریف حوزه خدمات</h4>
                        </div>
                        <form class="form-horizontal" method="post" id="form">
                            <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8 col-lg-8 col-sm-8">
                                        <div>
                                            <label>نام حوزه خدمات</label>
                                            <input type="text" class="form-control" name="name" id="name">
                                            <input type="text" id="serviceAreaId" name="serviceAreaId" style="display: none">
                                        </div>
                                        @error('name')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div class="content-group">
                                            <label> </label>
                                            <?php if ($accessList['insert_17'] == "on"){?>
                                                <div>
                                                    <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary" onclick="$('#startSpinner').click();">ثبت</button>
                                                </div>
                                            <?php } if ($accessList['update_17'] == "on"){?>
                                                <div>
                                                    <div class="col-md-6">
                                                        <button type="button" id="update" style="display: none; width: 100%" class="btn btn-primary" onclick="updateServiceArea(true, '#serviceAreaId', '#name', document.getElementById('token').value)">ویرایش</button>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="button" id="cancel" style="display: none; width: 100%" class="btn btn-danger" onclick="updateServiceArea(false, '#serviceAreaId', '#name', document.getElementById('token').value)">لغو</button>
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
                            <h4>حوزه خدمات</h4>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">
                                    <h4>جستجو حوزه خدمات</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8 col-lg-8 col-sm-8">
                                            <div>
                                                <label>نام حوزه خدمات</label>
                                                <input type="text" class="form-control" name="searchName" id="searchName">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-4">
                                            <div class="content-group">
                                                <label> </label>
                                                <div>
                                                    <button type="submit" id="save" style="display: block; width: 100%" class="btn btn-primary btn-labeled btn-xlg"
                                                            onclick="searchServiceArea(
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
                                    <h4>لیست حوزه خدمات</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>حوزه خدمات <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <?php if ($accessList['update_17'] == "on"){?>
                                                <th>ویرایش <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <?php } if ($accessList['delete_17'] == "on"){?>
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
                                            @foreach($areas as $area)
                                                <tr>
                                                    <td><?php echo $id++; ?></td>
                                                    <td>{{$area->title}}</td>
                                                    <?php if ($accessList['update_17'] == "on"){?>
                                                    @if($area->active)
                                                        <td id="{{$area->id}}">
                                                            <button type="button" class="btn btn-success btn-icon btn-rounded"
                                                                    onclick="activeService({{$area->id}}, document.getElementById('token').value)">
                                                                <i class="icon-user-check"></i>
                                                            </button>
                                                        </td>
                                                    @else
                                                        <td id="{{$area->id}}">
                                                            <button type="button" class="btn-secondary btn-icon btn-rounded"
                                                                    onclick="activeService({{$area->id}}, document.getElementById('token').value)">
                                                                <i class="icon-user-block"></i>
                                                            </button>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-icon btn-rounded"
                                                                onclick="startUpdateServiceArea({{$area->id}}, document.getElementById('token').value, '#serviceAreaId', '#name')">
                                                            <i class="icon-search4"></i>
                                                        </button>
                                                    </td>
                                                    <?php } if ($accessList['delete_17'] == "on"){?>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                                                onclick="deleteServiceArea({{$area->id}}, document.getElementById('token').value)">
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
                                        {!! $areas->render() !!}
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
