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
    {if $info}
        {html_set_header class="msgbox"         caption=$info}
    {/if}
    {html_set_header class="head"           caption=#head_dmpnummern#}
    {html_set_row    field="dmp_nr_start"   caption=$_dmp_nr_start_lbl  input=$_dmp_nr_start}
    {html_set_row    field="dmp_nr_end"     caption=$_dmp_nr_end_lbl    input=$_dmp_nr_end}

    {if $_dmp_nummern_2013_id_value}
        {html_set_header class="subhead"         caption=#subhead_info#}
        <tr>
            <td class="lbl">
                {$_dmp_nr_current_lbl}
            </td>
            <td class="edt">
                <b>{$_dmp_nr_current_value}</b>
                <input type="hidden" name="dmp_nr_current" value="{$_dmp_nr_current_value}"/>
            </td>
        </tr>
        <tr>
            <td class="lbl">
                {$_nr_count_lbl}
            </td>
            <td class="edt">
                <span style="font-weight: bold;color:{if $_nr_count_value >= 50} green {elseif $_nr_count_value >= 25} darkorange {else} red {/if}">
                    {$_nr_count_value}
                </span>
                <input type="hidden" name="nr_count" value="{$_nr_count_value}"/>
            </td>
        </tr>
    {/if}
</table>

{html_set_buttons modus=$button}
<div>
    <input type="hidden" value="{$_dmp_nummern_2013_id_value}" name="form_id" />
    <input type="hidden" value="{$_org_id_value}" name="org_id" />
    {$_dmp_nummern_2013_id}
</div>
