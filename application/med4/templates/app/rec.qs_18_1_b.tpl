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

{if $plNotPossible}
    <div class="info-msg" style="margin-bottom:0 !important">
        {#info_pl_not_possible#}
    </div>
{/if}

{if strlen($_qs_18_1_b_id_value) != 0}
<div class="msgbox">
    {#info_preload#}
</div>
{/if}

<table class="formtable">
   {html_set_header  caption=#head_basis#         class="head"}

{if strlen($_qs_18_1_b_id_value) == 0 && strlen($_aufenthalt_id_value) == 0 || $error_aufenthalt_id}
   <tr>
      <td colspan="2" style="padding:0 !important">
         <div class="info-msg" style="margin-bottom:0 !important">{#msg_bqs_preload#}</div>
      </td>
   </tr>
   <tr>
      <td class="lbl">
         {$_aufenthalt_id_lbl}
      </td>
      <td class="edt">
         {$_aufenthalt_id}
         {if $error_aufenthalt_id}
            <div class="bubbleTrigger" style="display:inline;">
               <button class="trigger trigger-err" type="button"></button>
                  <div class="bubbleInfo border-err" style=" display: none;">
                     <div style="max-width:325px;float:left;">
                        <img alt="Error" src="./media/img/base/editdelete.png">
                        {$error_aufenthalt_id}
                     </div>
                  </div>
             </div>
         {/if}
      </td>
   </tr>
   <tr>
       <td class="head" colspan="2">{#lbl_method#}</td>
   </tr>
   <tr>
       <td class="lbl">{#lbl_method_1#}</td>
       <td class="edt">
           <input class=" button" type="submit" alt="Erstellen" value="Erstellen" name="action[preload]">
       </td>
   </tr>
   <tr>
       <td class="lbl">{#lbl_method_2#}</td>
       <td class="edt">
           <input class=" button" type="submit" alt="Erstellen" value="Erstellen" name="action[create]">
       </td>
   </tr>
{else}
   <tr>
      <td class="lbl">
         {$_aufenthalt_id_lbl}
      </td>
      <td class="edt">
         <b>{$_aufenthalt_id_bez}</b>
         <input type="hidden" name="aufenthalt_id" value="{$_aufenthalt_id_value}" />
      </td>
   </tr>

    <tr>
        <td class="lbl">{#lbl_institutsk#}</td>
        <td class="edt"><b>{$info.institutionsk}</b></td>
    </tr>
    <tr>
        <td class="lbl">{#lbl_entl_standort#}</td>
        <td class="edt"><b>{$info.entl_standort}</b></td>
    </tr>
    <tr>
        <td class="lbl">{#lbl_bsnr#}</td>
        <td class="edt"><b>{$info.bsnr}</b></td>
    </tr>
    <tr>
        <td class="lbl">{#lbl_fachabt#}</td>
        <td class="edt"><b>{$info.fachabt}</b></td>
    </tr>

    {html_set_row     field="idnrpat"         caption=$_idnrpat_lbl        input=$_idnrpat}

    <tr>
        <td class="lbl">{#lbl_geb#}</td>
        <td class="edt"><b>{$info.geb}</b></td>
    </tr>

    <tr>
        <td class="lbl">{#lbl_gender#}</td>
        <td class="edt"><b>{$info.gender}</b></td>
    </tr>

    <tr>
        <td class="lbl">{$_aufndatum_lbl}</td>
        <td class="edt">
            <b>{$_aufndatum_value}</b>
            <input type="hidden" name="aufndatum" value="{$_aufndatum_value}" />
        </td>
    </tr>

    {html_set_header  caption=#subhead_aufnahmediag#         class="subhead"}

    <tr>
        <td class="lbl">{$_aufndiag_1_lbl}{#info_aufndiag#}</td>
        <td class="edt">{$_aufndiag_1}</td>
    </tr>
    <tr>
        <td class="lbl">{$_aufndiag_2_lbl}{#info_aufndiag#}</td>
        <td class="edt">{$_aufndiag_2}</td>
    </tr>
    <tr>
        <td class="lbl">{$_aufndiag_3_lbl}{#info_aufndiag#}</td>
        <td class="edt">{$_aufndiag_3}</td>
    </tr>
    <tr>
        <td class="lbl">{$_aufndiag_4_lbl}{#info_aufndiag#}</td>
        <td class="edt">{$_aufndiag_4}</td>
    </tr>
    <tr>
        <td class="lbl">{$_aufndiag_5_lbl}{#info_aufndiag#}</td>
        <td class="edt">{$_aufndiag_5}</td>
    </tr>

   {html_set_header  field="asa"      caption=#head_praeop#       class="head"}
   {html_set_row     field="asa"      caption=$_asa_lbl           input=$_asa}

   {html_set_header  field="adjutherapieplanung"      caption=#head_behandlung#       class="head"}

    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_1#}
            </div>
        </td>
    </tr>

    {html_set_row     field="adjutherapieplanung"      caption=$_adjutherapieplanung_lbl   input=$_adjutherapieplanung}
    {html_set_row     field="planbesprochen"      caption=$_planbesprochen_lbl   input=$_planbesprochen}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_2#}
            </div>
        </td>
    </tr>
    {html_set_row     field="planbesprochendatum"      caption=$_planbesprochendatum_lbl   input=$_planbesprochendatum}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_3#}
            </div>
        </td>
    </tr>
    {html_set_row     field="meldungkrebsregister"      caption=$_meldungkrebsregister_lbl   input=$_meldungkrebsregister}

   {html_set_header  field="entldatum"    caption=#head_entlassung#  class="head"}

   <tr>
      <td class="lbl">{$_entldatum_lbl}</td>
      <td class="edt">
         <b>{$_entldatum_value}</b>
         <input type="hidden" name="entldatum" value="{$_entldatum_value}" />
      </td>
   </tr>

    {html_set_header  caption=#subhead_entldiag#         class="subhead"}
    <tr>
        <td class="lbl">
            {$_entldiag_1_lbl}{#info_entldiag#}
        </td>
        <td class="select-small edt">
            {$_entldiag_1}
        </td>
    </tr>
    <tr>
        <td class="lbl">
            {$_entldiag_2_lbl}{#info_entldiag#}
        </td>
        <td class="select-small edt">
            {$_entldiag_2}
        </td>
    </tr>
    <tr>
        <td class="lbl">
            {$_entldiag_3_lbl}{#info_entldiag#}
        </td>
        <td class="select-small edt">
            {$_entldiag_3}
        </td>
    </tr>

    <tr>
        <td class="lbl">
            {$_entlgrund_lbl}{#info_entlgrund#}
        </td>
        <td class="select-small edt">
            {$_entlgrund}
        </td>
    </tr>

    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_4#}
            </div>
        </td>
    </tr>
   {html_set_row     field="sektion"      caption=$_sektion_lbl      input=$_sektion}

   {html_set_header  field="freigabe"     caption=#head_freigabe#    class="head"}
   {html_set_row     field="freigabe"     caption=$_freigabe_lbl     input=$_freigabe}
{/if}
</table>
{html_set_buttons modus=$button}

<div>
{$_qs_18_1_b_id}
{$_patient_id}
{$_erkrankung_id}
</div>
