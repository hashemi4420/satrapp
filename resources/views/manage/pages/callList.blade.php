@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('manageCall').classList.add('active');
        document.getElementById('callList').classList.add('active');
    </script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-body">

                            <div class="card">
                                <div class="card-header">
                                    <h4>لیست تماس ها</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>شماره <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>نوع شماره <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>تاریخ <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>ساعت <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>نوع تماس <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>مدت مکالمه <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>وضعیت <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                            </tr>
                                            </thead>
                                            <tbody id="dataTable">
                                            </tbody>
                                        </table>
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
