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

{if !$export_done}
	<table class="formtable">
		{html_set_header field="sel_melde_user_id" caption=#head_dmp# class="head"}
		{html_set_row    field="sel_melde_user_id" caption=$_sel_melde_user_id_lbl input=$_sel_melde_user_id}
		{html_set_row    field="sel_datum_von"     caption=$_sel_datum_von_lbl     input=$_sel_datum_von}
		{html_set_row    field="sel_datum_bis"     caption=$_sel_datum_bis_lbl     input=$_sel_datum_bis}
		{html_set_row    field="sel_empfaenger2"   caption=$_sel_empfaenger2_lbl   input=$_sel_empfaenger2}
		<tr>
			<td class="edt" colspan="2" align="center">
				{html_set_buttons modus='export' class='button_large' table=false}
			</td>
		</tr>
	</table>
{else}
	<table class="formtable">
	   {html_set_header class="head" colspan="3" caption=#head_export_file#}
	   <tr>
	   <td class="msgbox" colspan="3">{$exportergebnis}</td>
	   </tr>
	   <tr>
         <td class="edt" width="33%">
            {if $show_xkm}
               <strong>{#versanddokumente#}</strong><br><br>
               <a class="visible-anchor" href="{$xkm_url}" target="_blank">{#exportdatei#}</a><br><br>
               <a class="visible-anchor" href="{$idx_url}" target="_blank">{#begleitdatei#}</a><br><br>
               <a class="visible-anchor" href="index.php?page=report&amp;sub=b&amp;name=dmp_begleitzettel&amp;type=pdf&amp;footer=none&amp;action=report" target="_blank" >{#begleitzettel#}</a><br><br>
               {if $view_versandliste}<a class="visible-anchor" href="index.php?page=report&amp;sub=b&amp;name=dmp_versandliste&amp;type=pdf&amp;footer=none&amp;action=report" target="_blank" >{#versandliste#}</a>{/if}<br><br>
            {/if}
         </td>
         <td class="edt" width="33%">
            <strong>{#interne_dokumente#}</strong><br><br>
            {if $show_xkm}<a class="visible-anchor" href="{$zip_url}" target="_blank">{#zipdatei#}</a>{/if}<br><br>
            {if count($dmp_ed_patdat)}<a class="visible-anchor" href="index.php?page=dmp_popups&type=export_statistik_ed&file={$export_ed_protokol_file}" target="_blank" >{#exportstatistik_ed#}</a>{/if}<br><br>
            {if count($dmp_fd_patdat)}<a class="visible-anchor" href="index.php?page=dmp_popups&type=export_statistik_fd&file={$export_fd_protokol_file}" target="_blank" >{#exportstatistik_fd#}</a>{/if}<br><br>
            {if $show_xkm}<a class="visible-anchor" href="index.php?page=dmp_popups&type=verschluesselungsprotokoll&file={$xkm_protokol_filename}" target="_blank" >{#verschluesselungsprotokoll#}</a>{/if}<br><br>
         </td>
         <td class="edt" width="34%">
            {$info_beschriftung}
            <br><br>
         </td>
		</tr>
   </table>
   <table class="formtable">
	   {html_set_header class="head" colspan="9" caption=#head_liste_ed#}
	   <tr>
	   	<td class="sub_head">{#versich_nr#}</td>
	   	<td class="sub_head">{#patient#}</td>
	   	<td class="sub_head">{#geburtsdatum#}</td>
	   	<td class="sub_head">{#fall_nr#}</td>
	   	<td class="sub_head">{#dmp_dokument_id#}</td>
	   	<td class="sub_head">{#doku_datum#}</td>
	   	<td class="sub_head">{#unterschrift_datum#}</td>
	   	<td class="sub_head">{#exportstatus#}</td>
	   	<td class="sub_head">{#dokubogen#}</td>
	   </tr>
	   {foreach from=$dmp_ed_patdat item=patient_ed}
   	   <tr>
   	   	<td class="edt">{$patient_ed.kv_nr}</td>
   	   	<td class="edt">{$patient_ed.nachname}, {$patient_ed.vorname}</td>
   	   	<td class="edt">{$patient_ed.geburtsdatum}</td>
   	   	<td class="edt">{$patient_ed.fall_nr}</td>
   	   	<td class="edt">{$patient_ed.dmp_dokument_id}</td>
   	   	<td class="edt">{$patient_ed.doku_datum}</td>
   	   	<td class="edt">{$patient_ed.unterschrift_datum}</td>
   	   	{if $patient_ed.status=="Ok"}
   	   		<td class="edt"><span>{$patient_ed.status}</span></td>
   	   	{else}
   	   	    <td class="edt"><a class="visible-anchor" href="index.php?page=dmp_popups&type=dmp_log_file&file={$patient_ed.protokol_file}" target="_blank" >{$patient_ed.status}</a></td>
   	   	{/if}
   	   	<td class="edt"><a href="index.php?page=dmp_popups&type=dmp_eb_bogen&id={$patient_ed.dmp_brustkrebs_eb_id}" target="_blank" ><img border="0" src="media/img/app/dmp/btn_edmp_small.gif" alt="DMP-Bogen"></a></td>
   	   </tr>
   	{foreachelse}
   	   <tr>
   	      <td class="edt no_data" colspan="9">{#msg_no_data#}</td>
   	   </tr>
   	{/foreach}
	   {html_set_header class="head" colspan="9" caption=#head_liste_fd#}
	   <tr>
	   	<td class="sub_head">{#versich_nr#}</td>
	   	<td class="sub_head">{#patient#}</td>
	   	<td class="sub_head">{#geburtsdatum#}</td>
	   	<td class="sub_head">{#fall_nr#}</td>
	   	<td class="sub_head">{#dmp_dokument_id#}</td>
	   	<td class="sub_head">{#doku_datum#}</td>
	   	<td class="sub_head">{#unterschrift_datum#}</td>
	   	<td class="sub_head">{#exportstatus#}</td>
	   	<td class="sub_head">{#dokubogen#}</td>
	   </tr>
	   {foreach from=$dmp_fd_patdat item=patient_fd}
   	   <tr>
   	   	<td class="edt">{$patient_fd.kv_nr}</td>
   	   	<td class="edt">{$patient_fd.nachname}, {$patient_fd.vorname}</td>
   	   	<td class="edt">{$patient_fd.geburtsdatum}</td>
   	   	<td class="edt">{$patient_fd.fall_nr}</td>
   	   	<td class="edt">{$patient_fd.dmp_dokument_id}</td>
   	   	<td class="edt">{$patient_fd.doku_datum}</td>
   	   	<td class="edt">{$patient_fd.unterschrift_datum}</td>
   	   	{if $patient_fd.status=="Ok"}
   	   		<td class="edt"><span>{$patient_fd.status}</span></td>
   	   	{else}
   	   		<td class="edt"><a class="visible-anchor" href="index.php?page=dmp_popups&type=dmp_log_file&file={$patient_fd.protokol_file}" target="_blank" >{$patient_fd.status}</a></td>
   	   	{/if}
   	   	<td class="edt"><a href="index.php?page=dmp_popups&type=dmp_fb_bogen&id={$patient_fd.dmp_brustkrebs_fb_id}" target="_blank" ><img border="0" src="media/img/app/dmp/btn_edmp_small.gif" alt="DMP-Bogen"></a></td>
   	   </tr>
   	{foreachelse}
   	   <tr>
   	      <td class="edt no_data" colspan="9">{#msg_no_data#}</td>
   	   </tr>
   	{/foreach}
   </table>
{/if}
