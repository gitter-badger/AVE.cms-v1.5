function loadTxt() {
    document.getElementById("tab0").innerHTML = "ЧЕРТЕЖ";
    document.getElementById("tab1").innerHTML = "НАСТРОЙКИ";
    document.getElementById("tab3").innerHTML = "СОХРАНЕНО";

    document.getElementById("lblWidthHeight").innerHTML = "РАЗМЕР ХОЛСТА:";
    
    var optAlign = document.getElementsByName("optAlign");
    optAlign[0].text = ""
    optAlign[1].text = "Слева"
    optAlign[2].text = "Справа"

    document.getElementById("lblTitle").innerHTML = "ТИТУЛ:";
    document.getElementById("lblAlign").innerHTML = "ВЫРАВНИВАНИЕ:";
    document.getElementById("lblSpacing").innerHTML = "V-SPACING:";
    document.getElementById("lblSpacingH").innerHTML = "H-SPACING:";

    document.getElementById("btnCancel").value = "закрыть";
}
function writeTitle() {
    document.write("<title>" + "Чертеж" + "</title>")
}
function getTxt(s) {
    switch (s) {
        case "insert": return "вставить";
        case "change": return "ok";
        case "DELETE": return "УДАЛИТЬ";
    }
}