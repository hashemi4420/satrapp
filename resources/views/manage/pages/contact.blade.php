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
        document.getElementById('contact').classList.add('active');
    </script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h4>پیام ها</h4>
                        </div>
                        <div class="card-body">

                            <div class="card">
                                <div class="card-header">
                                    <h4>لیست پیام ها</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>ردیف <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>شماره کاربر <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>ایمیل کاربر <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>عنوان پیام <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>پیام کاربر <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>تاریخ دریافت پیام</th>
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
                                            @foreach($contacts as $contact)
                                                <tr>
                                                    <td><?php echo $id++; ?></td>
                                                    <td>{{$contact->customer->phone}}</td>
                                                    <td>{{$contact->email}}</td>
                                                    <td>{{$contact->title}}</td>
                                                    <td>{{$contact->message}}</td>
                                                    <td>{{$contact->date}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <nav aria-label="Page navigation example">
                                        {!! $contacts->render() !!}
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
