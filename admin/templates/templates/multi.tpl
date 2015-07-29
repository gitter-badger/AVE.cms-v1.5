<div class="title"><h5>{#TEMPLATES_COPY_TITLE#}</h5></div>

<div class="widget" style="margin-top: 0px;">
    <div class="body">
		{#TEMPLATES_TIP2#}
    </div>
</div>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li><a href="index.php?do=templates&cp={$sess}" title="">{#TEMPLATES_SUB_TITLE#}</a></li>
	        <li>{#TEMPLATES_COPY_TITLE#}</li>
	    </ul>
	</div>
</div>

      {foreach from=$errors item=e}
      {assign var=message value=$e}
		<ul class="messages">
			<li class="highlight red"><strong>Ошибка:</strong> {$message}</li>
		</ul>
      {/foreach}

<div class="widget first">
<div class="head"><h5 class="iFrames">{#TEMPLATES_COPY_TITLE#}</h5></div>
<form name="m" method="post" action="?do=templates&action=multi&sub=save&Id={$smarty.request.Id|escape}" class="mainForm">
<div class="rowElem noborder">

	<label>{#TEMPLATES_NAME2#}</label>
	<div class="formRight"><input name="template_title" type="text" value="{$smarty.request.template_title|escape|default:"Название"}" maxlength="50" style="width: 250px;" />&nbsp;<input class="basicBtn" type="submit" value="{#TEMPLATES_BUTTON_COPY#}" /></div>
	<div class="fix"></div>
	<input name="oId" type="hidden" id="oId" value="{$smarty.request.Id|escape}" />
</div>
</form>
</div>