function loadTxt() {
    document.getElementById("tab0").innerHTML = "ВСТАВИТЬ";
    document.getElementById("tab1").innerHTML = "ИЗМЕНИТЬ";
    document.getElementById("tab2").innerHTML = "АВТОФОРМАТ";
    document.getElementById("btnDelTable").value = "Удалить выбранное";
    document.getElementById("btnIRow1").value = "Вставить Строку Выше";
    document.getElementById("btnIRow2").value = "Вставить Строку Ниже";
    document.getElementById("btnICol1").value = "Вставка Столбца слева";
    document.getElementById("btnICol2").value = "Вставка Столбца право";
    document.getElementById("btnDelRow").value = "Удалить строку";
    document.getElementById("btnDelCol").value = "Удалить столбец";
    document.getElementById("btnMerge").value = "Объеденить ячейки";
    document.getElementById("lblFormat").innerHTML = "ФОРМАТ:";
    document.getElementById("lblTable").innerHTML = "Таблицы";
    document.getElementById("lblEven").innerHTML = "Четных строк";
    document.getElementById("lblOdd").innerHTML = "Нечетных строк";
    document.getElementById("lblCurrRow").innerHTML = "Текущей строки";
    document.getElementById("lblCurrCol").innerHTML = "Текущего столбца";
    document.getElementById("lblBg").innerHTML = "ФОН:";
    document.getElementById("lblText").innerHTML = "ТЕКСТ:";    
    document.getElementById("lblBorder").innerHTML = "ГРАНИЦЫ:";
    document.getElementById("lblThickness").innerHTML = "Толщина:";
    document.getElementById("lblBorderColor").innerHTML = "ЦВЕТ:";
    document.getElementById("lblCellPadding").innerHTML = "ПОЛЯ ЯЧЕЕК:";
    document.getElementById("lblFullWidth").innerHTML = "Полная Ширина";
    document.getElementById("lblAutofit").innerHTML = "Автоподбор";
    document.getElementById("lblFixedWidth").innerHTML = "Фикс. ширина:";
    document.getElementById("lnkClean").innerHTML = "ОЧИСТИТЬ";
    document.getElementById("lblTextAlign").innerHTML = "ВЫРАВНЯТЬ ТЕКСТ:";
    document.getElementById("btnAlignLeft").value = "Слева";
    document.getElementById("btnAlignCenter").value = "По центру";
    document.getElementById("btnAlignRight").value = "Справа";
    document.getElementById("btnAlignTop").value = "Сверху";
    document.getElementById("btnAlignMiddle").value = "Посередине";
    document.getElementById("btnAlignBottom").value = "Снизу";

    document.getElementById("lblColor").innerHTML = "ЦВЕТ:";
    document.getElementById("lblCellSize").innerHTML = "РАЗМЕР ЯЧЕЙКИ:";
    document.getElementById("lblCellWidth").innerHTML = "Ширина:";
    document.getElementById("lblCellHeight").innerHTML = "Высота:";       
}
function writeTitle() {
    document.write("<title>" + "Таблица" + "</title>")
}
function getTxt(s) {
    switch (s) {
        case "Clean Formatting": return "Очистить Форматирование";
    }
}