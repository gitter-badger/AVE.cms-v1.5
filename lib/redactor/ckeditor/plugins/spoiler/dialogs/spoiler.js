CKEDITOR.dialog.add( 'spoiler', function( editor )
{
	return {
		title : 'Spoiler',
		minWidth : 400,
		minHeight : 300,
		contents : [
			{
				id : 'tab1',
				label : 'First Tab',
				title : 'First Tab',
				elements :
				[
					{   id : 'input1',
						type : 'text',
						style : 'width:140px;',
						label : 'Введите текст кнопки:',
                        validate : function()

{
CKEDITOR.config.text_val= this.getValue();
if ( !this.getValue() )
{alert( 'Поле текст кнопки пустое!' );
return false;}
}},
					{
						type : 'html',
	                    id : 'content',
	                    html :
	                    '<textarea style="' +
                        'width:406px;' +
                        'height:170px;' +
	                    'resize: none;' +
	                    'border:1px solid black;' +
	                    'background-color:white">' +
                        '</textarea>',
                        validate : function()
{ if ( !this.getValue() )
{alert( 'Поле текст пустое!' );
return false;}
var element= editor.document.createElement( 'div' );
      element.setAttribute( 'style', 'margin:4px 0px 4px 0px' );
      element.setHtml("<input onclick=\"if(this.parentNode.getElementsByTagName('div')[0].style.display != '') { this.parentNode.getElementsByTagName('div')[0].style.display = ''; } else { this.parentNode.getElementsByTagName('div')[0].style.display = 'none'; }\" type=\"button\" value="+CKEDITOR.config.text_val+" />");
      var element2= editor.document.createElement( 'div' );
      element2.setAttribute( 'class', 'class="spoiler" style="display:none"' );
      element2.setText(this.getValue());
      element2.appendTo( element );
editor.insertElement( element );
CKEDITOR.ENTER_BR;
return true;
}

},{
	                                                                type : 'html',
                                                                id : 'pasteMsg',
	                                                                html : '<div style="white-space:normal;width:340px;">Вы можете использовать горячие клавиши.<br /><STRONG>Ctrl+X</STRONG> - вырезать.<br /> <STRONG>Ctrl+V</STRONG> - вставить.<br /><STRONG>Ctrl+C</STRONG> - скопировать. </div>'
                                                        }

				]
			}
		]
	};
} );