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

<div class="info-msg" style="margin-bottom:0 !important">
     {#msg_info#}
</div>

<table width="100%">
   <tr>
      <td style="width:54%"><!--  -->{$conference}</td>
      <td style="width:10%" align="center">
         <div class="bfl-toggle">
            <input type="checkbox" name="bt-anlage_dokument" value="1"  /><br/>
            {#lbl_all#}
         </div>
      </td>
      <td style="width:12%"><!-- --></td>
      <td style="width:12%" align="center">
         <div class="bfl-toggle">
            <input type="checkbox" name="bt-anlage_epikrise" value="1"  /><br/>
            {#lbl_all#}
         </div>
      </td>
      <td>
        <!-- -->
      </td>
   </tr>
</table>

<table class="listtable bfl" summary='{$bflparam}'>
<tr>
   <td class="head ext-search cookie-teilnehmer">{#lbl_teilnehmer#}</td>
   <td class="head unsortable" align="center" style="width:72px">
      Dokumente
      <input type="text" class="bfl-buffer" name="buffer-anlage_dokument" value='{literal}{"add":{},"remove":{}}{/literal}' />
   </td>
   <td class="head unsortable" align="center" style="width:135px">{#lbl_dokument_status#}</td>
   <td class="head unsortable" align="center" style="width:55px">
      Epikrise
      <input type="text" class="bfl-buffer" name="buffer-anlage_epikrise" value='{literal}{"add":{},"remove":{}}{/literal}' />
   </td>
   <td class="head unsortable" align="center" style="width:135px">{#lbl_epikrise_status#}</td>
</tr>

{include file=app/list/list.konferenz_abschluss.tpl}

</table>

<div style="padding-top:10px">
<table  width="100%">
<tr>
      <td align="right">
         <span class="hover" onclick="send('anlage', this);" >
         {#lbl_anlagen_versenden#}
         </span>
      </td>

      <td style="width: 20px;padding-right:25px">
         <span class="link" onclick="send('anlage', this);">
            <img src="media/img/base/mail.png" alt="" />
         </span>
      </td>
   </tr>
</table>
</div>