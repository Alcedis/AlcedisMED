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

<tr>   <td class="subhead" style="font-size:0.8em;">{#subhead_wirkstoff#}</td>   <td class="subhead" style="font-size:0.8em;width:5%;">{#subhead_dosis#}</td>   <td class="subhead" style="font-size:0.8em;">{#subhead_einheit#}</td>   <td class="subhead" style="font-size:0.8em;width:9%;">{#subhead_dosis_aend#}</td>   <td class="subhead" style="font-size:0.8em;">{#subhead_einheit_aend#}</td>   <td class="subhead" style="font-size:0.8em;">{#subhead_dosis_soll#}</td>   <td class="subhead" style="font-size:0.7em;width:10%;">{#subhead_dosis_verab#}</td>   <td class="subhead" style="font-size:0.8em;">{#subhead_einheit_verab#}</td>   <td class="subhead" style="font-size:0.8em;width:10%;">{#subhead_kreatinin#}</td>   <td class="subhead" style="font-size:0.7em;">{#subhead_cockroft#}</td>   <td class="subhead" style="font-size:0.7em;">{#subhead_jelliffe#}</td></tr>{section loop=$template.vorlage_therapie_wirkstoff_id.value name=l}<tr class="extform-identifier-{$template.vorlage_therapie_wirkstoff_id.value[l]}">   <td class="edt">        <span style="font-size:0.8em;"><!-- --> {$template.wirkstoff.bez[l]}</span>        <input type="hidden" name="wirkstoff_data[vorlage_therapie_wirkstoff_id][]" value="{$template.vorlage_therapie_wirkstoff_id.value[l]}"/>        {$_therapie_systemisch_zyklustag_wirkstoff_id}   </td>   <td class="edt">   	<span style="font-size:0.8em;"><!-- -->   		<span class="dosis-value">   			{$template.dosis.value[l]}   		</span>   	</span>  	</td>   <td class="edt">   	<span style="font-size:0.8em;"><!-- -->   		<span class="dosis-value">   			{$template.einheit.bez[l]}   		</span>		</span>	</td>   <td class="edt">{$_aenderung_dosis}</td>   <td class="edt">{$_aenderung_einheit}</td>   <td class="edt">{#lbl_dash#}</td>   <td class="edt">{$_verabreicht_dosis}</td>   <td class="edt">{$_verabreicht_einheit}</td>   {if $template.wirkstoff.value[l] == 'carboplatin' || $template.einheit.value[l] == 'auc'}      <td class="edt">{$_kreatinin}</td>      <td class="edt" align="center"><input type="text" name="wirkstoff_data[kreatinin_cockroft][]" class="fake-input" readonly="readonly"/></td>      <td class="edt" align="center"><input type="text" name="wirkstoff_data[kreatinin_jelliffe][]" class="fake-input" readonly="readonly"/></td>   {else}      <td colspan="3" class="edt">      	<input type="hidden" name="wirkstoff_data[kreatinin][]" />      	<input type="hidden" name="wirkstoff_data[kreatinin_cockroft][]" />      	<input type="hidden" name="wirkstoff_data[kreatinin_jelliffe][]" />      </td>   {/if}</tr>{/section}