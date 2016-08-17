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

<div class="msgbox">
    {#info_preload#}
</div>

<table class="formtable">
    {html_set_header  field="zuopseite"          caption=#head_brust#             class="head"}
    <tr>
        <td class="lbl">
            {$_zuopseite_lbl}
        </td>
        <td class="edt">
            <b>{$_zuopseite_bez}</b>
            <input type="hidden" value="{$_zuopseite_value}" name="zuopseite"/>
        </td>
    </tr>
    {html_set_row     field="arterkrank"         caption=$_arterkrank_lbl         input=$_arterkrank}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_1#}
            </div>
        </td>
    </tr>
    {html_set_row     field="erstoffeingriff"    caption=$_erstoffeingriff_lbl    input=$_erstoffeingriff}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_2#}
            </div>
        </td>
    </tr>
    {html_set_row     field="tastbarmammabefund" caption=$_tastbarmammabefund_lbl input=$_tastbarmammabefund}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_3#}
            </div>
        </td>
    </tr>
    {html_set_row     field="anlasstumordiag"             caption=$_anlasstumordiag_lbl             input=$_anlasstumordiag}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_4#}
            </div>
        </td>
    </tr>
    {html_set_row     field="anlasstumordiageigen"        caption=$_anlasstumordiageigen_lbl        input=$_anlasstumordiageigen}
    {html_set_row     field="anlasstumordiagfrueh"        caption=$_anlasstumordiagfrueh_lbl        input=$_anlasstumordiagfrueh}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_5#}
            </div>
        </td>
    </tr>
    {html_set_row     field="mammographiescreening"       caption=$_mammographiescreening_lbl       input=$_mammographiescreening}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_6#}
            </div>
        </td>
    </tr>
    {html_set_row     field="anlasstumordiagsympt"        caption=$_anlasstumordiagsympt_lbl        input=$_anlasstumordiagsympt}
    {html_set_row     field="anlasstumordiagnachsorge"    caption=$_anlasstumordiagnachsorge_lbl    input=$_anlasstumordiagnachsorge}
    {html_set_row     field="anlasstumordiagsonst"        caption=$_anlasstumordiagsonst_lbl        input=$_anlasstumordiagsonst}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_7#}
            </div>
        </td>
    </tr>
    <tr>
       <td class="lbl">
          {if $qs181Version > 2012}
             {#praehistdiagsicherung_gt2012#}
          {else}
             {$_praehistdiagsicherung_lbl}
          {/if}
       </td>
       <td class="edt">
          {$_praehistdiagsicherung}
       </td>
    </tr>
    {html_set_row     field="praehistbefund"              caption=$_praehistbefund_lbl              input=$_praehistbefund}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_8#}
            </div>
        </td>
    </tr>
    <tr>
        <td class="lbl">
            {$_praeicdo3_lbl}
            {#info_praeicdo3#}
        </td>
        <td class="select-small edt">
            {$_praeicdo3}
        </td>
    </tr>

    {html_set_row     field="ausganghistbefund"           caption=$_ausganghistbefund_lbl           input=$_ausganghistbefund}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_9#}
            </div>
        </td>
    </tr>
    {html_set_row     field="praethinterdisztherapieplan" caption=$_praethinterdisztherapieplan_lbl input=$_praethinterdisztherapieplan}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_10#}
            </div>
        </td>
    </tr>
    {html_set_row     field="datumtherapieplan"           caption=$_datumtherapieplan_lbl           input=$_datumtherapieplan}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_11#}
            </div>
        </td>
    </tr>
    {html_set_row     field="praeoptumorth"               caption=$_praeoptumorth_lbl               input=$_praeoptumorth}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_12#}
            </div>
        </td>
    </tr>
    {html_set_row     field="systchemoth"                 caption=$_systchemoth_lbl                 input=$_systchemoth}
    {html_set_row     field="endokrinth"                  caption=$_endokrinth_lbl                  input=$_endokrinth}
    {html_set_row     field="spezifantiktherapie"         caption=$_spezifantiktherapie_lbl         input=$_spezifantiktherapie}
    {html_set_row     field="strahlenth"                  caption=$_strahlenth_lbl                  input=$_strahlenth}
    {html_set_row     field="sonstth"                     caption=$_sonstth_lbl                     input=$_sonstth}

    {html_set_header  field="pokomplikatspez"    caption=#head_komplikation#      class="head"}
    {html_set_row     field="pokomplikatspez"    caption=$_pokomplikatspez_lbl    input=$_pokomplikatspez}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_13#}
            </div>
        </td>
    </tr>
    {html_set_row     field="pokowundinfektion"  caption=$_pokowundinfektion_lbl  input=$_pokowundinfektion}
    {html_set_row     field="nachblutung"        caption=$_nachblutung_lbl        input=$_nachblutung}
    {html_set_row     field="serom"              caption=$_serom_lbl              input=$_serom}
    {html_set_row     field="pokosonst"          caption=$_pokosonst_lbl          input=$_pokosonst}

    {html_set_header  field="posthistbefund"     caption=#head_histologie#        class="head"}
    {html_set_row     field="posthistbefund"     caption=$_posthistbefund_lbl     input=$_posthistbefund}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_14#}
            </div>
        </td>
    </tr>
    <tr>
        <td class="lbl">
            {$_posticdo3_lbl}{#info_posticdo3#}
        </td>
        <td class="select-small edt">
            {$_posticdo3}
        </td>
    </tr>
    <tr>
        <td class="lbl">
            {$_optherapieende_lbl}{#info_optherapieende#}
        </td>
        <td class="select-small edt">
            {$_optherapieende}
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_15#}
            </div>
        </td>
    </tr>
    {html_set_row     field="tumortherapieempf"  caption=$_tumortherapieempf_lbl  input=$_tumortherapieempf}

    {html_set_header  field="tnmptmamma"   caption=#head_ptpn#        class="head"}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_16#}
            </div>
        </td>
    </tr>
    {html_set_row     field="tnmptmamma"   caption=$_tnmptmamma_lbl   input=$_tnmptmamma}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_17#}
            </div>
        </td>
    </tr>
    {html_set_row     field="tnmpnmamma"   caption=$_tnmpnmamma_lbl   input=$_tnmpnmamma}

    {html_set_header  field="anzahllypmphknoten"    caption=#head_untersucht#           class="head"}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_18#}
            </div>
        </td>
    </tr>
    {html_set_row     field="anzahllypmphknoten"    caption=$_anzahllypmphknoten_lbl    input=$_anzahllypmphknoten}
    {html_set_row     field="anzahllypmphknotenunb" caption=$_anzahllypmphknotenunb_lbl input=$_anzahllypmphknotenunb}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_19#}
            </div>
        </td>
    </tr>
    {html_set_row     field="graddcis"              caption=$_graddcis_lbl              input=$_graddcis}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_20#}
            </div>
        </td>
    </tr>
    {html_set_row     field="gesamttumorgroesse"    caption=$_gesamttumorgroesse_lbl    input=$_gesamttumorgroesse add=#lbl_mm#}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_21#}
            </div>
        </td>
    </tr>
    {html_set_row     field="tnmgmamma"             caption=$_tnmgmamma_lbl             input=$_tnmgmamma}
    {html_set_row     field="rezeptorstatus"        caption=$_rezeptorstatus_lbl        input=$_rezeptorstatus}
    {html_set_row     field="her2neustatus"         caption=$_her2neustatus_lbl         input=$_her2neustatus}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_22#}
            </div>
        </td>
    </tr>
    {html_set_row     field="multizentrizitaet"     caption=$_multizentrizitaet_lbl     input=$_multizentrizitaet}
    {html_set_row     field="angabensicherabstand"  caption=$_angabensicherabstand_lbl  input=$_angabensicherabstand}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_23#}
            </div>
        </td>
    </tr>
    <tr>
        <td class="lbl">
            {$_sicherabstand_lbl}{#info_sicherabstand#}
        </td>
        <td class="select-small edt">
            {$_sicherabstand}{#lbl_mm#}
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_24#}
            </div>
        </td>
    </tr>
    {html_set_row     field="mnachstaging"          caption=$_mnachstaging_lbl          input=$_mnachstaging}

    {html_set_header  field="bet"                   caption=#head_therapie#             class="head"}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_25#}
            </div>
        </td>
    </tr>
    {html_set_row     field="bet"                   caption=$_bet_lbl                   input=$_bet}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_26#}
            </div>
        </td>
    </tr>
    {html_set_row     field="axlkentfomark"         caption=$_axlkentfomark_lbl         input=$_axlkentfomark}
    {html_set_row     field="axilladissektion"      caption=$_axilladissektion_lbl      input=$_axilladissektion}
    {html_set_row     field="slkbiopsie"            caption=$_slkbiopsie_lbl            input=$_slkbiopsie}

    {html_set_header                                caption=#head_markierung#             class="head"}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_27#}
            </div>
        </td>
    </tr>
    {html_set_row     field="radionuklidmarkierung" caption=$_radionuklidmarkierung_lbl input=$_radionuklidmarkierung}
    {html_set_row     field="farbmarkierung"        caption=$_farbmarkierung_lbl        input=$_farbmarkierung}

    {html_set_header  field="freigabe"     caption=#head_freigabe#    class="head"}
    {html_set_row     field="freigabe"      caption=$_freigabe_lbl      input=$_freigabe}

</table>
{html_set_buttons modus=$button}

<div>
{$_qs_18_1_brust_id}
{$_qs_18_1_b_id}
{$_patient_id}
{$_erkrankung_id}
{$_aufenthalt_id}
</div>
