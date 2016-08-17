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

<div class="dokumente-head">
	<img class="popup-trigger back-img" style="float:left" alt="table" src="media/img/base/back.png" title="" />
	<div class="info-popup center above" style="display:none;">{$smarty.config.btn_lbl_patient}</div>
	Dokumente
</div>

{foreach from=$documents key=erkrankung item=content}
    <table class="listtable no-filter" style="width:750px;">
        <tr>
            <td class="head">{$content.value}</td>
        </tr>
    </table>

    <table style="margin-bottom: 15px; width:750px;padding:0px!important">
        {foreach from=$content.content key=date item=dateContent}
            <tr style="background-color:#e3e3e3">
                <td style="text-align:center"><b><span style="font-size:9pt">{$dateContent.date}</span></b></td>
            </tr>
            <tr>
                <td>
                    <table width="100%">
                        <tr>            
                            {foreach from=$dateContent.content item=dokument name=d}
                                <td style="text-align:left;width:50%" >
                                    <table width="100%" style="margin-bottom:10px">
                                        <tr>
                                            <td style="width:40px;padding-left:20px" rowspan="5">
                                                <a href="index.php?page=rec.dokument&amp;dokument_id={$dokument.dokument_id}&amp;patient_id={$dokument.patient_id}&amp;erkrankung_id={$dokument.erkrankung_id}&amp;origin=true">
                                                    <img src="media/img/base/{$dokument.doc_type}_small.png" alt="" />
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:90px">
                                                <b>Titel:</b> 
                                            </td>
                                            <td>
                                                <b>{$dokument.bez}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Name:</b> 
                                            </td>
                                            <td>
                                                <span style="font-size:8pt">{$dokument.dokument_short}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                <b>Bemerkung:</b>
                                            </td>
                                            <td>
                                                <span style="font-size:8pt">{$dokument.bem}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Download:</b>
                                            </td>
                                            <td>
                                                <a href="index.php?page=rec.dokument&amp;action[file][dokument]=1&amp;dokument_id={$dokument.dokument_id}">
                                                    <img src="media/img/base/package.png" alt="" />
                                                </a>
                                            </td>
                                        </tr>                                       
                                    </table>
                                </td>
                                {if ($smarty.foreach.d.index+1)%2 == 0}
                                    </tr>
                                    <tr>
                                {/if}
                            {/foreach}
                        </tr>
                    </table>
                </td>
            </tr>      
        {/foreach}
    </table>
{/foreach}