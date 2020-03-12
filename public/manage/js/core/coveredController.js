function showCity(stateId, name, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/covered/showCity',
            data:{
                '_token' : token,
                'stateId' : stateId,
            },
            success:function (data) {
                $(name).children().remove();
                $(name).append(data);
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

function save(stateId, cityId, districtId, token) {
    // $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/covered/save',
            data:{
                '_token' : token,
                'stateId' : stateId,
                'cityId' : cityId,
                'districtId' : districtId,
            },
            success:function (data) {
                // window.location.href = '/manage/covered';
                // $(".loader").fadeOut("slow");
            },
            error:function (xhr, ajaxOptions, throwError) {
                // $(".loader").fadeOut("slow");
                // alert(xhr.status);
                // alert(throwError);
            }
        }
    );
}