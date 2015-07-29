function loadTxt() {
    document.getElementById("tab0").innerHTML = "FLICKR";
    document.getElementById("tab1").innerHTML = "МОИ ФАЙЛЫ";
    document.getElementById("tab2").innerHTML = "СТИЛИ";
    document.getElementById("tab3").innerHTML = "ЭФФЕКТЫ";
    document.getElementById("lblTag").innerHTML = "ТЕГ:";
    document.getElementById("lblFlickrUserName").innerHTML = "Flickr User Name:";
    document.getElementById("lnkLoadMore").innerHTML = "Загрузить Еще";
    document.getElementById("lblImgSrc").innerHTML = "ИСТОЧНИК ИЗОБРАЖЕНИЯ:";
    document.getElementById("lblWidthHeight").innerHTML = "ШИРИНА x ВЫСОТА:";
    
    var optAlign = document.getElementsByName("optAlign");
    optAlign[0].text = ""
    optAlign[1].text = "Слева"
    optAlign[2].text = "Справа"

    document.getElementById("lblTitle").innerHTML = "НАЗВАНИЕ (TITLE):";
    document.getElementById("lblAlign").innerHTML = "ВЫРОВНЯТЬ:";
    document.getElementById("lblMargin").innerHTML = "ОТСТУП: (СВЕРХУ / СПРАВА / СНИЗУ / СЛЕВА)";
    document.getElementById("lblSize1").innerHTML = "МАЛЕНЬКАЯ-КВАДРАТ";
    document.getElementById("lblSize2").innerHTML = "ПРЕВЬЮ";
    document.getElementById("lblSize3").innerHTML = "МАЛАЯ";
    document.getElementById("lblSize5").innerHTML = "СРЕДНЯЯ";
    document.getElementById("lblSize6").innerHTML = "БОЛЬШАЯ";

    document.getElementById("lblOpenLarger").innerHTML = "ОТКРЫТЬ ОРИГИНАЛ В LIGHTBOX, ИЛИ";
    document.getElementById("lblLinkToUrl").innerHTML = "ССЫЛКА НА URL:";
    document.getElementById("lblNewWindow").innerHTML = "ОТКРЫТЬ В НОВОМ ОКНЕ.";
    document.getElementById("btnCancel").value = "закрыть";
    document.getElementById("btnSearch").value = " Найти ";

    document.getElementById("btnRestore").value = "Оригинал изображения";
    document.getElementById("btnSaveAsNew").value = "Сохранить Как Новое Изображение"; 
}
function writeTitle() {
    document.write("<title>" + "Изображение" + "</title>")
}
function getTxt(s) {
    switch (s) {
        case "insert": return "вставить";
        case "change": return "ok";
        case "notsupported": return "Внешнее изображение не поддерживается.";
    }
}