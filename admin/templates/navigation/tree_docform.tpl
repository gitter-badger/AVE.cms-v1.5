<select name="parent_id" id="parent_id" style="width: 300px;">
	<option value="0">{#DOC_TOP_MENU_ITEM#}</option>
	{foreach from=$navis item=navi}
		<optgroup label="({$navi->id}) {$navi->navi_titel|escape}"></optgroup>
		{foreach name=e from=$navi_items item=item_1}
			{if $navi->id == $item_1->navi_id}
				<option value="{$item_1->Id}">&nbsp; {$item_1->title|escape}</option>
				{foreach from=$item_1->ebene_2 item=item_2}
					<option value="{$item_2->Id}">&nbsp;&nbsp;&nbsp;&nbsp;- {$item_2->title|escape}</option>
				{/foreach}
			{/if}
		{/foreach}
	{/foreach}
</select>