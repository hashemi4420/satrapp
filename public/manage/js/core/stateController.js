function searchState(name, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/state/searchState',
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

function startUpdateState(id, token, stateId, nameId) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/state/startUpdateState',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                var finalData = data.split("_:_");
                $(stateId).val(finalData[0]);
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

function updateState(status, stateId, name, token) {
    $(".loader").fadeIn();
    if(status){
        if($(name).val() === ""){

        } else {
            $.ajax(
                {
                    type:'POST',
                    url:'/manage/state/UpdateState',
                    data:{
                        '_token' : token,
                        'id' : $(stateId).val(),
                        'name' : $(name).val(),
                    },
                    success:function (data) {
                        $(stateId).val(null);
                        $(name).val(null);

                        $('.selectpicker').selectpicker('refresh');

                        window.location.href = '/manage/state';

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
        $(stateId).val(null);
        $(name).val(null);

        $('.selectpicker').selectpicker('refresh');

        window.location.href = '/manage/state';

        $(".loader").fadeOut("slow");
    }
}

function deleteState(id, token) {
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
                    url:'/manage/state/delete',
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
                            window.location.href = '/manage/state';
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
                        // window.location.href = '/manage/state';
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