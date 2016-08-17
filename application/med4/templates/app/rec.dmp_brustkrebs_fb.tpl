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

{html_set_header  class="msgbox"             caption=#info_fd#}
{html_set_header  field="melde_user_id"      caption=#head_admin_daten#       class="head"}
{html_set_row     field="melde_user_id"      caption=$_melde_user_id_lbl      input=$_melde_user_id}
{html_set_row     field="arztwechsel"        caption="<strong>$_arztwechsel_lbl</strong>"        input=$_arztwechsel add=#lbl_arztwechsel#}
{html_set_row     field="doku_datum"         caption=$_doku_datum_lbl         input=$_doku_datum}
{html_set_row     field="unterschrift_datum" caption="<strong>$_unterschrift_datum_lbl</strong>" input=$_unterschrift_datum}

{html_set_header  field="kv_iknr"                  caption=#head_kvd#                     class="head"}
{html_set_row     field="kv_iknr"                  caption="$_kv_iknr_lbl <input type='submit' class='button_large' name='action[get_kv]' value='aktualisieren' alt='aktualisieren'>"                 input="<strong>$_kv_iknr_bez</strong> <input type='hidden' name='kv_iknr' value='$_kv_iknr_value'/>"}
{html_set_row     field="versich_nr"               caption=$_versich_nr_lbl               input="<strong>$_versich_nr_value</strong> <input type='hidden' name='versich_nr' value='$_versich_nr_value'/>"}
{html_set_row     field="versich_status"           caption=$_versich_status_lbl           input="<strong>$_versich_status_bez</strong> <input type='hidden' name='versich_status' value='$_versich_status_value'/>"}
{html_set_row     field="versich_statusergaenzung" caption=$_versich_statusergaenzung_lbl input="<strong>$_versich_statusergaenzung_bez</strong> <input type='hidden' name='versich_statusergaenzung' value='$_versich_statusergaenzung_value'/>"}
{html_set_row     field="vk_gueltig_bis"           caption=$_vk_gueltig_bis_lbl           input="<strong>$_vk_gueltig_bis_bez</strong> <input type='hidden' name='vk_gueltig_bis' value='$_vk_gueltig_bis_bez'/>"}
{html_set_row     field="kvk_einlesedatum"         caption=$_kvk_einlesedatum_lbl         input="<strong>$_kvk_einlesedatum_bez</strong> <input type='hidden' name='kvk_einlesedatum' value='$_kvk_einlesedatum_bez'/>"}

{html_set_header  caption=#head_einschreibung#  class="head"}
{html_set_row     caption="$_einschreibung_grund_lbl <input type='submit' class='button_large' name='action[get_dmp]' value='Daten aktualisieren' alt='Daten aktualisieren'>" input="<strong>$einschreibung_grund_bez</strong> <input type='hidden' name='einschreibung_grund' value='$einschreibung_grund_bez'/>"}

{html_set_header  field="pth_fertig"         caption=#head_behandlung_nach_op#   class="head"}
{html_set_row     field="pth_fertig"         caption="<strong>$_pth_fertig_lbl</strong>"            input=$_pth_fertig}
{html_set_row     field="primaer_strahlen"   caption=$_primaer_strahlen_lbl      input=$_primaer_strahlen}
{html_set_row     field="primaer_chemo"      caption=$_primaer_chemo_lbl         input=$_primaer_chemo}
{html_set_row     field="primaer_endo"       caption=$_primaer_endo_lbl          input=$_primaer_endo}

{html_set_header  field="neu_rezidiv_nein"   caption=#head_seit_letzter_doku#    class="head"}
{html_set_html field="neu_rezidiv_nein" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width: 35%;'>`$smarty.config.lbl_mani_rezidiv`</td>
            <td class='edt'>$_neu_rezidiv_nein $_neu_rezidiv_nein_lbl</td>
            <td class='edt'>$_neu_rezidiv_intra $_neu_rezidiv_intra_lbl</td>
            <td class='edt'>$_neu_rezidiv_thorax $_neu_rezidiv_thorax_lbl</td>
            <td class='edt'>$_neu_rezidiv_axilla $_neu_rezidiv_axilla_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_row  field="neu_rezidiv_datum"  caption=$_neu_rezidiv_datum_lbl  input=$_neu_rezidiv_datum}
{html_set_row  field="neu_kontra_nein"    caption=#lbl_mani_brustkrebs#    input=$_neu_kontra_nein add=$_neu_kontra_nein_lbl}
{html_set_row  field="neu_kontra_datum"   caption=$_neu_kontra_datum_lbl   input=$_neu_kontra_datum}
{html_set_html field="neu_metast_nein" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width: 35%;' rowspan='2'>`$smarty.config.lbl_mani_metastasen`</td>
            <td class='edt'>$_neu_metast_nein $_neu_metast_nein_lbl</td>
            <td class='edt'>$_neu_metast_leber $_neu_metast_leber_lbl</td>
            <td class='edt'>$_neu_metast_lunge $_neu_metast_lunge_lbl</td>
         </tr><tr>
            <td class='edt'>$_neu_metast_knochen $_neu_metast_knochen_lbl</td>
            <td class='edt' colspan='2'>$_neu_metast_andere $_neu_metast_andere_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_row  field="neu_metast_datum"   caption=$_neu_metast_datum_lbl   input=$_neu_metast_datum}
{html_set_row  field="lymphoedem"         caption=$_lymphoedem_lbl         input=$_lymphoedem}

{html_set_header  field="rez_status_cr"   caption=#head_behandlung_fort#   class="head"}
{html_set_html field="rez_status_cr" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width: 35%;'><strong>`$smarty.config.lbl_behandlungsstatus`</strong></td>
            <td class='edt'>$_rez_status_cr $_rez_status_cr_lbl</td>
            <td class='edt'>$_rez_status_pr $_rez_status_pr_lbl</td>
            <td class='edt'>$_rez_status_nc $_rez_status_nc_lbl</td>
            <td class='edt'>$_rez_status_pd $_rez_status_pd_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_html field="rez_th_praeop" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'  style='margin-top:1px;'>
         <tr>
            <td class='lbl' style='width: 35%;' rowspan='3'><strong>`$smarty.config.lbl_therapie_rezidiv`</strong></td>
            <td class='edt'>$_rez_th_praeop $_rez_th_praeop_lbl</td>
            <td class='edt'>$_rez_th_exzision $_rez_th_exzision_lbl</td>
            <td class='edt'>$_rez_th_mastektomie $_rez_th_mastektomie_lbl</td>
         </tr><tr>
            <td class='edt'>$_rez_th_strahlen $_rez_th_strahlen_lbl</td>
            <td class='edt'>$_rez_th_chemo $_rez_th_chemo_lbl</td>
            <td class='edt'>$_rez_th_endo $_rez_th_endo_lbl</td>
         </tr><tr>
            <td class='edt'>$_rez_th_andere $_rez_th_andere_lbl</td>
            <td class='edt' colspan='2'>$_rez_th_keine $_rez_th_keine_lbl</td>
         </tr>
         <tr>
            <td class='lbl' style='width: 35%;' rowspan='2'><strong>`$smarty.config.lbl_therapie_metastasen`</strong></td>
            <td class='edt'>$_metast_th_operativ $_metast_th_operativ_lbl</td>
            <td class='edt'>$_metast_th_strahlen $_metast_th_strahlen_lbl</td>
            <td class='edt'>$_metast_th_chemo $_metast_th_chemo_lbl</td>
         </tr><tr>
            <td class='edt'>$_metast_th_endo $_metast_th_endo_lbl</td>
            <td class='edt'>$_metast_th_andere $_metast_th_andere_lbl</td>
            <td class='edt'>$_metast_th_keine $_metast_th_keine_lbl</td>
         </tr>
         <tr>
            <td class='lbl' style='width: 35%;'>`$smarty.config.lbl_bisphos`</td>
            <td class='edt'>$_metast_bip_ja $_metast_bip_ja_lbl</td>
            <td class='edt'>$_metast_bip_nein $_metast_bip_nein_lbl</td>
            <td class='edt'>$_metast_bip_kontra $_metast_bip_kontra_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_header  field="sonst_schmerz_ja"   caption=#head_sonstige#   class="head"}
{html_set_html field="sonst_schmerz_ja" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width: 35%;'>`$smarty.config.lbl_tumorschmerz`</td>
            <td class='edt'>$_sonst_schmerz_ja $_sonst_schmerz_ja_lbl</td>
            <td class='edt'>$_sonst_schmerz_nein $_sonst_schmerz_nein_lbl</td>
            <td class='edt'>$_sonst_schmerz_ne $_sonst_schmerz_ne_lbl</td>
         </tr><tr>
            <td class='lbl' style='width: 35%;'>`$smarty.config.lbl_mammo`</td>
            <td class='edt'>$_sonst_mammo_ja $_sonst_mammo_ja_lbl</td>
            <td class='edt'>$_sonst_mammo_nein $_sonst_mammo_nein_lbl</td>
            <td class='edt'>$_sonst_mammo_ne $_sonst_mammo_ne_lbl</td>
         </tr><tr>
            <td class='lbl' style='width: 35%;'>`$smarty.config.lbl_psychosozial`</td>
            <td class='edt'>$_sonst_psycho_ja $_sonst_psycho_ja_lbl</td>
            <td class='edt'>$_sonst_psycho_nein $_sonst_psycho_nein_lbl</td>
            <td class='edt'>$_sonst_psycho_abgelehnt $_sonst_psycho_abgelehnt_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_row  field="termin_datum" caption="<strong>$_termin_datum_lbl</strong>" input=$_termin_datum}

{html_set_header field="bem" caption=#head_bem#    class="head"}
{html_set_header field="bem" caption=$_bem         class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
{$_dmp_brustkrebs_fb_id}
{$_dmp_brustkrebs_eb_id}
{$_patient_id}
{$_erkrankung_id}
</div>