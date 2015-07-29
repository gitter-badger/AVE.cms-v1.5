/*** Translation ***/
LanguageDirectory="ru-RU";

function getTxt(s)
  {
  switch(s)
    {
    case "Save":return "Сохранить";
    case "Preview":return "Предпросмотр";
    case "Full Screen":return "Полный экран";
    case "Search":return "Поиск";
    case "Check Spelling":return "Проверка орфографии";
    case "Text Formatting":return "Форматирование текста";
    case "List Formatting":return "Список форматирования";
    case "Paragraph Formatting":return "Форматирование параграфа";
    case "Styles":return "Стили";
    case "Custom CSS":return "Пользовательский CSS";
    case "Styles & Formatting":return "Стили и Форматирование";
    case "Style Selection":return "Выбор стиля";
    case "Paragraph":return "Абзацы";
    case "Font Name":return "Имя шрифта";
    case "Font Size":return "Размер шрифта";
    case "Cut":return "Вырезать";
    case "Copy":return "Копировать";
    case "Paste":return "Вставить";
    case "Undo":return "Отменить";
    case "Redo":return "Вернуть";
    case "Bold":return "Жирный";
    case "Italic":return "Курсив";
    case "Underline":return "Подчеркнутый";
    case "Strikethrough":return "Зачерктнутый";
    case "Superscript":return "Верхний индекс";
    case "Subscript":return "Индекс";
    case "Justify Left":return "Выровнять слева";
    case "Justify Center":return "Выровнять по центру";
    case "Justify Right":return "Выровнять справа";
    case "Justify Full":return "Выровнять полностью";
    case "Numbering":return "Нумерованный список";
    case "Bullets":return "Маркированный список";
    case "Indent":return "Отступ";
    case "Outdent":return "Выступ";
    case "Left To Right":return "Слева направо";
    case "Right To Left":return "Справа налево";
    case "Foreground Color":return "Основной цвет";
    case "Background Color":return "Цвет фона";
    case "Hyperlink":return "Гиперссылка";
    case "Bookmark":return "Закладка";
    case "Special Characters":return "Специальные символы";
    case "Image":return "Изображение";
    case "Flash":return "Флеш";
    case "Media":return "Медиа";
    case "Content Block":return "Содержимое блока";
    case "Internal Link":return "Внутренние ссылки";
    case "Internal Image":return "Внутреннее Изображение";
    case "Object":return "Объект";
    case "Insert Table":return "Вставить таблицу";
    case "Table Size":return "Размер таблицы";
    case "Edit Table":return "Редактировать таблицу";
    case "Edit Cell":return "Редактировать ячейку";
    case "Table":return "Редактировать таблицу";
    case "AutoTable":return "Авто Формат Таблицы";
    case "Border & Shading":return "Границы и заливка";
    case "Show/Hide Guidelines":return "Показать/скрыть установки";
    case "Absolute":return "Абсолютный";
    case "Paste from Word":return "Вставить из Word";
    case "Line":return "Линия";
    case "Form Editor":return "Редактор форм";
    case "Form":return "Форма";
    case "Text Field":return "Текстовое поле";
    case "List":return "Список";
    case "Checkbox":return "Чекбокс";
    case "Radio Button":return "Радио кнопка";
    case "Hidden Field":return "Скрытое поле";
    case "File Field":return "Файловое поле";
    case "Button":return "Кнопка";
    case "Clean":return "Очистить";//not used
    case "View/Edit Source":return "Просмотр/редактирование исходника";
    case "Tag Selector":return "Селектор тегов";
    case "Clear All":return "Очистить все";
    case "Tags":return "Теги";

    case "Heading 1":return "Заголовок 1";
    case "Heading 2":return "Заголовок  2";
    case "Heading 3":return "Заголовок  3";
    case "Heading 4":return "Заголовок  4";
    case "Heading 5":return "Заголовок  5";
    case "Heading 6":return "Заголовок  6";
    case "Preformatted":return "Преформатирование";
    case "Normal (P)":return "Нормальный (P)";
    case "Normal (DIV)":return "Нормальный (DIV)";

    case "Size 1":return "Размер 1";
    case "Size 2":return "Размер 2";
    case "Size 3":return "Размер 3";
    case "Size 4":return "Размер 4";
    case "Size 5":return "Размер 5";
    case "Size 6":return "Размер 6";
    case "Size 7":return "Размер 7";

    case "Are you sure you wish to delete all contents?":
      return "Вы уверены, что хотите удалить все содержимое?";
    case "Remove Tag": return "Удалить тег";

    case "Custom Colors":return "Пользовательские цвета";
    case "More Colors...":return "Дополнительные цвета...";
    case "Box Formatting":return "Форматирование бокса";
    case "Advanced Table Insert":return "Вставить расширенную таблицу";
    case "Edit Table/Cell":return "Редактировать таблицу/ячейку";
    case "Print":return "Печать";
    case "Paste Text":return "Вставить текст";
    case "CSS Builder":return "CSS Builder";
    case "Remove Formatting":return "Удалить форматирование";
    case "Table Dimension Text": return "Таблица";
    case "Table Advance Link": return "Расширеный";

    case "Fonts": return "Шрифт";    
    case "Text": return "Текст";
    case "Link": return "Ссылка";
    case "YoutubeVideo": return "Youtube Видео";
    case "Search & Replace": return "Найти и заменить";
    case "HTML Editor": return "HTML Редактор";
    case "Emoticons": return "Смайлики";
    case "PasteWarning": return "Пожалуйста, вставьте с помощью клавиатуры (CTRL-V)"; /*Your browser security settings don't permit this operation.*/
    case "Quote": return "Кавычки";
    default:return "";
    }
  }