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

<div id="confirm_dialog_caption">
	{#head_confirm#}
</div>

<div class="info" style="padding-right:10px !important;">
	{$msg}
</div>

<div style="text-align:center;">
	<input type="submit" alt="" value="{#lbl_confirm#}" name="confirm" class="button">

	{foreach from=$submitButton item=button}
      <input type="submit" alt="" value="{$button.lbl}" name="{$button.action}" class="button">
	{/foreach}

	<input type="submit" alt="" value="{#lbl_cancel#}" name="cancel" class="button">
	
	{if $passtrough}
		<input type="submit" alt="" value="{#lbl_cancel#}" name="passtrough" class="button">
	{/if}
</div>