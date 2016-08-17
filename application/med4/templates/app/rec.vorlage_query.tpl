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

{if $freigabe == true || $inaktiv == true}
	<table class="formtable" style="margin-bottom:6px">
	{html_set_header class="head" caption=#head_aktivitaet#}
		<tr>
		   <td class='lbl'>{$_inaktiv_lbl}</td>
		   <td class='edt'>
		   	{$_inaktiv} {if $stateButton !== false}<input style="margin:0 5px" type="submit" class="button" name="action[{$stateButton}]" value="{#btn_lbl_update#}" />{/if}
		   </td>
		</tr>
	</table>

	{if $inaktiv == true}
		<div class="warn">
			{#info_inaktiv#}
		</div>
	{else}
		<div class="green-info">
			{#info_freigabe#}
		</div>
	{/if}
{/if}

<table class="formtable">

{html_set_header class="head" caption=#head_auswertung#}
{html_set_row caption=$_bez_lbl           input=$_bez}
{html_set_row caption=$_erkrankung_lbl    input=$_erkrankung}

{if $isAdmin != true}
    {html_set_row caption=$_sqlstring_lbl     input=$_sqlstring}
{/if}

{if $isAdmin === true}
    {html_set_header class="head" caption=#head_org#}
    <tr>
        <td colspan="2" style="padding:0">
            <div class="info-msg" style="margin-bottom:0 !important">
                {#msg_info#}
            </div>
        </td>
    </tr>
    </table>

    <div class="dlist" id="dlist_org">
       <div class="add">
       {if $restrict === null}
          <input class="button" type="button" name="vorlage_query_org" value="Hinzuf&uuml;gen" onclick="execute_request(this,'rec.vorlage_query_org', null, ['vorlage_query_id'])"/>
       {else}
          <br/>
       {/if}
       </div>
    </div>

    <table class="formtable">
    {html_set_header class="head" caption=#head_package#}
    {if $package}
            <tr>
               <td class='lbl'>{$_package_lbl}</td>
               <td class='edt'>
                    <div style="float:left">{$package_text} {$err_package}</div>
                  <div style="float:right">{$btn_unset_file}</div>
                    <input type="hidden" name="package" value="{$package}" />
               </td>
            </tr>
    {else}
            <tr>
               <td class='lbl'>{$_package_lbl}</td>
               <td class='edt'><input type='file' size='50' name='package' />{$err_package}</td>
            </tr>
    {/if}
    {html_set_row caption=$_typ_lbl input=$_typ}
    {html_set_row caption=$_ident_lbl input=$_ident}
{/if}
{html_set_header class="head" caption=#head_freigabe#}
{html_set_row caption=$_freigabe_lbl input=$_freigabe}

{html_set_header class="head" caption=#head_bem#}
{html_set_header class="edt" caption=$_bem}
</table>
{html_set_buttons modus=$button}

{$_vorlage_query_id}