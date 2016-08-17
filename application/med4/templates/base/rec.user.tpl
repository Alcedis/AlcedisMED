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

{if $form_id}
	<table class="formtable" style="margin-bottom:6px">
	{html_set_header class="head" caption=#head_aktivitaet#}
		<tr>
		   <td class='lbl'>{$_inaktiv_lbl}</td>
		   <td class='edt'>
		   	{$_inaktiv} {if $stateButton !== false}<input style="margin:0 5px" type="submit" class="button" name="action[{$stateButton}]" value="{#btn_lbl_update#}" />{/if}
		   </td>
		</tr>
	</table>

	{if $inaktiv == true}
		<div class="warn">
			{#info_inaktiv#}
		</div>
	{else}
		<div class="green-info">
			{#info_aktiv#}
		</div>
	{/if}
{/if}

<table class="formtable">

{if $page == 'vorlage_arzt'}
    {html_set_header caption=#head_arzt# class=head}
{else}
    {html_set_header caption=#head_benutzer# class=head}
{/if}

{html_set_row caption="$_anrede_lbl, $_titel_lbl"  input="$_anrede $_titel"}
{html_set_row caption=$_adelstitel_lbl             input=$_adelstitel}
{html_set_row caption=$_nachname_lbl               input=$_nachname}
{html_set_row caption=$_vorname_lbl                input=$_vorname}

{html_set_header caption=#head_ident# class=head}
{html_set_row caption=$_fachabteilung_lbl          input=$_fachabteilung}
{html_set_row caption=$_teilnahme_dmp_lbl          input=$_teilnahme_dmp}
{html_set_row caption=$_teilnahme_netzwerk_lbl     input=$_teilnahme_netzwerk}
{html_set_row caption=$_kr_kennung_lbl             input=$_kr_kennung}
{html_set_row caption=$_kr_kuerzel_lbl             input=$_kr_kuerzel}
{html_set_row caption=$_vertragsarztnummer_lbl     input=$_vertragsarztnummer}
{html_set_row caption=$_lanr_lbl                   input=$_lanr}
{html_set_row caption=$_bsnr_lbl                   input=$_bsnr}
{html_set_row caption=$_efn_lbl                    input=$_efn add="$_efn_nz$_efn_nz_lbl"}

{if $accessRegistration === true}
    {html_set_header caption=#head_org_reg# class=head}
{else}
    {html_set_header caption=#head_org# class=head}
{/if}

{if $accessRegistration === true}
    <tr>
        <td class="lbl">{#org#} <span style="font-family:Verdana, Arial;color:red ">*</span></td>
        <td class="edt">{$_org_id}
        <div style="margin-bottom:0 !important;margin-top:5px" class="info-msg">
            {#msg_org#}
        </div>
        <table>
            <tr>
                <td style="padding:3px !important; border:0 !important">{#lbl_name#}</td>
                <td style="padding:3px !important; border:0 !important">{$_org_name}</td>
            </tr>
            <tr>
                <td style="padding:3px !important; border:0 !important">{#lbl_namenszusatz#}</td>
                <td style="padding:3px !important; border:0 !important">{$_org_namenszusatz}</td>
            </tr>
            <tr>
                <td style="padding:3px !important; border:0 !important">{#lbl_street#}</td>
                <td style="padding:3px !important; border:0 !important">{$_org_strasse} {$_org_hausnr}</td>
            </tr>
            <tr>
                <td style="padding:3px !important; border:0 !important">{#lbl_location#}</td>
                <td style="padding:3px !important; border:0 !important">{$_org_plz} {$_org_ort}</td>
            </tr>
            <tr>
                <td style="padding:3px !important; border:0 !important">{#lbl_telefon#}</td>
                <td style="padding:3px !important; border:0 !important">{$_org_telefon}</td>
            </tr>
            <tr>
                <td style="padding:3px !important; border:0 !important">{#lbl_telefax#}</td>
                <td style="padding:3px !important; border:0 !important">{$_org_telefax}</td>
            </tr>
            <tr>
                <td style="padding:3px !important; border:0 !important">{#lbl_email#}</td>
                <td style="padding:3px !important; border:0 !important">{$_org_email}</td>
            </tr>
            <tr>
                <td style="padding:3px !important; border:0 !important">{#lbl_website#}</td>
                <td style="padding:3px !important; border:0 !important">{$_org_website}</td>
            </tr>
            <tr>
                <td style="padding:3px !important; border:0 !important">{#lbl_staat#}</td>
                <td style="padding:3px !important; border:0 !important">{$_org_staat}</td>
            </tr>
            <tr>
                <td style="padding:3px !important; border:0 !important">{#lbl_bundesland#}</td>
                <td style="padding:3px !important; border:0 !important">{$_org_bundesland}</td>
            </tr>
        </table>
        </td>
    </tr>
{else}
    {html_set_row caption=$_org_lbl                    input=$_org}
{/if}

{html_set_header caption=#head_kontakt# class=head}

{html_set_row caption=$_strasse_lbl                input=$_strasse add="$_hausnr"}
{html_set_row caption=$_plz_lbl                    input=$_plz add="$_ort"}
{html_set_row caption=$_telefon_lbl                input=$_telefon}
{html_set_row caption=$_handy_lbl                  input=$_handy}
{html_set_row caption=$_telefax_lbl                input=$_telefax}
{if $accessRegistration !== true}
   {html_set_row caption=$_email_lbl                  input=$_email}
{/if}
{html_set_row caption=$_staat_lbl                  input=$_staat}

{if $accessData === true}
   {html_set_header caption=#head_zugangsdaten# class=head}
   {html_set_row caption=$_loginname_lbl              input=$_loginname}
   {html_set_row caption=$_pwd_lbl                    input=$_pwd}
   {html_set_row caption=$_pwd_change_lbl             input=$_pwd_change}
{/if}

{if $accessRegistration === true}
   {html_set_header caption=#head_zugangsdaten# class=head}
   <tr>
    <td class="lbl">{$_email_lbl}</td>
    <td class="edt">{$_email}<br/>
    {#username#}
    </td>
   </tr>

   {html_set_row caption=$_pwd_lbl                    input=$_pwd}
   {html_set_row caption=$_captcha_lbl                input=$_captcha}
{/if}

{html_set_header caption=#head_bankverbindung# class=head}
{html_set_row caption=$_bank_kontoinhaber_lbl      input=$_bank_kontoinhaber}
{html_set_row caption=$_bank_name_lbl              input=$_bank_name}
{html_set_row caption=$_bank_blz_lbl               input=$_bank_blz}
{html_set_row caption=$_bank_kontonummer_lbl	   input=$_bank_kontonummer}
{html_set_row caption=$_bank_verwendungszweck_lbl  input=$_bank_verwendungszweck}

{html_set_header caption=#head_bem# class=head}
{html_set_header caption=$_bem class=edt}

</table>

{html_set_buttons modus=$button}
<div>
	<input type="hidden" value="{$_candidate_value}" name="candidate" />
   {$_user_id}
</div>
