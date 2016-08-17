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
   {html_set_header class="head" caption=#head_qsmed#}
   <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg" style="margin-bottom:0 !important">
                {#info_range#}
            </div>
        </td>
   </tr>
   {html_set_row    field="sel_datum_von"     caption=$_sel_datum_von_lbl     input=$_sel_datum_von}
   {html_set_row    field="sel_datum_bis"     caption=$_sel_datum_bis_lbl     input=$_sel_datum_bis}
   {if $export_done}
      {html_set_header class="head" caption=#head_downlad#}
      <tr>
         <td class="edt" colspan="2" style="padding:0 !important">
            <div class="info-msg" style="margin-bottom:0 !important">{#msg_export_done#}</div>
         </td>
      </tr>
      <tr>
         <td class="lbl">
            {#lbl_download#}
         </td>
         <td class="edt">
            <input class="dont_prevent_double_save filepackage" type="submit" alt="" value="{$filename}" name="action[download][{$filename}]">
         </td>
      </tr>
   {elseif $export_done === false}
      {html_set_header class="head" caption=#head_error#}
      <tr>
         <td class="edt" colspan="2" style="padding:0 !important">
            <div class="warn" style="margin-bottom:0 !important">{#msg_export_error#}</div>
         </td>
      </tr>
   {/if}
   <tr>
      <td class="edt" colspan="2" align="center">
          <table>
              <tr>
                  <td style="border: 0px;">
                      {html_set_buttons modus='export' class='button_large' table=false}
                      {html_set_buttons modus='cancel' class='button' table=false}
                  </td>
                  <td style="border: 0px;">
                      <div id="buttonbar">
                          <a class="button_large" style="margin-top: 3px;" href="index.php?page=history&feature=export&exportname=qsmed">{#start_history#}</a>
                      </div>
                  </td>
              </tr>
          </table>
      </td>
   </tr>
</table>
