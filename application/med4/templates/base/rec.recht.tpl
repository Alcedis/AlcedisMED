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

<table class="formtable">

   {html_set_header caption=#head_rechteauswahl# class=head}
   {html_set_row caption=$_user_id_lbl input=$_user_id}

   {if $restrictOrg !== null}
      <tr>
         <td class="lbl">{$_org_id_lbl}</td>
         <td class="edt">
            <strong>{$SESSION.sess_org_name}</strong>
            <input type="hidden" value="{$restrictOrg}" name="org_id" />
         </td>
      </tr>
   {else}
      {html_set_row caption=$_org_id_lbl  input=$_org_id}
   {/if}

   {html_set_row caption=$_rolle_lbl   input=$_rolle}
   {html_set_header caption=#subhead_behandler# class=head}
   {html_set_row caption=$_behandler_lbl   input=$_behandler}
    
   {html_set_header caption=#subhead_erkrankung# class=head}
   
   {if $role_recht_global || ($_recht_id_value && $recht_recht_global)}
      {html_set_row caption=$_recht_global_lbl   input=$_recht_global}
   {/if}
</table>

<div class="dlist" id="dlist_erkrankung">
   <div class="add">
   {if $restrict === null}
      <input class="button" type="button" name="recht_erkrankung" value="Hinzuf&uuml;gen" onclick="execute_request(this,'rec.recht_erkrankung', null, ['recht_id'])"/>
   {else}
      <br/>
   {/if}
   </div>
</div>

{html_set_buttons modus=$button}

<div>
{$_recht_id}
{$_parent_id}
</div>