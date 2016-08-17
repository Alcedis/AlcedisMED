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

<tr>
   <td class="subhead" rowspan="2" style="width:20%;">{#subhead_messwert#}</td>
   <td class="subhead" align="center" rowspan="2">{#subhead_wert#}</td>
   <td class="subhead" align="center" rowspan="2">{#subhead_einheit#}</td>
   <td class="subhead" align="center" colspan="2">{#subhead_min_max#}</td>
   <td class="subhead" align="center" rowspan="2">{#subhead_beurteilung#}</td>
</tr>
<tr>
   <td class="subhead" align="center">{#subhead_min#}</td>
   <td class="subhead" align="center">{#subhead_max#}</td>
</tr>

{section loop=$template.vorlage_labor_wert_id.value name=l}
<tr class="extform-identifier-{$template.vorlage_labor_wert_id.value[l]}">
   <td class="lbl">
      {$template.parameter.bez[l]}
      <input type="hidden" name="labor_wert[vorlage_labor_wert_id][]" value="{$template.vorlage_labor_wert_id.value[l]}"/>
      <input type="hidden" name="labor_wert[parameter][]" value="{$template.parameter.value[l]}"/>
      {$_labor_wert_id}
   </td>
   <td class="edt" align="center">{$_wert}</td>
   <td class="edt" align="center">{$template.einheit.bez[l]}</td>
   <td class="edt" align="center">
      {if $smarty.session.sess_patient_data.geschlecht == 'm'}
         {$template.normal_m_min.value[l]}
      {elseif $smarty.session.sess_patient_data.geschlecht == 'w'}
         {$template.normal_w_min.value[l]}
      {else}
         {$template.normal_m_min.value[l]}{#lbl_sep#}{$template.normal_w_min.value[l]}
      {/if}
   </td>
   <td class="edt" align="center">
      {if $smarty.session.sess_patient_data.geschlecht == 'm'}
         {$template.normal_m_max.value[l]}
      {elseif $smarty.session.sess_patient_data.geschlecht == 'w'}
         {$template.normal_w_max.value[l]}
      {else}
         {$template.normal_m_max.value[l]}{#lbl_sep#}{$template.normal_w_max.value[l]}
      {/if}
   </td>
   <td class="edt" align="center">{$_beurteilung}</td>
</tr>
{/section}