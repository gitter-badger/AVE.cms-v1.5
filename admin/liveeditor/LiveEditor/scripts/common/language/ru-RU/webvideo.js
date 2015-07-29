function loadTxt() {
    document.getElementById("tab0").innerHTML = "ПОСТЕР";
    document.getElementById("tab1").innerHTML = "MPEG4 ВИДЕО";
    document.getElementById("tab2").innerHTML = "Ogg ВИДЕО";
    document.getElementById("tab3").innerHTML = "WebM ВИДЕО";
    document.getElementById("lbImage").innerHTML = "Постер/превью изображение (.png or .jpg):";
    document.getElementById("lblMP4").innerHTML = "MPEG4 видео (.mp4):";
    document.getElementById("lblOgg").innerHTML = "Ogg видео (.ogv):";
    document.getElementById("lblWebM").innerHTML = "WebM видео (.webm):";
    document.getElementById("lblDimension").innerHTML = "Введите размер видео (ширина х высота):";
    document.getElementById("divNote1").innerHTML = "Для получения информации о HTML5-видео смотрите: <a href='http://www.w3schools.com/html5/html5_video.asp' target='_blank'>www.w3schools.com/html5/html5_video.asp</a>." +
        "Есть 3 поддерживаемых источников видео: MP4, WebM (e.g. for MSIE 9+), and Ogg (e.g. for FireFox). Браузер будет использовать первый признанный формат." +
        "Кроме того, вам потребуется предварительный просмотр или 'постер' изображения.";
    document.getElementById("divNote2").innerHTML = "Чтобы преобразовать видео в HTML5-видео (MP4, WebM & Ogg) Вы можете использовать: <a href='http://www.mirovideoconverter.com/' target='_blank'>www.mirovideoconverter.com</a>";

    document.getElementById("btnCancel").value = "закрыть";
    document.getElementById("btnInsert").value = "вставить";
}
function writeTitle() {
    document.write("<title>" + "HTML5 Видео" + "</title>")
}