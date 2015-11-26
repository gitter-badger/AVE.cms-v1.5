	{if $items}
	<ol class="dd-list">
	{foreach from=$items key=key item=item}
		<li class="dd-item dd3-item" data-id="{$item.navigation_item_id}" id="item-{$item.navigation_item_id}">
			<div class="dd-handle dd3-handle"></div>
			<div class="dd3-content{if $item.status == 0} red{/if}">
				<div class="name">
					<a href="index.php?do=navigation&action=itemedit&sub=edit&navigation_item_id={$item.navigation_item_id}&cp={$sess}&pop=1" data-width="400px" data-modal="true" data-dialog="item-{$item.navigation_item_id}" title="Редактировать пункт меню" class="openDialog topDir">{$item.title}</a>
				</div>

				<div class="url">
					<a style="color: #ccc;" href="{$item.alias|escape}" class="topDir icon_sprite ico_globus" target="_blank" title="{$item.alias|escape}"></a>
				</div>

				<div class="document">
					{if $item.document_title}
					<a href="index.php?do=docs&action=edit&Id={$item.document_id|escape}&cp={$sess}" class="topDir link" original-title="">{$item.document_title|escape}</a> (ID: {$item.document_id|escape})
					{else}
					<span class="date_text dgrey">Нет связанного документа</span>
					{/if}
				</div>

				<div class="status">
					{if $item.status == 1}
					<a href="index.php?do=navigation&action=itemestatus&navigation_item_id={$item.navigation_item_id}&cp={$sess}" data-status="0" class="topleftDir icon_sprite ico_ok_green changeStatus" title="Вкл/Выкл пункт меню"></a>
					{else}
					<a href="index.php?do=navigation&action=itemestatus&navigation_item_id={$item.navigation_item_id}&cp={$sess}" data-status="1" class="topleftDir icon_sprite ico_delete_no changeStatus" title="Вкл/Выкл пункт меню"></a>
					{/if}
				</div>

				<div class="action">
					<a href="index.php?do=navigation&action=itemedit&sub=edit&navigation_item_id={$item.navigation_item_id}&cp={$sess}&pop=1" data-width="400px" data-modal="true" data-dialog="item-{$item.navigation_item_id}" title="Редактировать пункт меню" class="openDialog topleftDir icon_sprite ico_edit"></a>
					<a href="index.php?do=navigation&action=itemedel&navigation_item_id={$item.navigation_item_id}&cp={$sess}" class="topleftDir ConfirmDelete icon_sprite ico_delete" title="Удалить пункт меню"></a>
				</div>
			</div>
		{include file="$nestable_tpl" items=$item.children level=$level+1}
		</li>
	{/foreach}
	</ol>
	{/if}
