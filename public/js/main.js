function getCurrentTime(){
    return moment().format('h:mm A');
}

function getCurrentDateTime(){
    return moment().format('DD/MM/YY h:mm A');
}

function dateFormat(datetime){
    return moment(datetime, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YY h:mm A');
}

function timeFormat(datetime) {
    return moment(datetime, 'YYYY-MM-DD HH:mm:ss').format('h:mm A');
}
