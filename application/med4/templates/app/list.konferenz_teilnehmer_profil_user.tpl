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

{if strlen($_konferenz_teilnehmer_profil_id_value) == 0}
    <div class="info">
        {#msg_insert#}
    </div>
{/if}

<table class="formtable">

{html_set_header caption=#caption# class='head' }

{if strlen($_konferenz_teilnehmer_profil_id_value) > 0}
    {html_set_row caption=$_bez_lbl input=<strong>$_bez_value</strong>}
    <input type="hidden" name="bez" value="{$_bez_value}" />
{else}
    {html_set_row caption=$_bez_lbl input=$_bez }
{/if}

{html_set_header caption=#head_bem# class='head' }
{html_set_header caption=$_bem class='edt' }

</table>
<div>
   <input type="hidden" name="konferenz_teilnehmer_profil_id" value="{$_konferenz_teilnehmer_profil_id_value}" />
</div>

{if strlen($_konferenz_teilnehmer_profil_id_value) > 0}
    <table class="listtable bfl" summary='{$bflparam}'>
    <tr>
       <td class="head unsortable" style="width:70px">
        {#lbl_select#}
        <input type="text" class="bfl-buffer" name="buffer-id" value='{$buffer}' />
        </td>
       <td class="head ext-search cookie-teilnehmer">{#lbl_teilnehmer#}</td>
       <td class="head ext-search cookie-telefon">{#lbl_telefon#}</td>
       <td class="head ext-search cookie-email">{#lbl_email#}</td>
    </tr>

    {include file=app/list/list.konferenz_teilnehmer_profil_user.tpl}

    </table>
{/if}

{html_set_buttons modus=$button}