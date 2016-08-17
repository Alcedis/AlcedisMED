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

{if $viewOrg === true}
    <table class="formtable">
        {html_set_header  caption=#head_organisation# class='head'}
        {if strlen($_patient_id_value)}
            <tr>
                <td class="lbl">{$_org_id_lbl}</td>
                <td class="edt"><b>{$_org_id_bez}</b>
                    <input type="hidden" name="org_id" value="{$_org_id_value}" />
                </td>
            </tr>
        {else}
            {html_set_row caption=$_org_id_lbl input=$_org_id}
        {/if}
    </table>
{/if}

<table class="formtable">
{html_set_header  caption=#head_patient# class='head'}
<tr>
    <td class="lbl">{$_patient_nr_lbl}</td>
    <td class="edt">
        {if $autoPatientId}
            <b>{$autoPatientId}</b>
            <input type="hidden" name="patient_nr" value="{$autoPatientId}" />
        {else}
            {$_patient_nr}
        {/if}
    </td>
</tr>
{html_set_row caption=$_titel_lbl                         input=$_titel            field="titel"}
{html_set_row caption=$_adelstitel_lbl                    input=$_adelstitel       field="adelstitel"}
{html_set_row caption=$_nachname_lbl                      input=$_nachname         field="nachname"}
{html_set_row caption=$_vorname_lbl                       input=$_vorname          field="vorname"}
{html_set_row caption=$_geschlecht_lbl                    input=$_geschlecht       field="geschlecht"}
{html_set_row caption=$_geburtsdatum_lbl                  input=$_geburtsdatum     field="geburtsdatum"}
{html_set_row caption=$_geburtsname_lbl                   input=$_geburtsname      field="geburtsname"}
{html_set_row caption=$_geburtsort_lbl                    input=$_geburtsort       field="geburtsort"}
{html_set_row caption=$_datenaustausch_lbl                input=$_datenaustausch   field="datenaustausch"   add=#datenaustausch_add#}
{html_set_row caption=$_datenspeicherung_lbl              input=$_datenspeicherung field="datenspeicherung" add=#datenspeicherung_add#}
{html_set_row caption=$_datenversand_lbl                  input=$_datenversand     field="datenversand"     add=#datenversand_add#}
{html_set_row caption=$_krebsregister_lbl                 input=$_krebsregister    field="krebsregister"}

{html_set_header caption=#head_kontakt# class='head' field="strasse,plz,hausnr,adrzusatz,ort,telefon,telefax,email,staat"}
{html_set_row caption=$_strasse_lbl                       input=$_strasse   add="$_hausnr" field="strasse"}
{html_set_row caption=$_adrzusatz_lbl                     input=$_adrzusatz                field="adrzusatz"}
{html_set_row caption=$_plz_lbl                           input=$_plz       add="$_ort"    field="plz"}
{html_set_row caption=$_land_lbl                          input=$_land                     field="land"}
{html_set_row caption=$_telefon_lbl                       input=$_telefon                  field="telefon"}
{html_set_row caption=$_telefax_lbl                       input=$_telefax                  field="telefax"}
{html_set_row caption=$_email_lbl                         input=$_email                    field="email"}
{html_set_row caption=$_staat_lbl                         input=$_staat                    field="staat"}

{html_set_header caption=#head_krankenv# class='head' field="kv_iknr,kv_nrkv_status,kv_statusergaenzung,kv_gueltig_bis,kv_einlesedatum"}
{html_set_row caption=$_kv_iknr_lbl                       input=$_kv_iknr                       field="kv_iknr"}
{html_set_row caption=$_kv_abrechnungsbereich_lbl         input=$_kv_abrechnungsbereich         field="kv_abrechnungsbereich"}
{html_set_row caption=$_kv_nr_lbl                         input=$_kv_nr                         field="kv_nr"}
{html_set_row caption=$_kv_fa_lbl                         input=$_kv_fa                         field="kv_fa"}
{html_set_row caption=$_kv_status_lbl                     input=$_kv_status                     field="kv_status"}
{html_set_row caption=$_kv_statusergaenzung_lbl           input=$_kv_statusergaenzung           field="kv_statusergaenzung"}
{html_set_row caption=$_kv_wop_lbl                        input=$_kv_wop                        field="kv_wop"}
{html_set_row caption=$_kv_besondere_personengruppe_lbl   input=$_kv_besondere_personengruppe   field="kv_besondere_personengruppe"}
{html_set_row caption=$_kv_dmp_kennzeichnung_lbl          input=$_kv_dmp_kennzeichnung          field="kv_dmp_kennzeichnung"}
{html_set_row caption=$_kv_versicherungsschutz_beginn_lbl input=$_kv_versicherungsschutz_beginn field="kv_versicherungsschutz_beginn"}
{html_set_row caption=$_kv_versicherungsschutz_ende_lbl   input=$_kv_versicherungsschutz_ende   field="kv_versicherungsschutz_ende"}
{html_set_row caption=$_kv_gueltig_bis_lbl                input=$_kv_gueltig_bis                field="kv_gueltig_bis"}
{html_set_row caption=$_kv_einlesedatum_lbl               input=$_kv_einlesedatum               field="kv_einlesedatum"}

{html_set_header  caption=#head_bem#                      class='head'  field="bem"}
{html_set_header  caption=$_bem                           class='edt'   field="bem"}

</table>
{html_set_buttons modus=$button}

<div>
{if $viewOrg == false}
    <input type="hidden" name="org_id" value="{$_org_id_value}" />
{/if}
{$_patient_id}
</div>
