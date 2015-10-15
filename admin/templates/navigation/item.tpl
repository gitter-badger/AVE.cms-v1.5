			<div class="dd3-content {if $smarty.request.sub == 'new'}green{/if}">
				<div class="name">
					<a href="index.php?do=navigation&action=itemedit&sub=edit&navigation_item_id={$item.navigation_item_id}&cp={$sess}&pop=1" data-width="400px" data-modal="true" data-dialog="item-{$item.navigation_item_id}" data-title="Редактирование пункта меню" class="openDialog">{$item.title}</a>
				</div>

				<div class="url">
					<a style="color: #ccc;" href="{$ABS_PATH}{$item.alias|escape}" class="topDir icon_sprite ico_globus" target="_blank" title="{$item.alias|escape}"></a>
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
					<span class="topleftDir icon_sprite ico_ok_green"></span>
					{else}
					<span class="topleftDir icon_sprite ico_delete"></span>
					{/if}
				</div>

				<div class="action">
					<a href="index.php?do=navigation&action=itemedit&sub=edit&navigation_item_id={$item.navigation_item_id}&cp={$sess}&pop=1" data-width="400px" data-modal="true" data-dialog="item-{$item.navigation_item_id}" data-title="Редактирование пункта меню" class="openDialog icon_sprite ico_edit"></a>
					<a href="index.php?do=navigation&action=itemedel&navigation_item_id={$item.navigation_item_id}&cp={$sess}" class="topleftDir ConfirmDelete icon_sprite ico_delete"></a>
				</div>
			</div>
