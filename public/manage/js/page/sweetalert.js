"use strict";

$("#swal-1").click(function () {
  swal('سلام');
});

$("#swal-2").click(function () {
  swal('موفقیت', 'بروی دکمه کلیک کنید!', 'success');
});

$("#swal-3").click(function () {
  swal('موفقیت', 'بروی دکمه کلیک کنید!', 'warning');
});

$("#swal-4").click(function () {
  swal('موفقیت', 'بروی دکمه کلیک کنید!', 'info');
});

$("#swal-5").click(function () {
  swal('خطا', 'بروی دکمه کلیک کنید!', 'error');
});

$("#swal-6").click(function () {
  swal({
    title: 'آیا شما مطمئن هستید؟',
    text: 'لورم به سادگی متن ساختگی صنعت چاپ و تحریر است.',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
  })
    .then((willDelete) => {
      if (willDelete) {
        swal('لورم به سادگی متن ساختگی صنعت چاپ و تحریر است.', {
          icon: 'success',
        });
      } else {
        swal('لورم به سادگی متن ساختگی صنعت چاپ و تحریر است.');
      }
    });
});

$("#swal-7").click(function () {
  swal({
    title: 'اسم شما چیست؟',
    content: {
      element: 'input',
      attributes: {
        placeholder: 'نام خود را وارد کنید',
        type: 'text',
      },
    },
  }).then((data) => {
    swal('سلام, ' + data + '!');
  });
});

$("#swal-8").click(function () {
  swal('لورم به سادگی متن ساختگی صنعت چاپ و تحریر است.', {
    buttons: false,
    timer: 3000,
  });
});