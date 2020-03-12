function getServiceGroupProvider(id, token, target) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/providerService/getGroup',
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

function getServiceCategoryProvider(id, token, target) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/providerService/getCategories',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                document.getElementById(target.replace("#","")).options.length = 1;
                $(target).append(data);
                $('.selectpicker').selectpicker('refresh');

                $('.selectpicker').selectpicker('refresh');
                $(".loader").fadeOut("slow");
            }
        }
    );
}

function getServiceProvider(brand, area, group, category, token, target) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/providerService/getService',
            data:{
                '_token' : token,
                'brand' : brand,
                'area' : area,
                'group' : group,
                'category' : category,
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

function activeServiceProvider(id, token) {
    const startDelete = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success col-md-7',
            cancelButton: 'btn btn-danger col-md-5',
        },
        buttonsStyling: false
    });

    startDelete.fire({
        title: 'سوال',
        text: 'آیا از فعال/غیر فعال کردن آیتم مورد نظر مطمئنید؟',
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
                    url:'/manage/providerService/active',
                    data:{
                        '_token' : token,
                        'id' : id,
                    },
                    success:function (data) {
                        startDelete.fire(
                            'تایید',
                            'آیتم مورد نظر فعال/غیر فعال گردید',
                            'success'
                        );
                        window.location.href = '/manage/providerService';
                    },
                    error:function (xhr, ajaxOptions, throwError) {
                        startDelete.fire(
                            'خطا',
                            'مشکلی در فرایند فعال/غیر فعال کردن آیتم مورد نظر پیش آمده است',
                            'error'
                        );
                        $(".loader").fadeOut("slow");
                        // window.location.href = '/manage/providerService';
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
                'آیتم مورد نظر فعال/غیر فعال نشد',
                'error'
            )
        }
    });
    $(".loader").fadeOut("slow");
}

function searchServiceProvider(brand, area, group, category, service, price, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/providerService/search',
            data:{
                '_token' : token,
                'brand' : brand,
                'area' : area,
                'group' : group,
                'category' : category,
                'service' : service,
                'price' : price,
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

function startUpdateServiceProvider(id, token, providerId, brand, area, group, category, service, price, status) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/providerService/startUpdate',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                var finalData = data.split("_:_");
                $(providerId).val(finalData[0]);
                $(price).val(finalData[1]);
                $(status).val(finalData[2]);

                document.getElementById(brand.replace("#","")).options.length = 0;
                $(brand).append(finalData[3]);

                document.getElementById(area.replace("#","")).options.length = 0;
                $(area).append(finalData[4]);

                document.getElementById(group.replace("#","")).options.length = 0;
                $(group).append(finalData[5]);

                document.getElementById(category.replace("#","")).options.length = 0;
                $(category).append(finalData[6]);

                document.getElementById(service.replace("#","")).options.length = 0;
                $(service).append(finalData[7]);

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

function updateServiceProvider(status, providerId, service, price, sharh, token) {
    $(".loader").fadeIn();
    if(status){
        if($(service).val() === "" || $(price).val() === ""){

        } else {
            $.ajax(
                {
                    type:'POST',
                    url:'/manage/providerService/Update',
                    data:{
                        '_token' : token,
                        'id' : $(providerId).val(),
                        'service' : $(service).val(),
                        'price' : $(price).val(),
                        'sharh' : $(sharh).val(),
                    },
                    success:function (data) {
                        $(providerId).val(null);
                        $(service).val(null);
                        $(price).val(null);
                        $(sharh).val(null);

                        $('.selectpicker').selectpicker('refresh');

                        window.location.href = '/manage/providerService';

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
        $(providerId).val(null);
        $(service).val(null);
        $(price).val(null);
        $(sharh).val(null);

        $('.selectpicker').selectpicker('refresh');

        window.location.href = '/manage/providerService';

        $(".loader").fadeOut("slow");
    }
}