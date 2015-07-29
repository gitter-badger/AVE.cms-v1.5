function loadTxt() {
    document.getElementById("lblSearch").innerHTML = "НАЙТИ:";
    document.getElementById("lblReplace").innerHTML = "ЗАМЕНИТЬ:";
    document.getElementById("lblMatchCase").innerHTML = "С учетом регистра";
    document.getElementById("lblMatchWhole").innerHTML = "Слово целиком";

    document.getElementById("btnSearch").value = "искать далее"; ;
    document.getElementById("btnReplace").value = "заменить";
    document.getElementById("btnReplaceAll").value = "заменить все";
}
function getTxt(s) {
    switch (s) {
        case "Finished searching": return "Поиск по документу закончен.\nНачать поиск сначала?";
        default: return "";
    }
}
function writeTitle() {
    document.write("<title>Поиск и замена</title>")
}