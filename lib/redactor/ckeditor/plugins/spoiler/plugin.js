/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Code plugin.
 */

(function()
{
var pluginName = 'spoiler';
// Регистрируем имя плагина .
CKEDITOR.plugins.add( pluginName,
{
init : function( editor )
{//Добавляем команду на нажатие кнопки
editor.addCommand( pluginName,new CKEDITOR.dialogCommand( 'spoiler' ));
 //Указываем где скрипт окна диалога.
CKEDITOR.dialog.add( pluginName, this.path + 'dialogs/spoiler.js' );
// Добавляем кнопочку
editor.ui.addButton( 'Spoiler',
{
label : 'Добавить Spoiler',//Title кнопки
command : pluginName,
icon : this.path + 'logo.gif'//Путь к иконке
});
}
});
})();