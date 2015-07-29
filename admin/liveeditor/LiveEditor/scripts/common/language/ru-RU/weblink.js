function loadTxt() {
    document.getElementById("tab0").innerHTML = "МОИ ФАЙЛЫ";
    document.getElementById("tab1").innerHTML = "СТИЛИ";
    document.getElementById("lblUrl").innerHTML = "URL:";
    document.getElementById("lblTitle").innerHTML = "TITLE:";
    document.getElementById("lblTarget1").innerHTML = "Открыть в этой же странице";
    document.getElementById("lblTarget2").innerHTML = "Открыть в новом окне";
    document.getElementById("lblTarget3").innerHTML = "Открыть в Lightbox";
    document.getElementById("lnkNormalLink").innerHTML = "Нормальная ссылка &raquo;";
    document.getElementById("btnCancel").value = "закрыть";
}
function writeTitle() {
    document.write("<title>" + "Ссылки" + "</title>")
}
function getTxt(s) {
    switch (s) {
        case "insert": return "вставить";
        case "change": return "ok";
    }
}