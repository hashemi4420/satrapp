function startUpdateAccessLevel(id, token, accessId, nameId, tableId) {
    $(".loader").fadeIn();
    $.ajax(
        {
            type:'POST',
            url:'/manage/accessLevel/startUpdate',
            data:{
                '_token' : token,
                'id' : id,
            },
            success:function (data) {
                var finalData = data.split("_:_");
                $(accessId).val(finalData[0]);
                $(nameId).val(finalData[1]);
                $(tableId).children().remove();
                $(tableId).append(finalData[2]);

                $('.selectpicker').selectpicker('refresh');

                $('#save').css('display','none');
                $('#edit').css('display','block');
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