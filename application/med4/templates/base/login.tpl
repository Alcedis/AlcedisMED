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

{config_load file=$smarty.const.FILE_CONFIG_DEFAULT section="login"}

{if isset($app_login_logo) === true}
<table border="0" style="width:100%">
	<tr align="center">
		<td>
			<img src="{$app_login_logo}" alt="logo" title="Logo"/>
		</td>
	</tr>
</table>
{/if}

{if $dontshowlogin !== true}

<table class="logintable">
<tr>
   <td class="head logintable-top" colspan="2" align="center">{$caption}</td>
</tr>

<tr>
    <td class="lbl" style="width:50%">
        <div style="float:left">
            <img src="media/img/base/username.png" alt="username" />
        </div>
        <div style="float:left; padding-left:5px">
            {if $allowRegistration === true}
                {#loginname_reg#}
            {else}
                {#loginname#}
            {/if}
        </div>
    </td>
	<td class="edt">
            <input class="input" type="text" name="loginname" value="{$loginname}" size="28"/>
        </td>
</tr>
<tr>
    <td class="lbl">
        <div style="float:left">
            <img src="media/img/base/key_small.png" alt="passwort" />
        </div>
        <div style="float:left; padding-left:5px">
            {#pwd#}
        </div>
    </td>
    <td class="edt">
        <input class="input" type="password" name="pwd" value="" size="28"/>
    </td>
</tr>
<tr>
    {if $allowRegistration === true}
    <td class="edt">
        <div style="float:left;padding-top:1px">
            <img src="media/img/base/list_small.png" alt="info" />
        </div>
        <div style="float:left; padding-left:5px"">
            <a href="index.php?page=registration&amp;feature=konferenz">
                {#lbl_register#}
            </a>
       </div>
    </td>
    {/if}
    <td colspan="{if $allowRegistration === true}1{else}2{/if}" class="edt">
        <div style="float:left;padding-top:3px">
        {if $allowPasswordReset === true}
                <img src="media/img/base/letter.png" alt="info" />
            </div >
            <div style="float:left; padding-left:5px"">
                <a href="index.php?page=reset&amp;feature=password">
        {else}
            {config_load file=$smarty.const.FILE_CONFIG_SERVER section="mail"}
                <img src="media/img/base/letter.png" alt="email" />
            </div>
            <div style="float:left; padding-left:5px">
                <a href="mailto:{#support_mail#}?subject={#app_name#}">
        {/if}
            {#lbl_password_forgot#}
            </a>
         </div>
    </td>
</tr>
<tr>
    <td class="edt logintable-bottom" colspan="2" align="center">
        {html_set_buttons modus="$modus" height="40px" table=false}
    </td>
</tr>
</table>

<script type="text/javascript">
<!--
   {if strlen($loginname)}
		document.getElementsByName('pwd')[0].focus();
	{else}
		document.getElementsByName('loginname')[0].focus();
	{/if}
-->
</script>
{/if}

<br/>
<br/>

<table class="infotable">
    <tr>
        <td class="head infotable-top" align="center">{#attention#}</td>
    </tr>
    <tr>
        <td class="infotable-bottom">
            {#info_ie8#}
        </td>
    </tr>
</table>
<br/>
<br/>
