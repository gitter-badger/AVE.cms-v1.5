<script type="text/javascript" language="JavaScript">
$(document).ready(function(){ldelim}
	{if check_permission('request_edit')}
		$(".AddRequest").click( function(event) {ldelim}
			event.preventDefault();
			var request_title_new = $('#add_request #request_title_new').fieldValue();
			var title = '{#REQUEST_NEW#}';
			var text = '{#REQUEST_ENTER_NAME#}';
			if (request_title_new == ""){ldelim}
				jAlert(text,title);
			{rdelim}else{ldelim}
				$.alerts._overlay('show');
				$("#add_request").submit();
			{rdelim}
		{rdelim});

		$(".CopyRequest").click( function(event) {ldelim}
			event.preventDefault();
			var href = $(this).attr('href');
			var title = '{#REQUEST_COPY#}';
			var text = '{#REQUEST_PLEASE_NAME#}';
			jPrompt(text, '', title, function(b){ldelim}
						if (b){ldelim}
							$.alerts._overlay('show');
							window.location = href + '&cname=' + b;
						{rdelim}
					{rdelim}
				);
		{rdelim});
	{/if}
{rdelim});
</script>

<div class="title"><h5>{#REQUEST_TITLE#}</h5></div>

<div class="widget" style="margin-top: 0px;">
	<div class="body">
		{#REQUEST_TIP#}
	</div>
</div>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
		<ul>
			<li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
			<li>{#REQUEST_TITLE#}</li>
		</ul>
	</div>
</div>

<div class="widget first">
	<ul class="tabs">
		<li class="activeTab"><a href="#tab1">{#REQUEST_ALL#}</a></li>
		{if check_permission('request_edit')}<li class=""><a href="#tab2">{#REQUEST_NEW#}</a></li>{/if}
	</ul>

		<div class="tab_container">
			<div id="tab1" class="tab_content" style="display: block;">
			<form class="mainForm">
			<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
				{if $items}
				<thead>
					<tr>
						<td width="40">{#REQUEST_ID#}</td>
						<td>{#REQUEST_NAME#}</td>
						<td width="120">{#REQUEST_SYSTEM_TAG#}</td>
						<td width="200">{#REQUEST_AUTHOR#}</td>
						<td width="200">{#REQUEST_DATE_CREATE#}</td>
						<td width="80" colspan="4">{#REQUEST_ACTIONS#}</td>
					</tr>
				</thead>
				<tbody>
					{foreach from=$items item=item}
					<tr>
						<td align="center">{$item->Id}</td>

						<td>
							{if check_permission('request_edit')}
							<a title="{$item->request_description|escape|default:#REQUEST_NO_DESCRIPTION#}" href="index.php?do=request&action=edit&Id={$item->Id}&rubric_id={$item->rubric_id}&cp={$sess}" class="topDir link">
								<strong>{$item->request_title|escape}</strong>
							</a>
							{else}
								<strong>{$item->request_title|escape}</strong>
							{/if}
						</td>

						<td><div style="padding-right: 12px"><input name="aiid" readonly type="text" id="aiid" value="[tag:request:{$item->Id}]"></div></td>

						<td align="center">{$item->request_author|escape}</td>

						<td align="center">
							<span class="date_text dgrey">{$item->request_created|date_format:$TIME_FORMAT|pretty_date}</span>
						</td>

						<td width="1%" align="center">
							{if check_permission('request_edit')}
								<a title="{#REQUEST_EDIT#}" href="index.php?do=request&action=edit&Id={$item->Id}&cp={$sess}&rubric_id={$item->rubric_id}" class="topleftDir icon_sprite ico_edit"></a>
							{else}
								<span class="icon_sprite ico_edit_no"></span>
							{/if}
						</td>

						<td width="1%" align="center">
							{if check_permission('request_edit')}
								<a title="{#REQUEST_CONDITION_EDIT#}" data-dialog="conditions-{$item->Id}" data-modal="true" data-title="{#REQUEST_CONDITION#}" href="index.php?do=request&action=conditions&rubric_id={$item->rubric_id}&Id={$item->Id}&cp={$sess}&pop=1" class="topleftDir icon_sprite ico_query openDialog"></a>
							{else}
								<span class="icon_sprite ico_query_no"></span>
							{/if}
						</td>

						<td width="1%" align="center">
							{if check_permission('request_edit')}
								<a title="{#REQUEST_COPY#}" href="index.php?do=request&action=copy&Id={$item->Id}&cp={$sess}&rubric_id={$item->rubric_id}" class="CopyRequest topleftDir icon_sprite ico_copy"></a>
							{else}
								<span class="icon_sprite ico_copy_no"></span>
							{/if}
						</td>

						<td width="1%" align="center">
							{if check_permission('request_edit')}
								<a title="{#REQUEST_DELETE#}" dir="{#REQUEST_DELETE#}" name="{#REQUEST_DELETE_CONFIRM#}" href="index.php?do=request&action=delete_query&rubric_id={$item->rubric_id}&Id={$item->Id}&cp={$sess}" class="ConfirmDelete topleftDir icon_sprite ico_delete"></a>
							{else}
								<span class="icon_sprite ico_delete_no"></span>
							{/if}
						</td>
					</tr>
					{/foreach}
					{else}
					<tr class="noborder">
						<td colspan="6">
							<ul class="messages">
								<li class="highlight yellow">{#REQUEST_NO_REQUST#}</li>
							</ul>
						</td>
					</tr>
					{/if}
				</tbody>
			</table>
			</form>

			</div>
			{if check_permission('request_edit')}
			<div id="tab2" class="tab_content" style="display: none;">
				<form id="add_request" method="post" action="index.php?do=request&action=new&cp={$sess}" class="mainForm">
				<div class="rowElem">
					<label>{#REQUEST_NAME3#}</label>
					<div class="formRight"><input placeholder="{#REQUEST_NAME#}" name="request_title_new" type="text" id="request_title_new" value="" style="width: 400px">
					&nbsp;<input type="button" class="basicBtn AddRequest" value="{#REQUEST_BUTTON_ADD#}" />
					</div>
					<div class="fix"></div>
				</div>
				</form>
			</div>
			{/if}
		</div>

<div class="fix"></div>
</div>

{if $page_nav}
	<div class="pagination">
	<ul class="pages">
		{$page_nav}
	</ul>
	</div>
{/if}