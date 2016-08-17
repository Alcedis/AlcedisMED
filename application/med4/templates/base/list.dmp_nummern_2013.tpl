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

<div id="buttonbar">
    <a href="index.php?page=rec.dmp_nummern_2013" class="button">{#lbl_add_dmp_nr#}</a>
</div>

<table class="listtable">
    <tr align="center">
        <td class="head edit unsortable"></td>
        <td class="head">{#dmp_nr_start#}</td>
        <td class="head">{#dmp_nr_end#}</td>
        <td class="head">{#lbl_available#}</td>
        <td class="head">{#dmp_nr_current#}</td>
    </tr>
    {section name=i loop=$dmpNumbers}
        {html_odd_even var="class" key=%i.index%}
        <tr align="center">
            <td class="{$class}" align="center">
                <a href="index.php?page=rec.dmp_nummern_2013&amp;rec.dmp_nummern_2013_id={$dmpNumbers[i].dmp_nummern_2013_id}" class="edit"></a>
            </td>
            <td class="{$class}">{$dmpNumbers[i].dmp_nr_start}</td>
            <td class="{$class}">{$dmpNumbers[i].dmp_nr_end}</td>
            <td class="{$class}">
                <span style="font-weight: bold;color:{if $dmpNumbers[i].nr_count >= 50} green {elseif $dmpNumbers[i].nr_count >= 25} darkorange {else} red {/if}">
                    {$dmpNumbers[i].nr_count}
                </span>
            </td>
            <td class="{$class}">
                {if $dmpNumbers[i].dmp_nr_current == $dmpNumbers[i].dmp_nr_end}
                    <b>--</b>
                {else}
                    <b>{$dmpNumbers[i].dmp_nr_current}</b>
                {/if}
            </td>
        </tr>
    {sectionelse}
        <tr>
	        <td class="edt" colspan="5">{#lbl_no_numbers#}</td>
        </tr>
    {/section}
</table>
