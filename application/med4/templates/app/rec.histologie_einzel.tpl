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

{html_set_header caption=#head_einzelhistologie#    class="head"}
{html_set_row  field="materialgewinnung_methode"  caption=$_materialgewinnung_methode_lbl  input="$_materialgewinnung_methode  $_materialgewinnung_anzahl_lbl $_materialgewinnung_anzahl"}
{html_set_row  field="diagnose_id"  caption=$_diagnose_id_lbl  input=$_diagnose_id}
{html_set_row  field="schnittechnik"  caption=$_schnittechnik_lbl  input=$_schnittechnik}
{html_set_row  field="clark"  caption=$_clark_lbl  input=$_clark}
{html_set_row  field="mikroskopisch"  caption=$_mikroskopisch_lbl  input=$_mikroskopisch}
{html_set_html field="groesse_x" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl'>$_groesse_x_lbl</td>
            <td class='edt'>$_groesse_x `$smarty.config.lbl_mm` `$smarty.config.lbl_x` $_groesse_y `$smarty.config.lbl_mm` `$smarty.config.lbl_x` $_groesse_z `$smarty.config.lbl_mm`</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_html field="morphologie" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl'>$_morphologie_lbl</td>
            <td class='edt'>$_morphologie</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_row  field="unauffaellig"  caption=$_unauffaellig_lbl  input=$_unauffaellig}
{html_set_html field="ptnm_praefix" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_ptnm_praefix_lbl</td>
            <td class='edt'>
               $_ptnm_praefix
               $_pt_lbl $_pt <br/><br/>
               $_g_lbl $_g $_l_lbl $_l $_v_lbl $_v $_r_lbl $_r $_ppn_lbl $_ppn
            </td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_row  field="uicc"  caption=$_uicc_lbl  input=$_uicc}
{html_set_row  field="ulzeration"  caption=$_ulzeration_lbl  input=$_ulzeration}
{html_set_row  field="regression"  caption=$_regression_lbl  input=$_regression}
{html_set_row  field="perineurale_invasion"  caption=$_perineurale_invasion_lbl  input=$_perineurale_invasion}
{html_set_row  field="wachstumsphase"  caption=$_wachstumsphase_lbl  input=$_wachstumsphase}
{html_set_row  field="melanom_muttermal"  caption=$_melanom_muttermal_lbl  input=$_melanom_muttermal}
{html_set_row  field="randkontrolle"  caption=$_randkontrolle_lbl  input=$_randkontrolle}
{html_set_row  field="resektionsrand"  caption=$_resektionsrand_lbl  input=$_resektionsrand add=#lbl_mm#}
{html_set_row  field="tumordicke"  caption=$_tumordicke_lbl  input=$_tumordicke add=#lbl_mm#}
{html_set_header caption=#bem# class="head"}
{html_set_header caption=$_bem class="edt"}
</table>

{html_set_ajax_buttons modus=$button}

<div>
<input type="hidden" name="sess_pos" value="{$sess_pos}" />
{$_histologie_einzel_id}
{$_histologie_id}
{$_patient_id}
{$_erkrankung_id}
</div>