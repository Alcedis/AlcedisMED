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

<div class='current-conference'>
    <b>{#current_conference#}</b> {$conference}
</div>

{if $SESSION.sess_rolle_code == 'moderator'}

<table class="listtable" border="0">
<tr>
   <td class="head">{#lbl_patient#}</td>
   <td class="head">{#lbl_erkrankung#}</td>
   <td class="head">{#lbl_art#}</td>
   <td class="head unsortable" style="width: 95px">{#lbl_datenstand#}</td>

   {if $final == false}
		<td class="head unsortable" align="center" style="width: 110px">{#lbl_therapieplan#}</td>
	{/if}

	<td class="head unsortable" align="center" style="width: 80px">{#lbl_epikrise#}</td>

	{if $final == false}
		<td class="head unsortable" align="center" style="width: 90px">{#lbl_zuordnung#}</td>
	{/if}
</tr>

{section name=i loop=$fields.konferenz_patient_id.value}

{html_odd_even var="class" key=%i.index%}
<tr>
   <td class="{$class}">
      <div style="font-weight:bold; font-size:9pt">{$fields.name.value[i]} ({$fields.geburtsdatum.value[i]})</div>
      <div style="font-size:9pt">{$fields.org.value[i]}</div>
   </td>
   <td class="{$class}">{$fields.erkrankung.value[i]}</td>
   <td class="{$class}">{$fields.art.bez[i]}</td>
   <td class="{$class}">
      <div style="font-size:9pt">
         <div style="font-weight:bold">{$fields.datenstand_datum.value[i]}</div>
         {$fields.datenstand_uhrzeit.value[i]}
      </div>
   </td>

	{if $final == false}
		<td class="{$class}" align="center">
         <a href="index.php?page=rec.therapieplan&amp;patient_id={$fields.patient_id.value[i]}&amp;konferenz_id={$fields.konferenz_id.value[i]}&amp;from=konferenz_patient&amp;therapieplan_id={$fields.therapieplan_id.value[i]}" class="edit"></a>
      </td>
	{/if}

	<td class="{$class}" align="center">
   	<input type="submit" class="report dont_prevent_double_save button_show_file" name="action[report]" value="" alt="{$fields.konferenz_patient_id.value[i]}"/>
   </td>

	{if $final == false}
		<td class="{$class}" align="center">
         <img src="media/img/base/btn_code_reset.png"
         onclick="openDeleteDialog(this, function(){literal}{{/literal} window.location.href = {$fields.removelink.value[i]};{literal}}{/literal}, true)"
         class="dlistbutton btndelete" alt="" />
      </td>
	{/if}
</tr>
{sectionelse}
<tr>
	<td class="even no-data" colspan="9">{#no_dataset#}</td>
</tr>
{/section}
</table>

{else}

<table class="listtable">
<tr>
   <td class="head">{#lbl_patient#}</td>
   <td class="head">{#lbl_erkrankung#}</td>
   <td class="head">{#lbl_art#}</td>
   <td class="head" style="width: 95px">{#lbl_datenstand#}</td>
   <td class="head unsortable" align="center" style="width: 80px">{#lbl_epikrise#}</td>
</tr>

{section name=i loop=$fields.konferenz_patient_id.value}

{html_odd_even var="class" key=%i.index%}

<tr>
   <td class="{$class}">
      <div style="font-weight:bold">{$fields.name.value[i]} ({$fields.geburtsdatum.value[i]})</div>
      {$fields.org.value[i]}
   </td>
   <td class="{$class}">{$fields.erkrankung.value[i]}</td>
   <td class="{$class}">{$fields.art.bez[i]}</td>
   <td class="{$class}">
      <div style="font-size:9pt">
         <div style="font-weight:bold">{$fields.datenstand_datum.value[i]}</div>
         {$fields.datenstand_uhrzeit.value[i]}
      </div>
   </td>
   <td class="{$class}" align="center">
      <input type="submit" class="report dont_prevent_double_save button_show_file" name="action[report]" value="" alt="{$fields.konferenz_patient_id.value[i]}"/>
   </td>
</tr>
{sectionelse}
<tr>
   <td class="even no-data" colspan="9">{#no_dataset#}</td>
</tr>
{/section}
</table>

{/if}

<div title="{#head_rep_gen#}" id="report-dialog" style="display:none;overflow:hidden;">
<span id="report-loading">
    <br/>
    {#msg_report_loading#}
    <br/>
    <br/>
    <br/>
    <span id="loading-info" style="font-size:1.2em;display:none;font-style:italic;">{#msg_gen_report#}</span>
    <br/>
    <br/>
    <br/>
    <span style="font-size:0.8em;">{#msg_report_patience#}</span>
</span>
<div id="report-error" style="display:none;">
    <div class="err" style="text-align:left;font-size:0.8em;">
    {#msg_report_error#}
    </div>
    <input type="button" class="button close-dialog" value="{#lbl_close#}"/>
</div>
</div>
