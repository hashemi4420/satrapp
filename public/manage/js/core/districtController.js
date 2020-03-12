function getCitiesDistrict(id, token, elementId) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/district/getCity',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                document.getElementById(elementId.replace("#","")).options.length = 0;
                $(elementId).append(data);

                $('.selectpicker').selectpicker('refresh');

                $(".loader").fadeOut("slow");
            },
            error:function (xhr, ajaxOptions, throwError) {
                $(".loader").fadeOut("slow");
                alert(xhr.status);
                alert(throwError);
            }
        }
    );
}

function searchDistrict(stateId, cityId, name, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/district/searchDistrict',
            data:{
                '_token' : token,
                'stateId' : stateId,
                'cityId' : cityId,
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

function startUpdateDistrict(id, token, districtId, stateId, cityId, nameId) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/district/startUpdateDistrict',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                var finalData = data.split("_:_");
                $(districtId).val(finalData[0]);
                $(nameId).val(finalData[1]);

                document.getElementById(stateId.replace("#","")).options.length = 0;
                $(stateId).append(finalData[2]);

                document.getElementById(cityId.replace("#","")).options.length = 0;
                $(cityId).append(finalData[3]);

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

function updateDistrict(status, districtId, stateId, cityId, name, token) {
    $(".loader").fadeIn();
    if(status){
        if($(name).val() === "" || $(stateId).val() === "" || $(cityId).val() === ""){

        } else {
            $.ajax(
                {
                    type:'POST',
                    url:'/manage/district/UpdateDistrict',
                    data:{
                        '_token' : token,
                        'id' : $(districtId).val(),
                        'stateId' : $(stateId).val(),
                        'cityId' : $(cityId).val(),
                        'name' : $(name).val(),
                    },
                    success:function (data) {
                        $(districtId).val(null);
                        document.getElementById(cityId.replace("#","")).options.length = 1;
                        $(name).val(null);

                        $('.selectpicker').selectpicker('refresh');

                        window.location.href = '/manage/district';

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
        $(districtId).val(null);
        document.getElementById(cityId.replace("#","")).options.length = 1;
        $(name).val(null);

        $('.selectpicker').selectpicker('refresh');

        window.location.href = '/manage/district';

        $(".loader").fadeOut("slow");
    }
}

function deleteDistrict(id, token) {
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
                    url:'/manage/district/delete',
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
                        window.location.href = '/manage/district';
                    },
                    error:function (xhr, ajaxOptions, throwError) {
                        startDelete.fire(
                            'خطا',
                            'مشکلی در فرایند حذف فایل پیش آمده است',
                            'error'
                        );
                        $(".loader").fadeOut("slow");
                        // window.location.href = '/manage/district';
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