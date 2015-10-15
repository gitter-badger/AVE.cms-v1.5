<script type="text/javascript" language="JavaScript">
$(document).ready(function(){ldelim}

	{if check_permission('navigation_edit')}
		$(".AddNavigation").click( function(event) {ldelim}
			event.preventDefault();
			var user_group = $('#add_navigation #navigation_title_new').fieldValue();
			var title = '{#NAVI_NEW_MENU#}';
			var text = '{#NAVI_ENTER_NAME#}';
			if (user_group == ""){ldelim}
				jAlert(text,title);
			{rdelim}else{ldelim}
				$.alerts._overlay('show');
				$("#add_navigation").submit();
			{rdelim}
		{rdelim});
	{/if}

	$(".CopyNavi").click( function(event) {ldelim}
		event.preventDefault();
		var href = $(this).attr('href');
		var title = '{#NAVI_COPY_TEMPLATE#}';
		var text = '{#NAVI_ENTER_NAME#}';
		jPrompt(text, '', title, function(b){ldelim}
					if (b){ldelim}
						$.alerts._overlay('show');
						window.location = href + '&navigation_title_new=' + b;
					{rdelim}
				{rdelim}
			);
	{rdelim});

{rdelim});
</script>


<div class="title">
	<h5>{#NAVI_SUB_TITLE#}</h5>
</div>

<div class="widget" style="margin-top: 0px;">
	<div class="body">
		{#NAVI_TIP_TEMPLATE#}
	</div>
</div>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
		<ul>
			<li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
			<li>{#NAVI_SUB_TITLE#}</li>
		</ul>
	</div>
</div>


<div class="widget first">
	<ul class="tabs">
		<li class="activeTab"><a href="#tab1">{#NAVI_ALL#}</a></li>
		{if check_permission('navigation_edit')}<li class=""><a href="#tab2">{#NAVI_NEW_MENU#}</a></li>{/if}
	</ul>

	<div class="tab_container">
		<div id="tab1" class="tab_content" style="display: block;">
		<form class="mainForm">
			<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
				<thead>
					<tr>
						<td width="40">{#NAVI_ID#}</td>
						<td>{#NAVI_NAME#}</td>
						<td width="150">{#NAVI_SYSTEM_TAG#}</td>
						<td width="80" colspan="4">{#NAVI_ACTIONS#}</td>
					</tr>
				</thead>
				<tbody>
				{foreach from=$navigations item=item}
				<tr>
					<td align="center">{$item->navigation_id}</td>
					<td>
						<strong>
							{if check_permission('navigation_edit')}
								<a title="{#NAVI_EDIT_ITEMS#}" href="index.php?do=navigation&action=entries&navigation_id={$item->navigation_id}&cp={$sess}" class="topDir link">{$item->title|escape:html|stripslashes}</a>
							{else}
								{$item->navigation_title|escape:html|stripslashes}
							{/if}
						</strong>
					</td>
					<td><div class="pr12"><input type="text" value="[tag:navigation:{$item->navigation_id}]" size="15" readonly></div></td>
					<td width="1%" align="center">
						{if check_permission('navigation_edit')}
							<a title="{#NAVI_EDIT_TEMPLATE#}" href="index.php?do=navigation&action=templates&navigation_id={$item->navigation_id}&cp={$sess}" class="topleftDir icon_sprite ico_template"></a>
						{else}
							<span title="" class="topleftDir icon_sprite ico_template_no"></span>
						{/if}
					</td>
					<td width="1%" align="center">
						{if check_permission('navigation_edit')}
							<a title="{#NAVI_EDIT_ITEMS#}" href="index.php?do=navigation&action=entries&navigation_id={$item->navigation_id}&cp={$sess}" class="topleftDir icon_sprite ico_navigation"></a>
						{else}
							<span title="" class="topleftDir icon_sprite ico_navigation_no"></span>
						{/if}
					</td>
					<td width="1%" align="center">
						{if check_permission('navigation_edit')}
							<a title="{#NAVI_COPY_TEMPLATE#}" href="index.php?do=navigation&action=copy&navigation_id={$item->navigation_id}&cp={$sess}" class="topleftDir CopyNavi icon_sprite ico_copy"></a>
						{else}
							<span title="" class="topleftDir icon_sprite ico_copy_no"></span>
						{/if}
					</td>
					<td width="1%" align="center">
						{if $item->navigation_id == 1}
								<span href="javascript:void(0);" class="topleftDir icon_sprite ico_delete_no"></span>
						{else}
							{if check_permission('navigation_edit')}
								<a title="{#NAVI_DELETE#}" dir="{#NAVI_DELETE#}" name="{#NAVI_DELETE_CONFIRM#}" href="index.php?do=navigation&action=delete&navigation_id={$item->navigation_id}&cp={$sess}" class="topleftDir ConfirmDelete icon_sprite ico_delete"></a>
							{else}
								<span title="" class="topleftDir icon_sprite ico_delete_no"></span>
							{/if}
						{/if}
					</td>
				</tr>
				{/foreach}
				</tbody>
			</table>
		</form>

		</div>
		{if check_permission('navigation_edit')}
		<div id="tab2" class="tab_content" style="display:none;">
			<form id="add_navigation" method="post" action="index.php?do=navigation&action=new&cp={$sess}" class="mainForm">
			<div class="rowElem">
				<label>{#NAVI_TITLE2#}</label>
				<div class="formRight"><input placeholder="{#NAVI_NAME#}" name="navigation_title_new" type="text" id="navigation_title_new" value="" style="width: 400px">
				&nbsp;<input type="button" class="basicBtn AddNavigation" value="{#NAVI_BUTTON_ADD_MENU#}" />
				</div>
				<div class="fix"></div>
			</div>
			</form>
		</div>
		{/if}
	</div>
	<div class="fix"></div>
</div>