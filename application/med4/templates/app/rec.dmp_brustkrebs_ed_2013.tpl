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

{html_set_header  class="msgbox"                caption=#info_erstdoku#}
<table class="formtable">
    {html_set_header caption=#head_getData#  class="head"}
    <tr>
        <td class='lbl' colspan='2'>
            <input style="margin-right:20px" type='submit' class='button_large btnconfirm' name='action[get_dmp]' value="{#lbl_getData#}" alt="{#lbl_getData#}"">
        </td>
    </tr>
</table>

<table class="formtable">
    {html_set_header  field="fall_nr"               caption=#head_administrativ#                            class="head"}
    <tr>
        <td class="lbl">
            {$_fall_nr_lbl}
        </td>
        <td class="edt">
            {if strlen($_dmp_brustkrebs_ed_2013_id_value) == 0 && $dmp_nr_pool_empty}
                <div class="info-msg">
                    {#info_empty_pool#}
                </div>
            {/if}
            {$_fall_nr}
        </td>
    </tr>

    {if strlen($_dmp_brustkrebs_ed_2013_id_value) > 0}
    {html_set_row     field="doku_datum"            caption="$_doku_datum_lbl"    input="<strong>$_doku_datum_value</strong> <input type='hidden' name='doku_datum' value='$_doku_datum_value'/>"}
    {else}
    {html_set_row     field="doku_datum"            caption="$_doku_datum_lbl"    input=$_doku_datum}
    {/if}
    {html_set_row     field="einschreibung_grund"   caption="<strong>$_einschreibung_grund_lbl</strong>"    input=$_einschreibung_grund}
    {html_set_row     field="melde_user_id"         caption=$_melde_user_id_lbl                             input=$_melde_user_id}
    {html_set_row     field="unterschrift_datum"    caption="<strong>$_unterschrift_datum_lbl</strong>"     input=$_unterschrift_datum}

    {html_set_header  field="kv_iknr"                  caption=#head_krankenver#              class="head"}
    <tr>
        <td class="lbl">
            {$_kv_iknr_lbl} <input type='submit' class='button_large' name='action[get_kv]' value='{#lbl_kv_update#}'/>
        </td>
        <td class="edt">
            <strong>{$_kv_iknr_bez}</strong>
            <input type='hidden' name='kv_iknr' value='{$_kv_iknr_value}'/>
        </td>
    </tr>

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

    {html_set_header  field="mani_primaer" caption=#head_einschreibung#  class="head"}
    {html_set_row     field="mani_primaer" caption=$_mani_primaer_lbl    input="$_mani_primaer    `$smarty.config.dateHistology`"}
    {html_set_row     field="mani_kontra"  caption=$_mani_kontra_lbl     input="$_mani_kontra     `$smarty.config.dateHistology`"}
    {html_set_row     field="mani_rezidiv" caption=$_mani_rezidiv_lbl    input="$_mani_rezidiv    `$smarty.config.dateHistology`"}
    {html_set_row     field="mani_metast"  caption=$_mani_metast_lbl     input=$_mani_metast}

    {html_set_header  field="anam_brust_links" caption=#head_brustkrebs#  class="head"}
    {html_set_html field="anam_brust_links" html="
        <tr>
            <td class='msg' colspan='2'>
                <table class='inline-table'>
                    <tr>
                        <td class='lbl' style='width: 35%;'>`$smarty.config.lbl_brust`</td>
                        <td class='edt'>$_anam_brust_links $_anam_brust_links_lbl</td>
                        <td class='edt'>$_anam_brust_rechts $_anam_brust_rechts_lbl</td>
                        <td class='edt'>$_anam_brust_beidseits $_anam_brust_beidseits_lbl</td>
                    </tr>
                </table>
            </td>
        </tr>
    "}
    {html_set_row  field="aktueller_status"   caption="<strong>$_aktueller_status_lbl</strong>   input=$_aktueller_status}
    {html_set_html field="anam_op_bet" html="
        <tr>
            <td class='msg' colspan='2'>
                <table class='inline-table'>
                    <tr>
                        <td class='lbl' style='width: 35%;' rowspan='2'>`$smarty.config.lbl_therapieart`</td>
                        <td class='edt'>$_anam_op_bet $_anam_op_bet_lbl</td>
                        <td class='edt'>$_anam_op_mast $_anam_op_mast_lbl</td>
                        <td class='edt'>$_anam_op_sln $_anam_op_sln_lbl</td>
                    </tr><tr>
                        <td class='edt'>$_anam_op_axilla $_anam_op_axilla_lbl</td>
                        <td class='edt'>$_anam_op_anderes $_anam_op_anderes_lbl</td>
                        <td class='edt'>$_anam_op_keine $_anam_op_keine_lbl</td>
                    </tr>
                </table>
            </td>
        </tr>
    "}

    {html_set_header  field="bef_pt_tis"        caption=#head_befundstand_bk#     class="head"}
    {html_set_row     field="bef_neoadjuvant"   caption="$_bef_neoadjuvant_lbl"   input=$_bef_neoadjuvant}
    {html_set_html    field="bef_pt_tis" html="
        <tr>
            <td class='msg' colspan='2'>
                <table class='inline-table'>
                    <tr>
                        <td class='lbl' style='width: 35%;' rowspan='2'>`$smarty.config.lbl_pt`</td>
                        <td class='edt'>$_bef_pt_tis $_bef_pt_tis_lbl</td>
                        <td class='edt'>$_bef_pt_0 $_bef_pt_0_lbl</td>
                        <td class='edt'>$_bef_pt_1 $_bef_pt_1_lbl</td>
                        <td class='edt'>$_bef_pt_2 $_bef_pt_2_lbl</td>
                    </tr><tr>
                        <td class='edt'>$_bef_pt_3 $_bef_pt_3_lbl</td>
                        <td class='edt'>$_bef_pt_4 $_bef_pt_4_lbl</td>
                        <td class='edt'>$_bef_pt_x $_bef_pt_x_lbl</td>
                        <td class='edt'>$_bef_pt_keine $_bef_pt_keine_lbl</td>
                    </tr>
                </table>
            </td>
        </tr>
    "}
    {html_set_html field="bef_pn_0" html="
        <tr>
            <td class='msg' colspan='2'>
                <table class='inline-table'  style='margin-top:1px;'>
                    <tr>
                        <td class='lbl' style='width: 35%;' rowspan='2' >`$smarty.config.lbl_pn`</td>
                        <td class='edt'>$_bef_pn_0 $_bef_pn_0_lbl</td>
                        <td class='edt'>$_bef_pn_1 $_bef_pn_1_lbl</td>
                        <td class='edt'>$_bef_pn_2 $_bef_pn_2_lbl</td>
                    </tr><tr>
                        <td class='edt'>$_bef_pn_3 $_bef_pn_3_lbl</td>
                        <td class='edt'>$_bef_pn_x $_bef_pn_x_lbl</td>
                        <td class='edt'>$_bef_pn_keine $_bef_pn_keine_lbl</td>
                    </tr>
                </table>
            </td>
        </tr>
    "}
    {html_set_row  field="bef_m"   caption=$_bef_m_lbl   input=$_bef_m}
    {html_set_row  field="bef_g"   caption=$_bef_g_lbl   input=$_bef_g}
    {html_set_html field="bef_r_0" html="
        <tr>
            <td class='msg' colspan='2'>
                <table class='inline-table'  style='margin-top:1px;'>
                    <tr>
                        <td class='lbl' style='width: 35%;' rowspan='2'>`$smarty.config.lbl_resektion`</td>
                        <td class='edt'>$_bef_r_0 $_bef_r_0_lbl</td>
                        <td class='edt'>$_bef_r_1 $_bef_r_1_lbl</td>
                        <td class='edt'>$_bef_r_2 $_bef_r_2_lbl</td>
                    </tr><tr>
                        <td class='edt'>$_bef_r_unbekannt $_bef_r_unbekannt_lbl</td>
                        <td class='edt' colspan='2'>$_bef_r_keine $_bef_r_keine_lbl</td>
                    </tr>
                </table>
            </td>
        </tr>
    "}
    {html_set_row  field="bef_rezeptorstatus"   caption=$_bef_rezeptorstatus_lbl   input=$_bef_rezeptorstatus}
    {html_set_row  field="bef_her2"   caption=$_bef_her2_lbl   input=$_bef_her2}
    {html_set_header  field="beh_strahlen" caption=#head_behandlung_bk#  class="head"}
    {html_set_row     field="beh_strahlen" caption=$_beh_strahlen_lbl    input=$_beh_strahlen}
    {html_set_row     field="beh_chemo"    caption=$_beh_chemo_lbl       input=$_beh_chemo}
    {html_set_row     field="beh_endo"     caption=$_beh_endo_lbl        input=$_beh_endo}
    {html_set_row     field="beh_ah"     caption=$_beh_ah_lbl        input=$_beh_ah}

    {html_set_header  field="rez_th_praeop" caption=#head_rezidiv#  class="head"}
    {html_set_html field="rez_th_praeop" html="
        <tr>
            <td class='msg' colspan='2'>
                <table class='inline-table'>
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
                </table>
            </td>
        </tr>
    "}

    {html_set_header  field="metast_lok_leber" caption=#head_fernmetastasen#  class="head"}
    {html_set_html field="metast_lok_leber" html="
        <tr>
            <td class='msg' colspan='2'>
                <table class='inline-table'>
                    <tr>
                        <td class='lbl' style='width: 35%;'>`$smarty.config.lbl_lokalisation`</td>
                        <td class='edt'>$_metast_lok_leber $_metast_lok_leber_lbl</td>
                        <td class='edt'>$_metast_lok_lunge $_metast_lok_lunge_lbl</td>
                        <td class='edt'>$_metast_lok_knochen $_metast_lok_knochen_lbl</td>
                        <td class='edt'>$_metast_lok_andere $_metast_lok_andere_lbl</td>
                    </tr>
                </table>
            </td>
        </tr>
    "}
    {html_set_html field="metast_th_operativ" html="
        <tr>
            <td class='msg' colspan='2'>
                <table class='inline-table' style='margin-top:1px;'>
                    <tr>
                        <td class='lbl' style='width: 35%;' rowspan='2'><strong>`$smarty.config.lbl_fernmetastasen`</strong></td>
                        <td class='edt'>$_metast_th_operativ $_metast_th_operativ_lbl</td>
                        <td class='edt'>$_metast_th_strahlen $_metast_th_strahlen_lbl</td>
                        <td class='edt'>$_metast_th_chemo $_metast_th_chemo_lbl</td>
                    </tr><tr>
                        <td class='edt'>$_metast_th_endo $_metast_th_endo_lbl</td>
                        <td class='edt'>$_metast_th_andere $_metast_th_andere_lbl</td>
                        <td class='edt'>$_metast_th_keine $_metast_th_keine_lbl</td>
                    </tr><tr>
                        <td class='lbl' style='width: 35%;'>`$smarty.config.lbl_bisphos`</td>
                        <td class='edt'>$_metast_bip_ja $_metast_bip_ja_lbl</td>
                        <td class='edt'>$_metast_bip_nein $_metast_bip_nein_lbl</td>
                        <td class='edt'>$_metast_bip_kontra $_metast_bip_kontra_lbl</td>
                    </tr>
                </table>
            </td>
        </tr>
    "}

    {html_set_header  field="lymphoedem"    caption=#head_beratung#                         class="head"}
    {html_set_row     field="lymphoedem"    caption=$_lymphoedem_lbl                        input=$_lymphoedem}
    {html_set_row     field="termin_datum"  caption="<strong>$_termin_datum_lbl</strong>"   input=$_termin_datum}

    {html_set_header  field="bem"           caption=#head_bem#                              class="head"}
    {html_set_header  field="bem"           caption=$_bem                                   class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
    {$_dmp_brustkrebs_ed_2013_id}
    {$_patient_id}
    {$_org_id}
    {$_erkrankung_id}
</div>
