@extends('manage.master')

@section('bottomHead')
    <script>
        document.getElementById('index').classList.remove('active');
        document.getElementById('covered').classList.add('active');
    </script>
    <script type="text/javascript" src="/manage/js/core/coveredController.js"></script>

    <script>
        function selectAll(value) {
            let i;
            const items = document.getElementsByName("asc");
            if(value === true){
                for(i = 0; i<items.length; i++){
                    if(items[i].type === "checkbox"){
                        items[i].checked = true;
                    }
                }
            } else {
                for(i = 0; i<items.length; i++){
                    if(items[i].type === "checkbox"){
                        items[i].checked = false;
                    }
                }
            }
        }

        function selectAllCity(arr, value) {
            for(var i = 0; i < arr.length; i++){
                if(value === true){
                    document.getElementById(arr[i]).checked = true;
                } else {
                    document.getElementById(arr[i]).checked = false;
                }
            }
        }
        (function($){
            $(document).ready(function () {
                $(".loader").fadeIn();
                $.ajax(
                    {
                        type:'POST',
                        url:'/manage/covered/showSelectedCity',
                        data:{
                            '_token' : document.getElementById('token').value,
                        },
                        success:function (data) {
                            $('#showCity').children().remove();
                            $('#showCity').append(data);
                            $(".loader").fadeOut("slow");
                        },
                        error:function (xhr, ajaxOptions, throwError) {
                            $(".loader").fadeOut("slow");
                        }
                    }
                );
            })
        })(jQuery);
    </script>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h1>مناطق تحت پوشش</h1>
                        </div>
                        <div style="padding: 10px 25px;">
                            <h4>فقط کافیست گزینه مورد نظر را تیک بزنید.</h4>
                        </div>
                        <form class="form-horizontal" method="post" id="form">
                            <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div>
                                            <label>استان</label>
                                            <select class="form-control selectpicker" name="state" id="state"
                                                    onchange="showCity(this.value, '#showCity' ,document.getElementById('token').value)" data-live-search="true">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($states as $state)
                                                    @if($state->id == $selected)
                                                        <option selected value="{{ $state->id }}">{{ $state->title }}</option>
                                                    @else
                                                        <option value="{{ $state->id }}">{{ $state->title }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('state')
                                        <span class="help-block text-danger"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row" id="showCity">

                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <button type="button" id="home" style="display: block; width: 100%" class="btn btn-primary" onclick="document.location.href='/manage/index'">بازگشت به صفحه اصلی</button>
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
