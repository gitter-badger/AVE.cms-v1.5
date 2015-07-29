{if $smarty.session.use_editor == 0}
	<script type="text/javascript" src="{$ABS_PATH}admin/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="{$ABS_PATH}admin/ckeditor/vendor/jquery.spellchecker.js"></script>
	<link rel="stylesheet" href="{$ABS_PATH}admin/ckeditor/vendor/jquery.spellchecker.css" type="text/css" media="all" />
{/if}

{if $smarty.session.use_editor == 1}
	<!-- elrte -->
	<link rel="stylesheet" href="{$ABS_PATH}admin/redactor/elrte/css/elrte.full.css" type="text/css" media="screen" />
	<script src="{$ABS_PATH}admin/redactor/elrte/js/elrte.full.js" type="text/javascript"></script>
	<script src="{$ABS_PATH}admin/redactor/elrte/js/i18n/elrte.ru.js" type="text/javascript"></script>

	<!-- elfinder -->
	<link rel="stylesheet" href="{$ABS_PATH}admin/redactor/elfinder/css/elfinder.full.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="{$ABS_PATH}admin/redactor/elfinder/css/theme.css" type="text/css" media="screen" />
	
	<script src="{$ABS_PATH}admin/redactor/elfinder/js/elfinder.full.js" type="text/javascript"></script>
	<script src="{$ABS_PATH}admin/redactor/elfinder/js/i18n/elfinder.ru.js" type="text/javascript"></script>
	<script src="{$ABS_PATH}admin/redactor/elfinder/js/jquery.dialogelfinder.js" type="text/javascript"></script>

	<script type="text/javascript" src="{$tpl_dir}/js/rle.js"></script>	
{/if}

<!-- liveeditor -->
{if $smarty.session.use_editor == 2}
	{literal}
		<style>
		.istoolbar_container { padding:0; margin:0}
		.istoolbar_container tbody tr { border-top: 0px !important; background:transparent !important}
		.istoolbar_container tbody tr:hover { background:transparent !important}		
		.istoolbar_container tbody td { border-left:0px !important}
		.istoolbar_container tbody td:hover { background:transparent !important}
		</style>
	{/literal}

	<script src="{$ABS_PATH}admin/liveeditor/LiveEditor/scripts/language/ru-RU/editor_lang.js"></script>
	<script src="{$ABS_PATH}admin/liveeditor/LiveEditor/scripts/innovaeditor.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/webfont/1.5.2/webfont.js" type="text/javascript"></script>
	<script src="{$ABS_PATH}admin/liveeditor/LiveEditor/scripts/common/webfont.js" type="text/javascript"></script>
{/if}

<div class="title">
	<h5>{#SYSBLOCK_INSERT_H#}</h5>
</div>

<div class="widget" style="margin-top: 0px;">
	<div class="body"> {#SYSBLOCK_INSERT#} </div>
</div>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
		<ul>
			<li class="firstB">
				<a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a>
			</li>
			<li>
				<a href="index.php?do=sysblocks&cp={$sess}" title="">{#SYSBLOCK_LIST_LINK#}</a>
			</li>
			<li>{if $smarty.request.id != ''}{#SYSBLOCK_EDIT_H#}{else}{#SYSBLOCK_INSERT_H#}{/if}</li>
			<li><strong class="code">{if $smarty.request.id != ''}{$sysblock_name|escape}{else}{$smarty.request.sysblock_name}{/if}</strong></li>
		</ul>
	</div>
</div>

<form id="sysblock" action="index.php?do=sysblocks&action=save&cp={$sess}" method="post" class="mainForm">
	<div class="widget first">
		<div class="head">
			<h5 class="iFrames">{if $smarty.request.id != ''}{#SYSBLOCK_EDIT_H#}{else}{#SYSBLOCK_INSERT_H#}{/if}</h5>
		</div>
		<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
			<col width="300">
			<col width="300">
			<col width="300">
			<col>
			<tr class="noborder">
				<td><strong>{#SYSBLOCK_NAME#}</strong></td>
				<td colspan="3">
					<div class="pr12">
						<input name="sysblock_name" class="mousetrap" type="text" value="{if $smarty.request.id != ''}{$sysblock_name|escape}{else}{$smarty.request.sysblock_name}{/if}" />
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" value="1" name="sysblock_external" class="float" {if $sysblock_external}checked="checked"{/if} /><label>{#SYSBLOCK_EXTERNAL#}</label>
				</td>
				<td>
					<input type="checkbox" value="1" name="sysblock_ajax" class="float" {if $sysblock_ajax}checked="checked"{/if} /><label>{#SYSBLOCK_AJAX#}</label>
				</td>
				<td>
					<input type="checkbox" value="1" name="sysblock_visual" class="float" {if $sysblock_visual}checked="checked"{/if} /><label>{#SYSBLOCK_VISUAL#}</label>
				</td>
				<td>

				</td>
			</tr>
			{if $sysblock_external}
			<tr>
				<td colspan="4">
					<ul class="messages">
						<li class="highlight grey">{#SYSBLOCK_LINK#} <a class="float" href="/?sysblock={$smarty.request.id}" target="_blank">http://{$smarty.server.HTTP_HOST}/?sysblock={$smarty.request.id}</a></li>
					</ul>
				</td>
			</tr>
			{/if}
		</table>
	</div>
	<div class="widget first">
		<div class="head">
			<h5 class="iFrames">{#SYSBLOCK_HTML#}</h5>
		</div>
		<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
			<tbody>
				<tr>
					<td>
						{if $smarty.session.use_editor == 0}
							{$sysblock_text}
						{elseif $smarty.session.use_editor == 1}
							<textarea class="mousetrap editor" id="sysblock_text" name="sysblock_text">{$sysblock_text|escape}</textarea>
						{elseif $smarty.session.use_editor == 2}
							{$sysblock_text}
						{/if}
					</td>
				</tr>
			</tbody>
		</table>

		<div class="rowElem" id="saveBtn">
			<div class="saveBtn">
				{if $smarty.request.id != ''}
				<input type="hidden" name="id" value="{$id}">
				<input name="submit" type="submit" class="basicBtn" value="{#SYSBLOCK_SAVEDIT#}" />
				{else}
				<input name="submit" type="submit" class="basicBtn" value="{#SYSBLOCK_SAVE#}" />
				{/if}
			</div>
		</div>

	</div>
</form>