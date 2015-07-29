   <link rel="stylesheet" type="text/css" href="{$ABS_PATH}admin/templates/liveeditor/css/mod_liveeditor.css" media="screen" />
	<script src="{$ABS_PATH}admin/liveeditor/LiveEditor/scripts/language/ru-RU/editor_lang.js"></script>
	<script src="{$ABS_PATH}admin/liveeditor/LiveEditor/scripts/innovaeditor.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/webfont/1.5.2/webfont.js" type="text/javascript"></script>
	<script src="{$ABS_PATH}admin/liveeditor/LiveEditor/scripts/common/webfont.js" type="text/javascript"></script>
	<script src="{$ABS_PATH}admin/liveeditor/LiveEditor/scripts/common/nlslightbox/nlslightbox.js" type="text/javascript"></script>
	<script src="{$ABS_PATH}admin/liveeditor/LiveEditor/scripts/common/nlslightbox/nlsanimation.js" type="text/javascript"></script>
	<script src="{$ABS_PATH}admin/liveeditor/LiveEditor/scripts/common/nlslightbox/dialog.js" type="text/javascript"></script>
	<script src="{$ABS_PATH}admin/templates/liveeditor/js/jquery.dragsort-0.5.1.min.js" type="text/javascript"></script>
	<link href="{$ABS_PATH}admin/liveeditor/LiveEditor/scripts/common/nlslightbox/nlslightbox.css" rel="stylesheet" type="text/css" />

{literal}
	<style type="text/css">
	  .activeline {background: #e8f2ff !important;}
	  .istoolbar_container tbody, tr, th, td {vertical-align:top;}/*Фикс, влияет reset.css - клик в тулбаре на кнопку стилей - они улет. вниз*/
	  .box_container {margin-top:0px !important;} /*Модальные окна на этой странице отображаются ниже/выше центра этим стилем*/
	</style>
{/literal}

<div class="title"><h5>{#LIVEEDITOR_EDIT_H#}</h5></div>

<div class="widget" style="margin-top: 0px;">
	<div class="body">
		{#LIVEEDITOR_INSERT#}
	</div>
</div>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
		<ul>
			<li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
			<li><a href="index.php?do=liveeditor&cp={$sess}" title="">{#LIVEEDITOR_LIST_LINK#}</a></li>
			<li>{if $smarty.request.id != ''}{#LIVEEDITOR_EDIT_H#}{else}{#LIVEEDITOR_INSERT_H#}{/if}</li>
			<li><strong class="code">{if $smarty.request.id != ''}{$liveeditor_name|escape}{else}{$smarty.request.liveeditor_name}{/if}</strong></li>
		</ul>
	</div>
</div>
 <script type="text/javascript">
	$(document).ready(function()
		{literal}{
		$('.collapsible').collapsible({
			defaultOpen: 'opened',
			cssOpen: 'inactive',
			cssClose: 'normal',
			cookieName: 'collaps_liv',
			cookieOptions: {
				expires: 35,
				domain: ''
			},
			speed: 200
		}); 
		
		$('div#mod_le_vid .istoolbar_container').prepend("<span class='topDir mod_le_video' title=\"{/literal}{#LIVEEDITOR_DEMO_DESC#}{literal}\" style='float:right;margin-right:90px; margin-top:20px;' onClick=\"modalDialog('/admin/templates/liveeditor/lesson/lesson.html', 896,524)\">&nbsp;&nbsp;{/literal}{#LIVEEDITOR_DEMO#}{literal}</span>");
		
		$('.istoolbar_container').find('img').addClass('topDir');
		//===== Tooltip =====//
	$('.topleftDir').tipsy({fade: false, gravity: 'se'});
	$('.toprightDir').tipsy({fade: false, gravity: 'sw'});
	$('.leftDir').tipsy({fade: false, gravity: 'e'});
	$('.rightDir').tipsy({fade: false, gravity: 'w'});
	$('.topDir').tipsy({fade: false, gravity: 's'});
	$('.botDir').tipsy({fade: false, gravity: 'n'});
	});
		{/literal}
</script>

<div class="widget first">
<div class="head collapsible" id="opened"><h5 class="iFrames"><span class="topDir mod_le_col" title="{#LIVEEDITOR_OPEN#}">{#LIVEEDITOR_CAP#}</span></h5></div>
<div id="mod_le_vid" style="display: block;">

<textarea class="mousetrap" id="txtContent" rows="4" cols="30" style="width: 100%;"></textarea>
{literal}
<script type="text/javascript">
var oEdit778 = new InnovaEditor("oEdit778");
oEdit778.css = ["/admin/liveeditor/LiveEditor/styles/default.css","/admin/liveeditor/AddOns/bootstrap/css/bridge.css"];
oEdit778.arrCustomButtons = [["Snippets", "modalDialog(\'/admin/liveeditor/AddOns/bootstrap/snippets.htm\',900,658,\'Insert Snippets\');", "Bootstrap", "btnContentBlock.gif"]];
oEdit778.fileBrowser = "/admin/liveeditor/AddOns/assetmanager/asset.php";
oEdit778.width="100%";
oEdit778.height="200";
oEdit778.disableFocusOnLoad = true;
oEdit778.groups = [
		["group1", "", ["FontName", "FontSize", "Superscript", "ForeColor", "BackColor", "FontDialog", "Quote", "CompleteTextDialog", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "TextDialog", "Styles", "RemoveFormat"]],
		["group2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "Paragraph", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
		["group3", "", ["Table", "TableDialog", "Emoticons", "FlashDialog", "BRK", "LinkDialog", "ImageDialog", "YoutubeDialog"]],
		["group4", "", ["CharsDialog", "Line", "BRK", "Snippets"]],
		["group5", "", ["SearchDialog", "SourceDialog", "ClearAll", "BRK", "Undo", "Redo", "FullScreen"]]
		];
oEdit778.REPLACE("txtContent");
</script>
{/literal}
</div>
</div>
<form id="liveeditor" action="index.php?do=liveeditor&action=save&cp={$sess}" method="post" class="mainForm">

<div class="widget first">
 <div class="head"><h5 class="iFrames">{if $smarty.request.id != ''}{#LIVEEDITOR_EDIT_H#}{else}{#LIVEEDITOR_INSERT_H#}{/if}&nbsp;|&nbsp;{#LIVEEDITOR_STATUS#} :&nbsp;<span class="mod_le_active"></span><span class="vlw topDir" style="margin-left:10px; cursor:pointer;" title="{#LIVEEDITOR_LES_VID_L#}" onclick="modalDialog('/admin/templates/liveeditor/lesson/lesson_sys.html', 930,638)">|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{#LIVEEDITOR_LES_VID_M#}</span></h5></div>
  <div class="rowElem noborder">
	<label>{#LIVEEDITOR_NAME_A#}</label>
	<div class="formRight"><input name="liveeditor_name" class="mousetrap" type="text" value="{if $smarty.request.id != ''}{$liveeditor_name|escape}{else}{$smarty.request.liveeditor_name}{/if}" size="80" /></div>
	<div class="fix"></div>
	</div>
 </div>
 
<script type="text/javascript">
$(document).ready(function()
		{literal}{
		var a = 1;
		var b = {/literal}{if $smarty.request.id != ''}{$liveeditor_status|escape}{else}{$smarty.request.liveeditor_status}{/if}{literal};
		var c = {/literal}{if $smarty.request.id != ''}{$liveeditor_fields|escape}{else}{$smarty.request.liveeditor_fields}{/if}{literal};
		if(a == b)     {
	$('.mod_le_active').prepend("{/literal}{#LIVEEDITOR_ACTIVATED#}{literal}&nbsp;")
} 
		else             {
	$('.mod_le_active').prepend("{/literal}{#LIVEEDITOR_DEACTIVATED#}{literal}&nbsp;")
}		
		if(a == c)     {
	$('.mod_le_active').append("{/literal}{#LIVEEDITOR_M_FIELD#}{literal}")
} 
		else if(++a == c) {
	$('.mod_le_active').append("{/literal}{#LIVEEDITOR_SM_FIELD#}{literal}")
}
		else if(++a == c) {
	$('.mod_le_active').append("{/literal}{#LIVEEDITOR_SB_FIELD#}{literal}")
}
		else             {
	$('.mod_le_active').append("{/literal}{#LIVEEDITOR_EM_FIELD#}{literal}")
}				
				
});
		{/literal}
</script>
 
<div class="widget first">
 <div class="head collapsibltool" id="opened"><h5 class="iFrames"><span class="topDir mod_le_col" title="{#LIVEEDITOR_TB_OPEN#}">{if $smarty.request.id != ''}{#LIVEEDITOR_TOOLBAR#}{else}{#LIVEEDITOR_TOOLBAR_ADD#}{/if}</span></h5></div>
 <div id="mod_le_vid" style="display: block;">
 <div title ="{#LIVEEDITOR_RESET_VAR#}" class="topleftDir mod_li_reset basicBtn">{#LIVEEDITOR_RESET#}</div>
 <div title="{#LIVEEDITOR_CHEC_HELP#}" class="topDir title_btn_mod_li_real_toolbar">{#LIVEEDITOR_CHEC_TITLE#}</div>
 <div class="btn_mod_li_real_toolbar"><input type="checkbox" id="btn_mod_li_real" /></div>
 <div class="rowElem noborder" style="background:transparent">
 
 <div class="mod_li_dv_2">
	<ul class="mod_le_drag_ul" id="list2">
	{if $smarty.request.id == 1}
	{php}
	require(BASE_DIR . "/admin/templates/liveeditor/f_config/li_available_mf.tpl");
	{/php}
	{elseif $smarty.request.id == 2}
	{php}
	require(BASE_DIR . "/admin/templates/liveeditor/f_config/li_available_smf.tpl");
	{/php}
	{elseif $smarty.request.id == 3}
	{php}
	require(BASE_DIR . "/admin/templates/liveeditor/f_config/li_available_sys.tpl");
	{/php}
	{/if}
	</ul>
  </div>
  
<div class="mod_li_dv_1">
	<ul class="mod_le_drag_ul" id="list1">
	{if $smarty.request.id == 1}
	{php}
	require(BASE_DIR . "/admin/templates/liveeditor/f_config/li_new_mf.tpl");
	{/php}
	{elseif $smarty.request.id == 2}
	{php}
	require(BASE_DIR . "/admin/templates/liveeditor/f_config/li_new_smf.tpl");
	{/php}
	{elseif $smarty.request.id == 3}
	{php}
	require(BASE_DIR . "/admin/templates/liveeditor/f_config/li_new_sys.tpl");
	{/php}
	{/if}
	</ul>
</div>

	<input name="liveeditor_available_toolbar" id ="liveeditor_available_toolbar{$smarty.request.id}" type="hidden" value="" />
	<input name="liveeditor_new_toolbar" id="liveeditor_new_toolbar{$smarty.request.id}" type="hidden" value="" />
	<input name="liveeditor_real_toolbar" id="liveeditor_real_toolbar{$smarty.request.id}" type="hidden"  value=""  />
	<p>&nbsp;</p>
	<div class="fix"></div>
  </div>
</div>
</div>

<div class="widget first">
<div class="head collapsiblsys" id="opened"><h5 class="iFrames"><span class="topDir mod_le_col" title="{#LIVEEDITOR_OPEN_TL_SYS#}">{#LIVEEDITOR_OPEN_SYS#}</span></h5></div>

<table style=" border:1px #DCE7EF solid; width:100%;" class="tableStatic">
	<tbody onclick="saveOrder()">
		<col width="500">
		<col>
		<tr>
			<td width="50%"><h5 class="li_system_h5">{#LIVEEDITOR_SYS_INS_TAG#}</h5>
			<span class="li_system_set">{#LIVEEDITOR_SYS_INS_DESC#}</span>
				</td>
			<td><div class="pr12">
			<input type="radio" name="li_insert_tag" value="0" {if $f_sys_settings[0]==0} checked{/if} class="li_insert_tag" /><label style="cursor: pointer;"><span class="li_system_span">Unknown</span></label>
			<input type="radio" name="li_insert_tag" value="1" {if $f_sys_settings[0]==1} checked{/if} class="li_insert_tag" /><label style="cursor: pointer;"><span class="li_system_span">&lt; DIV &gt;</span></label>
			<input type="radio" name="li_insert_tag" value="2" {if $f_sys_settings[0]==2} checked{/if} class="li_insert_tag" /><label style="cursor: pointer;"><span class="li_system_span">&lt; P &gt;</span></label>
			<input type="radio" name="li_insert_tag" value="3" {if $f_sys_settings[0]==3} checked{/if} class="li_insert_tag" /><label style="cursor: pointer;"><span class="li_system_span">&lt; BR &gt;</span></label>
		  </div>
				</td>
		</tr>
		<tr>
			<td><h5 class="li_system_h5">{#LIVEEDITOR_SYS_CLEAR_TXT#}</h5>
			<span class="li_system_set">{#LIVEEDITOR_SYS_CLEAR_DESC#}</span>
				</td>
			<td><div class="pr12">
			<input type="radio" name="li_clear_text" value="1" {if $f_sys_settings[1]==1} checked{/if} class="li_clear_text" /><label style="cursor: pointer;"><span class="li_system_span">{#LIVEEDITOR_SYS_YES#}</span></label>
			<input type="radio" name="li_clear_text" value="0" {if $f_sys_settings[1]==0} checked{/if} class="li_clear_text" /><label style="cursor: pointer;"><span class="li_system_span">{#LIVEEDITOR_SYS_NO#}</span></label>
		  </div>
				</td>
		</tr>
		<tr>
			<td><h5 class="li_system_h5">{#LIVEEDITOR_SYS_LOAD_FLC#}</h5>
				</td>
			<td><div class="pr12">
			<input type="radio" name="li_load_flickr" value="1" {if $f_sys_settings[2]==1} checked{/if} class="li_load_flickr" /><label style="cursor: pointer;"><span class="li_system_span">{#LIVEEDITOR_SYS_YES#}</span></label>
			<input type="radio" name="li_load_flickr" value="0" {if $f_sys_settings[2]==0} checked{/if} class="li_load_flickr" /><label style="cursor: pointer;"><span class="li_system_span">{#LIVEEDITOR_SYS_NO#}</span></label>
		  </div>
				</td>
		</tr>
		<tr>
			<td><h5 class="li_system_h5">{#LIVEEDITOR_SYS_INF#}</h5>
			 <span class="li_system_set">{#LIVEEDITOR_SYS_INF_DESC#}</span>
				</td>
			<td><div style="width:360px;" class="pr12"><input style="width: 238px; margin-right:4px;" name="li_input_name_flickr" type="text" value={$f_sys_settings[3]} class="li_input_name_flickr" /><input class="basicBtn" type="button" value="{#LIVEEDITOR_SYS_CONFIRM#}" onclick="$.jGrowl('{#LIVEEDITOR_SYS_CONFIRM_FU#}')" /></div>
				</td>
		</tr>
		<tr>
			<td><h5 class="li_system_h5">{#LIVEEDITOR_SYS_USE_CSSBTN#}</h5>
				</td>
			<td><div class="pr12">
			<input type="radio" name="li_use_css_btn" value="1" {if $f_sys_settings[4]==1} checked{/if} class="li_use_css_btn" /><label style="cursor: pointer;"><span class="li_system_span">{#LIVEEDITOR_SYS_YES#}</span></label>
			<input type="radio" name="li_use_css_btn" value="0" {if $f_sys_settings[4]==0} checked{/if} class="li_use_css_btn" /><label style="cursor: pointer;"><span class="li_system_span">{#LIVEEDITOR_SYS_NO#}</span></label>
		  </div>
				</td>
		</tr>
		<tr>
			<td><h5 class="li_system_h5">{#LIVEEDITOR_SYS_AUTOFTABLE#}</h5>
				</td>
			<td><div class="pr12">
			<input type="radio" name="li_use_autoformat_table" value="1" {if $f_sys_settings[5]==1} checked{/if} class="li_use_autoformat_table" /><label style="cursor: pointer;"><span class="li_system_span">{#LIVEEDITOR_SYS_YES#}</span></label>
			<input type="radio" name="li_use_autoformat_table" value="0" {if $f_sys_settings[5]==0} checked{/if} class="li_use_autoformat_table" /><label style="cursor: pointer;"><span class="li_system_span">{#LIVEEDITOR_SYS_NO#}</span></label>
		  </div>
				</td>
		</tr>
		<tr>
			<td><h5 class="li_system_h5">{#LIVEEDITOR_SYS_USECSSPREF#}</h5>
			<div class="toprightDir li_css_help" onclick="modalDialog('/admin/templates/liveeditor/lesson/help_rule_css.html', 930,440)" title="{#LIVEEDITOR_SYS_USECSSPREF_T#}"></div><span class="li_system_set">{#LIVEEDITOR_SYS_USECSSPREF_K#}</span>
				</td>
			<td><div style="width:360px;" class="pr12">
			<input style="width: 238px; margin-right:4px;" type="text" name="li_input_name_cssprefix" value={$f_sys_settings[6]} class="li_input_name_cssprefix" /><input class="basicBtn" type="button" value="{#LIVEEDITOR_SYS_CONFIRM#}" onclick="$.jGrowl('{#LIVEEDITOR_SYS_CONFIRM_SR#}')" /></div>
				</td>
		</tr>
		<tr>
			<td><h5 class="li_system_h5">{#LIVEEDITOR_SYS_USE_FOCUS#}</h5>
			<span class="li_system_set">{#LIVEEDITOR_SYS_USE_FOCUS_DESC#}</span>
				</td>
			<td><div class="pr12">
			<input type="radio" name="li_use_focus" value="0" {if $f_sys_settings[7]==0} checked{/if} class="li_use_focus" /><label style="cursor: pointer;"><span class="li_system_span">{#LIVEEDITOR_SYS_NO#}</span></label>
			<input type="radio" name="li_use_focus" value="1" {if $f_sys_settings[7]==1} checked{/if} class="li_use_focus" /><label style="cursor: pointer;"><span class="li_system_span">{#LIVEEDITOR_SYS_YES#}</span></label>
		  </div>
				</td>
		</tr>

	</tbody>
</table>

</div>


<script type="text/javascript">
			var p_insert_tag = {$f_sys_settings[0]};
			var p_clear_text_d = {$f_sys_settings[1]};
   {literal}if (p_clear_text_d == 1){p_clear_text = "true"} else {p_clear_text = "false"};{/literal}
			var p_load_flickr_d = {$f_sys_settings[2]};
   {literal}if (p_load_flickr_d == 1){p_load_flickr = "true"} else {p_load_flickr = "false"};{/literal}		
		   var p_input_name_flickr = {$f_sys_settings[3]};
   {literal}if (p_input_name_flickr == ""){p_input_name_flickr = '""'} else {p_input_name_flickr = '"'+ p_input_name_flickr +'"'};{/literal}
			var p_use_css_btn_d = {$f_sys_settings[4]};
   {literal}if (p_use_css_btn_d == 1){p_use_css_btn = "true"} else {p_use_css_btn = "false"};{/literal}	
			var p_use_autoformat_table_d = {$f_sys_settings[5]};
   {literal}if (p_use_autoformat_table_d == 1){p_use_autoformat_table = "true"} else {p_use_autoformat_table = "false"};{/literal}		
			var p_input_name_cssprefix = {$f_sys_settings[6]};
   {literal}if (p_input_name_cssprefix == ""){p_input_name_cssprefix = '""'} else {p_input_name_cssprefix = '"'+ p_input_name_cssprefix +'"'};{/literal}				
			var p_use_focus_d = {$f_sys_settings[7]};
   {literal}if (p_use_focus_d == 1){p_use_focus = "false"} else {p_use_focus = "true"};{/literal}		
			{literal}


		// получаем данные какие инпуты были выбраны на открытой странице и присваиваем значения value переменным
			$('.li_insert_tag').on('click', function() {p_insert_tag = $(this).attr("value"); });
			$('.li_clear_text').on('click', function() {p_clear_text_d = $(this).attr("value"); if (p_clear_text_d == 1){p_clear_text = "true"} else {p_clear_text = "false"}; });
			$('.li_load_flickr').on('click', function() {p_load_flickr_d = $(this).attr("value"); if (p_load_flickr_d == 1){p_load_flickr = "true"} else {p_load_flickr = "false"}; });
			$('.li_input_name_flickr').on('blur', function() {p_input_name_flickr = '"' + $(this).attr("value") + '"'; });
			$('.li_use_css_btn').on('click', function() {p_use_css_btn_d = $(this).attr("value");  if (p_use_css_btn_d == 1){p_use_css_btn = "true"} else {p_use_css_btn = "false"}; });
			$('.li_use_autoformat_table').on('click', function() {p_use_autoformat_table_d = $(this).attr("value"); if (p_use_autoformat_table_d == 1){p_use_autoformat_table = "true"} else {p_use_autoformat_table = "false"}; });
			$('.li_input_name_cssprefix').on('blur', function() {p_input_name_cssprefix = '"' + $(this).attr("value") + '"'; });
			$('.li_use_focus').on('click', function() {p_use_focus_d = $(this).attr("value"); if (p_use_focus_d == 1){p_use_focus = "false"} else {p_use_focus = "true"}; });
</script>
{/literal}




{literal}
<script type="text/javascript">
		  $('.collapsibltool').collapsible({
			defaultOpen: 'opened',
			cssOpen: 'inactive',
			cssClose: 'normal',
			cookieName: 'collapstool_liv',
			cookieOptions: {
				expires: 7,
				domain: ''
			},
			speed: 200
		}); 
		$('.collapsiblsys').collapsible({
			defaultOpen: 'opened',
			cssOpen: 'inactive',
			cssClose: 'normal',
			cookieName: 'collapsys_liv',
			cookieOptions: {
				expires: 7,
				domain: ''
			},
			speed: 200
		}); 
</script>
{/literal}



<script type="text/javascript">
// назначаем переменные из файла (значения верны только после сохранения, а затем обновления страницы!!!)
			var mod_li_id1 = {$id};
			var p_insert_tag1 = {$f_sys_settings[0]};
			var p_clear_text_d1 = {$f_sys_settings[1]};
   {literal}if (p_clear_text_d1 == 1){p_clear_text1 = "true"} else {p_clear_text1 = "false"};{/literal}
			var p_load_flickr_d1 = {$f_sys_settings[2]};
   {literal}if (p_load_flickr_d1 == 1){p_load_flickr1 = "true"} else {p_load_flickr1 = "false"};{/literal}		
			var p_input_name_flickr1 = {$f_sys_settings[3]};
   {literal}if (p_input_name_flickr1 == ""){p_input_name_flickr1 = '""'} else {p_input_name_flickr1 = '"'+ p_input_name_flickr1 +'"'};{/literal}		
			var p_use_css_btn_d1 = {$f_sys_settings[4]};
   {literal}if (p_use_css_btn_d1 == 1){p_use_css_btn1 = "true"} else {p_use_css_btn1 = "false"};{/literal}	
			var p_use_autoformat_table_d1 = {$f_sys_settings[5]};
   {literal}if (p_use_autoformat_table_d1 == 1){p_use_autoformat_table1 = "true"} else {p_use_autoformat_table1 = "false"};{/literal}		
			var p_input_name_cssprefix1 = {$f_sys_settings[6]};
   {literal}if (p_input_name_cssprefix1 == ""){p_input_name_cssprefix1 = '""'} else {p_input_name_cssprefix1 = '"'+ p_input_name_cssprefix1 +'"'};{/literal}	    
			var p_use_focus_d1 = {$f_sys_settings[7]};
   {literal}if (p_use_focus_d1 == 1){p_use_focus1 = "false"} else {p_use_focus1 = "true"};{/literal}		
			{literal}

//alert(tmp_sys_clear_text_dig); 



		  $(".mod_li_reset").click(function(){
		  if (mod_li_id1 == 1){
				$("#list2").load("{/literal}{$ABS_PATH}{literal}admin/templates/liveeditor/f_config/reset/li_res_available_mf.tpl");
				$("#list1").load("{/literal}{$ABS_PATH}{literal}admin/templates/liveeditor/f_config/reset/li_res_new_mf.tpl");
		   }
		   else if (mod_li_id1 == 2) {
				$("#list2").load("{/literal}{$ABS_PATH}{literal}admin/templates/liveeditor/f_config/reset/li_res_available_smf.tpl");
				$("#list1").load("{/literal}{$ABS_PATH}{literal}admin/templates/liveeditor/f_config/reset/li_res_new_smf.tpl");
		   }
			else if (mod_li_id1 == 3) {
				$("#list2").load("{/literal}{$ABS_PATH}{literal}admin/templates/liveeditor/f_config/reset/li_res_available_sys.tpl");
				$("#list1").load("{/literal}{$ABS_PATH}{literal}admin/templates/liveeditor/f_config/reset/li_res_new_sys.tpl");
		   }
		});	

var name_input_available_toolbar_first_open = document.getElementById({/literal}'liveeditor_available_toolbar{$smarty.request.id}{literal}')
	name_input_available_toolbar_first_open.value = document.getElementById('list2').innerHTML;
var name_input_new_toolbar_first_open = document.getElementById({/literal}'liveeditor_new_toolbar{$smarty.request.id}{literal}')
	name_input_new_toolbar_first_open.value = document.getElementById('list1').innerHTML;

	var datas = $("#list1 li").map(function() { return $(this).children().attr('class'); }).get();
	var tmp_toolbar_real01 = 'var oEdit\' . $field_id . \' = new InnovaEditor("oEdit\' . $field_id . \'");';
			var tmp_toolbar_real11 = 'oEdit\' . $field_id . \'.css = ["/admin/liveeditor/LiveEditor/styles/default.css","/admin/liveeditor/AddOns/bootstrap/css/bridge.css"]; ';
			var tmp_bootstrap1_sys = 'oEdit\' . $field_id . \'.arrCustomButtons = ' + '[["Snippets", "modalDialog(' + '\\' + '\'/admin/liveeditor/AddOns/bootstrap/snippets.htm' + '\\' + '\'' + ',900,658,' + '\\' + '\'Insert Snippets' + '\\' + '\'' + ');", "Bootstrap", "btnContentBlock.gif"]];' ;
			
			var sys_insert_tag1 = 'oEdit\' . $field_id . \'.returnKeyMode = ' + p_insert_tag1 + ';';
			var sys_clear_text1 = 'oEdit\' . $field_id . \'.pasteTextOnCtrlV = ' + p_clear_text1 + ';';
			var sys_load_flickr1 = 'oEdit\' . $field_id . \'.enableFlickr = ' + p_load_flickr1 + ';';
			var sys_input_name_flickr1 = 'oEdit\' . $field_id . \'.flickrUser = ' + p_input_name_flickr1 + ';';
			var sys_use_css_btn1 = 'oEdit\' . $field_id . \'.enableCssButtons = ' + p_use_css_btn1 + ';';
			var sys_use_autoformat_table1 = 'oEdit\' . $field_id . \'.enableTableAutoformat = ' + p_use_autoformat_table1 + ';';
			var sys_input_name_cssprefix1 = 'oEdit\' . $field_id . \'.styleSelectorPrefix = ' + p_input_name_cssprefix1 + ';';
			var sys_use_focus1 = 'oEdit\' . $field_id . \'.disableFocusOnLoad = ' + p_use_focus1 + ';';

			var tmp_toolbar_real21 = 'oEdit\' . $field_id . \'.fileBrowser = "/admin/liveeditor/AddOns/assetmanager/asset.php";';

			if(mod_li_id1 == 1){
				var tmp_toolbar_real_start1 = '\<?php\r\n $large = \'';
				var tmp_toolbar_real31 = 'oEdit\' . $field_id . \'.width = "100%";';
				var tmp_toolbar_real41 = 'oEdit\' . $field_id . \'.height = "450px";';
				var tmp_toolbar_real71 = 'oEdit\' . $field_id . \'.REPLACE("editor[\' . $field_id . \']");';
				var tmp_toolbar_real_finish1 = '\';\r\n $innova = array (1 =>"$large");\r\n ?>';
			}
			else if (mod_li_id1 == 2) {
				var tmp_toolbar_real_start1 = '<?php\r\n $small = \'';
				var tmp_toolbar_real31 = 'oEdit\' . $field_id . \'.width = "100%";';
				var tmp_toolbar_real41 = 'oEdit\' . $field_id . \'.height = "300px";';
				var tmp_toolbar_real71 = 'oEdit\' . $field_id . \'.REPLACE("small-editor[\' . $field_id . \']");';
				var tmp_toolbar_real_finish1 = '\';\r\n $innova = array (2 =>"$small");\r\n ?>';
			}
			var tmp_toolbar_real51 = 'oEdit\' . $field_id . \'.groups = [[\"group1\", \"\", [\"'
			var tmp_toolbar_real61 = '\"]]];';

			if (mod_li_id1 == 3) {
			var tmp_toolbar_real_start1 = '<?php\r\n $large = \'';
			var tmp_toolbar_real01 = 'var oEdit = new InnovaEditor("oEdit");';
			var tmp_toolbar_real11 = 'oEdit.css = ["/admin/liveeditor/LiveEditor/styles/default.css","/admin/liveeditor/AddOns/bootstrap/css/bridge.css"]; ';
			var tmp_bootstrap1_sys = 'oEdit.arrCustomButtons = ' + '[["Snippets", "modalDialog(' + '\\' + '\'/admin/liveeditor/AddOns/bootstrap/snippets.htm' + '\\' + '\'' + ',900,658,' + '\\' + '\'Insert Snippets' + '\\' + '\'' + ');", "Bootstrap", "btnContentBlock.gif"]];' ;

			var sys_insert_tag1 = 'oEdit.returnKeyMode = ' + p_insert_tag1 + ';';
			var sys_clear_text1 = 'oEdit.pasteTextOnCtrlV = ' + p_clear_text1 + ';';
			var sys_load_flickr1 = 'oEdit.enableFlickr = ' + p_load_flickr1 + ';';
			var sys_input_name_flickr1 = 'oEdit.flickrUser = ' + p_input_name_flickr1 + ';';
			var sys_use_css_btn1 = 'oEdit.enableCssButtons = ' + p_use_css_btn1 + ';';
			var sys_use_autoformat_table1 = 'oEdit.enableTableAutoformat = ' + p_use_autoformat_table1 + ';';
			var sys_input_name_cssprefix1 = 'oEdit.styleSelectorPrefix = ' + p_input_name_cssprefix1 + ';';
			var sys_use_focus1 = 'oEdit.disableFocusOnLoad = ' + p_use_focus1 + ';';
			var tmp_toolbar_real21 = 'oEdit.fileBrowser = "/admin/liveeditor/AddOns/assetmanager/asset.php";';
			var tmp_toolbar_real31 = 'oEdit.width = "100%";';
			var tmp_toolbar_real41 = 'oEdit.height = "450px";';
			var tmp_toolbar_real51 = 'oEdit.groups = [[\"group1\", \"\", [\"'
			var tmp_toolbar_real61 = '\"]]];';
			var tmp_toolbar_real71 = 'oEdit.REPLACE("sysblock_text");';
			var tmp_toolbar_real_finish1 = '\';\r\n $innova = array (1 =>"$large");\r\n ?>';
			}

			var pos_form_real_first_open = tmp_toolbar_real_start1 + '\r\n<sc' + 'ript type="text/javascript">\r\n' + tmp_toolbar_real01 + '\r\n' + tmp_toolbar_real11 + '\r\n' + tmp_bootstrap1_sys + '\r\n' + sys_insert_tag1 + '\r\n' + sys_clear_text1 + '\r\n' + sys_load_flickr1 + '\r\n' + sys_input_name_flickr1 + '\r\n' + sys_use_css_btn1 + '\r\n' + sys_use_autoformat_table1 + '\r\n' + sys_input_name_cssprefix1 + '\r\n' + sys_use_focus1 + '\r\n' + tmp_toolbar_real21 + '\r\n' + tmp_toolbar_real31 + '\r\n' + tmp_toolbar_real41 + '\r\n' + tmp_toolbar_real51 + datas.join("\", \"") + tmp_toolbar_real61 + '\r\n' + tmp_toolbar_real71 + '\r\n' + '</sc' + 'ript>\r\n' + tmp_toolbar_real_finish1;

			var name_input_first_open = document.getElementById({/literal}'liveeditor_real_toolbar{$smarty.request.id}'{literal})
			name_input_first_open.value = pos_form_real_first_open;
	
</script>
{/literal}



	<script type="text/javascript">
	var mod_li_id = {$id};
	
	{literal}
		$("#list1, #list2").dragsort({ dragSelector: "div", dragBetween: true, dragEnd: saveOrder, placeHolderTemplate: "<li class='placeHolder'><div></div></li>" });
			function saveOrder() {
			var data = $("#list1 li").map(function() { return $(this).children().attr('class'); }).get();
			var li_new = $("#list1 li").map(function() { return '<li title="' + $(this).attr('title') + '"><div class="' + $(this).children().attr('class') + '"></div></li>'; }).get();
			var li_available = $("#list2 li").map(function() { return '<li title="' + $(this).attr('title') + '"><div class="' + $(this).children().attr('class') + '"></div></li>'; }).get();
			var tmp_toolbar_real0 = 'var oEdit\' . $field_id . \' = new InnovaEditor("oEdit\' . $field_id . \'");';
			var tmp_toolbar_real1 = 'oEdit\' . $field_id . \'.css = ["/admin/liveeditor/LiveEditor/styles/default.css","/admin/liveeditor/AddOns/bootstrap/css/bridge.css"]; ';
			var tmp_bootstrap_sys = 'oEdit\' . $field_id . \'.arrCustomButtons = ' + '[["Snippets", "modalDialog(' + '\\' + '\'/admin/liveeditor/AddOns/bootstrap/snippets.htm' + '\\' + '\'' + ',900,658,' + '\\' + '\'Insert Snippets' + '\\' + '\'' + ');", "Bootstrap", "btnContentBlock.gif"]];' ;
			
			var sys_insert_tag = 'oEdit\' . $field_id . \'.returnKeyMode = ' + p_insert_tag + ';';
			var sys_clear_text = 'oEdit\' . $field_id . \'.pasteTextOnCtrlV = ' + p_clear_text + ';';
			var sys_load_flickr = 'oEdit\' . $field_id . \'.enableFlickr = ' + p_load_flickr + ';';
			var sys_input_name_flickr = 'oEdit\' . $field_id . \'.flickrUser = ' + p_input_name_flickr + ';';
			var sys_use_css_btn = 'oEdit\' . $field_id . \'.enableCssButtons = ' + p_use_css_btn + ';';
			var sys_use_autoformat_table = 'oEdit\' . $field_id . \'.enableTableAutoformat = ' + p_use_autoformat_table + ';';
			var sys_input_name_cssprefix = 'oEdit\' . $field_id . \'.styleSelectorPrefix = ' + p_input_name_cssprefix + ';';
			var sys_use_focus = 'oEdit\' . $field_id . \'.disableFocusOnLoad = ' + p_use_focus + ';';
			
			var tmp_toolbar_real2 = 'oEdit\' . $field_id . \'.fileBrowser = "/admin/liveeditor/AddOns/assetmanager/asset.php";';
			if(mod_li_id == 1){
				var tmp_toolbar_real_start = '\<?php\r\n $large = \'';
				var tmp_toolbar_real3 = 'oEdit\' . $field_id . \'.width = "100%";';
				var tmp_toolbar_real4 = 'oEdit\' . $field_id . \'.height = "450px";';
				var tmp_toolbar_real7 = 'oEdit\' . $field_id . \'.REPLACE("editor[\' . $field_id . \']");';
				var tmp_toolbar_real_finish = '\';\r\n $innova = array (1 =>"$large");\r\n ?>';
			}
			else if (mod_li_id1 == 2) {
				var tmp_toolbar_real_start = '<?php\r\n $small = \'';
				var tmp_toolbar_real3 = 'oEdit\' . $field_id . \'.width = "100%";';
				var tmp_toolbar_real4 = 'oEdit\' . $field_id . \'.height = "300px";';
				var tmp_toolbar_real7 = 'oEdit\' . $field_id . \'.REPLACE("small-editor[\' . $field_id . \']");';
				var tmp_toolbar_real_finish = '\';\r\n $innova = array (2 =>"$small");\r\n ?>';
			}
			var tmp_toolbar_real5 = 'oEdit\' . $field_id . \'.groups = [[\"group1\", \"\", [\"'
			var tmp_toolbar_real6 = '\"]]];';
				if (mod_li_id1 == 3) {
				var tmp_toolbar_real_start = '<?php\r\n $large = \'';
				var tmp_toolbar_real0 = 'var oEdit = new InnovaEditor("oEdit");';
				var tmp_toolbar_real1 = 'oEdit.css = ["/admin/liveeditor/LiveEditor/styles/default.css","/admin/liveeditor/AddOns/bootstrap/css/bridge.css"]; ';
				var tmp_bootstrap_sys = 'oEdit.arrCustomButtons = ' + '[["Snippets", "modalDialog(' + '\\' + '\'/admin/liveeditor/AddOns/bootstrap/snippets.htm' + '\\' + '\'' + ',900,658,' + '\\' + '\'Insert Snippets' + '\\' + '\'' + ');", "Bootstrap", "btnContentBlock.gif"]];' ;

				var sys_insert_tag = 'oEdit.returnKeyMode = ' + p_insert_tag + ';';
				var sys_clear_text = 'oEdit.pasteTextOnCtrlV = ' + p_clear_text + ';';
				var sys_load_flickr = 'oEdit.enableFlickr = ' + p_load_flickr + ';';
				var sys_input_name_flickr = 'oEdit.flickrUser = ' + p_input_name_flickr + ';';
				var sys_use_css_btn = 'oEdit.enableCssButtons = ' + p_use_css_btn + ';';
				var sys_use_autoformat_table = 'oEdit.enableTableAutoformat = ' + p_use_autoformat_table + ';';
				var sys_input_name_cssprefix = 'oEdit.styleSelectorPrefix = ' + p_input_name_cssprefix + ';';
				var sys_use_focus = 'oEdit.disableFocusOnLoad = ' + p_use_focus + ';';
				var tmp_toolbar_real2 = 'oEdit.fileBrowser = "/admin/liveeditor/AddOns/assetmanager/asset.php";';
				var tmp_toolbar_real3 = 'oEdit.width = "100%";';
				var tmp_toolbar_real4 = 'oEdit.height = "450px";';
				var tmp_toolbar_real5 = 'oEdit.groups = [[\"group1\", \"\", [\"'
				var tmp_toolbar_real6 = '\"]]];';
				var tmp_toolbar_real7 = 'oEdit.REPLACE("sysblock_text");';
				var tmp_toolbar_real_finish = '\';\r\n $innova = array (1 =>"$large");\r\n ?>';
			}

			var pos_form_real = tmp_toolbar_real_start + '\r\n<sc' + 'ript type="text/javascript">\r\n' + tmp_toolbar_real0 + '\r\n' + tmp_toolbar_real1 + '\r\n' + tmp_bootstrap_sys + '\r\n' + sys_insert_tag + '\r\n' + sys_clear_text + '\r\n' + sys_load_flickr + '\r\n' + sys_input_name_flickr + '\r\n' + sys_use_css_btn + '\r\n' + sys_use_autoformat_table + '\r\n' + sys_input_name_cssprefix + '\r\n' + sys_use_focus + '\r\n' + tmp_toolbar_real2 + '\r\n' + tmp_toolbar_real3 + '\r\n' + tmp_toolbar_real4 + '\r\n' + tmp_toolbar_real5 + data.join("\", \"") + tmp_toolbar_real6 + '\r\n' + tmp_toolbar_real7 + '\r\n' + '</sc' + 'ript>\r\n' + tmp_toolbar_real_finish;

			var name_input = document.getElementById({/literal}'liveeditor_real_toolbar{$smarty.request.id}'{literal})
			name_input.value=pos_form_real;

			var tmp_toolbar0 = 'var oEdit777 = new InnovaEditor("oEdit777");\r\n';
			var tmp_toolbar1 = 'oEdit777.css = "/admin/liveeditor/LiveEditor/styles/default.css";\r\n';
			var tmp_toolbar2 = 'oEdit777.fileBrowser = "/admin/liveeditor/AddOns/assetmanager/asset.php";\r\n';
			var tmp_toolbar3 = 'oEdit777.width="100%";\r\n';
			var tmp_toolbar4 = 'oEdit777.height="200";\r\n';
			var tmp_gr_start = 'oEdit777.groups = [[\"group1\", \"\", [\"';
			var tmp_gr_end = '\"]]];\r\n';
			var tmp_toolbar5 = 'oEdit777.REPLACE("onlineContent");\r\n';
			var pos_form_lived = '<sc' + 'ript>' + tmp_toolbar0 + tmp_toolbar1 + tmp_toolbar2 + tmp_toolbar3 + tmp_toolbar4 + tmp_gr_start + data.join("\", \"") + tmp_gr_end + tmp_toolbar5 + '</sc' + 'ript>';
			$.cookie("live_make_toolbar", pos_form_lived, {expires: 1, path: '/', domain: ''});
			
			var name_input_new_toolbar = document.getElementById({/literal}'liveeditor_new_toolbar{$smarty.request.id}'{literal})
			name_input_new_toolbar.value = li_new.join(" ");
			
			var name_input_available_toolbar = document.getElementById({/literal}'liveeditor_available_toolbar{$smarty.request.id}'{literal})
			name_input_available_toolbar.value = li_available.join(" ");

			li_check = $("#btn_mod_li_real").attr("checked");
			if(li_check == 'checked')
			{
			modalDialog('/admin/templates/liveeditor/templates/toolbar-on-line.html', 900,82);
			}
		};
	</script>
 {/literal}
 
 
 <div class="widget first">
 <div class="head"><h5 class="iFrames">{#LIVEEDITOR_EDIT_END#}</h5></div>

	<div class="rowElem" id="saveBtn">
		<div class="saveBtn">
		{if $smarty.request.id != ''}
			<input type="hidden" name="id" value="{$id}">
			<input name="submit" type="submit" class="basicBtn" value="{#LIVEEDITOR_SAVEDIT#}" />
		{else}
			<input name="submit" type="submit" class="basicBtn" value="{#LIVEEDITOR_SAVE#}" />
		{/if}

		&nbsp;или&nbsp;

		{if $smarty.request.action=='edit'}
			<input type="submit" class="blackBtn SaveEdit" name="next_edit" value="{#LIVEEDITOR_SAVEDIT_NEXT#}" />
		{else}
			<input type="submit" class="blackBtn SaveEdit" name="next_edit" value="{#LIVEEDITOR_SAVE_NEXT#}" />
		{/if}
		</div>
	</div>

</div>

</form>
<script language="javascript">

	var sett_options = {ldelim}
		url: 'index.php?do=liveeditor&action=save&cp={$sess}',
		beforeSubmit: Request,
		success: Response
	{rdelim}

	function Request(){ldelim}
		$.alerts._overlay('show');
	{rdelim}

	function Response(){ldelim}
		$.alerts._overlay('hide');
		$.jGrowl('{#LIVEEDITOR_SAVED#}',{ldelim} 
			header: '{#LIVEEDITOR_SUCCESS#}',
			theme: 'accept'
		{rdelim});
	{rdelim}

	$(document).ready(function(){ldelim}

		Mousetrap.bind(['ctrl+s', 'command+s'], function(e) {ldelim}
			if (e.preventDefault) {ldelim}
				e.preventDefault();
			{rdelim} else {ldelim}
				// internet explorer
				e.returnValue = false;
			{rdelim}
			$("#liveeditor").ajaxSubmit(sett_options);
			return false;
		{rdelim});

		$(".SaveEdit").click(function(e){ldelim}
			if (e.preventDefault) {ldelim}
				e.preventDefault();
			{rdelim} else {ldelim}
				// internet explorer
				e.returnValue = false;
			{rdelim}
			$("#liveeditor").ajaxSubmit(sett_options);
			return false;
		{rdelim});

	{rdelim});

</script>

