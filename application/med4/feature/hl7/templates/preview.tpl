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

{if isset($msg)}
   <table border="1" width="100%" class="formtable msg">
   	<tr>
   		<td class="head" colspan="3">HL7 Layout</td>
   	</tr>
   	<tr>
   		<td style="width:30%" class="subhead">Tabelle.Feld</td>
   		<td style="width:15%"class="subhead">HL7 Segment</td>
   		<td class="subhead">Wert</td>

   	</tr>

   {foreach from=$hl7fields item=field name=hl7}
   {html_odd_even var="class" key=$smarty.foreach.hl7.iteration}
   	<tr>
   		<td class="{$class}">{$field.med_feld}</td>
   		<td class="{$class}">{$field.hl7}</td>
   		<td class="{$class}">{$field.value}</td>
    	</tr>
   {/foreach}

   	<tr>
   		<td class="head" colspan="3">Message</td>
   	</tr>
   	<tr>
   		<td colspan="3">
            <div id="tabs">
               <ul>
                  {foreach from=$msg item=field key=title name=sec}
                     <li><a href='#tabs-{$smarty.foreach.sec.iteration}'>{$title}</a></li>
                  {/foreach}
               </ul>

               {foreach from=$msg item=fielda key=titlea name=seca}
                  <div id='tabs-{$smarty.foreach.seca.iteration}'>
                     <pre>{$fielda}</pre>
                  </div>
               {/foreach}
            </div>
   		</td>
   	</tr>
   </table>
{else}

<div class="info">
   Keine HL7 Message Files im System!
</div>
{/if}