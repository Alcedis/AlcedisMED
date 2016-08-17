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

<div id="accordion">
{section name=i loop=$auswertungen}
    {if count($auswertungen[i].auswertungen)}
    <h3><a href="#">{$auswertungen[i].label}{if isset($auswertungen[i].version) === true} <span style="font-size:7.6pt">({$auswertungen[i].version})</span>{/if}</a></h3>
    <div>
        <table width="100%">
          <tr>
            <td>
                <span style="font-size:8pt;padding-left:8px;color:grey">{#lbl_type#}</span><hr/>
            </td>
            {if $auswertungen[i].type != 'self'}
            <td>
                <span style="font-size:8pt;color:grey; padding-left:3px; padding-right:27px">{#lbl_nr#}</span>
                <hr/>
            </td>
            {/if}
            <td>
                <span style="font-size:8pt;color:grey">{#lbl_name#}</span><hr/>
            </td>
            <td>&nbsp;<hr/></td>
            <td>
                <span style="font-size:8pt;color:grey">{#lbl_spec#}</span><hr/>
            </td>
          </tr>
        {foreach from=$auswertungen[i].auswertungen key=type item=reports}
            {if $type == 'self' && count($reports) > 0 && $auswertungen[i].type !== 'self'}
                <tr>
                    <td class="subhead" colspan="5">{#lbl_eigene_auswertungen#}</td>
                </tr>
            {/if}
            {section name=x loop=$reports}
                <tr>
                {if $reports[x].type !== 'feature'}
                    <td style="width:5%{if $smarty.section.x.index == 0};padding-top:6px{/if}">
                        <a href="index.php?page=report{if strlen($reports[x].sub)}&amp;sub={$reports[x].sub}{/if}&amp;name={$reports[x].name}&amp;type={$reports[x].type}{if $reports[x].direct}&amp;action=report{/if}">
                            {if in_array($reports[x].img, array('rtf', 'doc')) === true }
                                <img src="media/img/base/pdf_small.png" alt="" />
                            {else}
                                <img src="media/img/base/{$reports[x].img}_small.png" alt="" />
                            {/if}
                        </a>
                    </td>
                    {if $reports[x].report_type !== 'self'}
                    <td>
                        <span style="font-size:9pt;color:grey">{$reports[x].caption_name}</span>
                    </td>
                    {/if}
                    <td {if $reports[x].report_type == 'self'}colspan="2"{/if}>
                        <a href="index.php?page=report{if strlen($reports[x].sub)}&amp;sub={$reports[x].sub}{/if}&amp;name={$reports[x].name}&amp;type={$reports[x].type}{if $reports[x].direct}&amp;action=report{/if}">
                            <span style="font-size:9pt">{$reports[x].caption}</span>
                        </a>
                    </td>
                    <td></td>
                    <td align="right" style="width:3%">
                        {if $reports[x].help}
                            <a class="link" href="index.php?page=auswertungen&amp;action=help&amp;sub={$reports[x].sub}&amp;name={$reports[x].name}">
                                <img class="help-img" src="media/img/base/help.png" alt="help" />
                            </a>
                        {/if}
                    </td>
                {else}
                    <td style="width:5%{if $smarty.section.x.index == 0};padding-top:6px{/if}">
                        <a href="index.php?{$reports[x].link}">
                            {if in_array($reports[x].img, array('rtf', 'doc')) === true }
                                <img src="media/img/base/pdf_small.png" alt="" />
                            {else}
                                <img src="media/img/base/{$reports[x].img}_small.png" alt="" />
                            {/if}
                        </a>
                    </td>
                    <td><!-- --></td>
                    <td>
                        <a href="index.php?{$reports[x].link}">
                            <span style="font-size:9pt">{$reports[x].caption}</span>
                        </a>
                    </td>
                    <td>
                        <img src="media/img/app/report/{$reports[x].name}.png" alt=""/>
                    </td>
                    <td align="right" style="width:3%">
                        {if $reports[x].help}
                            <a class="link" href="index.php?page=auswertungen&amp;action=help&amp;sub={$reports[x].sub}&amp;name={$reports[x].name}">
                                <img class="help-img" src="media/img/base/help.png" alt="help" />
                            </a>
                        {/if}
                    </td>
                {/if}
                </tr>
             {/section}
         {/foreach}
      </table>
   </div>
   {/if}
{/section}
</div>
{literal}<script type="text/javascript">
$(function(){$('a.blank-link').attr('target', '_blank');});
</script>{/literal}
