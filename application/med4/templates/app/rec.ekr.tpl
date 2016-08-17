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
{html_set_header caption=#head_meldung# class="head"}

{html_set_row field="datum"   caption=$_datum_lbl  input=$_datum}
{html_set_row field="user_id"   caption=$_user_id_lbl  input=$_user_id}

{html_set_row field="meldebegruendung"   caption=$_meldebegruendung_lbl  input=$_meldebegruendung}

{if $bundesland == 'BW'}
   {html_set_row field="wandlung_diagnose" caption=$_wandlung_diagnose_lbl  input=$_wandlung_diagnose}
{/if}

{if $bundesland == 'HE'}
   {html_set_row field="grund" caption=$_grund_lbl  input=$_grund}
{/if}

{if $bundesland == 'SH'}
   {html_set_row field="unterrichtet_krankheit" caption=$_unterrichtet_krankheit_lbl  input=$_unterrichtet_krankheit}
{/if}

{if in_array($bundesland, array('BE', 'BB', 'MV', 'TH', 'SN', 'ST'))}
    {html_set_row field="einzugsgebiet"   caption=$_einzugsgebiet_lbl  input=$_einzugsgebiet}
{/if}


{html_set_row field="datum_einverstaendnis" caption=$_datum_einverstaendnis_lbl  input=$_datum_einverstaendnis}

{if $bundesland == 'RP'}
   {html_set_row field="abteilung" caption=$_abteilung_lbl  input=$_abteilung}
{/if}

{if $bundesland == 'SH'}
   {html_set_row field="sh_wohnort"   caption=$_sh_wohnort_lbl  input=$_sh_wohnort}
   {html_set_row field="weiterleitung"   caption=$_weiterleitung_lbl  input=$_weiterleitung}
   {html_set_row field="weiterleitung_datum"   caption=$_weiterleitung_datum_lbl  input=$_weiterleitung_datum}
   {html_set_row field="forschungsvorhaben"   caption=$_forschungsvorhaben_lbl  input=$_forschungsvorhaben}
   {html_set_row field="forschungsvorhaben_datum"   caption=$_forschungsvorhaben_datum_lbl  input=$_forschungsvorhaben_datum}
   {html_set_row field="vermutete_tumorursachen"   caption=$_vermutete_tumorursachen_lbl  input=$_vermutete_tumorursachen}
{/if}

{if $bundesland == 'NI'}
	 {html_set_row field="export_for_onkeyline" caption=$_export_for_onkeyline_lbl input=$_export_for_onkeyline}
{/if}

{if $bundesland == 'RP'}
   {html_set_header caption=#head_nachsorge# class="head"}
   {html_set_row field="nachsorgeprogramm"   caption=$_nachsorgeprogramm_lbl  input=$_nachsorgeprogramm}
   {html_set_row field="nachsorgepassnr"   caption=$_nachsorgepassnr_lbl  input=$_nachsorgepassnr}
   {html_set_row field="nachsorge_user_id"   caption=$_nachsorge_user_id_lbl  input=$_nachsorge_user_id}
   {html_set_row field="nachsorgetermin"   caption=$_nachsorgetermin_lbl  input=$_nachsorgetermin}
{/if}

{html_set_header caption=#head_bem# class="head"}

{if in_array($bundesland, array('BE', 'BB', 'MV', 'TH', 'SN', 'ST'))}
   {html_set_row field="mitteilung"   caption=$_mitteilung_lbl  input=$_mitteilung}
{/if}

{html_set_header caption=$_bem class="edt"}
</table>
{html_set_buttons modus=$button}

<div>
{$_ekr_id}
{$_erkrankung_id}
{$_patient_id}
</div>
