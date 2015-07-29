function loadTxt() {
    document.getElementById("tab0").innerHTML = "YOUTUBE";
    document.getElementById("tab1").innerHTML = "СТИЛИ";
    document.getElementById("tab2").innerHTML = "РАЗМЕРЫ";
    document.getElementById("lnkLoadMore").innerHTML = "Загрузить Еще";
    document.getElementById("lblUrl").innerHTML = "URL:";
    document.getElementById("btnCancel").value = "закрыть";
    document.getElementById("btnInsert").value = "вставить";
    document.getElementById("btnSearch").value = " Найти ";    
}
function writeTitle() {
    document.write("<title>" + "Youtube Видео" + "</title>")
}