
jQuery(window).load(function () {
    $('.selectpicker').selectpicker({
        // style: 'btn-info',
        size: 8
    });
});

// user
function changePassUser(id, token) {

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
        confirmButtonText: 'مرحله بعد >>',
        cancelButtonText: 'انصراف',
        reverseButtons: true,
        progressSteps: ['1', '2', '3']
    }).queue([
        {
            title: 'تعویض کلمه عبور',
            text: 'کلمه عبور قبلی خود را وارد نمایید',
        },
        'کلمه عبور جدید را وارد نمایید',
        'لطفا کلمه عبور جدید را مجددا وارد نمایید'
    ]).then((result) => {
        if(result.value) {
            $(".loader").fadeIn();

            if(result.value[1] === result.value[2]){
                if(result.value[1].length < 8){
                    startDelete.fire(
                        'خطا',
                        'کلمه عبور نمیتواند کمتر از 8 کاراکتر باشد',
                        'error'
                    );
                    $(".loader").fadeOut("slow");
                } else {
                    $.ajax(
                        {
                            type:'POST',
                            url:'/manage/profile/checkPassword',
                            data:{
                                '_token' : token,
                                'id' : id,
                                'password' : result.value[0],
                            },
                            success:function (data) {
                                if(data === 'ok'){
                                    $.ajax(
                                        {
                                            type:'POST',
                                            url:'/manage/profile/changePass',
                                            data:{
                                                '_token' : token,
                                                'id' : id,
                                                'password' : result.value[1],
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
                                                    'مشکلی در فرایند تغییر کلمه عبور شما پیش آمده است',
                                                    'error'
                                                );
                                                $(".loader").fadeOut("slow");
                                                // alert(xhr.status);
                                                // alert(throwError);
                                            }
                                        }
                                    );
                                } else {
                                    startDelete.fire(
                                        'خطا',
                                        'کلمه عبور قبلی شما نادرست می باشد',
                                        'error'
                                    );
                                    $(".loader").fadeOut("slow");
                                }
                            },
                            error:function (xhr, ajaxOptions, throwError) {
                                startDelete.fire(
                                    'خطا',
                                    'مشکلی در فرایند تغییر کلمه عبور شما پیش آمده است',
                                    'error'
                                );
                                $(".loader").fadeOut("slow");
                                // alert(xhr.status);
                                // alert(throwError);
                            }
                        }
                    );
                }
            } else{
                startDelete.fire(
                    'خطا',
                    'کلمه های عبور یکسان نیستند',
                    'error'
                );
                $(".loader").fadeOut("slow");
            }
        }
    });
}

function changeTheme(id, theme, token) {
    var th = document.body.className;

    if(window.innerWidth <= 800){
        if(th == "light light-sidebar theme-white sidebar-gone"){
            theme = "dark dark-sidebar theme-black sidebar-gone";
            document.body.className = theme;
        } else {
            theme = "light light-sidebar theme-white sidebar-gone";
            document.body.className = theme;
        }
    }else {
        if(th == "light light-sidebar theme-white"){
            theme = "dark dark-sidebar theme-black";
            document.body.className = theme;
        } else {
            theme = "light light-sidebar theme-white";
            document.body.className = theme;
        }
    }

    // document.getElementById("sidebarMenu").click();

    $.ajax(
        {
            type:'POST',
            url:'/manage/profile/changeTheme',
            data:{
                '_token' : token,
                'id' : id,
                'theme' : theme,
            },
            success:function (data) {
            },
            error:function (xhr, ajaxOptions, throwError) {
                // alert(xhr.status);
                // alert(throwError);
            }
        }
    );
}

function updateProfile(status, brandId, name, picId, token) {
    $(".loader").fadeIn();
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