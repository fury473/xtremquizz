function chrono() {
    end = new Date();
    diff = end - start;
    diff = new Date(diff);
    var sec = diff.getSeconds();
    var min = diff.getMinutes();
    var hr = diff.getHours() - 1;

    if (sec < 10) {
        sec = "0" + sec;
    }
    if (min === 0 && hr === 0) {
        min = '';
    } else if (min < 10) {
        min = '0' + min + ' : ';
    } else {
        min = min + ' : ';
    }
    if (hr === 0) {
        hr = '';
    } else if (hr < 10) {
        hr = "0" + hr + " : ";
    } else {
        hr = hr + " : ";
    }
    $("#timer").html(hr + min + sec);
}

function Start(createdAt) {
    start = new Date(createdAt*1000);
    timerID = setInterval(chrono, 1000);
}

function Stop() {
    clearTimeout(timerID);
}