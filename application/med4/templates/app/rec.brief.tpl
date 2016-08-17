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
{html_set_header class="head" caption=#head_angaben#}
{html_set_row caption=$_vorlage_dokument_id_lbl input=$_vorlage_dokument_id}
{html_set_row caption=$_datum_lbl   input=$_datum}
{html_set_row caption=$_zeichen_sender_lbl   input=$_zeichen_sender}
{html_set_row caption=$_zeichen_empfaenger_lbl   input=$_zeichen_empfaenger}
{html_set_row caption=$_nachricht_lbl   input=$_nachricht}
{html_set_header class="head" caption=#head_empfaenger#}
{html_set_row caption=$_hauptempfaenger_id_lbl   input=$_hauptempfaenger_id}

<tr>
   <td class="lbl">{#lbl_weitere_empfaenger#}</td>
   <td class="edt">
      <img alt="picker-arzt" src="media/img/base/list-view.png" class="picker-img" onclick="openPicker('arzt', 'weitere_empfaenger', true)"/>
      <br/>
      <ul class="picker-ul" id="plist_weitere_empfaenger">
         {foreach from=$weitere_empfaenger item='empfaenger'}
            <li>{$empfaenger.prefix} {$empfaenger.nachname}, {$empfaenger.vorname}<img alt="del_img" src="media/img/base/editdelete.png" class="li-del-img"/><input type="hidden" name="weitere_empfaenger[]" value="{$empfaenger.user_id}"/></li>
         {/foreach}
         <li class="dummy" style="display:none;">&nbsp;</li>
      </ul>
   </td>
</tr>

{html_set_header class="head" caption=#head_absender#}
{html_set_row caption=$_abs_oberarzt_id_lbl  input=$_abs_oberarzt_id}
{html_set_row caption=$_abs_assistent_id_lbl input=$_abs_assistent_id}

{if count($images) > 0 && $fotoAktiv == true}
{html_set_header class="head" caption=#head_fotos#}

<tr>
   <td colspan="2" align="center" class="lbl">
      <table border="0" >
         <tr>
         {section name=i loop=$images}
            <td align="center" style="border: 0px;">
               <table border="0">
                  <tr>
                     <td align="center" style="border: 0px;">
                        {$images[i].bez}
                     </td>
                  </tr>
                  <tr>
                     <td align="center" style="border: 0px; height: 90px;">
                        <img id="img_{$images[i].foto_id}" alt="{$images[i].foto_id}" src="index.php?page=foto&amp;type=thumbnail&amp;thumb=80&amp;foto_id={$images[i].foto_id}">
                     </td>
                  </tr>
                  <tr>
                     <td align="center" style="border: 0px;">
                        <input type="checkbox" value="{$images[i].foto_id}" name="brief_fotos[]" {if isset($checkFotos) && in_Array($images[i].foto_id,$checkFotos)} checked="checked" {/if}>
                     </td>
                  </tr>
               </table>
            </td>

            {if (%i.index% + 1) % 5 == 0 }
               </tr><tr>
            {/if}

      {/section}
      </tr>
      </table>
   </td>
</tr>
{/if}

{html_set_header class="head" caption=#head_bemerkung#}
{html_set_header class="edt" caption=$_bem}

</table>


{if $form_id}
   <table class="formtable msg">
   {html_set_header caption=#subhead_datenstand# colspan="3"   class="head"}

      <tr>
         <td class="lbl">{#lbl_datum#}</td>
         <td class="edt"><div style="font-weight:bold; font-size:9pt">{$_datenstand_datum_value|date_format:'%d.%m.%Y'}</div></td>
         {if $statusLocked == false  && $protocolRights == true}
         <td rowspan="2" class="edt" style="width:240px" align="center">
            <span id="gen-report" style="display:none">
               <table class="button-container" border="0">
                  <tr>
                     <td><input type="submit" class="dont_prevent_double_save button_gen_rpt btnconfirm"       name="action[gen_report]"  value="" alt=""/></td>
                     <td><input type="submit" class="dont_prevent_double_save button_gen_rpt_text btnconfirm"  name="action[gen_report]"  value="{#lbl_aktualisieren#}" alt=""/></td>
                  </tr>
               </table>
            </span>
         </td>
         {/if}
      </tr>
      <tr>
         <td class="lbl">{#lbl_uhrzeit#}</td>
         <td class="edt"><div style="font-weight:bold; font-size:9pt">{$_datenstand_datum_value|date_format:'%H:%M'} {#lbl_uhr#}</div></td>
      </tr>

   </table>
   <table class="formtable msg">
      <tr>
         <td colspan="2" align="center" class="head">{#subhead_brief#}</td>
      </tr>
      <tr>
         <td align="left" class="edt" >

            <table class="button-container">
               <tr>
                  {if $statusLocked == false && $protocolRights == true}
                     <td>
                        <span id="save-report" style="display:none">
                           <table class="button-container" border="0">
                              <tr>
                                 <td><input type="submit" class="dont_prevent_double_save button_save_rpt" name="action[save_report]" value="" alt=""/></td>
                                 <td><input type="submit" name="action[save_report]" class="dont_prevent_double_save button_gen_rpt_text" value="{#lbl_speichern#}" alt=""/></td>
                              </tr>
                           </table>
                        </span>
                     </td>
                  {/if}
                  <td>
                     <span id="show-report" style="display:none">
                        <table class="button-container" border="0">
                           <tr>
                              <td><input type="submit" class="dont_prevent_double_save button_get_{$dokumentTyp}_rpt" name="action[report][{$dokumentTyp}]" value="" alt=""/></td>
                              <td><input type="submit" name="action[report][{$dokumentTyp}]" class="dont_prevent_double_save button_gen_rpt_text" value="{#lbl_anzeigen#}" alt=""/></td>
                           </tr>
                        </table>
                     </span>
                  </td>
               </tr>
            </table>

            <span id="load-report" style="padding-left:20px;">
               <img  src="media/img/ui/report-loader.gif" alt="" />
               <span class="load-report-text">Dokument wird erstellt</span>
            </span>

         </td>
      </tr>
   </table>
   {if $statusLocked == false  && $protocolRights == true}
	   <div>
	      <textarea cols="80" id="editor" name="editor" rows="10">{$xhtml}</textarea>
	   </div>
   {/if}
{/if}

{html_set_buttons modus=$button}
<div>
   {$_datenstand_datum}
   {$_xml_data}
   {$_brief_id}
   {$_patient_id}
   {$_erkrankung_id}
</div>