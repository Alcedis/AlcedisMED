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

{html_set_header class="head" caption=#head_fallkennzeichen# colspan=3}

<tr>
   <td class='subhead' style="width:30%;">{$_code_lbl}</td>
   <td class='subhead' style="width:45%;">{$_bez_lbl}</td>
   <td class='subhead'>{$_pos_lbl}</td>
</tr>
<tr>
   <td class='lbl'>{$_code}</td>
   <td class='lbl'>{$_bez}</td>
   <td class='lbl'>{$_pos}</td>
</tr>

</table>
{html_set_buttons modus=$button}

{$_vorlage_fallkennzeichen_id}