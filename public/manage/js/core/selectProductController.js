var page = 0;

function getProduct(id, page, token) {
    $(".loader").fadeIn();
    this.page = page;
    $.ajax(
        {
            type:'POST',
            url:'/manage/selectProduct/searchProduct',
            data:{
                '_token' : token,
                'id' : id,
                'page' : page,
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

function saveArticle(id, token) {
    $.ajax(
        {
            type:'POST',
            url:'/manage/selectProduct/saveArticle',
            data:{
                '_token' : token,
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

function saveService(id, token) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/selectProduct/saveService',
            data:{
                '_token' : token,
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