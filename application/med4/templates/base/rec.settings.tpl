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

<table width="100%">
    <tr>
        <td style="width:10%; text-align: center">
            <a href="index.php?page=rec.settings_hl7">
                <img src="media/img/app/hl7.png" alt=""/>
            </a>
        </td>
        <td style="width:10%; text-align: center">
            <a href="index.php?page=list.settings_pacs">
                <img src="media/img/app/pacs.png" alt=""/>
            </a>
        </td>
        <td style="width:10%; text-align: center">
            <a href="index.php?page=list.settings_export">
                <img src="media/img/app/export.png" alt=""/>
            </a>
        </td>
        <td style="width:10%; text-align: center">
            <a href="index.php?page=list.settings_import">
                <img src="media/img/app/import.png" alt=""/>
            </a>
        </td>
        <td style="width:10%; text-align: center">
            <a href="index.php?page=list.settings_report">
                <img src="media/img/app/pacs.png" alt=""/>
            </a>
        </td>
        <td></td>
    </tr>
    <tr>
        <td style="text-align: center">
            <a href="index.php?page=rec.settings_hl7">
                HL7
            </a>
        </td>
        <td style="text-align: center">
            <a href="index.php?page=list.settings_pacs">
                PACS
            </a>
        </td>
        <td style="text-align: center">
            <a href="index.php?page=list.settings_export">
                Exports
            </a>
        </td>
        <td style="text-align: center">
            <a href="index.php?page=list.settings_import">
                Imports
            </a>
        </td>
        <td style="text-align: center">
            <a href="index.php?page=list.settings_report">
                Auswertungen
            </a>
        </td>
        <td></td>
    </tr>
</table>

<div id="accordion">
    <h3><a href="#">{#head_version#}</a></h3>
    <div>
        <table width="100%">
            {html_set_row caption=$_software_version_lbl input=$_software_version}
            {html_set_row caption=$_software_title_lbl input=$_software_title}
            <tr>
                <td colspan="2" class="subhead">{#lbl_customer_specific_name#}</td>
            </tr>
            {html_set_row caption=$_software_custom_title_lbl input=$_software_custom_title}
        </table>
    </div>

    <h3><a href="#">{#head_login#}</a></h3>
    <div>
        <table width="100%">
            {html_set_row caption=$_allow_password_reset_lbl input=$_allow_password_reset}
            {html_set_row caption=$_allow_registration_lbl input=$_allow_registration}
            {html_set_row caption=$_show_last_login_lbl input=$_show_last_login}
            <tr>
                <td class="lbl">{$_user_max_login_lbl}</td>
                <td class="edt">{$_user_max_login} {#lbl_deaktivieren#} {$_user_max_login_deactivated}</td>
            </tr>

            {if strlen($logo)}
                <tr>
                    <td class="lbl">{$_logo_lbl}</td>
                    <td class="edt">
                        <img class='thumb-img' alt='thumb' src='index.php?page=rec.settings&amp;type=thumbnail&amp;settings_id={$_settings_id_value}'/>
                        <input type='hidden' name='logo' value="{$logo}"/> <input type="hidden" name="ext" value="{$ext}"/>
                        <div style="float:right">{$btn_unset_file}</div>
                    </td>
                </tr>
            {else}
                {html_set_row     field="logo"      caption=$_logo_lbl      input="<input type='file' name='logo'/>" }
            {/if}
        </table>
    </div>

    <h3><a href="#">{#head_app#}</a></h3>
    <div>
        <table style="width:100%">
            <tr>
                <td class="head" colspan="3">{#head_patient#}</td>
            </tr>
            {html_set_row caption=$_auto_patient_id_lbl input=$_auto_patient_id}
            {html_set_row caption=$_patient_initials_only_lbl input=$_patient_initials_only}
            <tr>
                <td class="lbl">{#lbl_patient#}</td>
                <td class="edt">
                    {$_pat_list_first}<br/><br/>
                    {$_pat_list_second}
                </td>
            </tr>
            <tr>
                <td class="head" colspan="3">{#head_application#}</td>
            </tr>
            <tr>
                <td class="lbl">{#lbl_cookie_reset#}</td>
                <td class="edt">
                    <input type="submit" class="button" name="action[reset_cookie]" value="{#btn_lbl_delete#}"/>
                </td>
           </tr>
           {html_set_row caption=$_extended_swage_lbl input=$_extended_swage}
           {html_set_row caption=$_show_pictures_lbl input=$_show_pictures}
            {html_set_row caption=$_check_ie_lbl input=$_check_ie}
           {html_set_row caption=$_codepicker_top_limit_lbl input=$_codepicker_top_limit}
           {html_set_row caption=$_deactivate_range_check_lbl input=$_deactivate_range_check}
           {html_set_row caption=$_fake_system_date_lbl input=$_fake_system_date}
        </table>
    </div>

    <h3><a href="#">{#head_erkrankung#}</a></h3>
    <div>
        <table>
            <tr>
               <td>
                  {$_erkrankung_b} {$_erkrankung_b_lbl}<br/>
                  {$_erkrankung_d} {$_erkrankung_d_lbl}<br/>
                  {$_erkrankung_gt} {$_erkrankung_gt_lbl}<br/>
                  {$_erkrankung_h} {$_erkrankung_h_lbl}<br/>
                  {$_erkrankung_kh} {$_erkrankung_kh_lbl}<br/>
                  {$_erkrankung_leu} {$_erkrankung_leu_lbl}<br/>
                  {$_erkrankung_lg} {$_erkrankung_lg_lbl}<br/>
                  {$_erkrankung_lu} {$_erkrankung_lu_lbl}
               </td>
               <td>
                  {$_erkrankung_ly} {$_erkrankung_ly_lbl}<br/>
                  {$_erkrankung_m} {$_erkrankung_m_lbl}<br/>
                  {$_erkrankung_nt} {$_erkrankung_nt_lbl}<br/>
                  {$_erkrankung_oes} {$_erkrankung_oes_lbl}<br/>
                  {$_erkrankung_p} {$_erkrankung_p_lbl}<br/>
                  {$_erkrankung_pa} {$_erkrankung_pa_lbl}<br/>
                  {$_erkrankung_snst} {$_erkrankung_snst_lbl}<br/>
                  {$_erkrankung_sst} {$_erkrankung_sst_lbl}
               </td>
            </tr>
        </table>
    </div>

    <h3><a href="#">{#head_features#}</a></h3>
    <div>
        <table width="100%">
            <tr>
                <td class="head" colspan="3">{#head_rights#}</td>
            </tr>
            <tr>
                <td colspan="3">
                    {$_fastreg} {$_fastreg_lbl} {$_fastreg_role}
                </td>
            </tr>
            <tr>
                <td class="subhead" colspan="3">{#subhead_roles#}</td>
            </tr>
            <tr>
                <td>
                    {$_rolle_konferenzteilnehmer} {$_rolle_konferenzteilnehmer_lbl}
                </td>
                <td colspan="2">
                    {$_rolle_dateneingabe} {$_rolle_dateneingabe_lbl}
                </td>
            </tr>
            <tr>
                <td class="head" colspan="3">{#head_tools#}</td>
            </tr>
            <tr>
                <td colspan="3">
                    {$_tools} {$_tools_lbl}
                </td>
            </tr>
            <tr>
                <td class="head" colspan="3">{#head_weitere_formulare#}</td>
            </tr>
            <tr>
                <td>
                    {$_dokument} {$_dokument_lbl} <br/>
                </td>
                <td colspan="2">
                    {$_zweitmeinung} {$_zweitmeinung_lbl} <br/>
                </td>
            </tr>
            <tr>
                <td class="head" colspan="3">{$_konferenz_lbl}</td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class='info-msg'>{#msg_status#}</div>
                </td>
            </tr>
            <tr>
                <td class="head" colspan="3">{$_konferenz_lbl}</td>
            </tr>
            <tr>
                <td colspan="3">
                  {$_konferenz} {$_konferenz_lbl} <br/>
                  {$_email_attachment} {$_email_attachment_lbl} <br/>
                  {$_pacs} {$_pacs_lbl} - {$_max_pacs_savetime_lbl} {$_max_pacs_savetime} {#lbl_tageinfo#}
                </td>
            </tr>
            <tr>
                <td style="width:300px" class="head">{#lbl_dkg#}</td>
                <td class="head">{#lbl_exports#}</td>
                <td class="head">{#lbl_imports#}</td>
            </tr>
                <td valign="top">
                    {$_feature_dkg_oz}         {$_feature_dkg_oz_lbl}        <br/>
                    {$_feature_dkg_b}          {$_feature_dkg_b_lbl}         <br/>
                    {$_feature_dkg_d}          {$_feature_dkg_d_lbl}         <br/>
                    {$_feature_dkg_gt}         {$_feature_dkg_gt_lbl}        <br/>
                    {$_feature_dkg_h}          {$_feature_dkg_h_lbl}         <br/>
                    {$_feature_dkg_lu}         {$_feature_dkg_lu_lbl}        <br/>
                    {$_feature_dkg_p}          {$_feature_dkg_p_lbl}         <br/>
                    {$_interface_oncobox_darm} {$_interface_oncobox_darm_lbl}<br/>
                    {$_interface_oncobox_prostata} {$_interface_oncobox_prostata_lbl}<br/>
                </td>
                <td valign="top">
                    {$_interface_gekid}        {$_interface_gekid_lbl}       <br/>
                    {$_interface_gekid_plus}   {$_interface_gekid_plus_lbl}  <br/>
                    {$_interface_ekr_h}        {$_interface_ekr_h_lbl}       <br/>
                    {$_interface_ekr_rp}       {$_interface_ekr_rp_lbl}      <br/>
                    <!--{$_interface_ekr_sh}       {$_interface_ekr_sh_lbl}      <br/>-->
                    {$_interface_krbw}         {$_interface_krbw_lbl}        <br/>
                    {$_interface_gkr}          {$_interface_gkr_lbl}         <br/>
                    <!--{$_interface_adt}          {$_interface_adt_lbl}         <br/>-->
                    <!--{$_interface_gtds}         {$_interface_gtds_lbl}        <br/>-->
                    {$_interface_onkeyline}    {$_interface_onkeyline_lbl}   <br/>
                    {$_interface_dmp_2014}     {$_interface_dmp_2014_lbl}    <br/>
                    {$_interface_qs181}        {$_interface_qs181_lbl}       <br/>
                    <!--{$_interface_eusoma}       {$_interface_eusoma_lbl}      <br/>-->
                    {$_interface_wbc}          {$_interface_wbc_lbl}         <br/>
                    {$_interface_wdc}          {$_interface_wdc_lbl}         <br/>
                    <!--{$_interface_onkonet}      {$_interface_onkonet_lbl}     <br/>-->
                    {$_interface_patho_e}      {$_interface_patho_e_lbl}     <br/>
                    {$_interface_hl7_e}        {$_interface_hl7_e_lbl}       <br/>
                    <hr/>
                    {$_interface_kr_he}        {$_interface_kr_he_lbl}       <br/>
                    <hr/>
                    {$_historys_path_lbl}                                    <br/>
                    {$_historys_path}
                </td>
                <td valign="top">
                    {$_interface_patho_i}        {$_interface_patho_i_lbl}    <br/>
                </td>
            </tr>
        </table>
    </div>
    <h3><a href="#">Statusvalidierung</a></h3>
    <div>
        <table width="100%">
            <tr>
                <td width="22%">
                    <input type="submit" class="button" id="statusreset" name="action[resetstatus]" value="Reset"/>
                    <input type="submit" class="button" id="statusvalidate" name="action[validatestatus]" value="Validieren"/>
                </td>
                <td width="32%">
                    {#lbl_last_status#} {$status_last}
                </td>
                <td>
                    {#lbl_status_count#} {$status_count}
                </td>
            </tr>
        </table>
    <div id="validationstatus"></div>
    </div>
</div>

{html_set_buttons modus=$button}

<div>
    {$_settings_id}
</div>
