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

<table class="listtable bfl">
	<tr>
	    <td class="head ext-search cookie-bez" style="width:12%;text-align:right" >{#lbl_titel#}</td>
	    <td class="head ext-search cookie-datum" style="width:10%;text-align:center">{#lbl_datum#}</td>
	    <td class="head ext-search cookie-moderator" style="width:18%">{#lbl_moderator#}</td>
	    <td class="head unsortable" style="width:6%" align="center">Bearb.</td>
	    <td class="head unsortable" style="width:10%" align="center">{#lbl_patienten#}</td>
	    <td class="head unsortable" style="text-align:right; width:12%" colspan="2">Protokolle</td>
	    <td class="head unsortable" style="text-align:center; width:14%" >{#lbl_dokumente#}</td>
	    <td class="head unsortable" style="text-align:right; width:10%" colspan="2">{#lbl_teilnehmer#}</td>
	    <td class="head unsortable" style="text-align:right">{if $showConclusion}{#lbl_abschluss#}{/if}</td>
   </tr>
	{include file=app/list/list.konferenz.tpl}
</table>

<div title="{#head_rep_gen#}" id="report-dialog" style="display:none;overflow:hidden;">
	<span id="report-loading">
	    <br/>
	    {#msg_report_loading#}
	    <br/>
	    <br/>
	    <br/>
	    <span id="loading-info" style="font-size:1.2em;display:none;font-style:italic;">{#msg_gen_report#}</span>
	    <br/>
	    <br/>
	    <br/>
	    <span style="font-size:0.8em;">{#msg_report_patience#}</span>
	</span>
	<div id="report-error" style="display:none;">
	    <div class="err" style="text-align:left;font-size:0.8em;">
	    {#msg_report_error#}
	    </div>
	    <input type="button" class="button close-dialog" value="{#lbl_close#}"/>
	</div>
</div>