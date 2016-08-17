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

{html_set_header caption=#head_parameter# class="head"}
{html_set_row field="parameter"        caption=$_parameter_lbl       input=$_parameter}
{html_set_row field="einheit"          caption=$_einheit_lbl       input=$_einheit}
{html_set_row field="normal_m_min"        caption=$_normal_m_min_lbl       input="$_normal_m_min <span style='padding-right:10px'>$_normal_m_max_lbl</span> $_normal_m_max"}
{html_set_row field="normal_w_min"        caption=$_normal_w_min_lbl       input="$_normal_w_min <span style='padding-right:10px'>$_normal_w_max_lbl</span> $_normal_w_max"}
</table>

{html_set_ajax_buttons modus=$button}

<div>
<input type="hidden" name="sess_pos" value="{$sess_pos}" />
{$_vorlage_labor_wert_id}
{$_vorlage_labor_id}
</div>