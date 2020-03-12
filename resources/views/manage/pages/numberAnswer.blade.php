@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('manageCall').classList.add('active');
        document.getElementById('numberAnswer').classList.add('active');
    </script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h2>شماره پاسخگو</h2>
                        </div>
                        <form class="form-horizontal" method="post" id="form">
                            <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>شماره جهت تماس رایگان</label>
                                            <input type="tel" step="any" class="form-control" maxlength="11" value="{{Auth::user()->numberFree}}" onkeyup="validate_int(this.value, this.id)" name="free" id="free">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>شماره جهت تماس عادی</label>
                                            <input type="tel" step="any" class="form-control" maxlength="11" value="{{Auth::user()->numberPey}}" onkeyup="validate_int(this.value, this.id)" name="pey" id="pey">
                                        </div>
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

                </div>
            </div>
        </div>
    </section>
@endsection
