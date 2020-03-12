@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('transAction').classList.add('active');
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
                                    <h4>لیست تراکنش ها</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>نوع تراکنش <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>تاریخ <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>ساعت <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>مبلغ</th>
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
