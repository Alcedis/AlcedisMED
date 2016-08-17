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
    {if in_array($from, array('konferenz')) == true}
    {html_set_header caption=#head_patient#    class="head"}
    <tr>
        <td class="lbl">{#lbl_name#}</td>
        <td class="edt">{$patient.name}</td>
    </tr>
    <tr>
        <td class="lbl">{#lbl_birthdate#}</td>
        <td class="edt">{$patient.bday}</td>
    </tr>
    <tr>
        <td class="lbl">{#lbl_disease#}</td>
        <td class="edt">{$patient.erkrankung_bez}</td>
    </tr>
    <tr>
        <td class="lbl">{#lbl_type#}</td>
        <td class="edt">{$patient.type}</td>
    </tr>
    {/if}
	{html_set_header caption=#head_daten#    class="head"}

	{html_set_row field="datum"                 caption=$_datum_lbl                 input=$_datum}
    {html_set_row field="org_id"                caption=$_org_id_lbl                input=$_org_id}
    {html_set_row field="leistungserbringer"    caption=$_leistungserbringer_lbl    input=$_leistungserbringer}

	{if in_array($from, array('konferenz', 'konferenz_patient')) == false}
      {html_set_row field="grundlage"              caption=$_grundlage_lbl             input=$_grundlage}
      {html_set_row field="zeitpunkt"              caption=$_zeitpunkt_lbl             input=$_zeitpunkt}
      {html_set_row field="konferenz_patient_id"   caption=$_konferenz_patient_id_lbl  input=$_konferenz_patient_id}
	{/if}

    {html_set_row field="zweitmeinung_id"         caption=$_zweitmeinung_id_lbl  input=$_zweitmeinung_id}

	{html_set_row field="vorgestellt"             caption=$_vorgestellt_lbl      input=$_vorgestellt}
	{html_set_row field="vorgestellt2"            caption=$_vorgestellt2_lbl     input=$_vorgestellt2}
	{html_set_html field="grund_keine_konferenz" html="
	   <tr>
	      <td class='msg' colspan='2'>
	         <table class='inline-table'>
	         <tr>
	            <td class='lbl' style='width:35%;'>$_grund_keine_konferenz_lbl</td>
	            <td class='edt'>$_grund_keine_konferenz<br/>
	               <div style='margin-top:5px'>
	                  $_grund_keine_konferenz_sonstige_lbl  $_grund_keine_konferenz_sonstige
	               </div>
	            </td>
	         </tr>
	         </table>
	      </td>
	   </tr>
	"}
	</table>
	<table class="formtable">
	{html_set_header caption=#head_massnahmen#    class="head"}
	{html_set_row field="intention"              caption=$_intention_lbl             input=$_intention}

	{html_set_html field="op" html="
	<tr>
	   <td class='msg' colspan='2'>
	      <table class='inline-table'>
	         <tr>
	            <td class='subhead' style='width:35%'><strong>`$smarty.config.lbl_eingriff`</strong></td>
	            <td class='subhead'><strong>`$smarty.config.lbl_intention`</strong></td>
	            <td class='subhead' align='center' style='width:52px'><strong>`$smarty.config.lbl_extern`</strong></td>
	         </tr>
	         <tr>
	            <td class='lbl'><strong>$_op</strong></td>
	            <td class='edt'>$_op_intention</td>
	            <td class='edt' align='center'>$_op_extern</td>
	         </tr>

	      </table>
	   </td>
	</tr>
	"}

	{html_set_row field="op_art_brusterhaltend"  caption=$_op_art_brusterhaltend_lbl input=$_op_art_brusterhaltend}
	{html_set_row field="op_art_mastektomie"     caption=$_op_art_mastektomie_lbl    input=$_op_art_mastektomie}
	{html_set_row field="op_art_nachresektion"   caption=$_op_art_nachresektion_lbl  input=$_op_art_nachresektion}
	{html_set_html field="op_art_sln" html="
	   <tr>
	      <td class='msg' colspan='2'>
	         <table class='inline-table'>
	         <tr>
	            <td class='lbl' style='width:35%;'>$_op_art_sln_lbl</td>
	            <td class='edt'>$_op_art_sln</td>
	            <td class='edt' rowspan='2'>$_keine_axilla_grund_lbl <br/> $_keine_axilla_grund</td>
	         </tr><tr>
	            <td class='lbl' style='width:35%;'>$_op_art_axilla_lbl</td>
	            <td class='edt'>$_op_art_axilla</td>
	         </tr>
	         </table>
	      </td>
	   </tr>
	"}

	{html_set_row field="op_art_prostata"     caption=$_op_art_prostata_lbl    input=$_op_art_prostata}
	{html_set_row field="op_art_nerverhaltend"     caption=$_op_art_nerverhaltend_lbl    input=$_op_art_nerverhaltend}
    {html_set_row field="op_art_lymphadenektomie"  caption=$_op_art_lymphadenektomie_lbl input=$_op_art_lymphadenektomie}

	{html_set_html field="op_art_transplantation_autolog" html="
	   <tr>
	      <td class='msg' colspan='2'>
	         <table class='inline-table'>
	         <tr>
	            <td class='lbl' style='width:35%;' rowspan='2'>`$smarty.config.lbl_transplantation`</td>
	            <td class='edt'>$_op_art_transplantation_autolog $_op_art_transplantation_autolog_lbl</td>
	            <td class='edt'>$_op_art_transplantation_allogen_v $_op_art_transplantation_allogen_v_lbl</td>
	         </tr><tr>
	            <td class='edt'>$_op_art_transplantation_allogen_nv $_op_art_transplantation_allogen_nv_lbl</td>
	            <td class='edt'>$_op_art_transplantation_syngen $_op_art_transplantation_syngen_lbl</td>
	         </tr>
	         </table>
	      </td>
	   </tr>
	"}

    {html_set_row field="op_sonstige"  caption=$_op_sonstige_lbl  input=$_op_sonstige}
    {html_set_row field="op_art"       caption=$_op_art_lbl       input=$_op_art}

	<tr>
	   <td class='msg' colspan='2'>

	      {html_set_html field="strahlen" html="
	      <table class='inline-table'>
	         <tr>
	            <td class='subhead' style='width: 200px !important;'><strong>`$smarty.config.lbl_therapieart`</strong></td>
	            <td class='subhead' style='width:40px;'><strong>`$smarty.config.lbl_indiziert`</strong></td>
	            <td class='subhead' style='width:40px;'><strong>`$smarty.config.lbl_geplant`</strong></td>
	      "}


           {if $SESSION.sess_erkrankung_data.code === 'b'}
              {html_set_html field="strahlen_mamma,strahlen_lokalisation" html="
                    <td class='subhead' style='width:320px;'><strong>`$smarty.config.lbl_lokalisation`</strong></td>
              "}
           {else}
               {html_set_html field="strahlen" html="
                    <td class='subhead' style='width:320px;'><strong>`$smarty.config.strahlen_lokalisation`</strong></td>
              "}

           {/if}

	      {html_set_html field="strahlen" html="
	            <td class='subhead'><strong>`$smarty.config.lbl_intention`</strong></td>
	            <td class='subhead' align='center' style='width:52px'><strong>`$smarty.config.lbl_extern`</strong></td>
	         </tr>
	         <tr>
	            <td class='lbl' style='width: 200px !important;'><strong>$_strahlen_lbl</strong></td>
	            <td class='edt' style='width:40px;'>$_strahlen_indiziert</td>
	            <td class='edt' style='width:40px;'>$_strahlen</td>
	      "}
	      {html_set_html field="strahlen_mamma" html="
	            <td class='edt' align='right' style='width:320px;'>
	               <div ><!--  -->$_strahlen_mamma_lbl $_strahlen_mamma</div>
	               <div style='margin-top:3px'><!--  -->$_strahlen_axilla_lbl $_strahlen_axilla</div>
	               <div style='margin-top:3px'><!--  -->$_strahlen_lk_supra_lbl $_strahlen_lk_supra</div>
	               <div style='margin-top:3px'><!--  -->$_strahlen_lk_para_lbl $_strahlen_lk_para</div>
	               <div style='margin-top:3px'><!--  -->$_strahlen_thoraxwand_lbl $_strahlen_thoraxwand</div>
	               <div style='margin-top:3px'><!--  -->$_strahlen_sonstige_lbl $_strahlen_sonstige</div>
	            </td>
	       "}

            {html_set_html field="strahlen_lokalisation" html="
                <td class='edt' align='right'><br/>$_strahlen_lokalisation</td>
            "}


            {html_set_html field="strahlen_lokalisation,strahlen,strahlen_mamma" html="
                <td class='edt'>$_strahlen_intention</td>
                <td class='edt' align='center'>$_strahlen_extern</td>
            </tr>
            </table>
            "}




	      {html_set_html field="strahlen_art,strahlen_zielvolumen,strahlen_gesamtdosis,strahlen_einzeldosis" html="
	      <table class='inline-table'>
	         <tr>
	            <td class='lbl' style='width: 200px !important; border-top: 1px solid #fff;'>$_strahlen_art_lbl</td>
	            <td class='edt'>$_strahlen_art</td>
	         </tr>
	         <tr>
	            <td class='lbl' style='width: 200px !important;'>$_strahlen_zielvolumen_lbl </td>
	            <td class='edt'>$_strahlen_zielvolumen `$smarty.config.lbl_ml`</td>
	         </tr>
	         <tr>
	            <td class='lbl' style='width: 200px !important;'>$_strahlen_gesamtdosis_lbl</td>
	            <td class='edt'>$_strahlen_gesamtdosis `$smarty.config.lbl_gyghd`</td>
	         </tr>
	         <tr>
	            <td class='lbl' style='width: 200px !important;'>$_strahlen_einzeldosis_lbl</td>
	            <td class='edt'>$_strahlen_einzeldosis `$smarty.config.lbl_gy`</td>
	         </tr>

	      </table>
	      "}
	      </td>
	   </tr>
	</table>

	{getView field='sonstige,sonstige_indiziert,sonstige_intention,sonstige_extern,andere,andere_indiziert,andere_intention,andere_extern,chemo,chemo_indiziert,chemo_intention,chemo_extern,immun,immun_indiziert,immun_intention,immun_extern,ah,ah_indiziert,ah_intention,ah_extern,ah_therapiedauer_prostata,ah_therapiedauer_monate'}
        <table class="formtable">
            <tr>
                <td class='subhead'><strong>{#lbl_therapieart#}</strong></td>
                <td class='subhead' width="40px"><strong>{#lbl_indiziert#}</strong></td>
                <td class='subhead' width="40px"><strong>{#lbl_geplant#}</strong></td>
                <td class='subhead'><strong>{#lbl_schema#}</strong></td>
                <td class='subhead' width="210px"><strong>{#lbl_intention#}</strong></td>
                <td class='subhead' align='center' width="52px"><strong>{#lbl_extern#}</strong></td>
            </tr>

            {getView field='chemo,chemo_indiziert,chemo_intention,chemo_extern'}
                <tr>
                   <td style='background-color:#E6E6E6'><strong>{$_chemo_lbl}</strong></td>
                   <td class='edt'>{$_chemo_indiziert}</td>
                   <td class='edt'>{$_chemo}</td>
                   <td class='edt'>{$_chemo_id}</td>
                   <td class='edt'>{$_chemo_intention}</td>
                   <td class='edt' align='center'>{$_chemo_extern}</td>
                </tr>
             {/getView}
             {getView field='immun,immun_indiziert,immun_intention,immun_extern'}
                <tr>
                   <td style='background-color:#E6E6E6'><strong>{$_immun_lbl}</strong></td>
                   <td class='edt'>{$_immun_indiziert}</td>
                   <td class='edt'>{$_immun}</td>
                   <td class='edt'>{$_immun_id}</td>
                   <td class='edt'>{$_immun_intention}</td>
                   <td class='edt' align='center'>{$_immun_extern}</td>
                </tr>
             {/getView}
             {getView field='ah,ah_indiziert,ah_intention,ah_extern,ah_therapiedauer_prostata,ah_therapiedauer_monate'}
                <tr>
                   <td style='background-color:#E6E6E6' {getView field='ah_therapiedauer_prostata, ah_therapiedauer_monate'} rowspan="2" {/getView}><strong>{$_ah_lbl}</strong></td>
                   <td class='edt'>{$_ah_indiziert}</td>
                   <td class='edt'>{$_ah}</td>
                   <td class='edt'>{$_ah_id}</td>
                   <td class='edt'>{$_ah_intention}</td>
                   <td class='edt' align='center'>{$_ah_extern}</td>
                </tr>

                {getView field='ah_therapiedauer_prostata, ah_therapiedauer_monate'}
                    <tr>
                        <td class='edt' colspan='2'>{$_ah_therapiedauer_prostata_lbl}</td>
                        <td class='edt' colspan='5'>{$_ah_therapiedauer_prostata} {$_ah_therapiedauer_monate_lbl} {$_ah_therapiedauer_monate} {#lbl_monate#}</td>
                    </tr>
                {/getView}
             {/getView}
             {getView field='andere,andere_indiziert,andere_intention,andere_extern'}
                <tr>
                   <td style='background-color:#E6E6E6'><strong>{#lbl_andere#}</strong></td>
                   <td class='edt'>{$_andere_indiziert}</td>
                   <td class='edt'>{$_andere}</td>
                   <td class='edt'>{$_andere_id}</td>
                   <td class='edt'>{$_andere_intention}</td>
                   <td class='edt' align='center'>{$_andere_extern}</td>
                </tr>
             {/getView}
             {getView field='sonstige,sonstige_indiziert,sonstige_intention,sonstige_extern'}
                <tr>
                   <td style='background-color:#E6E6E6'><strong>{#lbl_sonstige#}</strong></td>
                   <td class='edt'>{$_sonstige_indiziert}</td>
                   <td class='edt'>{$_sonstige}</td>
                   <td class='edt'>{$_sonstige_schema}</td>
                   <td class='edt'>{$_sonstige_intention}</td>
                   <td class='edt' align='center'>{$_sonstige_extern}</td>
                </tr>
             {/getView}
        </table>
    {/getView}

	<table class="formtable">
	{html_set_header caption=#head_weitere_angaben#    class="head"}

	{html_set_row field="watchful_waiting"             caption=$_watchful_waiting_lbl            input=$_watchful_waiting}
	{html_set_row field="active_surveillance"          caption=$_active_surveillance_lbl         input=$_active_surveillance}
	{html_set_row field="abweichung_leitlinie"         caption=$_abweichung_leitlinie_lbl        input=$_abweichung_leitlinie}
	{html_set_row field="nachsorge"                    caption=$_nachsorge_lbl                   input=$_nachsorge}
	{html_set_row field="abweichung_leitlinie_grund"   caption=$_abweichung_leitlinie_grund_lbl  input=$_abweichung_leitlinie_grund}
	{html_set_html field="studie" html="
	   <tr>
	      <td class='msg' colspan='2'>
	         <table class='inline-table'>
	         <tr>
	            <td class='lbl' style='width:35%;' rowspan='2'>$_studie_lbl</td>
	            <td class='edt'>$_studie</td>
	            <td class='edt'>$_vorlage_studie_id_lbl <br/> $_vorlage_studie_id</td>
	         </tr><tr>
	            <td class='edt' colspan='2'>$_studie_abweichung_lbl <br/> $_studie_abweichung</td>
	         </tr>
	         </table>
	      </td>
	   </tr>
	"}
	{html_set_row field="nachbehandler_id"             caption=$_nachbehandler_id_lbl             input=$_nachbehandler_id}
   {html_set_row field="palliative_versorgung"        caption=$_palliative_versorgung_lbl        input=$_palliative_versorgung}
   {html_set_row field="datum_palliative_versorgung"  caption=$_datum_palliative_versorgung_lbl  input=$_datum_palliative_versorgung}

   {html_set_header caption=#bem_palliative_versorgung#    class="subhead" field="bem_palliative_versorgung"}
   {html_set_header caption=$_bem_palliative_versorgung    class="edt" field="bem_palliative_versorgung"}

	{html_set_header caption=#head_bem#    class="head" field="bem"}
	{html_set_header caption=$_bem         class="edt" field="bem"}
</table>

{if $from == 'konferenz'}
	{html_set_ajax_buttons modus=$button}
{else}
	{html_set_buttons modus=$button}
{/if}

<div>
{if in_array($from, array('konferenz', 'konferenz_patient')) == true}
	<input type="hidden" name="grundlage" 			   value="{$fields.grundlage.value.0}" />
	<input type="hidden" name="zeitpunkt"              value="{$fields.zeitpunkt.value.0}" />
	<input type="hidden" name="konferenz_patient_id"   value="{$fields.konferenz_patient_id.value.0}" />
{/if}

{if $from == 'konferenz'}
	<input type="hidden" name="sess_pos" value="{$sess_pos}" />
{/if}

<input type="hidden" value="{$from}" name="from" />
{$_therapieplan_id}
{$_patient_id}
{$_erkrankung_id}
</div>
