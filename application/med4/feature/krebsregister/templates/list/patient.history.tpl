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

<div style="text-align: center">
    <div style="background-color: #ff756c; margin: 2em 0; padding: .6em; display: inline-block">
        <a style="color: #000" href="index.php?page=register_archive&feature=krebsregister&type={$type}&patient_id={$patient.id}&action=recreate">Erneuter Vollexport des Patienten</a>
    </div>
</div>
<table id="export_case" style="width: 100%">
    <tr>
        <th class="head" style="width: 5%" align="center">{#lbl_export_btn#}</th>
        <th class="head" style="width: 15%" align="center">{#lbl_export#}</th>
    </tr>
    {section name=i loop=$fields.export_id.value}
        {html_odd_even var="class" key=%i.index%}
        <tr>
            <td class="{$class}" align="center"><a class="export_case" href="index.php?page=register_archive&feature=krebsregister&export_log_id={$fields.export_id.value[i]}&patient_id={$patient.id}&action=archive"></a></td>
            <td class="{$class}" align="center">{$fields.createtime.value[i]}</td>
        </tr>
    {/section}
</table>
