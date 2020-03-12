$(document).ready(function () {
    new Cleave('#searchPhone', {
        phone: true,
        phoneRegionCode: 'IR'
    });

    new Cleave('#phone', {
        phone: true,
        phoneRegionCode: 'IR'
    });
});

function searchUser(email, phone, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/users/searchUser',
            data:{
                '_token' : token,
                'email' : email,
                'phone' : phone,
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

function startUpdateUser(id, token, userId, nameId, familyId, phoneId, emailId, passwordId, articleBrand_id, serviceBrand_id, userRole, picId) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/users/startUpdateUser',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                var finalData = data.split("_:_");
                $(userId).val(finalData[0]);
                $(nameId).val(finalData[1]);
                $(familyId).val(finalData[2]);
                $(phoneId).val(finalData[3]);
                $(emailId).val(finalData[4]);
                $(passwordId).val(finalData[5]);
                $(articleBrand_id).val(finalData[6]);
                $(serviceBrand_id).val(finalData[7]);
                $(userRole).val(finalData[8]);
                $(picId).attr('src', finalData[9]);

                $('.selectpicker').selectpicker('refresh');

                $('#save').css('display','none');
                $('#update').css('display','block');
                $('#cancel').css('display','block');

                $(phoneId).attr('disabled', 'true');
                $(emailId).attr('disabled', 'true');
                $(passwordId).attr('disabled', 'true');

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

function updateUser(status, userId, nameId, familyId, phoneId, emailId, passwordId, articleBrand_id, serviceBrand_id, userRole, picId, token) {
    $(".loader").fadeIn();
    if(status){
        if($(nameId).val() === "" || $(familyId).val() === ""){

        } else {
            var fd = new FormData();
            var files = $(picId)[0].files[0];
            fd.append('avatar', files);
            fd.append('_token', token);
            fd.append('id', $(userId).val());
            fd.append('name', $(nameId).val());
            fd.append('family', $(familyId).val());
            fd.append('articleBrand', $(articleBrand_id).val());
            fd.append('serviceBrand', $(serviceBrand_id).val());
            fd.append('userRole', $(userRole).val());
            $.ajax(
                {
                    type:'POST',
                    enctype:'multipart/form-data',
                    url:'/manage/users/UpdateUser',
                    data:fd,
                    processData:false,
                    contentType:false,
                    success:function (data) {
                        $(userId).val(null);
                        $(nameId).val(null);
                        $(familyId).val(null);
                        $(articleBrand_id).val(null);
                        $(serviceBrand_id).val(null);
                        $(userRole).val(null);
                        $(phoneId).val(null);
                        $(emailId).val(null);
                        $(passwordId).val(null);
                        $(picId).val(null);

                        $('.selectpicker').selectpicker('refresh');

                        $(phoneId).attr('disabled', 'false');
                        $(emailId).attr('disabled', 'false');
                        $(passwordId).attr('disabled', 'false');

                        window.location.href = '/manage/users';

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
        $(userId).val(null);
        $(nameId).val(null);
        $(familyId).val(null);
        $(articleBrand_id).val(null);
        $(serviceBrand_id).val(null);
        $(userRole).val(null);
        $(phoneId).val(null);
        $(emailId).val(null);
        $(passwordId).val(null);

        $('.selectpicker').selectpicker('refresh');

        $(phoneId).attr('disabled', 'false');
        $(emailId).attr('disabled', 'false');
        $(passwordId).attr('disabled', 'false');

        window.location.href = '/manage/users';

        $(".loader").fadeOut("slow");
    }
}

function activeUser(id, token) {
    const startDelete = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success col-md-7',
            cancelButton: 'btn btn-danger col-md-5',
        },
        buttonsStyling: false
    });

    startDelete.fire({
        title: 'سوال',
        text: 'آیا از فعال/غیر فعال کردن کاربر مطمئنید؟',
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
                    url:'/manage/users/active',
                    data:{
                        '_token' : token,
                        'id' : id,
                    },
                    success:function (data) {
                        startDelete.fire(
                            'تایید',
                            'کاربر فعال/غیر فعال گردید',
                            'success'
                        );
                        window.location.href = '/manage/users';
                    },
                    error:function (xhr, ajaxOptions, throwError) {
                        startDelete.fire(
                            'خطا',
                            'مشکلی در فرایند فعال/غیر فعال کردن کاربر پیش آمده است',
                            'error'
                        );
                        $(".loader").fadeOut("slow");
                        // window.location.href = '/manage/users';
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
                'کاربر فعال/غیر فعال نشد',
                'error'
            )
        }
    });
    $(".loader").fadeOut("slow");
}

function changePass(id, token) {

    const startDelete = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success col-md-6',
            cancelButton: 'btn btn-danger col-md-6',
        },
        buttonsStyling: false
    });

    startDelete.mixin({
        input: 'password',

        showCancelButton: true,
        confirmButtonText: 'تعویض کلمه عبور',
        cancelButtonText: 'انصراف',
        reverseButtons: true
    }).queue([
        {
            title: 'تعویض کلمه عبور',
            text: 'کلمه عبور جدید را وارد نمایید',
        }
    ]).then((result) => {
        if(result.value) {
            $(".loader").fadeIn();

            $.ajax(
                {
                    type:'POST',
                    url:'/manage/users/changePass',
                    data:{
                        '_token' : token,
                        'id' : id,
                        'password' : result.value,
                    },
                    success:function (data) {
                        startDelete.fire(
                            'تایید',
                            'کلمه عبور کاربر با موفقیت تغییر یافت',
                            'success'
                        );
                        $(".loader").fadeOut("slow");
                    },
                    error:function (xhr, ajaxOptions, throwError) {
                        startDelete.fire(
                            'خطا',
                            'مشکلی در فرایند تغییر کلمه عبور کاربر پیش آمده است',
                            'error'
                        );
                        $(".loader").fadeOut("slow");
                        // alert(xhr.status);
                        // alert(throwError);
                    }
                }
            );

        }
    });
}

function disableService(value) {
    if(value != null && value !== ""){
        $("#serviceBrand_id").prop("disabled", true);
        $("#serviceBrand_id").val(0);
        $(".selectpicker").selectpicker("refresh");
    } else {
        $("#serviceBrand_id").prop("disabled", false);
        $(".selectpicker").selectpicker("refresh");
    }
}

function disableArticle(value) {
    if(value != null && value !== ""){
        $("#articleBrand_id").prop("disabled", true);
        $("#articleBrand_id").val(0);
        $(".selectpicker").selectpicker("refresh");
    } else {
        $("#articleBrand_id").prop("disabled", false);
        $(".selectpicker").selectpicker("refresh");
    }
}