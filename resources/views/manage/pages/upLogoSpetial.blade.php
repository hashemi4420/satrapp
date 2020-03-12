@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('manageBrand').classList.add('active');
        document.getElementById('upLogoSpetial').classList.add('active');
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
                                    <h4>ارتقاء لوگو در صفحه خدمات یا محصول خاص</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>لیست خدمات یا محصولات <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>قیمت (تومان) <i style="color: #09d26e;" class="fas fa-arrow-left"></i></th>
                                                <th>انتخاب</th>
                                            </tr>
                                            </thead>
                                            <tbody id="dataTable">
                                            <tr>
                                                <td>بیمه عمر</td>
                                                <td>۵۰۰۰</td>
                                                <td>
                                                    <div class="pretty p-default">
                                                        <input type="checkbox">
                                                        <div class="state p-primary">
                                                            <label></label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>بیمه ایران</td>
                                                <td>۵۰۰۰</td>
                                                <td>
                                                    <div class="pretty p-default">
                                                        <input type="checkbox">
                                                        <div class="state p-primary">
                                                            <label></label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <label>مجموع: </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
