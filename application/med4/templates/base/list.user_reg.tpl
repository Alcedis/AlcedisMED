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

{if count($fields.user_reg_id.value) > 0}
    <table class="listtable bfl">
    	<tr>
    		<td class="head edit unsortable">
        	   <input type="text" class="bfl-buffer" name="buffer-id" value='{literal}{"add":{},"remove":{}}{/literal}' />
    		</td>
    		<td class="head ext-search cookie-nachname">{#lbl_nachname#}</td>
    		<td class="head ext-search cookie-vorname">{#lbl_vorname#}</td>
    		<td class="head ext-search cookie-loginname">{#lbl_loginname#}</td>
    		<td class="head ext-search cookie-telefon">{#lbl_telefon#}</td>
    		<td class="head ext-search cookie-org">{#lbl_organisation#}</td>
    		<td class="head unsortable" align="right">{#lbl_createtime#}</td>
    	</tr>
    	
    	{include file=base/list/list.user_reg.tpl}
    	
    </table>
{/if}