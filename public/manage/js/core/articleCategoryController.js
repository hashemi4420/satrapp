function getArticleGroup(id, token, target) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/articleCategory/getGroups',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                document.getElementById(target.replace("#","")).options.length = 1;
                $(target).append(data);
                $('.selectpicker').selectpicker('refresh');
                $(".loader").fadeOut("slow");
            }
        }
    );
}

function searchArticleCategory(areaId, groupId, name, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/articleCategory/search',
            data:{
                '_token' : token,
                'areaId' : areaId,
                'groupId' : groupId,
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

function startUpdateArticleCategory(id, token, categoryId, areaId, groupId, nameId) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/articleCategory/startUpdate',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                var finalData = data.split("_:_");
                $(categoryId).val(finalData[0]);
                $(nameId).val(finalData[1]);

                document.getElementById(groupId.replace("#","")).options.length = 0;
                $(groupId).append(finalData[2]);

                document.getElementById(areaId.replace("#","")).options.length = 0;
                $(areaId).append(finalData[3]);

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

function updateArticleCategory(status, categoryId, areaId, groupId, nameId, token) {
    $(".loader").fadeIn();
    if(status){
        if($(nameId).val() === "" || $(groupId).val() === ""){

        } else {
            $.ajax(
                {
                    type:'POST',
                    url:'/manage/articleCategory/Update',
                    data:{
                        '_token' : token,
                        'id' : $(categoryId).val(),
                        'groupId' : $(groupId).val(),
                        'areaId' : $(areaId).val(),
                        'name' : $(nameId).val(),
                    },
                    success:function (data) {
                        $(categoryId).val(null);
                        $(nameId).val(null);

                        document.getElementById(groupId.replace("#","")).options.length = 1;

                        $('.selectpicker').selectpicker('refresh');

                        window.location.href = '/manage/articleCategory';

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
        $(categoryId).val(null);
        $(nameId).val(null);

        $('.selectpicker').selectpicker('refresh');

        window.location.href = '/manage/articleCategory';

        $(".loader").fadeOut("slow");
    }
}

function deleteArticleCategory(id, token) {
    const startDelete = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success col-md-6',
            cancelButton: 'btn btn-danger col-md-6',
        },
        buttonsStyling: false
    });

    startDelete.fire({
        title: 'آیا از حذف اطلاعات مطمئنید؟',
        text: 'ممکن است این داده دارای زیر مجموعه باشد، با حذف این داده زیر مجموعه های آن نیز حذف خواهد شد.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'بله، حذف گردد',
        cancelButtonText: 'خیر، منصرف شدم',
        reverseButtons: true
    }).then((result) => {
        if (result.value){
            $(".loader").fadeIn();
            $.ajax(
                {
                    type:'POST',
                    url:'/manage/articleCategory/delete',
                    data:{
                        '_token' : token,
                        'id' : id,
                    },
                    success:function (data) {
                        startDelete.fire(
                            'تایید',
                            'فایل به همراه زیر مجموعه آن حذف گردید',
                            'success'
                        );
                        window.location.href = '/manage/articleCategory';
                    },
                    error:function (xhr, ajaxOptions, throwError) {
                        startDelete.fire(
                            'خطا',
                            'مشکلی در فرایند حذف فایل پیش آمده است',
                            'error'
                        );
                        $(".loader").fadeOut("slow");
                        // window.location.href = '/manage/articleCategory';
                        // alert(xhr.status);
                        // alert(throwError);
                    }
                }
            );
        } else if (
            result.dismiss ===Swal.DismissReason.cancel
        ){
            startDelete.fire(
                'انصراف',
                'فایل حذف نشد',
                'error'
            )
        }
    });
    $(".loader").fadeOut("slow");
}