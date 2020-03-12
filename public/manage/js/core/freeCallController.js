function changeResult(price, name) {
    var result = parseInt(price);
    $(name).text(result);
}

function save(price, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/freeCall/save',
            data:{
                '_token' : token,
                'price' : parseInt($(price).text()),
            },
            success:function (data) {
                window.location.href = '/manage/freeCall';
                $(".loader").fadeOut("slow");
            },
            error:function (xhr, ajaxOptions, throwError) {
                $(".loader").fadeOut("slow");
                // alert(xhr.status);
                // alert(throwError);
            }
        }
    );
}

function changeActivity(token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/freeCall/changeActivity',
            data:{
                '_token' : token,
            },
            success:function (data) {
                window.location.href = '/manage/freeCall';
                $(".loader").fadeOut("slow");
            },
            error:function (xhr, ajaxOptions, throwError) {
                $(".loader").fadeOut("slow");
                // alert(xhr.status);
                // alert(throwError);
            }
        }
    );
}