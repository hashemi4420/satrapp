@extends('manage.master')

@section('bottomHead')
    <script>
        (function($){
            $(document).ready(function () {
                $(".loader").fadeIn();
                $.ajax(
                    {
                        type:'POST',
                        url:'/manage/index/loadData',
                        data:{
                            '_token' : document.getElementById('token').value,
                        },
                        success:function (data) {
                            document.getElementById("indexSection").innerHTML = data;
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
    <form class="form-horizontal">
        <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">
    </form>
    <section class="section" id="indexSection"></section>
@endsection
