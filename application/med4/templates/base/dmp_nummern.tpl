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

<table class="formtable" >
   {html_set_header class="head"       caption=#head_dmpnummern#}
   {html_set_header class="msgbox"     caption=$info}
   {if !$action_done}
      {html_set_row caption=$_nr_von_lbl  input=$_nr_von}
      {html_set_row caption=$_nr_bis_lbl  input=$_nr_bis}
      <tr>
         <td class="edt" colspan="2" align="center">
            <input type='submit' class='button_large' name='action[insert]' value='Hinzufügen' alt='Hinzufügen'>
            <input type='submit' class='button_large' name='action[delete]' value='Löschen' alt='Löschen'>
         </td>
      </tr>
   {/if}
</table>
