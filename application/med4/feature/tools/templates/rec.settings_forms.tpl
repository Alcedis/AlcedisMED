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

{html_set_header  caption=#head#       class='head' }
{html_set_row     caption=$_org_id_lbl   input=$_org_id }

{html_set_header  caption=#head_settings# class='head' }
<tr>
    <td colspan="2" style="padding:0">
        <div class="info-msg" style="margin-bottom:0 !important">
            {#msg_info#}
        </div>
    </td>
</tr>
{foreach from=$forms item=section key=sectionName}
    <tr>
        <td class="subhead" colspan="2">{$section.caption}</td>
    </tr>
    <tr>
        <td class="edt" colspan="2" >
            <table border="0">
                <tr>
                    {foreach from=$section.content item=form name=name key=formname}
                        <td style="text-align:left;">
                            <input type="checkbox" name="form_{$sectionName}_{$formname}" {if strlen($form.value)} checked="checked"{/if}/>
                            <span style="font-size:7.8pt">{$form.caption}</span>
                        </td>

                        {if ($smarty.foreach.name.index+1)%6 == 0}
                            </tr>
                            <tr>
                        {/if}
                    {/foreach}
                </tr>
            </table>
        </td>
    </tr>
{/foreach}
{html_set_header  caption=#head_bem#      class='head' }
{html_set_header  caption=$_bem           class='edt' }
</table>

{html_set_buttons modus=$button}

<div>
{$_settings_forms_id}
</div>