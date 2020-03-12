// // var cleaveNumeral = new Cleave('.clock', {
// //     time : true,
// //     timePattern : ['h', 'm']
// // });
//
// $(document).ready(function () {
//     new Cleave('#saateKariFrom', {
//         time : true,
//         timePattern : ['h', 'm']
//     });
//
//     new Cleave('#saateKariTo', {
//         time : true,
//         timePattern : ['h', 'm']
//     });
//
//     $('#aniimated-thumbnials').lightGallery({
//         thumbnail: true,
//         selector: 'a'
//     });
// });
//
// var cleavePhone = new Cleave('.input-phone', {
//     phone: true,
//     phoneRegionCode: 'IR'
// });
//
// // date
// var cleaveDate = new Cleave('.input-date', {
//     date: true
// });
//
// // numeral
// // var cleaveNumeral = new Cleave('.input-numeral', {
// //     numeral: true,
// //     numeralThousandsGroupStyle: 'thousand'
// // });
//
// // custom
// var cleaveCustom = new Cleave('.input-custom', {
//     blocks: [3, 3, 3, 3],
//     delimiter: '-',
// });
$(document).ready(function () {
    // $(":input").inputmask();
    // $(".phone").inputmask({"mask": "(999) 999-9999"});
});

function validate_int(myEvento, id) {
    var arrayData = myEvento.split('');
    var result = "";

    for(var i = 0; i < arrayData.length; i++){
        if(!isNaN(parseInt(arrayData[i]))){
            result = result.concat(arrayData[i]);
        }
    }
    document.getElementById(id).value = result;
}

function validate_clock(myEvento, id) {
    var arrayData = myEvento.split('');
    var result = "";

    for(var i = 0; i < arrayData.length; i++){
        if(!isNaN(parseInt(arrayData[i]))){

            if(result === ""){
                if(parseInt(arrayData[i]) > 2){
                    result = result.concat("0");
                    result = result.concat(arrayData[i]);
                } else {
                    result = result.concat(arrayData[i]);
                }
            } else if(result.length === 1) {
                if(parseInt(arrayData[i]) > 4 && parseInt(result) === 2){
                    result = result.concat("4");
                } else {
                    result = result.concat(arrayData[i]);
                }
            } else {
                if(result.length === 2){
                    if(parseInt(arrayData[i]) > 5){
                        result = result.concat(":");
                        result = result.concat("0");
                        result = result.concat(arrayData[i]);
                    } else {
                        result = result.concat(arrayData[i]);
                    }
                } else {
                    result = result.concat(arrayData[i]);
                }
            }

            if(result.length === 3){
                result = result.substr(0,2).concat(":").concat(result.substr(2,1));
            }
        }
    }
    document.getElementById(id).value = result;
}