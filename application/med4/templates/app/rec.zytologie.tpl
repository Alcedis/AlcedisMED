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

{html_set_header caption=#head_zytobefund#    class="head"}
{html_set_row field="datum"                  caption=$_datum_lbl                 input=$_datum}
{html_set_row field="histologie_nr"          caption=$_histologie_nr_lbl         input=$_histologie_nr}
{html_set_row field="org_id"                 caption=$_org_id_lbl                input=$_org_id}
{html_set_row field="user_id"                caption=$_user_id_lbl               input=$_user_id}
{html_set_row field="eingriff_id"            caption=$_eingriff_id_lbl           input=$_eingriff_id}
{html_set_row field="untersuchungsmaterial"  caption=$_untersuchungsmaterial_lbl input=$_untersuchungsmaterial}

{html_set_header field="nhl_who_b,nhl_who_t,hl_who,ann_arbor_stadium,ann_arbor_aktivitaetsgrad,ann_arbor_extralymphatisch,nhl_ipi,flipi,durie_salmon,cll_rai,cll_binet,aml_fab,aml_who,all_egil,mds_fab,mds_who" caption=#head_tumor#    class="head"}

{html_set_header caption=#subhead_lymph# class="subhead" field="nhl_who_b,nhl_who_t,hl_who,ann_arbor_stadium,ann_arbor_aktivitaetsgrad,ann_arbor_extralymphatisch,nhl_ipi,flipi,durie_salmon"}

{html_set_html field=nhl_who_b html="
    <tr>
        <td class='msg' colspan='2'>
        <table class='inline-table'>
            <tr>
                <td class='lbl'>$_nhl_who_b_lbl</td>
                <td class='edt'>$_nhl_who_b</td>
            </tr>
        </table>
        </td>
    </tr>
"}
{html_set_row field=nhl_who_t                   caption=$_nhl_who_t_lbl                     input=$_nhl_who_t                   }
{html_set_row field=hl_who                      caption=$_hl_who_lbl                        input=$_hl_who                      }
{html_set_row field=ann_arbor_stadium           caption=$_ann_arbor_stadium_lbl             input=$_ann_arbor_stadium           }
{html_set_row field=ann_arbor_aktivitaetsgrad   caption=$_ann_arbor_aktivitaetsgrad_lbl     input=$_ann_arbor_aktivitaetsgrad   }
{html_set_row field=ann_arbor_extralymphatisch  caption=$_ann_arbor_extralymphatisch_lbl    input=$_ann_arbor_extralymphatisch  }
{html_set_row field=nhl_ipi                     caption=$_nhl_ipi_lbl                       input=$_nhl_ipi                     }
{html_set_row field=flipi                       caption=$_flipi_lbl                         input=$_flipi                       }
{html_set_row field=durie_salmon                caption=$_durie_salmon_lbl                  input=$_durie_salmon                }
{html_set_header                                caption=#subhead_cll# class="subhead" field="cll_rai,cll_binet"                 }
{html_set_row field=cll_rai                     caption=$_cll_rai_lbl                       input=$_cll_rai                     }
{html_set_row field=cll_binet                   caption=$_cll_binet_lbl                     input=$_cll_binet                   }
{html_set_header                                caption=#subhead_aml_all# class="subhead" field="aml_fab,aml_who,all_egil"      }
{html_set_row field=aml_fab                     caption=$_aml_fab_lbl                       input=$_aml_fab                     }
{html_set_row field=aml_who                     caption=$_aml_who_lbl                       input=$_aml_who                     }
{html_set_row field=all_egil                    caption=$_all_egil_lbl                      input=$_all_egil                    }
{html_set_header                                caption=#subhead_mds# class="subhead" field="mds_fab,mds_who"                   }
{html_set_row field=mds_fab                     caption=$_mds_fab_lbl                       input=$_mds_fab                     }
{html_set_row field=mds_who                     caption=$_mds_who_lbl                       input=$_mds_who                     }


{html_set_header caption=#head_zytologie#    class="head"}
{html_set_row field="zytologie_normal" caption=$_zytologie_normal_lbl   input=$_zytologie_normal}
<tr>
   <td class="msg" colspan="2">
      <table class="inline-table">
         <tr>
            <td class="lbl" rowspan="7">{#lbl_zytologische_parameter#}</td>
            <td class="edt" style="border-bottom:1px solid #fff;"><strong>{#lbl_param#}</strong></td>
            <td class="edt" style="border-bottom:1px solid #fff;"><strong>{#lbl_ergebnis#}</strong></td>
         </tr>
         <tr>
           <td class="edt">{$_zelldichte_lbl}</td>
           <td class="edt">{$_zelldichte}</td>
         </tr>
         <tr>
           <td class="edt">{$_erythropoese_lbl}</td>
           <td class="edt">{$_erythropoese}</td>
         </tr>
         <tr>
           <td class="edt">{$_granulopoese_lbl}</td>
           <td class="edt">{$_granulopoese}</td>
         </tr>
         <tr>
           <td class="edt">{$_megakaryopoese_lbl}</td>
           <td class="edt">{$_megakaryopoese}</td>
         </tr>
         <tr>
           <td class="edt">{$_km_infiltration_lbl}</td>
           <td class="edt">{$_km_infiltration}{#lbl_jwj#} {$_km_infiltration_anteil}{#lbl_prozent#} {$_km_i_nb}</td>
         </tr>
         <tr>
           <td class="edt" >{$_zyto_sonstiges_text_lbl}</td>
           <td class="edt">{$_zyto_sonstiges_text} {$_zyto_sonstiges}</td>
         </tr>
      </table>
   </td>
</tr>
{html_set_row field="zellveraenderung"    caption=$_zellveraenderung_lbl      input=$_zellveraenderung}
<tr>
   <td class="msg" colspan="2">
      <table class="inline-table">
         <tr>
            <td class="lbl" rowspan="7">{#lbl_quali_veraenderung#}</td>
            <td class="edt" style="border-bottom:1px solid #fff;"><strong>{#lbl_param#}</strong></td>
            <td class="edt" style="border-bottom:1px solid #fff;"><strong>{#lbl_ergebnis#}</strong></td>
            <td class="edt" style="border-bottom:1px solid #fff;"></td>
         </tr>
         <tr>
           <td class="edt">{$_erythrozyten_lbl}</td>
           <td class="edt">{$_erythrozyten} {$_erythrozyten_text_lbl}</td>
           <td class="edt">{$_erythrozyten_text}</td>
         </tr>
         <tr>
           <td class="edt">{$_granulozyten_lbl}</td>
           <td class="edt">{$_granulozyten} {$_granulozyten_text_lbl}</td>
           <td class="edt">{$_granulozyten_text}</td>
         </tr>
         <tr>
           <td class="edt">{$_megakaryozyten_lbl}</td>
           <td class="edt">{$_megakaryozyten} {$_megakaryozyten_text_lbl}</td>
           <td class="edt">{$_megakaryozyten_text}</td>
         </tr>
         <tr>
           <td class="edt">{$_lymphozyten_text_lbl}</td>
           <td class="edt">{$_lymphozyten_text}</td>
            <td class="edt"></td>
         </tr>
         <tr>
           <td class="edt">{$_plasmazellen_text_lbl}</td>
           <td class="edt">{$_plasmazellen_text}</td>
           <td class="edt"></td>
         </tr>
         <tr>
           <td class="edt">{$_zellen_sonstiges_lbl}</td>
           <td class="edt">{$_zellen_sonstiges}</td>
           <td class="edt">{$_zellen_sonstiges_text}</td>
         </tr>
      </table>
   </td>
</tr>
{html_set_header caption=#head_liquordiagnostik#    class="head"}
<tr>
   <td class="msg" colspan="2">
      <table class="inline-table">
         <tr>
            <td class="subhead">{#lbl_methode#}</td>
            <td class="subhead">{#lbl_methode#}</td>
            <td class="subhead">{#lbl_zellzahl#}</td>
            <td class="subhead">{#lbl_beurteilung#}</td>
         </tr>
         <tr>
            <td class="lbl" rowspan="3">{#lbl_liqour#}</td>
            <td class="edt">{$_liquordiag_1_methode_lbl} {$_liquordiag_1_methode}</td>
            <td class="edt">{$_liquordiag_1_zellzahl}</td>
            <td class="edt">{$_liquordiag_1_beurteilung}</td>
         </tr>
         <tr>
            <td class="edt">{$_liquordiag_2_methode_lbl} {$_liquordiag_2_methode}</td>
            <td class="edt">{$_liquordiag_2_zellzahl}</td>
            <td class="edt">{$_liquordiag_2_beurteilung}</td>
         </tr>
         <tr>
            <td class="edt">{$_liquordiag_3_methode_lbl} {$_liquordiag_3_methode}</td>
            <td class="edt">{$_liquordiag_3_zellzahl}</td>
            <td class="edt">{$_liquordiag_3_beurteilung}</td>
         </tr>
      </table>
   </td>
</tr>

{html_set_header caption=#head_zytochemie#    class="head"}
<tr>
   <td class="msg" colspan="2">
      <table class="inline-table">
         <tr>
            <td class="lbl" rowspan="5">{#lbl_zytochemie_parameter#}</td>
         </tr>
         <tr>
            <td class="edt" style='width:22%'><strong>{#lbl_param#}</strong></td>
            <td class="edt" style='width:20%'><strong>{#lbl_beurt#}</strong></td>
            <td class="edt"><strong>{#lbl_anteil#}</strong></td>
         </tr>
         <tr>
            <td class="edt">{#lbl_myeloperoxidase#}</td>
            <td class="edt">{$_myeloperoxidase_urteil}</td>
            <td class="edt">{$_myeloperoxidase_anteil} {#lbl_prozent#}</td>
         </tr>
         <tr>
            <td class="edt">{#lbl_monozytenesterase#}</td>
            <td class="edt">{$_monozytenesterase_urteil}</td>
            <td class="edt">{$_monozytenesterase_anteil} {#lbl_prozent#}</td>
         </tr>
         <tr>
            <td class="edt">{#lbl_pas_reaktion#}</td>
            <td class="edt">{$_pas_reaktion_urteil}</td>
            <td class="edt">{$_pas_reaktion_anteil} {#lbl_prozent#}</td>
         </tr>
      </table>
   </td>
</tr>
{html_set_header caption=#head_immunzytologie#    class="head"}
{html_set_row field="immunzytologie_pathologisch"  caption=$_immunzytologie_pathologisch_lbl input=$_immunzytologie_pathologisch}
{html_set_row field="immunzytologie_diagnose"      caption=$_immunzytologie_diagnose_lbl     input=$_immunzytologie_diagnose}

{html_set_header caption=#head_zytogenetik#    class="head"}
{html_set_row field="zytogenetik_normal"  caption=$_zytogenetik_normal_lbl input=$_zytogenetik_normal}

   <tr>
      <td class="msg" colspan="2">
         <div class="dlist" id="dlist_aberration">
            <div class="add">
               <input class="button" type="button" name="aberration" value="Hinzuf&uuml;gen" onclick="execute_request(this,'rec.zytologie_aberration', null, ['patient_id', 'zytologie_id', 'erkrankung_id'])"/>
            </div>
         </div>
      </td>
   </tr>

{html_set_header caption=#head_mrd#    class="head"}
{html_set_html field="mrd1_methode" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_mrd1_methode_lbl</td>
            <td class='edt' style='width:20%;'>$_mrd1_methode</td>
            <td class='edt'>$_mrd1_ergebnis </td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_html field="mrd2_methode" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_mrd2_methode_lbl</td>
            <td class='edt' style='width:20%;'>$_mrd2_methode</td>
            <td class='edt'>$_mrd2_ergebnis </td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_header caption=#head_bem# class="head"}
{html_set_header field="bem" caption=$_bem      class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
{$_zytologie_id}
{$_patient_id}
{$_erkrankung_id}
</div>