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

<table width="100%">
   <tr>
      <td>
         <div id="buttonbar" style="width:300px !important">
            <a href="index.php?page=list.konferenz_teilnehmer_zuweisen{$link_param}" class="button">Hinzufügen</a>
         </div>
      </td>

      {if count($teilnehmer_profil)}
	      <td align="right">
            <select name="teilnehmer_profil_id"{if $error_on_dropdown == true} style="background:#faa"{/if}>
                  <option value=""><!--  --></option>
               {foreach from=$teilnehmer_profil item=profil}
                  <option value="{$profil.konferenz_teilnehmer_profil_id}">{$profil.bez}</option>
               {/foreach}
            </select>
	      </td>
	      <td align="right" style="width:100px">
				<input type="submit" class="button" name="action[profil]" value="Profil laden"/>
	      </td>
      {/if}
   </tr>
</table>
<table width="100%">
   <tr>
      <td style="width:57.7%"><!--  -->{$conference}</td>
      <td style="width:9%" align="center">
         <div class="bfl-toggle">
            <input type="checkbox" name="bt-email" value="1"  /><br/>
            {#lbl_all#}
         </div>
      </td>
      <td>
        <!-- -->
      </td>
      <td style="width:9%" align="center">
         <div class="bfl-toggle">
            <input type="checkbox" name="bt-teilgenommen" value="1"  /><br/>
            {#lbl_all#}
         </div>
      </td>
      <td style="width:9%" align="center">
         <div class="bfl-toggle">
            <input type="checkbox" name="bt-entfernen" value="1"  /><br/>
            {#lbl_all#}
         </div>
      </td>
   </tr>
</table>
<table class="listtable bfl" summary='{$bflparam}'>
<tr>
   <td class="head ext-search cookie-teilnehmer" style="width:57.3%">{#lbl_teilnehmer#}</td>
   <td class="head unsortable" align="center" style="width:9%">
      {#lbl_email_senden#}
      <input type="text" class="bfl-buffer" name="buffer-email" value='{literal}{"add":{},"remove":{}}{/literal}' />
   </td>
   <td class="head unsortable" >{#lbl_email_status#}</td>
   <td class="head unsortable" align="center" style="width:9%">
      {#lbl_teilgenommen#}
      <input type="text" class="bfl-buffer" name="buffer-teilgenommen" value='{literal}{"add":{},"remove":{}}{/literal}' />
   </td>
   <td class="head unsortable" style="width:9%" align="center">
      {#lbl_entfernen#}
      <input type="text" class="bfl-buffer" name="buffer-entfernen" value='{literal}{"add":{},"remove":{}}{/literal}' />
   </td>
</tr>

{include file=app/list/list.konferenz_teilnehmer.tpl}

</table>

<div style="padding-top:10px">
<table  width="100%">
<tr>

      <td align="right">
         <span class="hover" onclick="send('email', this);" >
         {#lbl_email_versenden#}
         </span>
      </td>

      <td style="width: 20px">
         <span class="link" onclick="send('email', this);">
            <img src="media/img/base/mail.png" alt="" />
         </span>
      </td>

      <td align="right" style="width: 200px">
         <span class="hover" onclick="send('confirm', this);">
            {#lbl_teilnahme#}
         </span>
      </td>

      <td style="width: 20px">
         <span class="link" onclick="send('confirm', this);">
            <img src="media/img/base/ok_medium.png" alt="" />
         </span>
      </td>

      <td align="right" style="width: 200px">
         <span class="hover" onclick="send('remove', this);">
            {#lbl_remove#}
         </span>
      </td>
      <td style="width: 20px;padding-right:25px">
         <span class="link" onclick="send('remove', this);">
            <img src="media/img/base/remove.png" alt="" />
         </span>
      </td>
   </tr>
</table>
</div>