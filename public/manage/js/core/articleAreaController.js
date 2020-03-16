function searchArticleArea(name, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/articleArea/search',
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

function startUpdateArticleArea(id, token, areaId, nameId) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/articleArea/startUpdate',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                var finalData = data.split("_:_");
                $(areaId).val(finalData[0]);
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

function updateArticleArea(status, areaId, name, token) {
    $(".loader").fadeIn();
    if(status){
        if($(name).val() === ""){

        } else {
            $.ajax(
                {
                    type:'POST',
                    url:'/manage/articleArea/Update',
                    data:{
                        '_token' : token,
                        'id' : $(areaId).val(),
                        'name' : $(name).val(),
                    },
                    success:function (data) {
                        $(areaId).val(null);
                        $(name).val(null);

                        $('.selectpicker').selectpicker('refresh');

                        window.location.href = '/manage/articleArea';

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
        $(areaId).val(null);
        $(name).val(null);

        $('.selectpicker').selectpicker('refresh');

        window.location.href = '/manage/articleArea';

        $(".loader").fadeOut("slow");
    }
}

function deleteArticleArea(id, token) {
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
                    url:'/manage/articleArea/delete',
                    data:{
                        '_token' : token,
                        'id' : id,
                    },
                    success:function (data) {
                        window.location.href = '/manage/articleArea';
                        if(data == 1){
                            startDelete.fire(
                                'تایید',
                                'فایل به همراه زیر مجموعه آن حذف گردید',
                                'success'
                            );
                            window.location.href = '/manage/articleArea';
                        }else {
                            startDelete.fire(
                                'اخطار',
                                'این داده دارای زیر مجموعه می باشد، لطفا قبل از حذف آن تکلیف زیر مجموعه آن را مشخص نمایید.',
                                'warning'
                            );
                        }
                        $(".loader").fadeOut("slow");
                    },
                    error:function (xhr, ajaxOptions, throwError) {
                        startDelete.fire(
                            'خطا',
                            'مشکلی در فرایند حذف فایل پیش آمده است',
                            'error'
                        );
                        $(".loader").fadeOut("slow");
                        // window.location.href = '/manage/articleArea';
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

function activeArticle(id, token) {
    const startDelete = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success col-md-7',
            cancelButton: 'btn btn-danger col-md-5',
        },
        buttonsStyling: false
    });

    startDelete.fire({
        title: 'سوال',
        text: 'آیا از فعال/غیر فعال کردن حوزه مطمئنید؟',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'بله، فعال/غیر فعال گردد',
        cancelButtonText: 'خیر، منصرف شدم',
        reverseButtons: true
    }).then((result) => {
        if (result.value){
            $(".loader").fadeIn();
            $.ajax(
                {
                    type:'POST',
                    url:'/manage/articleArea/active',
                    data:{
                        '_token' : token,
                        'id' : id,
                    },
                    success:function (data) {
                        startDelete.fire(
                            'تایید',
                            'حوزه فعال/غیر فعال گردید',
                            'success'
                        );
                        window.location.href = '/manage/articleArea';
                    },
                    error:function (xhr, ajaxOptions, throwError) {
                        startDelete.fire(
                            'خطا',
                            'مشکلی در فرایند فعال/غیر فعال کردن حوزه پیش آمده است',
                            'error'
                        );
                        $(".loader").fadeOut("slow");
                        // window.location.href = '/manage/articleArea';
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
                'حوزه فعال/غیر فعال نشد',
                'error'
            )
        }
    });
    $(".loader").fadeOut("slow");
}