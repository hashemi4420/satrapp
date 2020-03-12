@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('aboutUs').classList.add('active');
    </script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h2>درباره شما</h2>
                        </div>
                        <form class="form-horizontal" method="post" id="form">
                            <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div>
                                            <label></label>
                                            <textarea style="height: 120px !important;resize: none;" maxlength="3000"
                                                      type="text"
                                                      class="form-control" name="name" id="name">{{Auth::user()->about}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
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

                </div>
            </div>
        </div>
    </section>
@endsection
