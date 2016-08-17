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
{html_set_header caption=#head_konferenz#    class="head"}

{if $_dokument_id_value}
    <tr>
        <td class="lbl">{$_bez_lbl}</td>
        <td class="edt"><b>{$_bez_value}</b>
            <input type="hidden" name="bez" value="{$_bez_value}"/>
        </td>
    </tr>
    <tr>
        <td class="lbl">{$_dokument_id_lbl}</td>
        <td class="edt">
        <div style="float:left">
            <input class="dont_prevent_double_save filepackage" type="submit" alt="" value="{$_dokument_id_bez}" name="action[file][dokument_id]">
        </div>
        </td>
    </tr>
{else}
    {html_set_row caption=$_bez_lbl                    input=$_bez}
    {if $datei}
       <tr>
    	   <td class='lbl'>{$_datei_lbl}</td>
    	   <td class='edt'>
    	   	<div style="float:left">{$datei_text} {$err_datei}</div>
    	   	<input type="hidden" name="datei" value="{$datei}" />
    	   </td>
    	</tr>
    {else}
       <tr>
          <td class='lbl'>{$_datei_lbl}</td>
          <td class='edt'><input type='file' size='50' name='datei'>{$err_datei}</td>
       </tr>
    {/if}
{/if}
{html_set_header caption=#pat# class="head"}

{if $_dokument_id_value}
    <tr>
        <td class="lbl">{$_konferenz_patient_id_lbl}</td>
        <td class="edt">
            <b>{$_konferenz_patient_id_bez}</b><br/>
            <span style="font-size:9pt">{$info.org}</span>
        </td>
    </tr>
{else}
    {html_set_row caption=$_konferenz_patient_id_lbl   input=$_konferenz_patient_id}
{/if}

{html_set_header caption=#head_bem# class="head"}
{html_set_header caption=$_bem      class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
    {$_konferenz_dokument_id}
    <input type="hidden" name="dokument_id" value="{$_dokument_id_value}"/>
    {$_konferenz_id}
</div>