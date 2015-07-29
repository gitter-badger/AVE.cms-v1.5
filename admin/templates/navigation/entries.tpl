<script language="javascript" type="text/javascript">

{literal}
	var fixHelper = function(e, ui) {
		ui.children('.maintr').children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	};
{/literal}

$(document).ready(function(){ldelim}
	$('.opentr').click(function () {ldelim}
		var $id =  $(this).attr('rel');
		$("#td_"+$id ).toggle();
	{rdelim});

	$('textarea.expand').focusin(function () {ldelim}
	    $(this).animate({ldelim} height: "4em" {rdelim}, 250);
	{rdelim});

	$('textarea.expand').focusout(function () {ldelim}
	    $(this).animate({ldelim} height: "14px" {rdelim}, 250);
	{rdelim});
 	$('input[type="text"]').mousedown(function(e){ldelim}e.stopPropagation();{rdelim});
 	$('textarea').mousedown(function(e){ldelim}e.stopPropagation();{rdelim})	

	// сортировка
	$('#sort').tableSortable({ldelim}
		items: 'tbody',
		key: 'item',
		url: 'index.php?do=navigation&action=entries&cp={$sess}&id={$smarty.request.id|escape}&save=pos',
		success: true
	{rdelim});

{rdelim});
</script>


{literal}
<style>

.ui-state-highlight {height: 30px; background: #c0c0c0;}
</style>
{/literal}

<div class="title"><h5>{#NAVI_SUB_TITLE2#}</h5></div>


<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
			<li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li><a href="index.php?do=navigation&cp={$sess}" title="">{#NAVI_SUB_TITLE#}</a></li>
	        <li>{#NAVI_SUB_TITLE2#}</li>
	        <li><strong class="code">{$NavigatonName|escape|stripslashes}</strong></li>
	    </ul>
	</div>
</div>

<form name="navquicksave" method="post" action="index.php?do=navigation&action=quicksave&id={$smarty.request.id|escape}&cp={$sess}" class="mainForm">
<fieldset>
<div class="widget first">
<div class="head"><h5 class="iFrames">{#NAVI_ITEMS_TIP#}</h5></div>
	<div id="AjaxResult"></div>
	<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<thead>
		<tr class="noborder">
			<td>{#NAVI_LINK_TITLE#}</td>
			<td>{#NAVI_LINK_TO_DOCUMENT#}</td>
			<td width="50" rowspan="2">&nbsp;</td>
			<td width="100">{#NAVI_POSITION#}</td>
			<td width="150">{#NAVI_TARGET_WINDOW#}</td>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td><div class="pr12"><input name="Titel_N[]" type="text" id="Titel_N" value="" /></div></td>
			<td><div class="pr12"><input name="Link_N[]" type="text" id="Link_N" value="" /></div></td>
			<td align="center">
				<input title="{#NAVI_BROWSE_DOCUMENTS#}" onclick="openLinkWindow('Link_N','Titel_N','Url_N');" type="button" class="basicBtn topDir" value="..." />
				<input title="{#NAVI_BROWSE_MEDIAPOOL#}" onclick="openFileWindow('Link_N','Link_N','Url_N');" type="button" class="basicBtn topDir" value="#" />
			</td>
			<td><div class="pr12"><input name="Rang_N[]" type="text" id="Rang_N" value="10" size="4" maxlength="3" /></div></td>
			<td nowrap="nowrap">
				<select name="Target_N[]" id="Target_N" style="width: 140px;">
					<option value="_self" selected="selected">{#NAVI_OPEN_IN_THIS#}</option>
					<option value="_blank">{#NAVI_OPEN_IN_NEW#}</option>
				</select>
			</td>
		</tr>
		</tbody>
		<thead>
		<tr class="noborder">
			<td>{#NAVI_LINK_SOLUT#}</td>
			<td width="">{#NAVI_LINK_IMAGE#}</td>
			<td width="50">{#NAVI_BUTTON_CHANGE#}</td>
			<td width="100">{#NAVI_LINK_IMGID#}</td>
			<td width="60"></td>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td><div class="pr12"><textarea class="expand" rows="1" cols="10" name="descr_N[]"></textarea></div></td>	
			<td><div class="pr12"><input name="Img_N[]" type="text" id="Img_N" value="" /></div></td>
			<td><input value="{#NAVI_BUTTON_CHANGE#}" title="{#NAVI_LINK_IMGTL#}" class="basicBtn topDir" onclick="openFileWindow('Img_N','Img_N');" type="button"></td>
			<td><input style="width:85%" name="Img_id_N[]" type="text" id="Img_id_N" value="" /></td>
			<td><input type="hidden" name="Url_N[]" id="Url_N" value="" /><input type="submit" class="blackBtn" value="{#NAVI_BUTTON_ADD#}" /></td>
		</tr>
		</tbody>
	</table>
	<div class="fix"></div>
</div>

<div class="widget first">
<div class="head"><h5 class="iFrames">{#NAVI_LIST#}</h5><div class="num"><a class="basicNum" href="index.php?do=navigation&action=templates&cp={$sess}&id={$smarty.request.id}">{#NAVI_EDIT_TEMPLATE#}</a></div></div>
<div class="body">{#NAVI_LIST_TIP#}</div>

	<table cellpadding="0" cellspacing="0" width="100%" id="sort" class="tableStatic">
	<colgroup style="" class="disabled">
		<col width="20">
		<col>
		<col width="30%">
		<col width="100">
		<col width="110">
		<col width="100">
		<col width="45">
		<col width="45">
	</colgroup>
		<thead class="disabled">
		<tr class="noborder disabled">
			<td>[?]</td>
			<td>{#NAVI_LINK_TITLE#}</td>
			<td width="30%">{#NAVI_LINK_TO_DOCUMENT#}</td>
			<td width="100"></td>
			<td width="110"></td>
			<td width="100"></td>
			<td width="45" align="center"><div align="center"><a title="{#NAVI_MARK_ACTIVE#}" href="javascript:void(0);" class="topleftDir icon_sprite ico_ok"></a></div></td>
			<td  width="45" align="center"><div align="center"><a title="{#NAVI_MARK_DELETE#}" href="javascript:void(0);" class="topleftDir icon_sprite ico_delete"></a></div></td>
		</tr>
		</thead>

		{if $entries}
		{foreach from=$entries item=item}
		<tbody id="item_{$item->Id}" data-id="item_{$item->Id}">
			<tr>
				<td align="center">
					<span class="icon_sprite topDir ico_move{if $entries|@count<2}_no{/if}" title="{#RUBRIK_MOVE#}" style="cursor:move"></span>
				</td>
				<input type="hidden" name="document_alias[{$item->Id}]" id="Url_{$item->Id}" value="{$item->document_alias|stripslashes}" />
				<td align="center"><div class="pr12"><input name="title[{$item->Id}]" type="text" id="Titel_{$item->Id}" value="{$item->title|escape|stripslashes}" /></div></td>
				<td><div class="pr12"><input name="navi_item_link[{$item->Id}]" type="text" id="Link_{$item->Id}" value="{$item->navi_item_link|escape|stripslashes}" /></div></td>
				<td nowrap align="center">
				<input title="{#NAVI_BROWSE_DOCUMENTS#}" onclick="openLinkWindow('Link_{$item->Id}','Titel_{$item->Id}','Url_{$item->Id}');" type="button" class="basicBtn topDir" value="..." />
				<input title="{#NAVI_BROWSE_MEDIAPOOL#}" onclick="openFileWindow('Link_{$item->Id}','Link_{$item->Id}','Url_{$item->Id}');" type="button" class="basicBtn topDir" value="#" />
				</td>
				<td align="center"><input title="{#NAVI_ADD_SUBITEM#}" type="button" class="basicBtn topDir" onclick="document.getElementById('Item_2_{$item->Id}').style.display='';" value="{#NAVI_BUTTON_SUBITEM#}" /></td>
				<td align="center"><input title="{#NAVI_BUTTON_OPTION#}" type="button" class="basicBtn topDir opentr" rel="{$item->Id}" value="{#NAVI_BUTTON_OPTION#}" /></td>
				<td align="center"><input name="navi_item_status[{$item->Id}]" type="checkbox" value="1" {if $item->navi_item_status==1}checked="checked"{/if} /></td>
				<td align="center"><input name="del[{$item->Id}]" type="checkbox" id="del[{$item->Id}]" value="1" /></td>
			</tr>

			<tr id="td_{$item->Id}" class="subtr blank" style="display:none;">
				<td  colspan="2" valign="top">{#NAVI_LINK_SOLUT#}<div class="pr12"><textarea class="expand" rows="1" name="descr[{$item->Id}]">{$item->navi_item_desc|escape:html|stripslashes}</textarea></div></td>
				<td valign="top">{#NAVI_LINK_IMAGE#}<div class="pr12"><input name="Img[{$item->Id}]" type="text" id="Img{$item->Id}" value="{$item->navi_item_Img|escape:html|stripslashes}" /></div></td>
				<td align="center" valign="top">&nbsp;<input value="{#NAVI_BUTTON_CHANGE#}" title="{#NAVI_LINK_IMGTL#}" class="greenBtn topDir" onclick="openFileWindow('Img{$item->Id}','Img{$item->Id}');" type="button"></td>
				<td align="center" valign="top">{#NAVI_LINK_IMGID#}<div class="pr12"><input name="Img_id[{$item->Id}]" type="text" id="Img_id{$item->Id}" value="{$item->navi_item_Img_id|escape:html|stripslashes}" /></div></td>
				<td align="center" valign="top"></td>
				<td align="center" colspan="2" valign="top">{#NAVI_OPEN_IN_NEW#}<input name="navi_item_target[{$item->Id}]" id="Target_{$item->Id}" type="checkbox" value="_blank" {if $item->navi_item_target=='_blank'}checked="checked"{/if} /></td>
			</tr>

			<tr id="Item_2_{$item->Id}" style="display:none;" class="dgreen">
				<input type="hidden" name="Url_Item_2[{$item->Id}]" id="Url_Item_2_{$item->Id}" value="" />
				<td>&nbsp;</td>
				<td><div class="pr12 level1"><input style="width:100%" name="Titel_Item_2[{$item->Id}]" type="text" id="Titel_Item_2_{$item->Id}" value="" /></div></td>
				<td><div class="pr12"><input style="width:100%" name="Link_Item_2[{$item->Id}]" type="text" id="Link_Item_2_{$item->Id}" value="" /></div></td>
				<td nowrap align="center">
					<input title="{#NAVI_BROWSE_DOCUMENTS#}" onclick="openLinkWindow('Link_Item_2_{$item->Id}','Titel_Item_2_{$item->Id}','Url_Item_2_{$item->Id}');" type="button" class="greenBtn topDir" value="..." />
					<input title="{#NAVI_BROWSE_MEDIAPOOL#}" onclick="openFileWindow('Link_Item_2_{$item->Id}','Link_Item_2_{$item->Id}','Url_Item_2_{$item->Id}');" type="button" class="greenBtn topDir" value="#" />
				</td>
				<td>&nbsp;</td>
				<td align="center"><input title="{#NAVI_BUTTON_OPTION#}" type="button" class="greenBtn topDir opentr" rel="Item_2_{$item->Id}" value="{#NAVI_BUTTON_OPTION#}" /></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>

			<tr id="td_Item_2_{$item->Id}" style="display:none;" class="lgreen">
				<td colspan="2" valign="top">{#NAVI_LINK_SOLUT#}<div class="pr12"><textarea class="expand" rows="1" name="descr_Item_2[{$item->Id}]"></textarea></div></td>
				<td valign="top">{#NAVI_LINK_IMAGE#}<div class="pr12"><input name="Img_Item_2[{$item->Id}]" type="text" id="Img_Item_2{$item->Id}" value="" /></div></td>
				<td align="center" valign="top">&nbsp;<input value="{#NAVI_BUTTON_CHANGE#}" title="{#NAVI_LINK_IMGTL#}" class="greenBtn topDir" onclick="openFileWindow('Img_Item_2{$item->Id}','Img_Item_2{$item->Id}');" type="button"></td>
				<td align="center" valign="top">{#NAVI_LINK_IMGID#}<div class="pr12"><input name="Img_id_Item_2[{$item->Id}]" type="text" id="Img_id_Item_2{$item->Id}" value="" /></div></td>
				<td align="center" valign="top">{#NAVI_POSITION#}<div class="pr12"><input name="Rang_Item_2[{$item->Id}]" type="text" id="Rang_Item_2_{$item->Id}" value="" size="3" maxlength="3" /></div></td>
				<td align="center" colspan="2" valign="top">{#NAVI_OPEN_IN_NEW#}<input name="Target_Item_2[{$item->Id}]" id="Target_Item_2_{$item->Id}" type="checkbox" value="_blank" /></td>
			</tr>

			{foreach from=$item->ebene_2 item=item_2}
				<tr id="table_rows" class="green">
					<td>&nbsp;</td>
					<input type="hidden" name="document_alias[{$item_2->Id}]" id="Url_{$item_2->Id}" value="{$item_2->document_alias|stripslashes}" />
					<td><div class="pr12 level1"><input style="width:100%" name="title[{$item_2->Id}]" type="text" id="Titel_{$item_2->Id}" value="{$item_2->title|stripslashes}" /></div></td>
					<td><div class="pr12"><input style="width:100%" name="navi_item_link[{$item_2->Id}]" type="text" id="Link_{$item_2->Id}" value="{$item_2->navi_item_link|escape|stripslashes}" /></div></td>
					<td nowrap align="center">
						<input title="{#NAVI_BROWSE_DOCUMENTS#}" onclick="openLinkWindow('Link_{$item_2->Id}','Titel_{$item_2->Id}','Url_{$item_2->Id}');" type="button" class="greenBtn topDir" value="..." />
						<input title="{#NAVI_BROWSE_MEDIAPOOL#}" onclick="openFileWindow('Link_{$item_2->Id}','Link_{$item_2->Id}','Url_{$item_2->Id}');" type="button" class="greenBtn topDir" value="#" />
					</td>
					<td align="center"><input title="{#NAVI_ADD_SUBITEM#}" type="button" class="greenBtn topDir" onclick="document.getElementById('Item_3_{$item_2->Id}').style.display='';" value="{#NAVI_BUTTON_SUBITEM#}" /></td>
					<td align="center"><input title="{#NAVI_BUTTON_OPTION#}" type="button" class="greenBtn topDir opentr" rel="{$item_2->Id}" value="{#NAVI_BUTTON_OPTION#}" /></td>
					<td align="center"><input name="navi_item_status[{$item_2->Id}]" type="checkbox" value="1" {if $item_2->navi_item_status==1}checked="checked" {/if}/></td>
					<td align="center"><input name="del[{$item_2->Id}]" type="checkbox" id="del[{$item_2->Id}]" value="1" /></td>
				</tr>

				<tr id="td_{$item_2->Id}" style="display:none;" class="lgreen">
					<td valign="top" colspan="2">{#NAVI_LINK_SOLUT#}<div class="pr12"><textarea class="expand" rows="1" name="descr[{$item_2->Id}]">{$item_2->navi_item_desc|escape:html|stripslashes}</textarea></div></td>
					<td valign="top">{#NAVI_LINK_IMAGE#}<div class="pr12"><input name="Img[{$item_2->Id}]" type="text" id="Img{$item_2->Id}" value="{$item_2->navi_item_Img|escape:html|stripslashes}" /></div></td>
					<td align="center" valign="top">&nbsp;<input value="{#NAVI_BUTTON_CHANGE#}" title="{#NAVI_LINK_IMGTL#}" class="greenBtn topDir" onclick="openFileWindow('Img{$item_2->Id}','Img{$item_2->Id}');" type="button"></td>
					<td align="center" valign="top">{#NAVI_LINK_IMGID#}<div class="pr12"><input name="Img_id[{$item_2->Id}]" type="text" id="Img_id{$item_2->Id}" value="{$item_2->navi_item_Img_id|escape:html|stripslashes}" /></div></td>
					<td align="center" valign="top">{#NAVI_POSITION#}<div class="pr12"><input name="navi_item_position[{$item_2->Id}]" type="text" id="Rang_{$item_2->Id}" value="{$item_2->navi_item_position}" size="4" maxlength="3" /></div></td>
					<td align="center" colspan="2" valign="top">{#NAVI_OPEN_IN_NEW#}<input name="navi_item_target[{$item_2->Id}]" id="Target_{$item_2->Id}" type="checkbox" value="_blank" {if $item_2->navi_item_target=='_blank'}checked="checked"{/if} /></td>
				</tr>

				<tr id="Item_3_{$item_2->Id}" style="display:none;" class="dgrey">
					<input type="hidden" name="Url_Item_3[{$item_2->Id}]" id="Url_Item_3_{$item_2->Id}" value="" />
					<td>&nbsp;</td>
					<td><div class="pr12 level2"><input style="width:100%" name="Titel_Item_3[{$item_2->Id}]" type="text" id="Titel_Item_3_{$item_2->Id}" value="" /></div></td>
					<td><div class="pr12"><input style="width:100%" name="Link_Item_3[{$item_2->Id}]" type="text" id="Link_Item_3_{$item_2->Id}" value="" /></div></td>
					<td nowrap align="center">
						<input title="{#NAVI_BROWSE_DOCUMENTS#}" onclick="openLinkWindow('Link_Item_3_{$item_2->Id}','Titel_Item_3_{$item_2->Id}','Url_Item_3_{$item_2->Id}');" type="button" class="greyishBtn topDir" value="..." />
						<input title="{#NAVI_BROWSE_MEDIAPOOL#}" onclick="openFileWindow('Link_Item_3_{$item_2->Id}','Link_Item_3_{$item_2->Id}','Url_Item_3_{$item_2->Id}');" type="button" class="greyishBtn topDir" value="#" />
					</td>
					<td ></td>
					<td align="center"><input title="{#NAVI_BUTTON_OPTION#}" type="button" class="greyishBtn topDir opentr" rel="Item_3_{$item_2->Id}" value="{#NAVI_BUTTON_OPTION#}" /></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>

				<tr id="td_Item_3_{$item_2->Id}" style="display:none;" class="lgrey">
					<td colspan="2" valign="top">{#NAVI_LINK_SOLUT#}<div class="pr12"><textarea class="expand" rows="1" name="descr_Item_3[{$item_2->Id}]"></textarea></div></td>
					<td valign="top">{#NAVI_LINK_IMAGE#}<div class="pr12"><input name="Img_Item_3[{$item_2->Id}]" type="text" id="Img_Item_3{$item_2->Id}" value="" /></div></td>
					<td align="center" valign="top">&nbsp;<input value="{#NAVI_BUTTON_CHANGE#}" title="{#NAVI_LINK_IMGTL#}" class="greyishBtn topDir" onclick="openFileWindow('Img_Item_3{$item_2->Id}','Img_Item_3{$item_2->Id}');" type="button"></td>
					<td align="center" valign="top">{#NAVI_LINK_IMGID#}<div class="pr12"><input name="Img_id_Item_3[{$item_2->Id}]" type="text" id="Img_id_Item_3{$item_2->Id}" value="" /></div></td>
					<td align="center" valign="top">{#NAVI_POSITION#}<div class="pr12"><input name="Rang_Item_3[{$item_2->Id}]" type="text" id="Rang_Item_3_{$item_2->Id}" value="10" size="4" maxlength="3" /></div></td>
					<td align="center" colspan="2" valign="top">{#NAVI_OPEN_IN_NEW#}<input name="Target_Item_3[{$item_2->Id}]" id="Target_Item_3_{$item_2->Id}" type="checkbox" value="_blank" {if $item_2->Target_Item_3=='_blank'}checked="checked"{/if} /></td>
				</tr>

				{foreach from=$item_2->ebene_3 item=item_3}
					<tr id="table_rows" class="grey">
						<input type="hidden" name="document_alias[{$item_3->Id}]" id="Url_{$item_3->Id}" value="{$item_3->document_alias|stripslashes}" />
						<td>&nbsp;</td>
						<td><div class="pr12 level2"><input style="width:100%" name="title[{$item_3->Id}]" type="text" id="Titel_{$item_3->Id}" value="{$item_3->title|stripslashes}" /></div></td>
						<td><div class="pr12"><input style="width:100%" name="navi_item_link[{$item_3->Id}]" type="text" id="Link_{$item_3->Id}" value="{$item_3->navi_item_link|escape|stripslashes}" /></div></td>
						<td nowrap align="center">
						<input title="{#NAVI_BROWSE_DOCUMENTS#}" onclick="openLinkWindow('Link_{$item_3->Id}','Titel_{$item_3->Id}','Url_{$item_3->Id}');" type="button" class="greyishBtn topDir" value="..." />
						<input title="{#NAVI_BROWSE_MEDIAPOOL#}" onclick="openFileWindow('Link_{$item_3->Id}','Link_{$item_3->Id}','Url_{$item_3->Id}');" type="button" class="greyishBtn topDir" value="#" />
						</td>
						<td ></td>
						<td align="center"><input title="{#NAVI_BUTTON_OPTION#}" type="button" class="greyishBtn topDir opentr" rel="{$item_3->Id}" value="{#NAVI_BUTTON_OPTION#}" /></td>
						<td align="center"><input name="navi_item_status[{$item_3->Id}]" type="checkbox" id="del[{$item_3->Id}]" value="1" {if $item_3->navi_item_status==1}checked="checked" {/if}/></td>
						<td align="center"><input name="del[{$item_3->Id}]" type="checkbox" value="1" /></td>
					</tr>

					<tr id="td_{$item_3->Id}" style="display:none;" class="lgrey">
						<td colspan="2" valign="top">{#NAVI_LINK_SOLUT#}<div class="pr12"><textarea class="expand" rows="1" name="descr[{$item_3->Id}]">{$item_3->navi_item_desc|escape:html|stripslashes}</textarea></div></td>
						<td valign="top">{#NAVI_LINK_IMAGE#}<div class="pr12"><input name="Img[{$item_3->Id}]" type="text" id="Img{$item_3->Id}" value="{$item_3->navi_item_Img|escape:html|stripslashes}" /></div></td>
						<td align="center" valign="top">&nbsp;<input value="{#NAVI_BUTTON_CHANGE#}" title="{#NAVI_LINK_IMGTL#}" class="greyishBtn topDir" onclick="openFileWindow('Img{$item_3->Id}','Img{$item_3->Id}');" type="button"></td>
						<td align="center" valign="top">{#NAVI_LINK_IMGID#}<div class="pr12"><input name="Img_id[{$item_3->Id}]" type="text" id="Img_id{$item_3->Id}" value="{$item_3->navi_item_Img_id|escape:html|stripslashes}" /></div></td>
						<td align="center" valign="top">{#NAVI_POSITION#}<div class="pr12"><input name="navi_item_position[{$item_3->Id}]" type="text" id="Rang_{$item_3->Id}" value="{$item_3->navi_item_position}" size="4" maxlength="3" /></div></td>
						<td align="center" colspan="2" valign="top">{#NAVI_OPEN_IN_NEW#}<input name="navi_item_target[{$item_3->Id}]" id="Target_{$item_3->Id}" type="checkbox" value="_blank" {if $item_3->navi_item_target=='_blank'}checked="checked"{/if} /></td>
					</tr>						
				{/foreach}


			{/foreach}
			</tbody>
		{/foreach}
		{else}
			<tr>
				<td colspan="8">
					<ul class="messages">
						<li class="highlight yellow">{#NAVI_ENTRIES_NO_ITEMS#}</li>
					</ul>
				</td>
			</tr>
		{/if}
	</table>

	<div class="rowElem" id="saveBtn">
		<div class="saveBtn">
		<input type="hidden" id="navi_id" name="navi_id" value="{$smarty.request.id|escape}" />
		<input type="submit" class="basicBtn" value="{#NAVI_BUTTON_SAVE#}" />
		</div>
	</div>

	<div class="fix"></div>
</div>

</fieldset>
</form>
