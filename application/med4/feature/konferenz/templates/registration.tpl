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

{if $registered === true}
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
            <td align="center">{#msg_registration_successfull#}</td>
        </tr>
    </table>
{else}
    {include file="base/rec.user.tpl"}
{/if}