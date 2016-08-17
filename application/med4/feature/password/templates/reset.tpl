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

{if $reseted === true}
    {if isset($app_login_logo) === true}
        <table border="0" style="width:100%">
            <tr align="center">
                <td>
                    <img src="data:image/{$app_login_logo_img_type};base64,{$app_login_logo}" alt="" title=""/>
                </td>
            </tr>
        </table>
    {/if}
    <table width="100%">
        <tr>
            <td align="center">{#msg_password_reseted#}</td>
        </tr>
    </table>
{else}
    <table class="formtable">
    {html_set_header caption=#head_benutzername# class=head}
    <tr>
        <td colspan="2" style="padding:0">
            <div class="info-msg" style="margin:0">
                {#msg_info#}
            </div>
        </td>
    </tr>
    <tr>
        <td class="lbl" style="width: 20%!important;">
            {#username#}
            <span style="font-family:Verdana, Arial;color:red">*</span><br/>
        </td>
        <td class="edt">
            <input name="username" type="text" value="{$username}"/>
            {if $uFail || $nFail || $eFail}
                <div style='display:inline;' class='bubbleTrigger'>
                    <button type="button" class="trigger trigger-err"><!-- --></button>
                    <div class='bubbleInfo border-err'>
                        <div style='max-width:325px;float:left;'>
                            <img src='./media/img/base/editdelete.png' alt='Error' />
                            {if $uFail}
                                {#valid_fill_username#}
                            {elseif $nFail}
                                {#valid_wrong_username#}
                            {elseif $eFail}
                                {#valid_no_email#}
                            {/if}
                        </div>
                    </div>
                </div>
            {/if}
        </td>
    </tr>
    <tr>
        <td class="lbl">
            {#captcha#}
            <span style="font-family:Verdana, Arial;color:red">*</span>
        </td>
        <td class="edt"><img src="data:image/png;base64,{$captcha}" alt="" title=""/><br/>
            <input type="text" name="captcha" size="15">
            {if $cFail}
                <div style='display:inline;' class='bubbleTrigger'>
                    <button type="button" class="trigger trigger-err"><!-- --></button>
                    <div class='bubbleInfo border-err'>
                        <div style='max-width:325px;float:left;'>
                            <img src='./media/img/base/editdelete.png' alt='Error' />
                            {#valid_wrong_captcha#}
                        </div>
                    </div>
                </div>
            {/if}
        </td>
    </tr>
    <tr>
        <td colspan="2" class="edt">
            {#msg_reset#}
        </td>
    </tr>
    </table>
    <br/>
    {html_set_buttons modus=$button class=button}
{/if}