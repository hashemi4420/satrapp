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
        document.getElementById('report').classList.add('active');
    </script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h4>گزارشات</h4>
                        </div>
                        <div class="card-body">

                            <div class="card">
                                <div class="card-header">
                                    <h4>لیست گزارشات</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>شماره کاربر <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>ایمیل کاربر <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>گزارش کاربر <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>تامین کننده</th>
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
                                            @foreach($reports as $report)
                                                <tr>
                                                    <td><?php echo $id++; ?></td>
                                                    <td>{{$report->customer->phone}}</td>
                                                    <td>{{$report->customer->email}}</td>
                                                    <td>{{$report->question->title}}</td>
                                                    <td>{{$report->user->title_company}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <nav aria-label="Page navigation example">
                                        {!! $reports->render() !!}
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
