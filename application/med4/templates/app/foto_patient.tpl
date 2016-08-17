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

{if isset($fields.erkrankung.value)}
    {foreach from=$erkArr key=krz item=erk}
       {if in_array($krz, $fields.erkrankung.value)}
          <table style="width:100%;">
          <tr>
             <td class="head" colspan="7">{$erk}</td>
          </tr>
          </table>
          <table>
          <tr>
             {section name=i loop=$fields.foto_id.value}
                {html_odd_even var="class" key=%i.index%}
                {if $fields.erkrankung.value[i] == $krz}
                   <td style="text-align:center;">
                      <a title="{$fields.bez.value[i]}" href='index.php?page=rec.foto&amp;origin=true&amp;foto_id={$fields.foto_id.value[i]}&amp;erkrankung_id={$fields.erkrankung_id.value[i]}'><img style="margin:5px 11px;" class='thumb-img' alt='thumb' src='index.php?page=foto&amp;type=thumbnail&amp;thumb=125&amp;foto_id={$fields.foto_id.value[i]}'/></a><br/>
                   </td>
                   {if ($smarty.section.i.index+1)%7 == 0}
                      </tr>
                       <tr>
                   {/if}
                {/if}
             {/section}
             </tr>
          </table>
       {/if}
    {/foreach}
{else}

{/if}