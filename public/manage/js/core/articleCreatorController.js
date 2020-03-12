function getArticleGroupCreator(id, token, target) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/articleCreator/getGroup',
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

function getArticleCategories(id, token, target) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/articleCreator/getCategories',
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

function searchArticleCreator(brand, area, group, category, name, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/articleCreator/search',
            data:{
                '_token' : token,
                'brand' : brand,
                'area' : area,
                'group' : group,
                'category' : category,
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

function startUpdateArticleCreator(id, token, articleId, nameId, picId, brandId, areaId, groupId, categoryId) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/articleCreator/startUpdate',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                var finalData = data.split("_:_");
                $(articleId).val(finalData[0]);
                $(nameId).val(finalData[1]);
                $(picId).attr('src', finalData[2]);
                $(brandId).val(finalData[3]);

                document.getElementById(areaId.replace("#","")).options.length = 0;
                $(areaId).append(finalData[4]);

                document.getElementById(groupId.replace("#","")).options.length = 0;
                $(groupId).append(finalData[5]);

                document.getElementById(categoryId.replace("#","")).options.length = 0;
                $(categoryId).append(finalData[6]);

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

function updateArticleCreator(status, articleId, name, picId, brandId, areaId, groupId, categoryId, token) {
    $(".loader").fadeIn();
    if(status){
        if($(name).val() === "" || $(brandId).val() === "" || $(categoryId).val() === ""){

        } else {
            var fd = new FormData();
            var files = $(picId)[0].files[0];
            fd.append('avatar', files);
            fd.append('_token', token);
            fd.append('id', $(articleId).val());
            fd.append('name', $(name).val());
            fd.append('brand', $(brandId).val());
            fd.append('area', $(areaId).val());
            fd.append('group', $(groupId).val());
            fd.append('category', $(categoryId).val());
            $.ajax(
                {
                    type:'POST',
                    enctype:'multipart/form-data',
                    url:'/manage/articleCreator/Update',
                    data:fd,
                    processData:false,
                    contentType:false,
                    success:function (data) {
                        $(articleId).val(null);
                        $(name).val(null);
                        $(picId).val(null);
                        $(brandId).val(null);
                        $(areaId).val(null);
                        $(groupId).val(null);
                        $(categoryId).val(null);

                        $('.selectpicker').selectpicker('refresh');

                        window.location.href = '/manage/createArticle';

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
        $(articleId).val(null);
        $(name).val(null);
        $(picId).val(null);
        $(brandId).val(null);
        $(groupId).val(null);
        $(categoryId).val(null);

        $('.selectpicker').selectpicker('refresh');

        window.location.href = '/manage/createArticle';

        $(".loader").fadeOut("slow");
    }
}

function deleteArticleCreator(id, token) {
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
                    url:'/manage/articleCreator/delete',
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
                        window.location.href = '/manage/createArticle';
                    },
                    error:function (xhr, ajaxOptions, throwError) {
                        startDelete.fire(
                            'خطا',
                            'مشکلی در فرایند حذف فایل پیش آمده است',
                            'error'
                        );
                        $(".loader").fadeOut("slow");
                        // window.location.href = '/manage/createArticle';
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
        text: 'آیا از فعال/غیر فعال کردن کالا مطمئنید؟',
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
                    url:'/manage/articleCreator/active',
                    data:{
                        '_token' : token,
                        'id' : id,
                    },
                    success:function (data) {
                        startDelete.fire(
                            'تایید',
                            'کالا فعال/غیر فعال گردید',
                            'success'
                        );
                        window.location.href = '/manage/createArticle';
                    },
                    error:function (xhr, ajaxOptions, throwError) {
                        startDelete.fire(
                            'خطا',
                            'مشکلی در فرایند فعال/غیر فعال کردن کالا پیش آمده است',
                            'error'
                        );
                        $(".loader").fadeOut("slow");
                        // window.location.href = '/manage/createArticle';
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