function loadTxt() {
    document.getElementById("tab0").innerHTML = "ТЕКСТ";
    document.getElementById("tab1").innerHTML = "ТЕНИ";
    document.getElementById("tab2").innerHTML = "ЗАГОЛОВКИ";
    document.getElementById("tab3").innerHTML = "СПИСКИ";
    document.getElementById("tab4").innerHTML = "РАЗМЕР";

    document.getElementById("lblColor").innerHTML = "ЦВЕТ ТЕКСТА:";
    document.getElementById("lblHighlight").innerHTML = "ЦВЕТ ФОНА:";
    document.getElementById("lblLineHeight").innerHTML = "ВЫСОТА СТРОКИ:";
    document.getElementById("lblLetterSpacing").innerHTML = "РАССТОЯНИЕ МЕЖДУ БУКВАМИ:";
    document.getElementById("lblWordSpacing").innerHTML = "РАССТОЯНИЕ МЕЖДУ СЛОВАМИ:";
    document.getElementById("lblNote").innerHTML = "Эта функция не поддерживается в IE.";
    document.getElementById("divShadowClear").innerHTML = "ОЧИСТИТЬ";    
}
function writeTitle() {
    document.write("<title>" + "Текст" + "</title>")
}
function getTxt(s) {
    switch (s) {
        case "DEFAULT SIZE": return "Размер по умолчанию";
        case "Heading 1": return "Заголовок 1";
        case "Heading 2": return "Заголовок 2";
        case "Heading 3": return "Заголовок 3";
        case "Heading 4": return "Заголовок 4";
        case "Heading 5": return "Заголовок 5";
        case "Heading 6": return "Заголовок 6";
        case "Preformatted": return "Форматированный";
        case "Normal": return "Нормальный";
    }
}