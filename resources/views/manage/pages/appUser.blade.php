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
        document.getElementById('appUser').classList.add('active');
    </script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h4>کاربران اپلیکیشن</h4>
                        </div>
                        <div class="card-body">

                            <div class="card">
                                <div class="card-header">
                                    <h4>لیست کاربران</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>شماره <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>ایمیل <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>استان <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>شهر <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>شهرستان <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>موجودی</th>
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
                                                    <td>{{$customer->email}}</td>
                                                    <td>{{$customer->state->title}}</td>
                                                    @if($customer->city_id != null)
                                                        <td>{{$customer->city->title}}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    @if($customer->district_id != null)
                                                        <td>{{$customer->district->title}}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    <td>{{$customer->cash}}</td>
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
@endsection
