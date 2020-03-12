<?php

use Illuminate\Support\Facades\Auth;

$accessLevel = Auth::user()->usrRole;
$accessList = json_decode($accessLevel->json, true);
?>

@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('manageCall').classList.add('active');
        document.getElementById('freeCallForCustomer').classList.add('active');
    </script>
    <script type="text/javascript" src="/manage/js/core/freeCallForCustomerController.js"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h2>تعریف شماره</h2>
                        </div>
                        <div style="padding: 10px 25px;">
                            <h5>درصورتی که میخواهید فقط برخی مشتریان از طریق تماس رایگان با شما مکالمه کنند، در این بخش شماره آنان را ثبت کنید.</h5>
                            <h5>توجه: با این کار بقیه مشتریان نمی توانند از تماس رایگان استفاده کنند.</h5>
                        </div>
                        <form class="form-horizontal" method="post" enctype="multipart/form-data" id="form">
                            <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>شماره تلفن همراه مشتری</label>
                                            <input type="tel" step="any" class="form-control" maxlength="11" onkeyup="validate_int(this.value, this.id)" name="name" id="name">
                                        </div>
                                        @error('name')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>شماره تلفن همراه مشتری</label>
                                            <input type="tel" step="any" class="form-control" maxlength="11" onkeyup="validate_int(this.value, this.id)" name="name1" id="name1">
                                        </div>
                                        @error('name')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>شماره تلفن همراه مشتری</label>
                                            <input type="tel" step="any" class="form-control" maxlength="11" onkeyup="validate_int(this.value, this.id)" name="name2" id="name2">
                                        </div>
                                        @error('name2')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>شماره تلفن همراه مشتری</label>
                                            <input type="tel" step="any" class="form-control" maxlength="11" onkeyup="validate_int(this.value, this.id)" name="name3" id="name3">
                                        </div>
                                        @error('name3')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>شماره تلفن همراه مشتری</label>
                                            <input type="tel" step="any" class="form-control" maxlength="11" onkeyup="validate_int(this.value, this.id)" name="name4" id="name4">
                                        </div>
                                        @error('name4')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>شماره تلفن همراه مشتری</label>
                                            <input type="tel" step="any" class="form-control" maxlength="11" onkeyup="validate_int(this.value, this.id)" name="name5" id="name5">
                                        </div>
                                        @error('name5')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>شماره تلفن همراه مشتری</label>
                                            <input type="tel" step="any" class="form-control" maxlength="11" onkeyup="validate_int(this.value, this.id)" name="name6" id="name6">
                                        </div>
                                        @error('name6')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>شماره تلفن همراه مشتری</label>
                                            <input type="tel" step="any" class="form-control" maxlength="11" onkeyup="validate_int(this.value, this.id)" name="name7" id="name7">
                                        </div>
                                        @error('name7')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>شماره تلفن همراه مشتری</label>
                                            <input type="tel" step="any" class="form-control" maxlength="11" onkeyup="validate_int(this.value, this.id)" name="name8" id="name8">
                                        </div>
                                        @error('name8')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>شماره تلفن همراه مشتری</label>
                                            <input type="tel" step="any" class="form-control" maxlength="11" onkeyup="validate_int(this.value, this.id)" name="name9" id="name9">
                                        </div>
                                        @error('name9')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>شماره های مشتریان</label>
                                            <input type="file" class="file-styled" id="excel" name="excel">
                                        </div>
                                        @error('excel')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
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

                    <div class="card">
                        <div class="card-header">
                            <h4>شماره ها</h4>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">
                                    <h4>لیست شماره ها</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>شماره مشتری <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>وضعیت <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <?php if ($accessList['update_28'] == "on"){?>
                                                    <th>تغییر وضعیت <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <?php } if ($accessList['delete_28'] == "on"){?>
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
                                                @foreach($customers as $customer)
                                                    <tr>
                                                        <td><?php echo $id++; ?></td>
                                                        <td>{{$customer->phone}}</td>
                                                        @if($customer->active)
                                                            <td>فعال</td>
                                                        @else
                                                            <td style="color: red">غیرفعال</td>
                                                        @endif
                                                        <?php if ($accessList['update_28'] == "on"){?>
                                                            @if($customer->active)
                                                                <td id="{{$customer->id}}">
                                                                    <button type="button" class="btn btn-success btn-icon btn-rounded"
                                                                            onclick="activeNumber({{$customer->id}}, document.getElementById('token').value)">
                                                                        <i class="icon-user-check"></i>
                                                                    </button>
                                                                </td>
                                                            @else
                                                                <td id="{{$customer->id}}">
                                                                    <button type="button" class="btn-secondary btn-icon btn-rounded"
                                                                            onclick="activeNumber({{$customer->id}}, document.getElementById('token').value)">
                                                                        <i class="icon-user-block"></i>
                                                                    </button>
                                                                </td>
                                                            @endif
                                                        <?php }?>
                                                        <?php if ($accessList['delete_28'] == "on"){?>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-icon btn-rounded"
                                                                    onclick="deleteNumber({{$customer->id}}, document.getElementById('token').value)">
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
                                        {!! $customers->render() !!}
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
        const alarm = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success col-md-6',
                cancelButton: 'btn btn-danger col-md-6',
            },
            buttonsStyling: false
        });

        $(document).ready(function () {
            $('#excel').on('change', function () {
                var fileName = document.getElementById('excel').value.toLowerCase();
                if(!fileName.endsWith('.xlsx') && !fileName.endsWith('.xls')){
                    alarm.fire(
                        'اخطار',
                        'فقط فایل اکسل را می توانید انتخاب نمایید.',
                        'warning'
                    );
                    document.getElementById('excel').value = null;
                }
            });
        });
    </script>
@endsection
