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

<div id="flashDiv" style="width:100%;">
   <object data="{$client}" type="application/x-shockwave-flash" width="{$width}" height="{$height}" id="flashkonferenz">
           <param name="movie"           value="{$client}" />
           <param name="wmode"           value="opaque" />
           <param name="quality"         value="high" />
           <param name="bgcolor"         value="#FFFFFF" />
           <param name="allowFullScreen" value="true" />
           <param name="FlashVars"       value='{$flashVars}' />
           <param name="allowScriptAccess" value="always" />
   </object>
</div>

{if $SESSION.sess_rolle_code == 'moderator'}


<table class="formtable">
   <tr>
      <td class="head" colspan="5">{#lbl_therapieplan_bearbeiten#}</td>
   </tr>
</table>
<div class="dlist scroll" id="dlist_therapieplan">
</div>
{/if}

<div>
	<input type="hidden" name="konferenz_id" value="{$konferenz_id}" />
	<input type="hidden" name="dlist_param" value="konferenz" />
</div>