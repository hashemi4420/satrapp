function searchService(name, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/service/search',
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

function startUpdateService(id, token, serviceId, nameId) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/service/startUpdate',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                var finalData = data.split("_:_");
                $(serviceId).val(finalData[0]);
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

function updateService(status, serviceId, name, token) {
    $(".loader").fadeIn();
    if(status){
        if($(name).val() === ""){

        } else {
            $.ajax(
                {
                    type:'POST',
                    url:'/manage/service/Update',
                    data:{
                        '_token' : token,
                        'id' : $(serviceId).val(),
                        'name' : $(name).val(),
                    },
                    success:function (data) {
                        $(serviceId).val(null);
                        $(name).val(null);

                        $('.selectpicker').selectpicker('refresh');

                        location.reload();

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
        $(serviceId).val(null);
        $(name).val(null);

        $('.selectpicker').selectpicker('refresh');

        location.reload();

        $(".loader").fadeOut("slow");
    }
}

function deleteService(id, token) {
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
                    url:'/manage/service/delete',
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
                        location.reload();
                    },
                    error:function (xhr, ajaxOptions, throwError) {
                        startDelete.fire(
                            'خطا',
                            'مشکلی در فرایند حذف فایل پیش آمده است',
                            'error'
                        );
                        $(".loader").fadeOut("slow");
                        // location.reload();
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