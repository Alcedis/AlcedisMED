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

{if $show_extended_swage == false}

{html_set_html field="sbr_beurteilung,sbl_beurteilung"  html="
	<div class='msgbox' style='margin-top:1px;'>`$smarty.config.info_biopsie`</div>

	<table class='formtable msg stamps'>
   <tr>
      <td class='subhead' align='center'  colspan='4'>`$smarty.config.subhead_seite_r`</td>
      <td class='subhead' align='center' rowspan='2' style='width:280px'></td>
      <td class='subhead' align='center' colspan='4'>`$smarty.config.subhead_seite_l`</td>
   </tr>
   <tr>
      <td class='subhead' align='center' style='font-size:8pt'>`$smarty.config.lbl_ort`</td>
      <td class='subhead' align='center' style='font-size:8pt'>`$smarty.config.lbl_beurteilung`</td>
      <td class='subhead' align='center' style='font-size:8pt'>`$smarty.config.lbl_max_laenge`</td>
      <td class='subhead' align='center' style='font-size:8pt'>`$smarty.config.subhead_tumoranteil`</td>

      <td class='subhead' align='center' style='font-size:8pt'>`$smarty.config.lbl_ort`</td>
      <td class='subhead' align='center' style='font-size:8pt'>`$smarty.config.lbl_beurteilung`</td>
      <td class='subhead' align='center' style='font-size:8pt'>`$smarty.config.lbl_max_laenge`</td>
      <td class='subhead' align='center' style='font-size:8pt'>`$smarty.config.subhead_tumoranteil`</td>
   </tr>
   <tr>
      <td class='edt'>`$smarty.config.lbl_sbr`</td>
      <td class='edt'>$_sbr_beurteilung</td>
      <td class='edt'>$_sbr_1_laenge</td>
      <td class='edt'>$_sbr_1_tumoranteil`$smarty.config.lbl_anteil`</td>
      <td class='lbl' rowspan='8' align='center' valign='middle'><img src='media/img/app/prostata_stanze.png' alt='' /></td>
      <td class='edt'>`$smarty.config.lbl_sbl`</td>
      <td class='edt'>$_sbl_beurteilung</td>
      <td class='edt'>$_sbl_1_laenge</td>
      <td class='edt'>$_sbl_1_tumoranteil`$smarty.config.lbl_anteil`</td>
   </tr>
   <tr>
      <td class='edt'>`$smarty.config.lbl_blr`</td>
      <td class='edt'>$_blr_beurteilung</td>
      <td class='edt'>$_blr_1_laenge</td>
      <td class='edt'>$_blr_1_tumoranteil`$smarty.config.lbl_anteil`</td>
      <td class='edt'>`$smarty.config.lbl_bll`</td>
      <td class='edt'>$_bll_beurteilung</td>
      <td class='edt'>$_bll_1_laenge</td>
      <td class='edt'>$_bll_1_tumoranteil`$smarty.config.lbl_anteil`</td>
   </tr>
   <tr>
      <td class='edt'>`$smarty.config.lbl_br`</td>
      <td class='edt'>$_br_beurteilung</td>
      <td class='edt'>$_br_1_laenge</td>
      <td class='edt'>$_br_1_tumoranteil`$smarty.config.lbl_anteil`</td>
      <td class='edt'>`$smarty.config.lbl_bl`</td>
      <td class='edt'>$_bl_beurteilung</td>
      <td class='edt'>$_bl_1_laenge</td>
      <td class='edt'>$_bl_1_tumoranteil`$smarty.config.lbl_anteil`</td>
   </tr>
   <tr>
      <td class='edt'>`$smarty.config.lbl_tr`</td>
      <td class='edt'>$_tr_beurteilung</td>
      <td class='edt'>$_tr_1_laenge</td>
      <td class='edt'>$_tr_1_tumoranteil`$smarty.config.lbl_anteil`</td>
      <td class='edt'>`$smarty.config.lbl_tl`</td>
      <td class='edt'>$_tl_beurteilung</td>
      <td class='edt'>$_tl_1_laenge</td>
      <td class='edt'>$_tl_1_tumoranteil`$smarty.config.lbl_anteil`</td>
   </tr>
   <tr>
      <td class='edt'>`$smarty.config.lbl_mlr`</td>
      <td class='edt'>$_mlr_beurteilung</td>
      <td class='edt'>$_mlr_1_laenge</td>
      <td class='edt'>$_mlr_1_tumoranteil`$smarty.config.lbl_anteil`</td>
      <td class='edt'>`$smarty.config.lbl_mll`</td>
      <td class='edt'>$_mll_beurteilung</td>
      <td class='edt'>$_mll_1_laenge</td>
      <td class='edt'>$_mll_1_tumoranteil`$smarty.config.lbl_anteil`</td>
   </tr>
   <tr>
      <td class='edt'>`$smarty.config.lbl_mr`</td>
      <td class='edt'>$_mr_beurteilung</td>
      <td class='edt'>$_mr_1_laenge</td>
      <td class='edt'>$_mr_1_tumoranteil`$smarty.config.lbl_anteil`</td>
      <td class='edt'>`$smarty.config.lbl_ml`</td>
      <td class='edt'>$_ml_beurteilung</td>
      <td class='edt'>$_ml_1_laenge</td>
      <td class='edt'>$_ml_1_tumoranteil`$smarty.config.lbl_anteil`</td>
   </tr>
   <tr>
      <td class='edt'>`$smarty.config.lbl_ar`</td>
      <td class='edt'>$_ar_beurteilung</td>
      <td class='edt'>$_ar_1_laenge</td>
      <td class='edt'>$_ar_1_tumoranteil`$smarty.config.lbl_anteil`</td>
      <td class='edt'>`$smarty.config.lbl_al`</td>
      <td class='edt'>$_al_beurteilung</td>
      <td class='edt'>$_al_1_laenge</td>
      <td class='edt'>$_al_1_tumoranteil`$smarty.config.lbl_anteil`</td>
   </tr>
   <tr>
      <td class='edt'>`$smarty.config.lbl_alr`</td>
      <td class='edt'>$_alr_beurteilung</td>
      <td class='edt'>$_alr_1_laenge</td>
      <td class='edt'>$_alr_1_tumoranteil`$smarty.config.lbl_anteil`</td>
      <td class='edt'>`$smarty.config.lbl_all`</td>
      <td class='edt'>$_all_beurteilung</td>
      <td class='edt'>$_all_1_laenge</td>
      <td class='edt'>$_all_1_tumoranteil`$smarty.config.lbl_anteil`</td>
   </tr>
   </table>
"}


{else}
<div class="msgbox" style="margin-top:1px;">{#info_biopsie#}</div>

<table class="formtable stamps">
<tr>
	<td class="edt" colspan="12" align="center" valign="middle"><img src="media/img/app/prostata_stanze.png" alt="" /></td>
</tr>
<tr>
   <td class="subhead" align="center" colspan="6">{$_sbr_beurteilung_lbl}</td>
   <td class="subhead" align="center" colspan="6">{$_sbl_beurteilung_lbl}</td>
</tr>
<tr>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_sbr_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_sbr_anz_positiv}</td>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_sbl_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_sbl_anz_positiv}</td>
</tr>
<tr>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_sbr_1_laenge}</td>
   <td class="edt">{$_sbr_2_laenge}</td>
   <td class="edt">{$_sbr_3_laenge}</td>
   <td class="edt">{$_sbr_4_laenge}</td>
   <td class="edt">{$_sbr_5_laenge}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_sbl_1_laenge}</td>
   <td class="edt">{$_sbl_2_laenge}</td>
   <td class="edt">{$_sbl_3_laenge}</td>
   <td class="edt">{$_sbl_4_laenge}</td>
   <td class="edt">{$_sbl_5_laenge}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_sbr_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_sbr_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_sbr_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_sbr_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_sbr_5_tumoranteil}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_sbl_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_sbl_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_sbl_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_sbl_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_sbl_5_tumoranteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_sbr_1_gleason1}{#lbl_plus#}{$_sbr_1_gleason2}</td>
   <td class="edt">{$_sbr_2_gleason1}{#lbl_plus#}{$_sbr_2_gleason2}</td>
   <td class="edt">{$_sbr_3_gleason1}{#lbl_plus#}{$_sbr_3_gleason2}</td>
   <td class="edt">{$_sbr_4_gleason1}{#lbl_plus#}{$_sbr_4_gleason2}</td>
   <td class="edt">{$_sbr_5_gleason1}{#lbl_plus#}{$_sbr_5_gleason2}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_sbl_1_gleason1}{#lbl_plus#}{$_sbl_1_gleason2}</td>
   <td class="edt">{$_sbl_2_gleason1}{#lbl_plus#}{$_sbl_2_gleason2}</td>
   <td class="edt">{$_sbl_3_gleason1}{#lbl_plus#}{$_sbl_3_gleason2}</td>
   <td class="edt">{$_sbl_4_gleason1}{#lbl_plus#}{$_sbl_4_gleason2}</td>
   <td class="edt">{$_sbl_5_gleason1}{#lbl_plus#}{$_sbl_5_gleason2}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_sbr_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbr_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbr_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbr_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbr_5_gleason1_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_sbl_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbl_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbl_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbl_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbl_5_gleason1_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_sbr_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbr_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbr_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbr_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbr_5_gleason2_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_sbl_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbl_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbl_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbl_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_sbl_5_gleason2_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_sbr_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_sbr_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_sbr_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_sbr_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_sbr_5_gleason4}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_sbl_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_sbl_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_sbl_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_sbl_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_sbl_5_gleason4}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_sbr_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_sbr_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_sbr_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_sbr_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_sbr_5_diff}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_sbl_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_sbl_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_sbl_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_sbl_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_sbl_5_diff}{#lbl_anteil#}</td>
</tr>
<tr>
   <td class="subhead" align="center" colspan="6">{$_blr_beurteilung_lbl}</td>
   <td class="subhead" align="center" colspan="6"> {$_bll_beurteilung_lbl}</td>
</tr>

<tr>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_blr_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_blr_anz_positiv}</td>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_bll_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_bll_anz_positiv}</td>
</tr>
<tr>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_blr_1_laenge}</td>
   <td class="edt">{$_blr_2_laenge}</td>
   <td class="edt">{$_blr_3_laenge}</td>
   <td class="edt">{$_blr_4_laenge}</td>
   <td class="edt">{$_blr_5_laenge}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_bll_1_laenge}</td>
   <td class="edt">{$_bll_2_laenge}</td>
   <td class="edt">{$_bll_3_laenge}</td>
   <td class="edt">{$_bll_4_laenge}</td>
   <td class="edt">{$_bll_5_laenge}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_blr_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_blr_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_blr_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_blr_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_blr_5_tumoranteil}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_bll_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_bll_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_bll_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_bll_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_bll_5_tumoranteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_blr_1_gleason1}{#lbl_plus#}{$_blr_1_gleason2}</td>
   <td class="edt">{$_blr_2_gleason1}{#lbl_plus#}{$_blr_2_gleason2}</td>
   <td class="edt">{$_blr_3_gleason1}{#lbl_plus#}{$_blr_3_gleason2}</td>
   <td class="edt">{$_blr_4_gleason1}{#lbl_plus#}{$_blr_4_gleason2}</td>
   <td class="edt">{$_blr_5_gleason1}{#lbl_plus#}{$_blr_5_gleason2}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_bll_1_gleason1}{#lbl_plus#}{$_bll_1_gleason2}</td>
   <td class="edt">{$_bll_2_gleason1}{#lbl_plus#}{$_bll_2_gleason2}</td>
   <td class="edt">{$_bll_3_gleason1}{#lbl_plus#}{$_bll_3_gleason2}</td>
   <td class="edt">{$_bll_4_gleason1}{#lbl_plus#}{$_bll_4_gleason2}</td>
   <td class="edt">{$_bll_5_gleason1}{#lbl_plus#}{$_bll_5_gleason2}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_blr_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_blr_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_blr_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_blr_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_blr_5_gleason1_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_bll_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bll_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bll_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bll_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bll_5_gleason1_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_blr_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_blr_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_blr_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_blr_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_blr_5_gleason2_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_bll_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bll_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bll_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bll_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bll_5_gleason2_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_blr_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_blr_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_blr_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_blr_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_blr_5_gleason4}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_bll_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_bll_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_bll_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_bll_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_bll_5_gleason4}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_blr_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_blr_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_blr_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_blr_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_blr_5_diff}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_bll_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_bll_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_bll_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_bll_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_bll_5_diff}{#lbl_anteil#}</td>
</tr>
<tr>
   <td class="subhead" align="center" colspan="6">{$_br_beurteilung_lbl}</td>
   <td class="subhead" align="center" colspan="6"> {$_bl_beurteilung_lbl}</td>
</tr>

<tr>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_br_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_br_anz_positiv}</td>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_bl_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_bl_anz_positiv}</td>
</tr>
<tr>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_br_1_laenge}</td>
   <td class="edt">{$_br_2_laenge}</td>
   <td class="edt">{$_br_3_laenge}</td>
   <td class="edt">{$_br_4_laenge}</td>
   <td class="edt">{$_br_5_laenge}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_bl_1_laenge}</td>
   <td class="edt">{$_bl_2_laenge}</td>
   <td class="edt">{$_bl_3_laenge}</td>
   <td class="edt">{$_bl_4_laenge}</td>
   <td class="edt">{$_bl_5_laenge}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_br_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_br_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_br_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_br_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_br_5_tumoranteil}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_bl_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_bl_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_bl_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_bl_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_bl_5_tumoranteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_br_1_gleason1}{#lbl_plus#}{$_br_1_gleason2}</td>
   <td class="edt">{$_br_2_gleason1}{#lbl_plus#}{$_br_2_gleason2}</td>
   <td class="edt">{$_br_3_gleason1}{#lbl_plus#}{$_br_3_gleason2}</td>
   <td class="edt">{$_br_4_gleason1}{#lbl_plus#}{$_br_4_gleason2}</td>
   <td class="edt">{$_br_5_gleason1}{#lbl_plus#}{$_br_5_gleason2}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_bl_1_gleason1}{#lbl_plus#}{$_bl_1_gleason2}</td>
   <td class="edt">{$_bl_2_gleason1}{#lbl_plus#}{$_bl_2_gleason2}</td>
   <td class="edt">{$_bl_3_gleason1}{#lbl_plus#}{$_bl_3_gleason2}</td>
   <td class="edt">{$_bl_4_gleason1}{#lbl_plus#}{$_bl_4_gleason2}</td>
   <td class="edt">{$_bl_5_gleason1}{#lbl_plus#}{$_bl_5_gleason2}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_br_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_br_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_br_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_br_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_br_5_gleason1_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_bl_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bl_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bl_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bl_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bl_5_gleason1_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_br_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_br_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_br_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_br_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_br_5_gleason2_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_bl_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bl_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bl_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bl_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_bl_5_gleason2_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_br_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_br_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_br_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_br_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_br_5_gleason4}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_bl_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_bl_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_bl_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_bl_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_bl_5_gleason4}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_br_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_br_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_br_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_br_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_br_5_diff}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_bl_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_bl_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_bl_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_bl_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_bl_5_diff}{#lbl_anteil#}</td>
</tr>
<tr>
   <td class="subhead" align="center" colspan="6">{$_tr_beurteilung_lbl}</td>
   <td class="subhead" align="center" colspan="6"> {$_tl_beurteilung_lbl}</td>
</tr>

<tr>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_tr_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_tr_anz_positiv}</td>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_tl_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_tl_anz_positiv}</td>
</tr>
<tr>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_tr_1_laenge}</td>
   <td class="edt">{$_tr_2_laenge}</td>
   <td class="edt">{$_tr_3_laenge}</td>
   <td class="edt">{$_tr_4_laenge}</td>
   <td class="edt">{$_tr_5_laenge}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_tl_1_laenge}</td>
   <td class="edt">{$_tl_2_laenge}</td>
   <td class="edt">{$_tl_3_laenge}</td>
   <td class="edt">{$_tl_4_laenge}</td>
   <td class="edt">{$_tl_5_laenge}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_tr_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_tr_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_tr_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_tr_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_tr_5_tumoranteil}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_tl_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_tl_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_tl_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_tl_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_tl_5_tumoranteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_tr_1_gleason1}{#lbl_plus#}{$_tr_1_gleason2}</td>
   <td class="edt">{$_tr_2_gleason1}{#lbl_plus#}{$_tr_2_gleason2}</td>
   <td class="edt">{$_tr_3_gleason1}{#lbl_plus#}{$_tr_3_gleason2}</td>
   <td class="edt">{$_tr_4_gleason1}{#lbl_plus#}{$_tr_4_gleason2}</td>
   <td class="edt">{$_tr_5_gleason1}{#lbl_plus#}{$_tr_5_gleason2}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_tl_1_gleason1}{#lbl_plus#}{$_tl_1_gleason2}</td>
   <td class="edt">{$_tl_2_gleason1}{#lbl_plus#}{$_tl_2_gleason2}</td>
   <td class="edt">{$_tl_3_gleason1}{#lbl_plus#}{$_tl_3_gleason2}</td>
   <td class="edt">{$_tl_4_gleason1}{#lbl_plus#}{$_tl_4_gleason2}</td>
   <td class="edt">{$_tl_5_gleason1}{#lbl_plus#}{$_tl_5_gleason2}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_tr_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tr_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tr_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tr_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tr_5_gleason1_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_tl_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tl_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tl_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tl_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tl_5_gleason1_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_tr_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tr_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tr_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tr_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tr_5_gleason2_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_tl_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tl_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tl_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tl_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_tl_5_gleason2_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_tr_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_tr_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_tr_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_tr_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_tr_5_gleason4}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_tl_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_tl_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_tl_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_tl_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_tl_5_gleason4}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_tr_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_tr_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_tr_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_tr_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_tr_5_diff}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_tl_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_tl_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_tl_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_tl_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_tl_5_diff}{#lbl_anteil#}</td>
</tr>
<tr>
   <td class="subhead" align="center" colspan="6">{$_mlr_beurteilung_lbl}</td>
   <td class="subhead" align="center" colspan="6"> {$_mll_beurteilung_lbl}</td>
</tr>

<tr>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_mlr_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_mlr_anz_positiv}</td>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_mll_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_mll_anz_positiv}</td>
</tr>
<tr>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_mlr_1_laenge}</td>
   <td class="edt">{$_mlr_2_laenge}</td>
   <td class="edt">{$_mlr_3_laenge}</td>
   <td class="edt">{$_mlr_4_laenge}</td>
   <td class="edt">{$_mlr_5_laenge}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_mll_1_laenge}</td>
   <td class="edt">{$_mll_2_laenge}</td>
   <td class="edt">{$_mll_3_laenge}</td>
   <td class="edt">{$_mll_4_laenge}</td>
   <td class="edt">{$_mll_5_laenge}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_mlr_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_mlr_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_mlr_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_mlr_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_mlr_5_tumoranteil}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_mll_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_mll_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_mll_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_mll_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_mll_5_tumoranteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_mlr_1_gleason1}{#lbl_plus#}{$_mlr_1_gleason2}</td>
   <td class="edt">{$_mlr_2_gleason1}{#lbl_plus#}{$_mlr_2_gleason2}</td>
   <td class="edt">{$_mlr_3_gleason1}{#lbl_plus#}{$_mlr_3_gleason2}</td>
   <td class="edt">{$_mlr_4_gleason1}{#lbl_plus#}{$_mlr_4_gleason2}</td>
   <td class="edt">{$_mlr_5_gleason1}{#lbl_plus#}{$_mlr_5_gleason2}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_mll_1_gleason1}{#lbl_plus#}{$_mll_1_gleason2}</td>
   <td class="edt">{$_mll_2_gleason1}{#lbl_plus#}{$_mll_2_gleason2}</td>
   <td class="edt">{$_mll_3_gleason1}{#lbl_plus#}{$_mll_3_gleason2}</td>
   <td class="edt">{$_mll_4_gleason1}{#lbl_plus#}{$_mll_4_gleason2}</td>
   <td class="edt">{$_mll_5_gleason1}{#lbl_plus#}{$_mll_5_gleason2}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_mlr_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mlr_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mlr_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mlr_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mlr_5_gleason1_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_mll_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mll_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mll_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mll_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mll_5_gleason1_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_mlr_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mlr_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mlr_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mlr_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mlr_5_gleason2_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_mll_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mll_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mll_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mll_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mll_5_gleason2_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_mlr_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_mlr_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_mlr_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_mlr_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_mlr_5_gleason4}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_mll_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_mll_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_mll_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_mll_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_mll_5_gleason4}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_mlr_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_mlr_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_mlr_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_mlr_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_mlr_5_diff}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_mll_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_mll_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_mll_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_mll_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_mll_5_diff}{#lbl_anteil#}</td>
</tr>
<tr>
   <td class="subhead" align="center" colspan="6">{$_mr_beurteilung_lbl}</td>
   <td class="subhead" align="center" colspan="6"> {$_ml_beurteilung_lbl}</td>
</tr>

<tr>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_mr_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_mr_anz_positiv}</td>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_ml_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_ml_anz_positiv}</td>
</tr>
<tr>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_mr_1_laenge}</td>
   <td class="edt">{$_mr_2_laenge}</td>
   <td class="edt">{$_mr_3_laenge}</td>
   <td class="edt">{$_mr_4_laenge}</td>
   <td class="edt">{$_mr_5_laenge}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_ml_1_laenge}</td>
   <td class="edt">{$_ml_2_laenge}</td>
   <td class="edt">{$_ml_3_laenge}</td>
   <td class="edt">{$_ml_4_laenge}</td>
   <td class="edt">{$_ml_5_laenge}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_mr_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_mr_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_mr_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_mr_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_mr_5_tumoranteil}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_ml_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_ml_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_ml_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_ml_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_ml_5_tumoranteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_mr_1_gleason1}{#lbl_plus#}{$_mr_1_gleason2}</td>
   <td class="edt">{$_mr_2_gleason1}{#lbl_plus#}{$_mr_2_gleason2}</td>
   <td class="edt">{$_mr_3_gleason1}{#lbl_plus#}{$_mr_3_gleason2}</td>
   <td class="edt">{$_mr_4_gleason1}{#lbl_plus#}{$_mr_4_gleason2}</td>
   <td class="edt">{$_mr_5_gleason1}{#lbl_plus#}{$_mr_5_gleason2}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_ml_1_gleason1}{#lbl_plus#}{$_ml_1_gleason2}</td>
   <td class="edt">{$_ml_2_gleason1}{#lbl_plus#}{$_ml_2_gleason2}</td>
   <td class="edt">{$_ml_3_gleason1}{#lbl_plus#}{$_ml_3_gleason2}</td>
   <td class="edt">{$_ml_4_gleason1}{#lbl_plus#}{$_ml_4_gleason2}</td>
   <td class="edt">{$_ml_5_gleason1}{#lbl_plus#}{$_ml_5_gleason2}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_mr_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mr_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mr_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mr_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mr_5_gleason1_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_ml_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ml_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ml_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ml_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ml_5_gleason1_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_mr_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mr_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mr_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mr_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_mr_5_gleason2_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_ml_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ml_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ml_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ml_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ml_5_gleason2_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_mr_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_mr_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_mr_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_mr_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_mr_5_gleason4}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_ml_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_ml_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_ml_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_ml_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_ml_5_gleason4}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_mr_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_mr_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_mr_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_mr_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_mr_5_diff}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_ml_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_ml_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_ml_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_ml_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_ml_5_diff}{#lbl_anteil#}</td>
</tr>
<tr>
   <td class="subhead" align="center" colspan="6">{$_ar_beurteilung_lbl}</td>
   <td class="subhead" align="center" colspan="6"> {$_al_beurteilung_lbl}</td>
</tr>

<tr>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_ar_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_ar_anz_positiv}</td>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_al_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_al_anz_positiv}</td>
</tr>
<tr>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_ar_1_laenge}</td>
   <td class="edt">{$_ar_2_laenge}</td>
   <td class="edt">{$_ar_3_laenge}</td>
   <td class="edt">{$_ar_4_laenge}</td>
   <td class="edt">{$_ar_5_laenge}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_al_1_laenge}</td>
   <td class="edt">{$_al_2_laenge}</td>
   <td class="edt">{$_al_3_laenge}</td>
   <td class="edt">{$_al_4_laenge}</td>
   <td class="edt">{$_al_5_laenge}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_ar_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_ar_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_ar_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_ar_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_ar_5_tumoranteil}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_al_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_al_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_al_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_al_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_al_5_tumoranteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_ar_1_gleason1}{#lbl_plus#}{$_ar_1_gleason2}</td>
   <td class="edt">{$_ar_2_gleason1}{#lbl_plus#}{$_ar_2_gleason2}</td>
   <td class="edt">{$_ar_3_gleason1}{#lbl_plus#}{$_ar_3_gleason2}</td>
   <td class="edt">{$_ar_4_gleason1}{#lbl_plus#}{$_ar_4_gleason2}</td>
   <td class="edt">{$_ar_5_gleason1}{#lbl_plus#}{$_ar_5_gleason2}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_al_1_gleason1}{#lbl_plus#}{$_al_1_gleason2}</td>
   <td class="edt">{$_al_2_gleason1}{#lbl_plus#}{$_al_2_gleason2}</td>
   <td class="edt">{$_al_3_gleason1}{#lbl_plus#}{$_al_3_gleason2}</td>
   <td class="edt">{$_al_4_gleason1}{#lbl_plus#}{$_al_4_gleason2}</td>
   <td class="edt">{$_al_5_gleason1}{#lbl_plus#}{$_al_5_gleason2}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_ar_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ar_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ar_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ar_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ar_5_gleason1_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_al_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_al_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_al_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_al_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_al_5_gleason1_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_ar_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ar_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ar_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ar_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_ar_5_gleason2_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_al_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_al_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_al_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_al_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_al_5_gleason2_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_ar_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_ar_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_ar_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_ar_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_ar_5_gleason4}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_al_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_al_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_al_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_al_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_al_5_gleason4}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_ar_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_ar_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_ar_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_ar_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_ar_5_diff}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_al_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_al_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_al_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_al_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_al_5_diff}{#lbl_anteil#}</td>
</tr>
<tr>
   <td class="subhead" align="center" colspan="6">{$_alr_beurteilung_lbl}</td>
   <td class="subhead" align="center" colspan="6"> {$_all_beurteilung_lbl}</td>
</tr>

<tr>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_alr_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_alr_anz_positiv}</td>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487' colspan="5">{$_all_beurteilung}<strong>{#lbl_wpdwieviel#}</strong>{$_all_anz_positiv}</td>
</tr>
<tr>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
   <td class="subhead" align="center"></td>
   <td class="subhead" align="center">{#lbl_1#}</td>
   <td class="subhead" align="center">{#lbl_2#}</td>
   <td class="subhead" align="center">{#lbl_3#}</td>
   <td class="subhead" align="center">{#lbl_4#}</td>
   <td class="subhead" align="center">{#lbl_5#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_alr_1_laenge}</td>
   <td class="edt">{$_alr_2_laenge}</td>
   <td class="edt">{$_alr_3_laenge}</td>
   <td class="edt">{$_alr_4_laenge}</td>
   <td class="edt">{$_alr_5_laenge}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_max_laenge#}</strong></td>
   <td class="edt">{$_all_1_laenge}</td>
   <td class="edt">{$_all_2_laenge}</td>
   <td class="edt">{$_all_3_laenge}</td>
   <td class="edt">{$_all_4_laenge}</td>
   <td class="edt">{$_all_5_laenge}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_alr_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_alr_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_alr_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_alr_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_alr_5_tumoranteil}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#subhead_tumoranteil#}</strong></td>
   <td class="edt">{$_all_1_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_all_2_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_all_3_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_all_4_tumoranteil}{#lbl_anteil#}</td>
   <td class="edt">{$_all_5_tumoranteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_alr_1_gleason1}{#lbl_plus#}{$_alr_1_gleason2}</td>
   <td class="edt">{$_alr_2_gleason1}{#lbl_plus#}{$_alr_2_gleason2}</td>
   <td class="edt">{$_alr_3_gleason1}{#lbl_plus#}{$_alr_3_gleason2}</td>
   <td class="edt">{$_alr_4_gleason1}{#lbl_plus#}{$_alr_4_gleason2}</td>
   <td class="edt">{$_alr_5_gleason1}{#lbl_plus#}{$_alr_5_gleason2}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason#}</strong></td>
   <td class="edt">{$_all_1_gleason1}{#lbl_plus#}{$_all_1_gleason2}</td>
   <td class="edt">{$_all_2_gleason1}{#lbl_plus#}{$_all_2_gleason2}</td>
   <td class="edt">{$_all_3_gleason1}{#lbl_plus#}{$_all_3_gleason2}</td>
   <td class="edt">{$_all_4_gleason1}{#lbl_plus#}{$_all_4_gleason2}</td>
   <td class="edt">{$_all_5_gleason1}{#lbl_plus#}{$_all_5_gleason2}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_alr_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_alr_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_alr_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_alr_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_alr_5_gleason1_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason1_anteil#}</strong></td>
  <td class="edt">{$_all_1_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_all_2_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_all_3_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_all_4_gleason1_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_all_5_gleason1_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_alr_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_alr_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_alr_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_alr_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_alr_5_gleason2_anteil}{#lbl_anteil#}</td>
  <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason2_anteil#}</strong></td>
  <td class="edt">{$_all_1_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_all_2_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_all_3_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_all_4_gleason2_anteil}{#lbl_anteil#}</td>
  <td class="edt">{$_all_5_gleason2_anteil}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_alr_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_alr_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_alr_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_alr_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_alr_5_gleason4}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_gleason4_anteil#}</strong></td>
   <td class="edt">{$_all_1_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_all_2_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_all_3_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_all_4_gleason4}{#lbl_anteil#}</td>
   <td class="edt">{$_all_5_gleason4}{#lbl_anteil#}</td>
</tr>
<tr>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_alr_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_alr_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_alr_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_alr_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_alr_5_diff}{#lbl_anteil#}</td>
   <td style='background-color:#E6E6E6; color:#4A7487'><strong>{#lbl_diff_anteil#}</strong></td>
   <td class="edt">{$_all_1_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_all_2_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_all_3_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_all_4_diff}{#lbl_anteil#}</td>
   <td class="edt">{$_all_5_diff}{#lbl_anteil#}</td>
</tr>
</table>

{/if}

<table class="formtable msg">
<tr>
   <td class="subhead" align="center">{#lbl_ort#}</td>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td class="subhead" align="center">{#lbl_max_laenge#}</td>
   <td class="subhead" align="center">{#subhead_tumoranteil#}</td>
   <td class="subhead" align="center">{#lbl_ort#}</td>
   <td class="subhead" align="center">{#lbl_beurteilung#}</td>
   <td class="subhead" align="center">{#lbl_max_laenge#}</td>
   <td class="subhead" align="center">{#subhead_tumoranteil#}</td>
</tr>
<tr>
   <td class="edt">{$_r_beurteilung_lbl}</td>
   <td class="edt">{$_r_beurteilung}</td>
   <td class="edt">{#lbl_max#} {$_r_laenge}</td>
   <td class="edt">{$_r_tumoranteil}{#lbl_anteil#}</td>

   <td class="edt">{$_l_beurteilung_lbl}</td>
   <td class="edt">{$_l_beurteilung}</td>
   <td class="edt">{#lbl_max#} {$_l_laenge}</td>
   <td class="edt">{$_l_tumoranteil}{#lbl_anteil#}</td>
</tr>
<tr>
    <td class='edt' colspan="8" align="center">
        <div class="info-msg-clear">
            <div style="padding-bottom: 7px"><input class='button' style="margin:0" type='button' value='{#lbl_calculate#}' name='btn_calculate'/></div>
            <span style="font-size: 9pt;margin-top:5px">{#lbl_attention#}</span>
        </div>
    </td>
</tr>
<tr>
   <td class="lbl" colspan="3" >{$_r_anz_lbl}</td>
   <td class="edt">{$_r_anz}</td>

   <td class="lbl" colspan="3" >{$_l_anz_lbl}</td>
   <td class="edt">{$_l_anz}</td>
</tr>
<tr>
   <td class="lbl" colspan="3" >{#lbl_anzahl_positiver_stanzen#}</td>
   <td class="edt">{$_r_anz_positiv}</td>

   <td class="lbl" colspan="3" >{#lbl_anzahl_positiver_stanzen#}</td>
   <td class="edt">{$_l_anz_positiv}</td>
</tr>
</table>

{html_set_html field="stanzen_ges_anz,stanzen_ges_anz_positiv" html="
<table class='formtable msg'>
"}

{html_set_header field="stanzen_ges_anz,stanzen_ges_anz_positiv" caption=#subhead_gesamt# class="subhead"}
{html_set_row field="stanzen_ges_anz"           caption=$_stanzen_ges_anz_lbl             input=$_stanzen_ges_anz}
{html_set_row field="stanzen_ges_anz_positiv"   caption=$_stanzen_ges_anz_positiv_lbl     input=$_stanzen_ges_anz_positiv}
{html_set_html field="stanzen_ges_anz,stanzen_ges_anz_positiv" html="
</table>
"}
