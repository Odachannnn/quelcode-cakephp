var d = 0; //日
var h = 0; //時
var m = 0; //分
var s = 0; //秒

timerID = setInterval('countdown()',1000); //1秒毎にcountup()を呼び出し

function countdown() {
    if (diff > 0) {
        diff--;
        d = Math.floor(diff / (60 * 60 * 24));
        h = Math.floor(diff / (60 * 60)) % 24;
        m = Math.floor(diff / 60) % 60;
        s = Math.floor(diff % 60);
        document.getElementById("count").innerHTML = 'あと' + d + '日' + h + '時' +  m + '分' + s + '秒';
    } else {
        document.getElementById("count").innerHTML = 'この商品のオークションは終了しました';
    }
}
