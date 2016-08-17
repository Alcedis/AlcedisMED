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

<div class="fotos-head">
	<img class="popup-trigger back-img" style="float:left" alt="table" src="media/img/base/back.png" title="" />
	<div class="info-popup center above" style="display:none;">{$smarty.config.btn_lbl_patient}</div>
	Fotos des Patienten
</div>

{foreach from=$images key=erkrankung item=content}
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
                    <table>
                        <tr>            
                            {foreach from=$dateContent.content item=foto name=f}
                                <td style="text-align:left;">
                                    <a title="{$foto.bez}" href='index.php?page=rec.foto&amp;origin=true&amp;foto_id={$foto.foto_id}&amp;erkrankung_id={$foto.erkrankung_id}'><img style="margin:5px 11px;" class='thumb-img' alt='Lade...' src='index.php?page=foto&amp;type=thumbnail&amp;thumb=75&amp;foto_id={$foto.foto_id}'/></a>
                                </td>
                                    
                                {if ($smarty.foreach.f.index+1)%7 == 0}
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