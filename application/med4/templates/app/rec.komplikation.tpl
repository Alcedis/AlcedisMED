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

{html_set_header caption=#head_komplikation#  class="head"}
{html_set_row field="datum"            caption=$_datum_lbl              input=$_datum}
{html_set_row field="komplikation"     caption=$_komplikation_lbl       input=$_komplikation}
{html_set_row field="eingriff_id"      caption=$_eingriff_id_lbl        input=$_eingriff_id}
{html_set_row field="untersuchung_id"  caption=$_untersuchung_id_lbl    input=$_untersuchung_id}
{html_set_row field="zeitpunkt"        caption=$_zeitpunkt_lbl          input=$_zeitpunkt}
{html_set_row field="clavien_dindo"    caption=$_clavien_dindo_lbl      input=$_clavien_dindo}
{html_set_row field="ctcae"            caption=$_ctcae_lbl              input=$_ctcae}
{html_set_row field="reintervention"   caption=$_reintervention_lbl     input=$_reintervention}
{html_set_header caption=#head_behandlung#  class="head"}

{html_set_html field="antibiotikum" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl'>$_antibiotikum_lbl</td>
            <td class='edt' style='width:10%'>$_antibiotikum</td>
            <td class='edt'>$_antibiotikum_dauer $_antibiotikum_dauer_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_row field="drainage_intervent"    caption=$_drainage_intervent_lbl   input=$_drainage_intervent}
{html_set_row field="drainage_transanal"    caption=$_drainage_transanal_lbl   input=$_drainage_transanal}
{html_set_row field="sekundaerheilung"    caption=$_sekundaerheilung_lbl   input=$_sekundaerheilung}
{html_set_row field="revisionsoperation"  caption=$_revisionsoperation_lbl input=$_revisionsoperation}

{html_set_html field="wund_spuelung,wund_spreizung,wund_vac" html="
<tr>
    <td class='lbl'>
        `$smarty.config.lbl_chir_wundrevision`
    </td>
    <td class='edt'>
"}
    {html_set_html field="wund_spuelung" html="<div>$_wund_spuelung $_wund_spuelung_lbl</div>"}
    {html_set_html field="wund_spreizung" html="<div>$_wund_spreizung $_wund_spreizung_lbl</div>"}
    {html_set_html field="wund_vac" html="<div>$_wund_vac $_wund_vac_lbl</div>"}
{html_set_html field="wund_spuelung,wund_spreizung,wund_vac" html="
    </td>
</tr>
"}

{html_set_html field="transfusion" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl'>$_transfusion_lbl</td>
            <td class='edt' style='width:10%'>$_transfusion</td>
            <td class='edt'>$_transfusion_anzahl_ek $_transfusion_anzahl_ek_lbl</td>
            <td class='edt'>$_transfusion_anzahl_tk $_transfusion_anzahl_tk_lbl</td>
            <td class='edt'>$_transfusion_anzahl_ffp $_transfusion_anzahl_ffp_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_row field="gerinnungshemmer"  caption=$_gerinnungshemmer_lbl input=$_gerinnungshemmer}

{html_set_html field="beatmung" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl'>$_beatmung_lbl</td>
            <td class='edt' style='width:10%'>$_beatmung</td>
            <td class='edt'>$_beatmung_dauer $_beatmung_dauer_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_html field="intensivstation" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='margin-top:1px;'>
         <tr>
            <td class='lbl'>$_intensivstation_lbl</td>
            <td class='edt' style='width:10%'>$_intensivstation</td>
            <td class='edt'>$_intensivstation_dauer $_intensivstation_dauer_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_header caption=#head_bem#  class="head"}
{html_set_header caption=$_bem  class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
{$_komplikation_id}
{$_patient_id}
{$_erkrankung_id}
</div>
