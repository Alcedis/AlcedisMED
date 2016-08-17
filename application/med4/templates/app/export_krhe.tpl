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

{if $view == 'error'}
      <table {#table_login#} width="50%" align="center" cellspacing="1" style="padding-bottom:20px;">
      <tr>
         <td class="head" colspan="2" align="center">{#head_krhe#}</td>
      </tr>
      <tr>
         <td class="edt" colspan="2" align="center">
            <div style="margin:15px;">
               {#info_keine_daten_exportierbar#}
            </div>
            <div style="margin:15px;">
               <a href="index.php?page=export_krhe">{#lbl_zurueck#}</a>
            </div>
         </td>
      </tr>
   </table>
{elseif $view == 'result'}
      <table width="100%" {#table_form#}>
      <tr>
         <td class="head" colspan="2" align="center">{#head_export_file#}</td>
      </tr>
      <tr>
         <td class="edt" colspan="2" align="center">
            <!-- div class="msgbox" -->

               {if $cnt_patient_invalid}
                  <div style="font-size:14px; font-weight:bold; margin:10px">{$info_patienten_invalid}</div>
                  <div style="width: 400px; text-align:left; list-style-image:url(media/img/base/editdelete.png)">
                     <ul>
                     {foreach from=$result.invalid item=ekr}
                        <li><a class="visible-anchor" href="#" onclick="execute_request( this, 'export_krhe_msg&amp;export_id={$export_id}&amp;ekr_id={$ekr.ekr_id}', null, [] )" >{$ekr.bez}</a></li>
                     {/foreach}
                     </ul>
                  </div>
               {/if}

               {if $cnt_patient_valid}
                  <div style="font-size:14px; font-weight:bold; margin:10px">{$info_patienten_valid}</div>
                  <div style="width: 300px; text-align:left; list-style-image:url(media/img/base/ok_small.png)">
                     <ul>
                     {foreach from=$result.valid item=ekr}
                        <li>{$ekr.bez}</li>
                     {/foreach}
                     </ul>
                  </div>
                  <table style="margin:30px;">
                  <tr>
                     <td colspan="2" align="center" style="font-size:14px; font-weight:bold;">
                        {#lbl_download#}
                     </td>
                  </tr>
                  <tr>
                     <td><a href="{$zip_url}">{#img_file#}</a></td>
                     <td><a class="visible-anchor" href="{$zip_url}" style="font-size:14px; font-weight:bold; margin:5px">{$zip_filename}</a></td>
                  </tr>
                  </table>
               {/if}

            <!-- /div -->
         </td>
      </tr>
   </table>
{else}
   <table class="formtable">
		{html_set_header caption=#head_zeitraum# class="head"}
		{html_set_row    caption=$_sel_melde_user_id_lbl input=$_sel_melde_user_id}
		{html_set_row    caption=$_sel_datum_von_lbl     input=$_sel_datum_von}
		{html_set_row    caption=$_sel_datum_bis_lbl     input=$_sel_datum_bis}
		<tr>
			<td class="edt" colspan="2" align="center">
				{html_set_buttons modus='export' class='button_large' table=false}
			</td>
		</tr>
	</table>
{/if}