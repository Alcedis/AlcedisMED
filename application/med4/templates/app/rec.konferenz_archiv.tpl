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

<!-- Konferenz -->
<table class="formtable">
    <tr>
        <td class="head">{#head_konferenztitel#}</td>
    </tr>
    <tr>
        <td class="even">{$titel}</td>
    </tr>
</table>
<br/>

<!-- Patient -->
<table class="formtable">
<tr>
   <td class="head" colspan="6">{#head_patienten#}</td>
</tr>
<tr>
	<td class="subhead">{#lbl_patient#}</td>
	<td class="subhead" style="width:190px">{#lbl_erkrankung#}</td>
	<td class="subhead">{#lbl_art#}</td>
	<td class="subhead">{#lbl_geschlecht#}</td>
	<td class="subhead" align="center" style="width:110px">{#lbl_geburtsdatum#}</td>
	<td class="subhead" align="center" style="width:80px">{#lbl_protokoll#}</td>
</tr>

{section name=l loop=$fieldsKonferenzPatient.konferenz_patient_id.value}
{html_odd_even var="class" key=%l.index%}
<tr>
	<td class="{$class}">{$fieldsKonferenzPatient.nachname.value[l]}, {$fieldsKonferenzPatient.vorname.value[l]}</td>
   <td class="{$class}">{$fieldsKonferenzPatient.erkrankung.bez[l]}</td>
	<td class="{$class}">{$fieldsKonferenzPatient.art.bez[l]}</td>

	<td class="{$class}">{$fieldsKonferenzPatient.geschlecht.bez[l]}</td>
	<td class="{$class}" align="center">{$fieldsKonferenzPatient.geburtsdatum.value[l]}</td>


   <td class="{$class}" align="center">
   	<input type="submit" class="report dont_prevent_double_save button_show_file" name="action[report]" value="" alt="{$fieldsKonferenzPatient.param.value[l]}"/>
   </td>
</tr>
{sectionelse}
<tr>
	<td class="odd" colspan="6">{#lbl_keine_patienten#}</td>
</tr>
{/section}
</table>
<br/>

<!-- Dokumente -->
<table class="formtable">
<tr>
   <td class="head" colspan="4">{#head_dokumente#}</td>
</tr>
<tr>
   <td class="subhead">{#lbl_dokument_titel#}</td>
   <td class="subhead">{#lbl_beschreibung#}</td>
   <td class="subhead">{#lbl_patient_zugeordnet#}</td>
   <td class="subhead" style="width:80px" align="center">{#lbl_dokument#}</td>
</tr>

{section name=i loop=$fieldsKonferenzDokument.konferenz_dokument_id.value}
{html_odd_even var="class" key=%i.index%}
<tr>
   <td class="{$class}">{$fieldsKonferenzDokument.bez.value[i]}</td>
   <td class="{$class}">{$fieldsKonferenzDokument.bem.value[i]}</td>
   <td class="{$class}">{$fieldsKonferenzDokument.patient.value[i]}</td>

   <td class="{$class}" align="center">
      <input type="submit" class="report dont_prevent_double_save button_show_{$fieldsKonferenzDokument.type.value[i]}" name="action[report]" value="" alt="{$fieldsKonferenzDokument.param.value[i]}"/> 
   </td>
</tr>
{sectionelse}
    <tr>
    <td class="odd" colspan="4">{#lbl_keine_dokumente#}</td>
    </tr>
{/section}
</table>
<br/>

<!-- Teilnehmer -->
<table class="formtable">
<tr>
   <td class="head" colspan="5">{#head_teilnehmer#}</td>
</tr>
<tr>
   <td class="subhead">{#lbl_teilnehmer#}</td>
   <td class="subhead">{#lbl_adresse#}</td>
   <td class="subhead">{#lbl_telefon#}</td>
   <td class="subhead">{#lbl_email#}</td>
   <td class="subhead">{#lbl_fachabteilung#}</td>
</tr>

{section name=k loop=$fieldsKonferenzTeilnehmer.konferenz_teilnehmer_id.value}
{html_odd_even var="class" key=%k.index%}
<tr>
   <td class="{$class}">{$fieldsKonferenzTeilnehmer.name.value[k]} </td>
   <td class="{$class}">{$fieldsKonferenzTeilnehmer.strasse.value[k]}  </td>
   <td class="{$class}">{$fieldsKonferenzTeilnehmer.telefon.value[k]}  </td>
   <td class="{$class}">{$fieldsKonferenzTeilnehmer.email.value[k]}    </td>
   <td class="{$class}">{$fieldsKonferenzTeilnehmer.fachabteilung.bez[k]}</td>
</tr>
{sectionelse}
<tr>
   <td class="odd" colspan="5">{#lbl_keine_teilnehmer#}</td>
</tr>
{/section}
</table>

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