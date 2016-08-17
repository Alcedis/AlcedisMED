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

{if $final === true}
<div class="info">
{#lbl_konferenz_abgeschlossen#}

{if $SESSION.sess_rolle_code === 'supervisor' && !$migrated}
   {#lbl_konferenz_freigeben#}
{/if}
</div>
{/if}

<table class="formtable">
{html_set_header caption=#head_konferenz#       class="head"}
{if $final == false}
    {html_set_row    caption=$_datum_lbl            input=$_datum}
    {html_set_row    caption=$_uhrzeit_beginn_lbl   input=$_uhrzeit_beginn}
    {html_set_row    caption=$_uhrzeit_ende_lbl     input=$_uhrzeit_ende}
    {html_set_row    caption=$_bez_lbl              input=$_bez}
    {html_set_row    caption=$_moderator_id_lbl     input=$_moderator_id}
    {html_set_header caption=#head_abschluss#       class="head"}
    {html_set_row    caption=$_final_lbl            input=$_final}
{else}
    {html_set_row    caption=$_datum_lbl            input=<b>$_datum_bez</b>}
    {html_set_row    caption=$_uhrzeit_beginn_lbl   input=<b>$_uhrzeit_beginn_value</b>}
    {html_set_row    caption=$_uhrzeit_ende_lbl     input=<b>$_uhrzeit_ende_value</b>}
    {html_set_row    caption=$_bez_lbl              input=<b>$_bez_value</b>}
{/if}

{html_set_header caption=#head_bem#    class="head"}
{html_set_header caption=$_bem         class="edt"}
<!-- {html_set_header caption=#head_bem_einladung#    class="head"} -->
<!--{html_set_header caption=$_bem_einladung         class="edt"} -->
<!--{html_set_header caption=#head_bem_abschluss#    class="head"} -->
<!--{html_set_header caption=$_bem_abschluss         class="edt"} -->

</table>
{html_set_buttons modus=$button}

<div>
{$_konferenz_id}
</div>
