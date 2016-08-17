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

<div id="c_caption">{if strlen($caption)}{$caption}{else}{#caption#}{/if}</div>
<div class="c_frame">
   <div id="c_search">
      {include file="base/codepicker/suchleiste.tpl"}
   </div>

   <div id="c_body">
      {include file="$body"}
   </div>

   <div>
      <input type="hidden" name="type"       value="{$codepickertype}"/>
      <input type="hidden" name="vorauswahl" value="{$vorauswahl}"/>
      <input type="hidden" name="txtfield"   value="{$txtfield}"/>
      <input type="hidden" name="r_exp"      value="{$r_exp}"/>
      <input type="hidden" name="parentform" value="{$parentform}"/>
      <input type="hidden" name="gruppen"    value="{$gruppen}"/>
      <input type="hidden" name="o3_type"    value="{$o3_type}"/>
   </div>
</div>