function searchNumber(number, user, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/businessNumber/search',
            data:{
                '_token' : token,
                'number' : number,
                'user' : user,
            },
            success:function (data) {
                $("#dataTable").children().remove();
                $("#dataTable").append(data);
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