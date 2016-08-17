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

{html_set_header                                      caption=#head_beratung#                       class="head"}
{html_set_row field="datum"                           caption=$_datum_lbl                           input=$_datum}
{html_set_row field="fragebogen_ausgehaendigt"        caption=$_fragebogen_ausgehaendigt_lbl        input=$_fragebogen_ausgehaendigt}
{html_set_row field="psychoonkologie"                 caption=$_psychoonkologie_lbl                 input=$_psychoonkologie}
{html_set_row field="psychoonkologie_dauer"           caption=$_psychoonkologie_dauer_lbl           input=$_psychoonkologie_dauer add="`$smarty.config.lbl_min`"}
{html_set_row field="hads"                            caption=$_hads_lbl                            input=$_hads}
{html_set_row field="hads_d_depression"               caption=$_hads_d_depression_lbl               input=$_hads_d_depression}
{html_set_row field="hads_d_angst"                    caption=$_hads_d_angst_lbl                    input=$_hads_d_angst}
{html_set_row field="bc_pass_a"                       caption=$_bc_pass_a_lbl                       input=$_bc_pass_a}
{html_set_row field="bc_pass_b"                       caption=$_bc_pass_b_lbl                       input=$_bc_pass_b}
{html_set_row field="bc_pass_c"                       caption=$_bc_pass_c_lbl                       input=$_bc_pass_c}
{html_set_row field="sozialdienst"                    caption=$_sozialdienst_lbl                    input=$_sozialdienst}
{html_set_row field="fam_risikosprechstunde"          caption=$_fam_risikosprechstunde_lbl          input=$_fam_risikosprechstunde}
{html_set_row field="fam_risikosprechstunde_erfolgt"  caption=$_fam_risikosprechstunde_erfolgt_lbl  input=$_fam_risikosprechstunde_erfolgt}
{html_set_row field="humangenet_beratung"             caption=$_humangenet_beratung_lbl             input=$_humangenet_beratung}
{html_set_row field="interdisziplinaer_angeboten"     caption=$_interdisziplinaer_angeboten_lbl     input=$_interdisziplinaer_angeboten}
{html_set_row field="interdisziplinaer_durchgefuehrt" caption=$_interdisziplinaer_durchgefuehrt_lbl input=$_interdisziplinaer_durchgefuehrt}
{html_set_row field="ernaehrungsberatung"             caption=$_ernaehrungsberatung_lbl             input=$_ernaehrungsberatung}

{html_set_header caption=#head_bem# class="head"}
{html_set_header caption=$_bem class="edt"}
</table>
{html_set_buttons modus=$button}

<div>
{$_beratung_id}
{$_erkrankung_id}
{$_patient_id}
</div>
