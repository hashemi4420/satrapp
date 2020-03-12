function searchArticleGroup(areaId, name, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/articleGroup/search',
            data:{
                '_token' : token,
                'areaId' : areaId,
                'name' : name,
            },
            success:function (data) {
                $("#dataTable").children().remove();
                $("#dataTable").append(data);
                $(".loader").fadeOut("slow");

                $('.aniimated-thumbnials').lightGallery({
                    thumbnail: true,
                    selector: 'a'
                });
            },
            error:function (xhr, ajaxOptions, throwError) {
                $(".loader").fadeOut("slow");
                // alert(xhr.status);
                // alert(throwError);
            }
        }
    );
}

function startUpdateArticleGroup(id, token, groupId, areaId, nameId) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/articleGroup/startUpdate',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                var finalData = data.split("_:_");
                $(groupId).val(finalData[0]);
                $(nameId).val(finalData[1]);

                document.getElementById(areaId.replace("#","")).options.length = 0;
                $(areaId).append(finalData[2]);

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

function updateArticleGroup(status, groupId, areaId, name, token) {
    $(".loader").fadeIn();
    if(status){
        if($(name).val() === ""){

        } else {
            $.ajax(
                {
                    type:'POST',
                    url:'/manage/articleGroup/Update',
                    data:{
                        '_token' : token,
                        'id' : $(groupId).val(),
                        'areaId' : $(areaId).val(),
                        'name' : $(name).val(),
                    },
                    success:function (data) {
                        $(groupId).val(null);
                        $(name).val(null);

                        $('.selectpicker').selectpicker('refresh');

                        window.location.href = '/manage/articleGroup';

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
        $(groupId).val(null);
        $(name).val(null);

        $('.selectpicker').selectpicker('refresh');

        window.location.href = '/manage/articleGroup';

        $(".loader").fadeOut("slow");
    }
}

function deleteArticleGroup(id, token) {
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
                    url:'/manage/articleGroup/delete',
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
                        window.location.href = '/manage/articleGroup';
                    },
                    error:function (xhr, ajaxOptions, throwError) {
                        startDelete.fire(
                            'خطا',
                            'مشکلی در فرایند حذف فایل پیش آمده است',
                            'error'
                        );
                        $(".loader").fadeOut("slow");
                        // window.location.href = '/manage/articleGroup';
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