function searchArticleBrand(name, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/articleBrand/search',
            data:{
                '_token' : token,
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

function startUpdateArticleBrand(id, token, brandId, nameId, picId) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/articleBrand/startUpdate',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                var finalData = data.split("_:_");
                $(brandId).val(finalData[0]);
                $(nameId).val(finalData[1]);
                $(picId).attr('src', finalData[2]);

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

function updateArticleBrand(status, brandId, name, picId, token) {
    $(".loader").fadeIn();
    if(status){
        if($(name).val() === ""){

        } else {
            var fd = new FormData();
            var files = $(picId)[0].files[0];
            fd.append('avatar', files);
            fd.append('_token', token);
            fd.append('id', $(brandId).val());
            fd.append('name', $(name).val());
            $.ajax(
                {
                    type:'POST',
                    enctype:'multipart/form-data',
                    url:'/manage/articleBrand/Update',
                    data:fd,
                    processData:false,
                    contentType:false,
                    success:function (data) {
                        $(brandId).val(null);
                        $(name).val(null);
                        $(picId).val(null);

                        $('.selectpicker').selectpicker('refresh');

                        window.location.href = '/manage/articleBrand';

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
        $(brandId).val(null);
        $(name).val(null);
        $(picId).val(null);

        $('.selectpicker').selectpicker('refresh');

        window.location.href = '/manage/articleBrand';

        $(".loader").fadeOut("slow");
    }
}

function deleteArticleBrand(id, token) {
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
                    url:'/manage/articleBrand/delete',
                    data:{
                        '_token' : token,
                        'id' : id,
                    },
                    success:function (data) {
                        if(data == 1){
                            startDelete.fire(
                                'تایید',
                                'فایل به همراه زیر مجموعه آن حذف گردید',
                                'success'
                            );
                            window.location.href = '/manage/articleBrand';
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
                        // window.location.href = '/manage/articleBrand';
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