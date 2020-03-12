function searchForm(name, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/questionReport/search',
            data:{
                '_token' : token,
                'name' : name,
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

function startUpdateForm(id, token, formId, nameId) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/questionReport/startUpdate',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                var finalData = data.split("_:_");
                $(formId).val(finalData[0]);
                $(nameId).val(finalData[1]);

                $('.selectpicker').selectpicker('refresh');

                $('#save').css('display','none');
                $('#update').css('display','block');
                $('#cancel').css('display','block');

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

function updateForm(status, formId, name, token) {
    $(".loader").fadeIn();
    if(status){
        if($(name).val() === ""){

        } else {
            $.ajax(
                {
                    type:'POST',
                    url:'/manage/questionReport/Update',
                    data:{
                        '_token' : token,
                        'id' : $(formId).val(),
                        'name' : $(name).val(),
                    },
                    success:function (data) {
                        $(formId).val(null);
                        $(name).val(null);

                        $('.selectpicker').selectpicker('refresh');

                        window.location.href = '/manage/questionReport';

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
    } else {
        $(formId).val(null);
        $(name).val(null);

        $('.selectpicker').selectpicker('refresh');

        window.location.href = '/manage/questionReport';

        $(".loader").fadeOut("slow");
    }
}