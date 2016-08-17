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

<table class="listtable bfl">
    <tr>
        <td class="head edit unsortable">{#download#}</td>
        <td class="head unsortable" >{#dateiname#}</td>
        <td class="head unsortable" >{#datum#}</td>
        <td class="head unsortable" >{#filter#}</td>
        <td class="head unsortable" >{#action#}</td>
    </tr>
    {section name=i loop=$histories}
    {html_odd_even var="class" key=%i.index%}
    <tr>
        {if $smarty.section.i.index == 0}
            {php}
                $this->assign("class", "td-selected");
            {/php}
        {/if}
        <td class="{$class}" align="center">
            <a class="visible-anchor" href="index.php?page=history&amp;feature=export&amp;exportname={$histories[i].export_name}&amp;action=download&amp;type=zip&amp;history_id={$histories[i].export_history_id}" style="font-size:14px; font-weight:bold; margin:5px">
                <img border="0" src="media/img/base/download.png" alt="edit">
            </a>
        </td>
        <td class="{$class}" style="width: 200px;" align="left">{$histories[i].file}</td>
        <td class="{$class}" style="width: 80px;" align="left">{$histories[i].date}</td>
        <td class="{$class}" align="left">
            <lu style="list-style-type:none;">
            {foreach key=k item=v from=$histories[i].filter}
                <li>
                    <span>{$k}</span><span> => </span><span>{$v}</span>
                </li>
            {/foreach}
            </lu>
        </td>
        <td class="{$class}" style="width: 80px;" align="center">
            {if $smarty.section.i.index == 0}
                {html_set_buttons modus='delete' class='button_large btndelete' table=false}
            {else}
                &nbsp;
            {/if}

        </td>
    </tr>
    {sectionelse}
    <tr>
        <td class="even no-data" colspan="9">{#info_keine_histories_vorhanden#}</td>
    </tr>
    {/section}
</table>
{if count($histories) > 0}
<div>
    <input type="hidden" name="history_id" value="{$histories[0].export_history_id}" />
</div>
{/if}
