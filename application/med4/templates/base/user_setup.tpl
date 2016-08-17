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
   {html_set_header class="head"    caption=#head_einstellungen#}
   {if $first_login != 1}
      {html_set_header class="msgbox"  caption=#lbl_pwd_hinweis#}
   {/if}
   {html_set_row  caption=$_pwd_old_lbl    input=$_pwd_old}
   {html_set_row  caption=$_pwd_new1_lbl   input=$_pwd_new1}
   {html_set_row  caption=$_pwd_new2_lbl   input=$_pwd_new2}
</table>

{html_set_buttons modus=$button}

<div>
   <input type="hidden" name="page"  		value="{$page}"/>
   <input type="hidden" name="FormName" 	value="Record"/>
</div>