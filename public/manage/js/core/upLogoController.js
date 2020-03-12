function changeResult(price, name, result, value) {
    var cash = document.getElementById(name.replace("#","")).value;

    if(value){
        cash = parseInt(cash) + parseInt(price);
    } else {
        cash = parseInt(cash) - parseInt(price);
    }
    var finalResult = parseInt(cash);
    document.getElementById(name.replace("#","")).value = finalResult;
    $(result).text(finalResult);
}