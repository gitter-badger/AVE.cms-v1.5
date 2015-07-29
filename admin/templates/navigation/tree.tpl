<select name="document_linked_navi_id" id="document_linked_navi_id" style="width: 350px;">
	<option value="0">&nbsp;</option>
	{foreach from=$navis item=navi}
		<optgroup label="({$navi->id}) {$navi->navi_titel|escape}"></optgroup>
		{foreach name=e from=$navi_items item=item_1}
			{if $navi->id == $item_1->navi_id}
				<option value="{$item_1->Id}" {if $document->document_linked_navi_id==$item_1->Id}selected{/if}>&nbsp; {$item_1->title|escape}</option>
				{foreach from=$item_1->ebene_2 item=item_2}
					<option value="{$item_2->Id}" {if $document->document_linked_navi_id==$item_2->Id}selected{/if}>&nbsp;&nbsp;&nbsp;&nbsp;->&nbsp;{$item_2->title|escape}</option>
					{foreach from=$item_2->ebene_3 item=item_3}
						<option value="{$item_3->Id}" {if $document->document_linked_navi_id==$item_3->Id}selected{/if}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;->&nbsp;{$item_3->title|escape}</option>
					{/foreach}
				{/foreach}
			{/if}
		{/foreach}
	{/foreach}
</select>
