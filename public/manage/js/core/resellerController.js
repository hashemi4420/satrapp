function searchUser(email, phone, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/createReseller/searchUser',
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

function startUpdateUser(id, token, userId, nameId, familyId, companyId, emailId, passwordId, phoneId, articleId, serviceId, numberFreeId, freeCallId, freeLogo, picId) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/createReseller/startUpdateUser',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                var finalData = data.split("_:_");
                $(userId).val(finalData[0]);
                $(nameId).val(finalData[1]);
                $(familyId).val(finalData[2]);
                $(companyId).val(finalData[3]);
                $(emailId).val(finalData[4]);
                $(passwordId).val(finalData[5]);
                $(phoneId).val(finalData[6]);
                $(articleId).val(finalData[7]);

                //document.getElementById(articleId.replace("#","")).options.length = 0;
                // $(articleId).append(finalData[7]);

                $(serviceId).val(finalData[8]);

                //document.getElementById(serviceId.replace("#","")).options.length = 0;
                // $(serviceId).append(finalData[8]);

                $(numberFreeId).val(finalData[9]);
                $(freeCallId).val(finalData[10]);
                $(freeLogo).val(finalData[11]);
                $(picId).attr('src', finalData[12]);

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

function updateUser(status, token, userId, nameId, familyId, companyId, emailId, passwordId, phoneId, articleId, serviceId, numberFreeId, freeCallId, freeLogo, picId) {

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
            fd.append('articleId', $(articleId).val());
            fd.append('serviceId', $(serviceId).val());
            fd.append('numberFreeId', $(numberFreeId).val());
            fd.append('freeCallId', $(freeCallId).val());
            fd.append('freeLogo', $(freeLogo).val());
            $.ajax(
                {
                    type:'POST',
                    enctype:'multipart/form-data',
                    url:'/manage/createReseller/UpdateUser',
                    data:fd,
                    processData:false,
                    contentType:false,
                    success:function (data) {
                        $(userId).val(null);
                        $(nameId).val(null);
                        $(familyId).val(null);
                        $(companyId).val(null);
                        $(emailId).val(null);
                        $(passwordId).val(null);
                        $(phoneId).val(null);
                        $(articleId).val(null);
                        $(serviceId).val(null);
                        $(numberFreeId).val(null);
                        $(freeCallId).val(null);
                        $(freeLogo).val(null);
                        $(picId).val(null);

                        $('.selectpicker').selectpicker('refresh');

                        $(phoneId).attr('disabled', 'false');
                        $(emailId).attr('disabled', 'false');
                        $(passwordId).attr('disabled', 'false');

                        window.location.href = '/manage/createReseller';

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
        $(companyId).val(null);
        $(emailId).val(null);
        $(passwordId).val(null);
        $(phoneId).val(null);
        $(articleId).val(null);
        $(serviceId).val(null);
        $(numberFreeId).val(null);
        $(freeCallId).val(null);
        $(freeLogo).val(null);
        $(picId).val(null);

        $('.selectpicker').selectpicker('refresh');

        $(phoneId).attr('disabled', 'false');
        $(emailId).attr('disabled', 'false');
        $(passwordId).attr('disabled', 'false');

        window.location.href = '/manage/createReseller';

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
                    url:'/manage/createReseller/active',
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
                        window.location.href = '/manage/createReseller';
                    },
                    error:function (xhr, ajaxOptions, throwError) {
                        startDelete.fire(
                            'خطا',
                            'مشکلی در فرایند فعال/غیر فعال کردن کاربر پیش آمده است',
                            'error'
                        );
                        $(".loader").fadeOut("slow");
                        // window.location.href = '/manage/createReseller';
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
                    url:'/manage/createReseller/changePass',
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

function takhsis(workFieldArticle, workFieldService, userId, name, token) {

    const inputOptions = new Map();

    if(workFieldArticle != null){
        var selection = (document.getElementById("selecetionArticle").value);
        var items = selection.split(",");
        for (var i = 0; i < items.length; i++){
            var item = items[i].split(":");
            inputOptions.set(item[0], item[1]);
        }
    } else if(workFieldService != null){
        var selection = (document.getElementById("selecetionService").value);
        var items = selection.split(",");
        for (var i = 0; i < items.length; i++){
            var item = items[i].split(":");
            inputOptions.set(item[0], item[1]);
        }
    }

    const value  = "";
    Swal.fire({
        title: 'برند را انتخاب نمایید',
        input: 'select',
        inputOptions,
        // inputPlaceholder: 'انتخاب نمایید',
        showCancelButton: true,

        customClass: {
            confirmButton: 'btn btn-success col-md-6',
            cancelButton: 'btn btn-danger col-md-6',
        },
        buttonsStyling: false,
        confirmButtonText: 'اعمال',
        cancelButtonText: 'انصراف',

        inputValidator: (value) => {
            return new Promise((resolve) => {
                $(".loader").fadeIn();
                $.ajax(
                    {
                        type:'POST',
                        url:'/manage/createReseller/takhsis',
                        data:{
                            '_token' : token,
                            'token' : token,
                            'userId' : userId,
                            'brand' : value,
                            'page' : 1,
                        },
                        success:function (data) {
                            $(name).children().remove();
                            $(name).append(data);
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
                resolve();
            })
        }
    });
/*
    */
}

function takhsisPage(userId, page, brandId, name, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/createReseller/takhsis',
            data:{
                '_token' : token,
                'token' : token,
                'userId' : userId,
                'brand' : brandId,
                'page' : parseInt(page),
            },
            success:function (data) {
                $(name).children().remove();
                $(name).append(data);
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

function productService(userId, name, token) {
    $(".loader").fadeIn();

    $.ajax(
        {
            type:'POST',
            url:'/manage/createReseller/productService',
            data:{
                '_token' : token,
                'token' : token,
                'userId' : userId,
                'page' : 1,
            },
            success:function (data) {
                $(name).children().remove();
                $(name).append(data);
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

function productServicePage(userId, page, name, token) {
    $(".loader").fadeIn();

    $.ajax(
        {
            type:'POST',
            url:'/manage/createReseller/productService',
            data:{
                '_token' : token,
                'token' : token,
                'userId' : userId,
                'page' : parseInt(page),
            },
            success:function (data) {
                $(name).children().remove();
                $(name).append(data);
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

function deleteArticleProvider(id, token) {
    const startDelete = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success col-md-6',
            cancelButton: 'btn btn-danger col-md-6',
        },
        buttonsStyling: false
    });

    startDelete.fire({
        title: 'مطمئن هستید؟',
        text: '',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'بله',
        cancelButtonText: 'خیر',
        reverseButtons: true
    }).then((result) => {
        if (result.value){
            $(".loader").fadeIn();
            $.ajax(
                {
                    type:'POST',
                    url:'/manage/createReseller/deleteArticle',
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
                        window.location.href = '/manage/createReseller';
                    },
                    error:function (xhr, ajaxOptions, throwError) {
                        startDelete.fire(
                            'خطا',
                            'مشکلی در فرایند حذف فایل پیش آمده است',
                            'error'
                        );
                        $(".loader").fadeOut("slow");
                        // window.location.href = '/manage/createReseller';
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

function deleteServiceProvider(id, token) {
    const startDelete = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success col-md-6',
            cancelButton: 'btn btn-danger col-md-6',
        },
        buttonsStyling: false
    });

    startDelete.fire({
        title: 'مطمئن هستید؟',
        text: '',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'بله',
        cancelButtonText: 'خیر',
        reverseButtons: true
    }).then((result) => {
        if (result.value){
            $(".loader").fadeIn();
            $.ajax(
                {
                    type:'POST',
                    url:'/manage/createReseller/deleteService',
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
                        window.location.href = '/manage/createReseller';
                    },
                    error:function (xhr, ajaxOptions, throwError) {
                        startDelete.fire(
                            'خطا',
                            'مشکلی در فرایند حذف فایل پیش آمده است',
                            'error'
                        );
                        $(".loader").fadeOut("slow");
                        // window.location.href = '/manage/createReseller';
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

function covered(userId, name, token) {

    const inputOptions = new Map();

    var selection = (document.getElementById("selecetionState").value);
    var items = selection.split(",");
    for (var i = 0; i < items.length; i++){
        var item = items[i].split(":");
        inputOptions.set(item[0], item[1]);
    }

    const value  = "";
    Swal.fire({
        title: 'استان را انتخاب نمایید',
        input: 'select',
        inputOptions,
        // inputPlaceholder: 'انتخاب نمایید',
        showCancelButton: true,

        customClass: {
            confirmButton: 'btn btn-success col-md-6',
            cancelButton: 'btn btn-danger col-md-6',
        },
        buttonsStyling: false,
        confirmButtonText: 'اعمال',
        cancelButtonText: 'انصراف',

        inputValidator: (value) => {
            return new Promise((resolve) => {
                $(".loader").fadeIn();
                $.ajax(
                    {
                        type:'POST',
                        url:'/manage/createReseller/covered',
                        data:{
                            '_token' : token,
                            'token' : token,
                            'userId' : userId,
                            'stateId' : value,
                        },
                        success:function (data) {
                            $(name).children().remove();
                            $(name).append(data);
                            $(".loader").fadeOut("slow");
                        },
                        error:function (xhr, ajaxOptions, throwError) {
                            $(".loader").fadeOut("slow");
                            // alert(xhr.status);
                            // alert(throwError);
                        }
                    }
                );
                resolve();
            })
        }
    });
    /*
        */
}

function saveCovered(stateId, cityId, districtId, userId, token) {
    // $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/createReseller/saveCovered',
            data:{
                '_token' : token,
                'stateId' : stateId,
                'cityId' : cityId,
                'districtId' : districtId,
                'userId' : userId,
            },
            success:function (data) {
                // window.location.href = '/manage/createReseller';
                // $(".loader").fadeOut("slow");
            },
            error:function (xhr, ajaxOptions, throwError) {
                // $(".loader").fadeOut("slow");
                // alert(xhr.status);
                // alert(throwError);
            }
        }
    );
}

function saveArticle(userId, id, token) {
    $.ajax(
        {
            type:'POST',
            url:'/manage/createReseller/saveArticle',
            data:{
                '_token' : token,
                'userId' : userId,
                'id' : id,
                'prise' : "0",
                'persend' : "0",
                'atless' : "0",
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

function saveService(userId, id, token) {
    $.ajax(
        {
            type:'POST',
            url:'/manage/createReseller/saveService',
            data:{
                '_token' : token,
                'userId' : userId,
                'id' : id,
                'prise' : "0",
                'persend' : "0",
                'atless' : "0",
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

function selectAll(value) {
    let i;
    const items = document.getElementsByName("asc");
    if(value === true){
        for(i = 0; i<items.length; i++){
            if(items[i].type === "checkbox"){
                items[i].checked = true;
            }
        }
    } else {
        for(i = 0; i<items.length; i++){
            if(items[i].type === "checkbox"){
                items[i].checked = false;
            }
        }
    }
}

function selectAllCity(arr, value) {
    for(var i = 0; i < arr.length; i++){
        if(value === true){
            document.getElementById(arr[i]).checked = true;
        } else {
            document.getElementById(arr[i]).checked = false;
        }
    }
}

function disableService(value) {
    if(value != null && value !== ""){
        $("#serviceId").prop("disabled", true);
        $("#serviceId").val(0);
        $(".selectpicker").selectpicker("refresh");
    } else {
        $("#serviceId").prop("disabled", false);
        $(".selectpicker").selectpicker("refresh");
    }
}

function disableArticle(value) {
    if(value != null && value !== ""){
        $("#articleId").prop("disabled", true);
        $("#articleId").val(0);
        $(".selectpicker").selectpicker("refresh");
    } else {
        $("#articleId").prop("disabled", false);
        $(".selectpicker").selectpicker("refresh");
    }
}