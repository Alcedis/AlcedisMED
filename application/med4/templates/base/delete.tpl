{*
AlcedisMED
Copyright (C) 2010-2016  Alcedis GmbH

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*} 

<div id="delete_dialog_caption">
	{#head_delete#}
</div>
<div class="err">
	{if strlen($msg) == 0}
      {#lbl_delete_this#}
	{else}
      {$msg}
	{/if}

</div>

{if count($extDelete) || count($extReference)}
<div class="warn" style="padding-right:10px !important;">
{/if}

{if count($extDelete)}
{#lbl_ext_delete#}

<ul style="list-style:disc inside;margin:5px;">
    {foreach from=$extDelete item=delete}
        <li>
            <b>{if isset($delete.date) == true}
                  {$delete.date} - {$delete.lbl}
               {else}
                  {$delete.lbl}
               {/if}
            </b>
        </li>
   {/foreach}
</ul>

{/if}

{if count($extDelete) && count($extReference)}
<br/>
{/if}

{if count($extReference)}
{#lbl_ext_reference#}

<ul style="list-style:disc inside;margin:5px;">
    {foreach from=$extReference item=reference}
        <li><b>{$reference.date} - {$reference.lbl}</b></li>
   {/foreach}
</ul>

{/if}

{if count($extDelete) || count($extReference)}
</div>
{/if}

<div style="text-align:center;">
	<input type="submit" alt="" value="{#lbl_delete#}" name="delete" class="button">

	{foreach from=$submitButton item=button}
      <input type="submit" alt="" value="{$button.lbl}" name="{$button.action}" class="button">
	{/foreach}

	<input type="submit" alt="" value="{#lbl_cancel#}" name="cancel" class="button">
</div>