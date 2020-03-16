function getServiceGroup(id, token, target) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/serviceCategory/getGroup',
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

function searchServiceCategory(areaId, groupId, name, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/serviceCategory/search',
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

function startUpdateServiceCategory(id, token, categoryId, areaId, groupId, nameId) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/serviceCategory/startUpdate',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                var finalData = data.split("_:_");
                $(categoryId).val(finalData[0]);
                $(nameId).val(finalData[1]);

                document.getElementById(areaId.replace("#","")).options.length = 0;
                $(areaId).append(finalData[2]);

                document.getElementById(groupId.replace("#","")).options.length = 0;
                $(groupId).append(finalData[3]);

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

function updateServiceCategory(status, categoryId, areaId, groupId, nameId, token) {
    $(".loader").fadeIn();
    if(status){
        if($(nameId).val() === "" || $(groupId).val() === ""){

        } else {
            $.ajax(
                {
                    type:'POST',
                    url:'/manage/serviceCategory/Update',
                    data:{
                        '_token' : token,
                        'id' : $(categoryId).val(),
                        'areaId' : $(areaId).val(),
                        'groupId' : $(groupId).val(),
                        'name' : $(nameId).val(),
                    },
                    success:function (data) {
                        $(categoryId).val(null);
                        $(nameId).val(null);
                        $(areaId).val(null);
                        $(groupId).val(null);

                        $('.selectpicker').selectpicker('refresh');

                        window.location.href = '/manage/serviceCategory';

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
        $(areaId).val(null);
        $(groupId).val(null);

        $('.selectpicker').selectpicker('refresh');

        window.location.href = '/manage/serviceCategory';

        $(".loader").fadeOut("slow");
    }
}

function deleteServiceCategory(id, token) {
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
                    url:'/manage/serviceCategory/delete',
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
                            window.location.href = '/manage/serviceCategory';
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
                        // window.location.href = '/manage/serviceCategory';
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