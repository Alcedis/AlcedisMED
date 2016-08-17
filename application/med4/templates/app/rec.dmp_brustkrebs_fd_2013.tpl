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

{html_set_header  class="msgbox"             caption=#info_fd#}

<table class="formtable">
    {html_set_header caption=#head_getData#  class="head"}
    <tr>
        <td class='lbl' colspan='2'>
            <input style="margin-right:20px" type='submit' class='button_large btnconfirm' name='action[get_dmp]' value="{#lbl_getData#}" alt="{#lbl_getData#}"">
        </td>
    </tr>
</table>

<table class="formtable">

{html_set_header  field="melde_user_id"      caption=#head_admin_daten#       class="head"}
{html_set_row     field="melde_user_id"      caption=$_melde_user_id_lbl      input=$_melde_user_id}
{html_set_row     field="arztwechsel"        caption="<strong>$_arztwechsel_lbl</strong>"        input=$_arztwechsel add=#lbl_arztwechsel#}
{if strlen($_dmp_brustkrebs_fd_2013_id_value) > 0}
    {html_set_row     field="doku_datum"            caption="$_doku_datum_lbl"    input="<strong>$_doku_datum_value</strong> <input type='hidden' name='doku_datum' value='$_doku_datum_value'/>"}
{else}
    {html_set_row     field="doku_datum"            caption="$_doku_datum_lbl"    input=$_doku_datum}
{/if}
{html_set_row     field="unterschrift_datum" caption="<strong>$_unterschrift_datum_lbl</strong>" input=$_unterschrift_datum}

{html_set_header  field="kv_iknr"                  caption=#head_kvd#                     class="head"}
{html_set_row     field="kv_iknr"                  caption="$_kv_iknr_lbl <input type='submit' class='button_large' name='action[get_kv]' value='aktualisieren' alt='aktualisieren'>"                 input="<strong>$_kv_iknr_bez</strong> <input type='hidden' name='kv_iknr' value='$_kv_iknr_value'/>"}
{html_set_row     field="kv_abrechnungsbereich"    caption=$_kv_abrechnungsbereich_lbl    input="<strong>$_kv_abrechnungsbereich_bez</strong> <input type='hidden' name='kv_abrechnungsbereich' value='$_kv_abrechnungsbereich_value'/>"}
{html_set_row     field="versich_nr"               caption=$_versich_nr_lbl               input="<strong>$_versich_nr_value</strong> <input type='hidden' name='versich_nr' value='$_versich_nr_value'/>"}
{html_set_row     field="versich_status"           caption=$_versich_status_lbl           input="<strong>$_versich_status_bez</strong> <input type='hidden' name='versich_status' value='$_versich_status_value'/>"}
{html_set_row     field="versich_statusergaenzung" caption=$_versich_statusergaenzung_lbl input="<strong>$_versich_statusergaenzung_bez</strong> <input type='hidden' name='versich_statusergaenzung' value='$_versich_statusergaenzung_value'/>"}

{if $showKvExtension}
    {html_set_row     field="kv_besondere_personengruppe"   caption=$_kv_besondere_personengruppe_lbl input="<strong>$_kv_besondere_personengruppe_bez</strong> <input type='hidden' name='kv_besondere_personengruppe' value='$_kv_besondere_personengruppe_value'/>"}
    {html_set_row     field="kv_dmp_kennzeichnung"          caption=$_kv_dmp_kennzeichnung_lbl input="<strong>$_kv_dmp_kennzeichnung_bez</strong> <input type='hidden' name='kv_dmp_kennzeichnung' value='$_kv_dmp_kennzeichnung_value'/>"}
    {html_set_row     field="kv_versicherungsschutz_beginn" caption=$_kv_versicherungsschutz_beginn_lbl input="<strong>$_kv_versicherungsschutz_beginn_bez</strong> <input type='hidden' name='kv_versicherungsschutz_beginn' value='$_kv_versicherungsschutz_beginn_value'/>"}
    {html_set_row     field="kv_versicherungsschutz_ende"   caption=$_kv_versicherungsschutz_ende_lbl input="<strong>$_kv_versicherungsschutz_ende_bez</strong> <input type='hidden' name='kv_versicherungsschutz_ende' value='$_kv_versicherungsschutz_ende_value'/>"}
{/if}

{html_set_row     field="vk_gueltig_bis"           caption=$_vk_gueltig_bis_lbl           input="<strong>$_vk_gueltig_bis_bez</strong> <input type='hidden' name='vk_gueltig_bis' value='$_vk_gueltig_bis_bez'/>"}
{html_set_row     field="kvk_einlesedatum"         caption=$_kvk_einlesedatum_lbl         input="<strong>$_kvk_einlesedatum_bez</strong> <input type='hidden' name='kvk_einlesedatum' value='$_kvk_einlesedatum_bez'/>"}

{html_set_row     field="einschreibung_grund"   caption=$_einschreibung_grund_lbl    input="<strong>$_einschreibung_grund_bez</strong> <input type='hidden' name='einschreibung_grund' value='$_einschreibung_grund_value'/>"}

{html_set_header  field="primaer_strahlen"   caption=#head_behandlung_nach_op#   class="head"}
{html_set_row     field="primaer_strahlen"   caption=$_primaer_strahlen_lbl      input=$_primaer_strahlen}
{html_set_row     field="primaer_chemo"      caption=$_primaer_chemo_lbl         input=$_primaer_chemo}
{html_set_row     field="primaer_endo"       caption=$_primaer_endo_lbl          input=$_primaer_endo}
{html_set_row     field="primaer_ah"         caption=$_primaer_ah_lbl            input=$_primaer_ah}

{html_set_header  field="neu_rezidiv_nein"   caption=#head_seit_letzter_doku#    class="head"}
{html_set_html field="neu_rezidiv_nein" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width: 35%;' rowspan='2'>`$smarty.config.lbl_mani_rezidiv`</td>
            <td class='edt'>$_neu_rezidiv_nein $_neu_rezidiv_nein_lbl</td>
            <td class='edt'>$_neu_rezidiv_datum $_neu_rezidiv_datum_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_html field="neu_kontra_nein" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'  style='margin-top:1px;'>
         <tr>
            <td class='lbl' style='width: 35%;' rowspan='2'>`$smarty.config.lbl_mani_brustkrebs`</td>
            <td class='edt'>$_neu_kontra_nein $_neu_kontra_nein_lbl</td>
            <td class='edt'>$_neu_kontra_datum $_neu_kontra_datum_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_html field="neu_metast_nein" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'  style='margin-top:1px;'>
         <tr>
            <td class='lbl' style='width: 35%;' rowspan='2'>`$smarty.config.lbl_mani_metastasen`</td>
            <td class='edt'>$_neu_metast_nein $_neu_metast_nein_lbl</td>
            <td class='edt'>$_neu_metast_leber $_neu_metast_leber_lbl</td>
            <td class='edt'>$_neu_metast_lunge $_neu_metast_lunge_lbl</td>
            <td class='edt'>$_neu_metast_knochen $_neu_metast_knochen_lbl</td>
            <td class='edt'>$_neu_metast_andere $_neu_metast_andere_lbl</td>
         </tr><tr>
            <td class='edt' colspan='5'>$_neu_metast_datum $_neu_metast_datum_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_row  field="lymphoedem"         caption=$_lymphoedem_lbl         input=$_lymphoedem}

{html_set_header  field="rez_status_cr"   caption=#head_behandlung_fort#   class="head"}
{html_set_html field="rez_status_cr" html="
   <tr>
      <td class='msg' colspan='2'>
        <table class='inline-table'  style='margin-top:1px;'>
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

{html_set_header  field="termin_datum"   caption=#head_termin_datum#   class="head"}
{html_set_row  field="termin_datum" caption="<strong>$_termin_datum_lbl</strong>" input=$_termin_datum}

{html_set_header field="bem" caption=#head_bem#    class="head"}
{html_set_header field="bem" caption=$_bem         class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
{$_dmp_brustkrebs_fd_2013_id}
{$_dmp_brustkrebs_ed_2013_id}
{$_patient_id}
{$_erkrankung_id}
</div>
