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

{html_set_header caption=#head_user# class=head}
<tr>
    <td class="lbl">{#lbl_user#}</td>
    <td class="edt">{$user}</td>
</tr>
<tr>
    <td class="lbl">{#lbl_loginname#}</td>
    <td class="edt"><b>{$loginname}</b></td>
</tr>
{if strlen($telefon)}
<tr>
    <td class="lbl">{#lbl_telefon#}</td>
    <td class="edt">{$telefon}</td>
</tr>
{/if}
<tr>
    <td class="lbl">{#lbl_createtime#}</td>
    <td class="edt">{$_createtime_value|date_format:'%d.%m.%Y um %H:%M Uhr'}</td>
</tr>
{html_set_header caption=#head_organisation# class=head}
{if strlen($selectedOrg) > 0}
    <tr>
        <td class="lbl">{#lbl_org#}</td>
        <td class="edt">
        {foreach from=$orgDD item=org}
            {if $org.id == $selectedOrg}
                <b>{$org.name}</b>
                {if strlen($org.ort) > 0}
                    <br/>
                    <span style="font-size:8pt">({$org.ort})</span>
                {/if}
            {/if}
        {/foreach}
        </td>
    </tr>
{else}
    <tr>
        <td colspan="2" style="padding:0px !important">
            <div class="info" style="margin: 0 !important">
                <table border="0">
                    <tr>
                        <td style="border:0 !important">{#info_org_h#}</td>
                        <td style="border:0 !important">{#info_org#}</td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td class="lbl">{#lbl_org#}</td>
        <td class="edt">
            <b>{$_org_name_value}</b>
            {if strlen($_org_ort_value) > 0}
                <br/><span style="font-size:8pt">({$_org_ort_value})</span>
            {/if}
            <input type="hidden" name="org_ort" value="{$_org_ort_value}"/>
            <input type="hidden" name="org_name" value="{$_org_name_value}"/>
        </td>
    </tr>
    <tr>
        <td class="lbl">{#lbl_create_org#}<br/>
        <span style="font-size:8pt">{#lbl_create_org_info#}</span></td>
        <td class="edt"><input style="margin:0px !important" class="button btnconfirm" type="submit" alt="{#btn_lbl_insert#}" value="{#btn_lbl_insert#}" name="action[org]">

        </td>
    </tr>
{/if}
{html_set_header caption=#head_organisation_z# class=head}
    <tr>
        <td class="lbl">{$_org_id_lbl} <span style="font-family:Verdana, Arial;color:red ">*</span></td>
        <td class="edt">
            <select name="org_id">
                <option value=""></option>
            {foreach from=$orgDD item=org}
                <option value="{$org.id}" {if strlen($selectedOrg) > 0 && $selectedOrg == $org.id}selected="true"{/if}>{$org.name}{if strlen($org.ort)} ({$org.ort}){/if}
                </option>
            {/foreach}
            </select>

            {if $fillOrg}
            <div class="bubbleTrigger" style="display:inline;">
                <button class="trigger trigger-err" type="button"><!-- --></button>
                <div class="bubbleInfo border-err" style="top: 210px; left: 1175.13px; display: none;"><div style="max-width:325px;float:left;">
                    <img alt="Error" src="./media/img/base/editdelete.png"> {#err_req#}
                </div></div>
            </div>
            {/if}
            <br/>
            <span style="font-size:8pt">{#lbl_org_id_info#}</span>
        </td>
    </tr>
    <tr>
        <td class="lbl">{#lbl_role#}</td>
        <td class="edt"><b>{$regRole}</b></td>
    </tr>
</table>

{html_set_buttons modus=$button}
<div>
   {$_user_reg_id}
</div>
