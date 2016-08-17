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

{html_set_header caption=#head_untersuchung#       class="head"}
{if in_array($SESSION.sess_erkrankung_data.code, array('b', 'lu')) === true}
<tr>
    <td style="padding:0 !important" colspan="2">
        <div class="info-msg" style="margin-bottom:0 !important">
            {#info_seitenangabe#}
        </div>
    </td>
</tr>
{/if}
{html_set_row field="art"                          caption=$_art_lbl                            input=$_art}
{html_set_row field="koloskopie_vollstaendig"      caption=$_koloskopie_vollstaendig_lbl        input=$_koloskopie_vollstaendig}
{html_set_row field="ct_becken"                    caption=$_ct_becken_lbl                      input=$_ct_becken}

{html_set_html field="kontrastmittel_iv,kontrastmittel_po,kontrastmittel_rektal" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl'>`$smarty.config.lbl_kontrastmittel`</td>
            <td class='edt'>
               $_kontrastmittel_iv $_kontrastmittel_iv_lbl<br/>
               $_kontrastmittel_po $_kontrastmittel_po_lbl<br/>
               $_kontrastmittel_rektal $_kontrastmittel_rektal_lbl<br/>
            </td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_row field="pe"                           caption=$_pe_lbl           input=$_pe}
{html_set_row field="datum"                        caption=$_datum_lbl        input=$_datum}
{html_set_row field="anlass"                       caption=$_anlass_lbl       input=$_anlass}
{html_set_row field="org_id"                       caption=$_org_id_lbl       input=$_org_id}
{html_set_row field="arzt_id"                      caption=$_arzt_id_lbl      input=$_arzt_id}
{html_set_row field="beurteilung"                  caption=$_beurteilung_lbl  input=$_beurteilung}
{html_set_row field="hno_untersuchung"             caption=$_hno_untersuchung_lbl  input=$_hno_untersuchung}
{html_set_row field="lunge"                        caption=$_lunge_lbl        input=$_lunge}
{html_set_row field="birads"                       caption=$_birads_lbl       input=$_birads}
{html_set_row field="ut"                           caption=$_ut_lbl           input=$_ut}
{html_set_row field="un"                           caption=$_un_lbl           input=$_un}
{html_set_row field="cn"                           caption=$_cn_lbl           input=$_cn}
{html_set_row field="mesorektale_faszie"           caption=$_mesorektale_faszie_lbl input=$_mesorektale_faszie add=#lbl_mm#}
{html_set_row field="lavage_menge"                 caption=$_lavage_menge_lbl input=$_lavage_menge add=#lbl_ml#}
<tr>
   <td class="msg" colspan="2">
      <div class="dlist" id="dlist_lokalisation">
         <div class="add">
            <input class="button" type="button" name="untersuchung_lokalisation" value="Hinzuf&uuml;gen" onclick="execute_request(this,'rec.untersuchung_lokalisation', null, ['patient_id', 'untersuchung_id', 'erkrankung_id'])"/>
         </div>
      </div>
   </td>
</tr>

{html_set_header caption=#head_lymph# field="bulky"    class="head"}
{html_set_html field="bulky" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl'>$_bulky_lbl</td>
            <td class='edt'  style='width: 10%;'>$_bulky</td>
            <td class='edt'  style='width: 10%; text-align: right;'>$_bulky_groesse_lbl</td>
            <td class='edt'>$_bulky_groesse `$smarty.config.lbl_cm`</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_header caption=#lbl_areale# field="lk_a"    class="head"}
{html_set_html field="lk_a" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width: 60% !important;'>$_lk_a_lbl</td>
            <td class='edt' style='text-align: center;'>$_lk_a</td>
            <td class='edt' style='width: 210px;' rowspan='11'><img src='media/img/app/hbt_koerper.png' alt='' /></td>
         </tr>
         <tr>
            <td class='lbl' style='width: 60% !important;'>$_lk_b_lbl</td>
            <td class='edt' style='text-align: center; border-right: 1px solid #fff !important;'>$_lk_b</td>
         </tr>
         <tr>
            <td class='lbl' style='width: 60% !important;'>$_lk_c_lbl</td>
            <td class='edt' style='text-align: center; border-right: 1px solid #fff !important;'>$_lk_c</td>
         </tr>
         <tr>
            <td class='lbl' style='width: 60% !important;'>$_lk_d_lbl</td>
            <td class='edt' style='text-align: center; border-right: 1px solid #fff !important;'>$_lk_d</td>
         </tr>
         <tr>
            <td class='lbl' style='width: 60% !important;'>$_lk_e_lbl</td>
            <td class='edt' style='text-align: center; border-right: 1px solid #fff !important;'>$_lk_e</td>
         </tr>
         <tr>
            <td class='lbl' style='width: 60% !important;'>$_lk_f_lbl</td>
            <td class='edt' style='text-align: center; border-right: 1px solid #fff !important;'>$_lk_f</td>
         </tr>
         <tr>
            <td class='lbl' style='width: 60% !important;'>$_lk_g_lbl</td>
            <td class='edt' style='text-align: center; border-right: 1px solid #fff !important;'>$_lk_g</td>
         </tr>
         <tr>
            <td class='lbl' style='width: 60% !important;'>$_lk_h_lbl</td>
            <td class='edt' style='text-align: center; border-right: 1px solid #fff !important;'>$_lk_h</td>
         </tr>
         <tr>
            <td class='lbl' style='width: 60% !important;'>$_lk_i_lbl</td>
            <td class='edt' style='text-align: center; border-right: 1px solid #fff !important;'>$_lk_i</td>
         </tr>
         <tr>
            <td class='lbl' style='width: 60% !important;'>$_lk_k_lbl</td>
            <td class='edt' style='text-align: center; border-right: 1px solid #fff !important;'>$_lk_k</td>
         </tr>
         <tr>
            <td class='lbl' style='width: 60% !important;'>$_lk_l_lbl</td>
            <td class='edt' style='text-align: center; border-right: 1px solid #fff !important;'>$_lk_l</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_header caption=#head_angaben# field="konsistenz,rsh_verschieblich,abgrenzbarkeit,gesamtvolumen,kapselueberschreitung,invasion,invasion_detail"    class="head"}
{html_set_row field="konsistenz"                     caption=$_konsistenz_lbl             input=$_konsistenz}
{html_set_row field="rsh_verschieblich"              caption=$_rsh_verschieblich_lbl      input=$_rsh_verschieblich}
{html_set_row field="abgrenzbarkeit"                 caption=$_abgrenzbarkeit_lbl         input=$_abgrenzbarkeit}
{html_set_row field="gesamtvolumen"                  caption=$_gesamtvolumen_lbl          input=$_gesamtvolumen   add=#lbl_cm3#}
{html_set_row field="kapselueberschreitung"          caption=$_kapselueberschreitung_lbl  input=$_kapselueberschreitung}
{html_set_row field="invasion"                       caption=$_invasion_lbl               input="$_invasion $_invasion_detail_lbl $_invasion_detail"}
{html_set_header caption=#head_bem# class="head"}
{html_set_header field="bem" caption=$_bem      class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
<input type="hidden" value="{$_untersuchung_id_value}" name="form_id" />
{$_untersuchung_id}
{$_patient_id}
{$_erkrankung_id}
</div>
