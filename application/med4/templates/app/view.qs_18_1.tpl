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

<div style="padding:5px 20px 8px 0px;width:100%;text-align:right">
   <b>{$qs181version}</b>
</div>

{if $showInfo}
    <div class="info-msg" style="margin-bottom:0 !important">
        {#info_pre#}
    </div>
{/if}
<table width="100%" class="formtable msg">
    <tr>
        <td colspan="7">
           <span class="subform_heads">{#head_base#}</span>
        </td>
    </tr>
    <tr>
        <td class="head" style="width:33px"></td>
        <td class="head" align="center" style="width:90px">{#nr#}</td>
        <td class="head" style="width:150px">{#idnr#}</td>
        <td class="head">{#aufnahme#}</td>
        <td class="head">{#entlassung#}</td>
        <td class="head" style="width:47px" align="center">{#status#}</td>
        <td class="head" style="width:70px">{#freigabe#}</td>
    </tr>
    <tr>
        <td class="edt" align="center">
            <a class="edit" href="index.php?page=rec.qs_18_1_b&qs_18_1_b_id={$qs181.qs_18_1_b_id}"></a>
        </td>
        <td class="edt" align="center">
            <b>{$qs181.qs_18_1_b_id}</b>
        </td>
        <td class="edt">
            {$qs181.idnrpat}
        </td>
        <td class="edt">
            {$qs181.aufnahmedatum}
        </td>
        <td class="edt">
            {$qs181.entlassungsdatum}
        </td>
        <td class="edt" align="center">
            <img src="media/img/app/ampel/{$qs181.status}.png" alt="{$qs181.status}" title=""/>
        </td>
        <td class="edt">
            {if $qs181.freigabe == 1}
                <div>
                    <div style="float:left; padding-top:2px">
                        <img src="media/img/base/apply-filter.png"/>
                    </div>
                    <div style="float:left; padding-left:5px">
                        <b>{#ja#}</b>
                    </div>
                </div>
            {else}
                <div>
                    <div style="float:left; padding-top:2px">
                        <img src="media/img/base/editdelete.png"/>
                    </div>
                    <div style="float:left; padding-left:5px">
                        <b>{#nein#}</b>
                    </div>
                </div>
            {/if}
        </td>
    </tr>
</table>
<br/>
<br/>
<table width="100%" class="formtable msg">
    <tr>
        <td colspan="8">
           <span class="subform_heads">{#head_brust#}</span>
        </td>
    </tr>
        <td class="head" style="width:50%" colspan="4">{#rechts#}</td>
        <td class="head" colspan="4">{#links#}</td>
    <tr>
        <td class="subhead" style="width:5%"></td>
        <td class="subhead" >{#art#}</td>
        <td class="subhead" style="width:6%">{#status#}</td>
        <td class="subhead" style="width:8%">{#freigabe#}</td>
        <td class="subhead" style="width:5%"></td>
        <td class="subhead" >{#art#}</td>
        <td class="subhead" style="width:6%">{#status#}</td>
        <td class="subhead" style="width:8%">{#freigabe#}</td>
    </tr>
    <td class="edt" align="center">
            {if strlen($qs181.qs_18_1_brust_r_id) == 0}
                <a class="newform" href="index.php?page=rec.qs_18_1_brust&amp;patient_id={$qs181.patient_id}&amp;erkrankung_id={$qs181.erkrankung_id}&amp;qs_18_1_b_id={$qs181.qs_18_1_b_id}&amp;zuopseite=R"></a>
            {else}
                <a class="edit" href="index.php?page=rec.qs_18_1_brust&amp;qs_18_1_brust_id={$qs181.qs_18_1_brust_r_id}"></a>
            {/if}
        </td>
        <td class="edt">
            {$qs181.qs_18_1_brust_r_art}
        </td>
        <td class="edt" align="center">
            {if strlen($qs181.qs_18_1_brust_r_status)}
                <img src="media/img/app/ampel/{$qs181.qs_18_1_brust_r_status}.png" alt="{$qs181.qs_18_1_brust_r_status}" title=""/>
            {/if}
        </td>
        <td class="edt">
            {if strlen($qs181.qs_18_1_brust_r_id) > 0}
                {if $qs181.qs_18_1_brust_r_freigabe == 1}
                    <div>
                        <div style="float:left; padding-top:2px">
                            <img src="media/img/base/apply-filter.png"/>
                        </div>
                        <div style="float:left; padding-left:5px">
                            <b>{#ja#}</b>
                        </div>
                    </div>
                {else}
                    <div>
                        <div style="float:left; padding-top:2px">
                            <img src="media/img/base/editdelete.png"/>
                        </div>
                        <div style="float:left; padding-left:5px">
                            <b>{#nein#}</b>
                        </div>
                    </div>
                {/if}
            {/if}
        </td>
        <td class="edt" align="center">
            {if strlen($qs181.qs_18_1_brust_l_id) == 0}
                <a class="newform" href="index.php?page=rec.qs_18_1_brust&amp;patient_id={$qs181.patient_id}&amp;erkrankung_id={$qs181.erkrankung_id}&amp;qs_18_1_b_id={$qs181.qs_18_1_b_id}&amp;zuopseite=L"></a>
            {else}
                <a class="edit" href="index.php?page=rec.qs_18_1_brust&amp;qs_18_1_brust_id={$qs181.qs_18_1_brust_l_id}"></a>
            {/if}
        </td>
        <td class="edt">
            {$qs181.qs_18_1_brust_l_art}
        </td>
        <td class="edt" align="center">
            {if strlen($qs181.qs_18_1_brust_l_status)}
                <img src="media/img/app/ampel/{$qs181.qs_18_1_brust_l_status}.png" alt="{$qs181.qs_18_1_brust_l_status}" title=""/>
            {/if}
        </td>
        <td class="edt">
            {if strlen($qs181.qs_18_1_brust_l_id) > 0}
                {if $qs181.qs_18_1_brust_l_freigabe == 1}
                    <div>
                        <div style="float:left; padding-top:2px">
                            <img src="media/img/base/apply-filter.png"/>
                        </div>
                        <div style="float:left; padding-left:5px">
                            <b>{#ja#}</b>
                        </div>
                    </div>
                {else}
                    <div>
                        <div style="float:left; padding-top:2px">
                            <img src="media/img/base/editdelete.png"/>
                        </div>
                        <div style="float:left; padding-left:5px">
                            <b>{#nein#}</b>
                        </div>
                    </div>
                {/if}
            {/if}
        </td>
    </tr>
</table>
<br/>
<br/>
<table width="100%" class="formtable msg">
   <tr>
      <td colspan="10">
         <span class="subform_heads">{#head_op#}</span>
      </td>
   </tr>
    <tr>
        <td class="head" style="width:50%" colspan="5">Rechts</td>
        <td class="head" colspan="5">Links</td>
    </tr>
    <tr>
        <td class="subhead" style="width:5%"> </td>
        <td class="subhead" style="width:10%">{#eingriff_nr#}</td>
        <td class="subhead">{#op_datum#}</td>
        <td class="subhead" style="width:6%">{#status#}</td>
        <td class="subhead" style="width:8%">{#freigabe#}</td>
        <td class="subhead" style="width:5%"></td>
        <td class="subhead" style="width:10%">{#eingriff_nr#}</td>
        <td class="subhead">{#op_datum#}</td>
        <td class="subhead" style="width:6%">{#status#}</td>
        <td class="subhead" style="width:8%">{#freigabe#}</td>

    </tr>

    {foreach from=$qs181o item=td}
        <tr>
            <td align="center" class="edt">
                {if strlen($td.qs_18_1_o_r_id) && strlen($qs181.qs_18_1_brust_r_id)}
                    {if $td.qs_18_1_o_r_id == 'new'}
                        <a class="newform" href="index.php?page=rec.qs_18_1_o&amp;patient_id={$qs181.patient_id}&amp;erkrankung_id={$qs181.erkrankung_id}&amp;qs_18_1_b_id={$qs181.qs_18_1_b_id}&amp;qs_18_1_brust_id={$qs181.qs_18_1_brust_r_id}"></a>
                    {else}
                        <a class="edit" href="index.php?page=rec.qs_18_1_o&amp;qs_18_1_o_id={$td.qs_18_1_o_r_id}"></a>
                    {/if}
                {else}
                --
                {/if}
            </td>
            <td class="edt" align="center">
                {if strlen($td.qs_18_1_o_r_id) && strlen($qs181.qs_18_1_brust_r_id)}
                    <b>{$td.qs_18_1_o_r_nr}</b>
                {/if}
            </td>
            <td class="edt">
                {if strlen($td.qs_18_1_o_r_id) && strlen($qs181.qs_18_1_brust_r_id)}
                    {$td.qs_18_1_o_r_opdatum}
                {/if}
            </td>
            <td class="edt" align="center">
                {if strlen($td.qs_18_1_o_r_id) && strlen($qs181.qs_18_1_brust_r_id)}
                    {if strlen($td.qs_18_1_o_r_status)}
                        <img src="media/img/app/ampel/{$td.qs_18_1_o_r_status}.png" alt="{$td.qs_18_1_o_r_status}" title=""/>
                    {/if}
                {/if}
            </td>
            <td class="edt">
                {if strlen($td.qs_18_1_o_r_id) && strlen($qs181.qs_18_1_brust_r_id) && $td.qs_18_1_o_r_id != 'new'}
                    {if $td.qs_18_1_o_r_freigabe == 1}
                        <div>
                            <div style="float:left; padding-top:2px">
                                <img src="media/img/base/apply-filter.png"/>
                            </div>
                            <div style="float:left; padding-left:5px">
                                <b>{#ja#}</b>
                            </div>
                        </div>
                    {else}
                        <div>
                            <div style="float:left; padding-top:2px">
                                <img src="media/img/base/editdelete.png"/>
                            </div>
                            <div style="float:left; padding-left:5px">
                                <b>{#nein#}</b>
                            </div>
                        </div>
                    {/if}
                {/if}
            </td class="edt">
            <td class="edt" align="center">
                {if strlen($td.qs_18_1_o_l_id) && strlen($qs181.qs_18_1_brust_l_id)}
                    {if $td.qs_18_1_o_l_id == 'new'}
                        <a class="newform" href="index.php?page=rec.qs_18_1_o&amp;patient_id={$qs181.patient_id}&amp;erkrankung_id={$qs181.erkrankung_id}&amp;qs_18_1_b_id={$qs181.qs_18_1_b_id}&amp;qs_18_1_brust_id={$qs181.qs_18_1_brust_l_id}"></a>
                    {else}
                        <a class="edit" href="index.php?page=rec.qs_18_1_o&amp;qs_18_1_o_id={$td.qs_18_1_o_l_id}"></a>
                    {/if}
                {else}
                --
                {/if}
            </td>
            <td class="edt" align="center">
                {if strlen($td.qs_18_1_o_l_id) && strlen($qs181.qs_18_1_brust_l_id)}
                    <b>{$td.qs_18_1_o_l_nr}</b>
                {/if}
            </td>
            <td class="edt">
                {if strlen($td.qs_18_1_o_l_id) && strlen($qs181.qs_18_1_brust_l_id)}
                    {$td.qs_18_1_o_l_opdatum}
                {/if}
            </td>
            <td class="edt" align="center">
                {if strlen($td.qs_18_1_o_l_id) && strlen($qs181.qs_18_1_brust_l_id)}
                    {if strlen($td.qs_18_1_o_l_status)}
                        <img src="media/img/app/ampel/{$td.qs_18_1_o_l_status}.png" alt="{$td.qs_18_1_o_l_status}" title=""/>
                    {/if}
                {/if}
            </td>
            <td class="edt">
                {if strlen($td.qs_18_1_o_l_id) && strlen($qs181.qs_18_1_brust_l_id) && $td.qs_18_1_o_l_id != 'new'}
                    {if $td.qs_18_1_o_l_freigabe == 1}
                        <div>
                            <div style="float:left; padding-top:2px">
                                <img src="media/img/base/apply-filter.png"/>
                            </div>
                            <div style="float:left; padding-left:5px">
                                <b>{#ja#}</b>
                            </div>
                        </div>
                    {else}
                        <div>
                            <div style="float:left; padding-top:2px">
                                <img src="media/img/base/editdelete.png"/>
                            </div>
                            <div style="float:left; padding-left:5px">
                                <b>{#nein#}</b>
                            </div>
                        </div>
                    {/if}
                {/if}
            </td>
        </tr>
    {/foreach}
</table>


<table class="inline-table">
    <tr style="border-bottom: 0px none;">
        <td align="center" style="margin: 0px; padding: 0px;">
            <input class="button" type="submit" alt="{#btn_lbl_back#}" value="{#btn_lbl_back#}" name="action[cancel]">
        </td>
    </tr>
</table>

