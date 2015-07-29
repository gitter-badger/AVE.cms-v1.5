<form method="post" class="ctrlrequest" action="">
  <table>
    <tr>
{foreach from=$ctrlrequest item=items key=selname}
      <td>
        <label>{$items.titel} </label>
        <select name="req_{$request_id}[{$selname}]">
          <option value=''>Все</option>
          {html_options values=$items.options output=$items.options selected=$items.selected}
        </select>
      </td>
{/foreach}
      <td><input type="submit" class="button" value="Отфильтровать" /></td>
    </tr>
  </table>
</form>