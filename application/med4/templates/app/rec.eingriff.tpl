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

<table class="formtable msg">

{html_set_header                                      caption=#head_eingriff#                      class="head"                           }
{html_set_row field="datum"                           caption=$_datum_lbl                          input=$_datum                          }
{html_set_row field="notfall"                         caption=$_notfall_lbl                        input=$_notfall                        }
{html_set_row field="org_id"                          caption=$_org_id_lbl                         input=$_org_id                         }
{html_set_row field="operateur1_id"                   caption=$_operateur1_id_lbl                  input=$_operateur1_id                  }
{html_set_row field="operateur2_id"                   caption=$_operateur2_id_lbl                  input=$_operateur2_id                  }
{html_set_row field="therapieplan_id"                 caption=$_therapieplan_id_lbl                input=$_therapieplan_id                }
{html_set_row field="dauer"                           caption=$_dauer_lbl                          input=$_dauer    add=#lbl_min#         }
{html_set_row field="asa"                             caption=$_asa_lbl                            input=$_asa                            }
{html_set_row field="wundkontamination_cdc"           caption=$_wundkontamination_cdc_lbl          input=$_wundkontamination_cdc}
{html_set_row field="interdisziplinaer"               caption=$_interdisziplinaer_lbl              input=$_interdisziplinaer              }
{html_set_row field="urologie_bet"                    caption=$_urologie_bet_lbl                   input=$_urologie_bet                   }
{html_set_row field="chirurgie_bet"                   caption=$_chirurgie_bet_lbl                  input=$_chirurgie_bet                  }

{html_set_header caption=#head_beschreibung# class="head" field='diagnose_seite,intention,art_diagnostik,art_primaertumor,art_lk,art_metastasen,art_rezidiv,art_nachresektion,art_revision,art_rekonstruktion,art_sonstige,art_transplantation_autolog,art_transplantation_allogen_v,art_transplantation_allogen_nv,art_transplantation_syngen,verwandschaftsgrad'}
{html_set_row field="diagnose_seite"                  caption=$_diagnose_seite_lbl                 input=$_diagnose_seite hiddenIfFalse=true default="-" }
{html_set_row field="intention"                       caption=$_intention_lbl                      input=$_intention                      }
{html_set_row field="art_diagnostik"                  caption=$_art_diagnostik_lbl                 input=$_art_diagnostik                 }
{html_set_row field="art_staging"                     caption=$_art_staging_lbl                    input=$_art_staging                    }
{html_set_row field="art_sonstige"                    caption=$_art_sonstige_lbl                   input=$_art_sonstige                   }

{html_set_header caption=#subhead_resektion# class="subhead" field="art_primaertumor,art_lk,art_metastasen,art_rezidiv,art_nachresektion,art_revision,art_rekonstruktion"}
{html_set_row field="art_primaertumor"                caption=$_art_primaertumor_lbl               input=$_art_primaertumor               }
{html_set_row field="art_lk"                          caption=$_art_lk_lbl                         input=$_art_lk                         }
{html_set_row field="art_metastasen"                  caption=$_art_metastasen_lbl                 input=$_art_metastasen                 }
{html_set_row field="art_rezidiv"                     caption=$_art_rezidiv_lbl                    input=$_art_rezidiv                    }
{html_set_row field="art_nachresektion"               caption=$_art_nachresektion_lbl              input=$_art_nachresektion              }
{html_set_row field="art_revision"                    caption=$_art_revision_lbl                   input=$_art_revision                   }
{html_set_row field="art_rekonstruktion"              caption=$_art_rekonstruktion_lbl             input=$_art_rekonstruktion             }

{html_set_header field="art_transplantation_autolog,art_transplantation_allogen_v,art_transplantation_allogen_nv,art_transplantation_syngen,verwandschaftsgrad"  caption=#subhead_transplantation# class="subhead"}
{html_set_row field="art_transplantation_autolog"     caption=$_art_transplantation_autolog_lbl    input=$_art_transplantation_autolog    }
{html_set_row field="art_transplantation_allogen_v"   caption=$_art_transplantation_allogen_v_lbl  input=$_art_transplantation_allogen_v  }
{html_set_row field="art_transplantation_allogen_nv"  caption=$_art_transplantation_allogen_nv_lbl input=$_art_transplantation_allogen_nv }
{html_set_row field="art_transplantation_syngen"      caption=$_art_transplantation_syngen_lbl     input=$_art_transplantation_syngen     }
{html_set_row field="verwandschaftsgrad"              caption=$_verwandschaftsgrad_lbl             input=$_verwandschaftsgrad             }

{html_set_header                                      caption=#head_begl_massnahmen#               class="head" field="stamm_purging_text,postop_mrt_ergebnis,postop_sono_ergebnis,postop_roentgen_ergebnis,intraop_mrt_ergebnis,intraop_sono_ergebnis,intraop_roe_ergebnis,stamm_purging,stamm_sep_cd34_konz_absolut,stamm_sep_cd34_konz,stamm_sep_menge_absolut,stamm_sep_menge,stamm_sep_datum,leukapheresen_anzahlstammzellenmobilisierung,postop_mrt,postop_sono,postop_roentgen,intraop_mrt,intraop_sono,antibiotikaprophylaxe,thromboseprophylaxe,blasenkatheter,intraop_roe,schnellschnitt_dauer,schnellschnitt,mark_abstand,mark,mesorektale_faszie,stomaposition"}

{html_set_row field="stomaposition"                   caption=$_stomaposition_lbl                  input="$_stomaposition"}
{html_set_row field="mesorektale_faszie"              caption=$_mesorektale_faszie_lbl             input="$_mesorektale_faszie" add=#lbl_mm#}

{html_set_html field="mark" html="
<tr>
   <td class='lbl' rowspan='2'>$_mark_lbl</td>
   <td class='edt'>$_mark `$smarty.config.lbl_ja`</td>
</tr>
<tr>
   <td class='edt'>
      $_mark_mammo $_mark_mammo_lbl
      $_mark_sono  $_mark_sono_lbl
      $_mark_mrt   $_mark_mrt_lbl
   </td>
</tr>
"}
{html_set_row field="mark_abstand"                    caption=$_mark_abstand_lbl                   input="$_mark_abstand"}
{html_set_row field="schnellschnitt"                  caption=$_schnellschnitt_lbl                 input=$_schnellschnitt}
{html_set_row field="schnellschnitt_dauer"            caption=$_schnellschnitt_dauer_lbl           input=$_schnellschnitt_dauer    add=#lbl_min#       }
{html_set_row field="intraop_roe"                     caption=$_intraop_roe_lbl                    input="$_intraop_roe $_intraop_roe_ergebnis_lbl $_intraop_roe_ergebnis"}
{html_set_row field="blasenkatheter"                  caption=$_blasenkatheter_lbl                 input=$_blasenkatheter                 }
{html_set_row field="thromboseprophylaxe"             caption=$_thromboseprophylaxe_lbl            input=$_thromboseprophylaxe            }
{html_set_row field="antibiotikaprophylaxe"           caption=$_antibiotikaprophylaxe_lbl          input=$_antibiotikaprophylaxe          }
{html_set_row field="intraop_sono"                    caption=$_intraop_sono_lbl                   input="$_intraop_sono $_intraop_sono_ergebnis_lbl $_intraop_sono_ergebnis"}
{html_set_row field="intraop_mrt"                     caption=$_intraop_mrt_lbl                    input="$_intraop_mrt $_intraop_mrt_ergebnis_lbl $_intraop_mrt_ergebnis"}
{html_set_row field="postop_roentgen"                 caption=$_postop_roentgen_lbl                input="$_postop_roentgen $_postop_roentgen_ergebnis_lbl $_postop_roentgen_ergebnis"}
{html_set_row field="postop_sono"                     caption=$_postop_sono_lbl                    input="$_postop_sono $_postop_sono_ergebnis_lbl $_postop_sono_ergebnis"}
{html_set_row field="postop_mrt"                      caption=$_postop_mrt_lbl                     input="$_postop_mrt $_postop_mrt_ergebnis_lbl $_postop_mrt_ergebnis"}
{html_set_row field="stammzellenmobilisierung"        caption=$_stammzellenmobilisierung_lbl       input=$_stammzellenmobilisierung       }
{html_set_row field="leukapheresen_anzahl"            caption=$_leukapheresen_anzahl_lbl           input=$_leukapheresen_anzahl           }
{html_set_row field="stamm_sep_datum"                 caption=$_stamm_sep_datum_lbl                input=$_stamm_sep_datum                }
{html_set_row field="stamm_sep_menge"                 caption=$_stamm_sep_menge_lbl                input=$_stamm_sep_menge                }
{html_set_row field="stamm_sep_menge_absolut"         caption=$_stamm_sep_menge_absolut_lbl        input=$_stamm_sep_menge_absolut        }
{html_set_row field="stamm_sep_cd34_konz"             caption=$_stamm_sep_cd34_konz_lbl            input=$_stamm_sep_cd34_konz            }
{html_set_row field="stamm_sep_cd34_konz_absolut"     caption=$_stamm_sep_cd34_konz_absolut_lbl    input=$_stamm_sep_cd34_konz_absolut    }
{html_set_row field="stamm_purging"                   caption=$_stamm_purging_lbl                  input="$_stamm_purging $_stamm_purging_text_lbl $_stamm_purging_text" }

{html_set_header caption=#head_chimerismus# class="head" field="dli2_wert,dli2_datum,dli1_wert,dli1_datum,chim4_wert,chim4_datum,chim3_wert,chim3_datum,chim2_wert,chim2_datum,chim1_datum,chim1_wert"}
{getView field="chim4_wert,chim4_datum,chim3_wert,chim3_datum,chim2_wert,chim2_datum,chim1_datum,chim1_wert"}
   <tr>
      <td class='subhead'>{$_chim1_datum_lbl}</td>
      <td class='subhead'>{$_chim1_wert_lbl}</td>
   </tr>
{/getView}
{getView field="chim1_datum,chim1_wert"}
<tr>
    <td class='edt'>{$_chim1_datum}</td>
    <td class='edt'>{$_chim1_wert} {#lbl_proz#}</td>
</tr>
{/getView}
{getView field="chim2_datum,chim2_wert"}
<tr>
    <td class='edt'>{$_chim2_datum}</td>
    <td class='edt'>{$_chim2_wert} {#lbl_proz#}</td>
</tr>
{/getView}
{getView field="chim3_datum,chim3_wert"}
<tr>
    <td class='edt'>{$_chim3_datum}</td>
    <td class='edt'>{$_chim3_wert} {#lbl_proz#}</td>
</tr>
{/getView}
{getView field="chim4_datum,chim4_wert"}
<tr>
    <td class='edt'>{$_chim4_datum}</td>
    <td class='edt'>{$_chim4_wert} {#lbl_proz#}</td>
</tr>
{/getView}


{getView field="dli1_datum,dli1_wert,dli2_datum,dli2_wert"}
   <tr>
      <td class='subhead'>{$_dli1_datum_lbl}</td>
      <td class='subhead'>{$_dli1_wert_lbl}</td>
   </tr>
{/getView}
{getView field="dli1_datum,dli1_wert"}
   <tr>
      <td class='edt'>{$_dli1_datum}</td>
      <td class='edt'>{$_dli1_wert} {#lbl_kg#}</td>
   </tr>
{/getView}
{getView field="dli2_datum,dli2_wert"}
   <tr>
      <td class='edt'>{$_dli2_datum}</td>
      <td class='edt'>{$_dli2_wert} {#lbl_kg#}</td>
   </tr>
{/getView}

{html_set_header caption=#head_zeitpunkt# class="head" field="erholung_leukozyten_datum,erholung_granulozyten_datum,erholung_thrombozyten_datum,erholung_gesamt_datum"}
{html_set_row field="erholung_leukozyten_datum"    caption=$_erholung_leukozyten_datum_lbl   input=$_erholung_leukozyten_datum   }
{html_set_row field="erholung_granulozyten_datum"  caption=$_erholung_granulozyten_datum_lbl input=$_erholung_granulozyten_datum }
{html_set_row field="erholung_thrombozyten_datum"  caption=$_erholung_thrombozyten_datum_lbl input=$_erholung_thrombozyten_datum }
{html_set_row field="erholung_gesamt_datum"        caption=$_erholung_gesamt_datum_lbl       input=$_erholung_gesamt_datum       }



{html_set_header                                   caption=#head_eingriff_massnahmen#        class="head"                        }
   <tr>
      <td class="msg" colspan="2">
         <div class="dlist" id="dlist_ops">
            <div class="add">
               <input class="button" type="button" name="eingriff_ops" value="Hinzuf&uuml;gen" onclick="execute_request(this,'rec.eingriff_ops', null, ['patient_id', 'eingriff_id', 'erkrankung_id'])"/>
            </div>
         </div>
      </td>
   </tr>

{html_set_row field="axilla_sampling"                     caption=$_axilla_sampling_lbl                      input=$_axilla_sampling}
{html_set_row field="axilla_nein_grund"                   caption=$_axilla_nein_grund_lbl                    input=$_axilla_nein_grund}

{html_set_html field="res_oberlappen" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='border-top:1px solid #fff'>
         <tr>
            <td class='lbl'>`$smarty.config.lbl_rez_bereich`</td>
            <td class='edt'>$_res_oberlappen $_res_oberlappen_lbl</td>
            <td class='edt'>$_res_mittellappen $_res_mittellappen_lbl</td>
            <td class='edt'>$_res_unterlappen $_res_unterlappen_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_row field="tme"                                 caption=$_tme_lbl                                  input=$_tme}
{html_set_row field="pme"                                 caption=$_pme_lbl                                  input=$_pme}
{html_set_row field="ther_koloskopie_vollstaendig"        caption=$_ther_koloskopie_vollstaendig_lbl         input=$_ther_koloskopie_vollstaendig}
{html_set_row field="op_verfahren"                        caption=$_op_verfahren_lbl                         input=$_op_verfahren}
{html_set_row field="nerverhalt_seite"                    caption=$_nerverhalt_seite_lbl                     input=$_nerverhalt_seite}
{html_set_row field="lymphadenektomie_methode_prostata"   caption=$_lymphadenektomie_methode_prostata_lbl    input=$_lymphadenektomie_methode_prostata}


{html_set_html field="intraop_bestrahlung" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='border-top:1px solid #fff'>
         <tr>
            <td class='lbl' style='width:35%;'>$_intraop_bestrahlung_lbl</td>
            <td class='edt' style='width:5%;'>$_intraop_bestrahlung</td>
            <td class='edt' style='width:5%;' align='right'>$_intraop_bestrahlung_dosis_lbl</td>
            <td class='edt' >$_intraop_bestrahlung_dosis `$smarty.config.lbl_gy`</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_html field="intraop_zytostatika" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='border-top:1px solid #fff'>
         <tr>
            <td class='lbl' style='width:35%;'>$_intraop_zytostatika_lbl</td>
            <td class='edt' style='width:5%;'>$_intraop_zytostatika</td>

            <td class='edt' style='width:5%;' align='right'>$_intraop_zytostatika_art_lbl</td>
            <td class='edt' >$_intraop_zytostatika_art</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_row field="hypertherme_perfusion"               caption=$_hypertherme_perfusion_lbl                input=$_hypertherme_perfusion                }
{html_set_row field="plastischer_verschluss"              caption=$_plastischer_verschluss_lbl               input=$_plastischer_verschluss               }

{if $erkrankungData.code == 'gt'}
    {html_set_header field="laparotomie"                  caption=#head_staging#                             class="head"                                 }
    {html_set_header  class="msgbox"                      caption=#info_dkg#}
{/if}
{html_set_row field="laparotomie"                         caption=$_laparotomie_lbl                          input=$_laparotomie                  }
{html_set_row field="peritonealzytologie"                 caption=$_peritonealzytologie_lbl                  input=$_peritonealzytologie          }
{html_set_row field="peritonealbiopsie"                   caption=$_peritonealbiopsie_lbl                    input=$_peritonealbiopsie            }
{html_set_row field="adnexexstirpation"                   caption=$_adnexexstirpation_lbl                    input=$_adnexexstirpation            }
{html_set_row field="hysterektomie"                       caption=$_hysterektomie_lbl                        input=$_hysterektomie                }
{html_set_row field="omentektomie"                        caption=$_omentektomie_lbl                         input=$_omentektomie                 }
{html_set_row field="lymphonodektomie"                    caption=$_lymphonodektomie_lbl                     input=$_lymphonodektomie             }


{html_set_header field="sln_markierung"                   caption=#head_sln_biopsie#                         class="head"                                 }
{html_set_row field="sln_nein_grund"                      caption=$_sln_nein_grund_lbl                       input=$_sln_nein_grund                       }
{html_set_row field="sentinel_nicht_detektierbar"         caption=$_sentinel_nicht_detektierbar_lbl          input=$_sentinel_nicht_detektierbar          }
{html_set_row field="sln_markierung"                      caption=$_sln_markierung_lbl                       input=$_sln_markierung                       }
{html_set_row field="sln_anzahl"                          caption=$_sln_anzahl_lbl                           input=$_sln_anzahl                           }
{html_set_row field="sln_parasternal"                     caption=$_sln_parasternal_lbl                      input=$_sln_parasternal                      }
{html_set_row field="sln_restaktivitaet"                  caption=$_sln_restaktivitaet_lbl                   input=$_sln_restaktivitaet                   }
{html_set_row field="sln_schnellschnitt"                  caption=$_sln_schnellschnitt_lbl                   input=$_sln_schnellschnitt                   }
{html_set_row field="sln_schnellschnitt_befall"           caption=$_sln_schnellschnitt_befall_lbl            input=$_sln_schnellschnitt_befall            }
{html_set_row field="sln_schnellschnitt_dauer_versendung" caption=$_sln_schnellschnitt_dauer_versendung_lbl  input=$_sln_schnellschnitt_dauer_versendung  add=#lbl_min#}
{html_set_row field="sln_schnellschnitt_dauer_eingang"    caption=$_sln_schnellschnitt_dauer_eingang_lbl     input=$_sln_schnellschnitt_dauer_eingang     add=#lbl_min#}
</table>

<table class="formtable msg">

{html_set_header caption=#head_intraop_situs#            class="head" field ="blutverlust,polypen,polypen_anz_gef,polypen_anz_entf,polypen_op_areal,aszites_volumen,tumorrest"}
{html_set_row field="blutverlust"                        caption=$_blutverlust_lbl                           input=$_blutverlust        add=#lbl_ml#      }
{html_set_row field="polypen"                            caption=$_polypen_lbl                               input=$_polypen                              }
{html_set_row field="polypen_anz_gef"                    caption=$_polypen_anz_gef_lbl                       input=$_polypen_anz_gef                      }
{html_set_row field="polypen_anz_entf"                   caption=$_polypen_anz_entf_lbl                      input=$_polypen_anz_entf                     }
{html_set_row field="polypen_op_areal"                   caption=$_polypen_op_areal_lbl                      input=$_polypen_op_areal                     }
{html_set_row field="aszites_volumen" caption=$_aszites_volumen_lbl input=$_aszites_volumen}
</table>

{getView field='peritonealkarzinose_darm,peritonealkarzinose_becken,peritonealkarzinose_mittelbauch,peritonealkarzinose_zwerchfell'}
<table class="formtable msg">
    <tr>
      <td class='lbl' rowspan='4'>{#lbl_peritonealkarzinose#}</td>
      {getView field='peritonealkarzinose_darm'}
      <td class='edt' style="width:90px">{$_peritonealkarzinose_darm_lbl}</td>
      <td class='edt'>{$_peritonealkarzinose_darm}</td>
      {/getView}
   </tr>
   {getView field='peritonealkarzinose_becken'}
   <tr>
      <td class='edt' style="width:90px">{$_peritonealkarzinose_becken_lbl}</td>
      <td class='edt'>{$_peritonealkarzinose_becken}</td>
   </tr>
   {/getView}
   {getView field='peritonealkarzinose_mittelbauch'}
   <tr>
      <td class='edt' style="width:90px">{$_peritonealkarzinose_mittelbauch_lbl}</td>
      <td class='edt'>{$_peritonealkarzinose_mittelbauch}</td>
   </tr>
   {/getView}
   {getView field='peritonealkarzinose_zwerchfell'}
   <tr>
      <td class='edt' style="width:90px">{$_peritonealkarzinose_zwerchfell_lbl}</td>
      <td class='edt'>{$_peritonealkarzinose_zwerchfell}</td>
   </tr>
   {/getView}
</table>
{/getView}

{getView field='tumorlast_a3,tumorlast_a2,tumorlast_a1,tumorlast_b3,tumorlast_b2,tumorlast_b1,tumorlast_c3,tumorlast_c2,tumorlast_c1'}
<table class="formtable msg">
    <tr>
      <td class='subhead' colspan='6'>{#subhead_tumorlast#}</td>
    </tr>
    <tr>
      <td class='lbl' colspan='2'></td>
      <td class='lbl' style='width:10%;' align='center'>{#lbl_a#}</td>
      <td class='lbl' style='width:10%;' align='center'>{#lbl_b#}</td>
      <td class='lbl' style='width:10%;' align='center'>{#lbl_c#}</td>
      <td class='edt' rowspan='6' align='center' style='width: 20%'>
        <img src='media/img/app/tumor_sitz.png' alt='images/clipart/tumor_sitz.png' style='text-align: center; border:0px;'/>
      </td>
   </tr>
   <tr>
      <td class='lbl' rowspan='3'>{#lbl_hoechste_tumorlast#}</td>
      <td class='lbl' align='center'>{#lbl_3#}</td>
      <td class='edt' align='center'>{$_tumorlast_a3}</td>
      <td class='edt' align='center'>{$_tumorlast_b3}</td>
      <td class='edt' align='center'>{$_tumorlast_c3}</td>
   </tr>
   <tr>
      <td class='lbl' align='center'>{#lbl_2#}</td>
      <td class='edt' align='center'>{$_tumorlast_a2}</td>
      <td class='edt' align='center'>{$_tumorlast_b2}</td>
      <td class='edt' align='center'>{$_tumorlast_c2}</td>
   </tr>
   <tr>
      <td class='lbl' align='center'>{#lbl_1#}</td>
      <td class='edt' align='center'>{$_tumorlast_a1}</td>
      <td class='edt' align='center'>{$_tumorlast_b1}</td>
      <td class='edt' align='center'>{$_tumorlast_c1}</td>
   </tr>
   <tr>
       <td colspan="5" class="edt"><!-- --></td>
   </tr>
</table>
{/getView}

{getView field='tumorrest,tumorrest_groesse,tumorrest_detail,tumorrest_a3,tumorrest_b3,tumorrest_c3,tumorrest_a2,tumorrest_b2,tumorrest_c2,tumorrest_a1,tumorrest_b1,tumorrest_c1,r0limit_aetas,r0limit_komplikation,r0limit_lokalisation,r0limit_wunsch,r0limit_sonstige,r0limit_wunsch_text,r0limit_lokalisation_text,r0limit_sonstige_text,befall_ovar,befall_ovar_rest,befall_ovar_seite,befall_tube,befall_tube_rest,befall_tube_seite,befall_milz,befall_milz_rest,befall_lk,befall_lk_rest,befall_ureter,befall_ureter_rest,befall_douglas,befall_douglas_rest,befall_vaginalstumpf,befall_vaginalstumpf_rest,befall_duenndarm,befall_duenndarm_rest,befall_zwerchfell,befall_zwerchfell_rest,befall_magen,befall_magen_rest,befall_lsm,befall_lsm_rest,befall_blasenwand,befall_blasenwand_rest,befall_beckenwand,befall_beckenwand_rest,befall_mesenterium,befall_mesenterium_rest,befall_dickdarm,befall_dickdarm_rest,befall_dickdarmschleimhaut,befall_dickdarmschleimhaut_rest,befall_omentum_majus,befall_omentum_majus_rest,befall_bauchwand,befall_bauchwand_rest,befall_blasenschleimhaut,befall_blasenschleimhaut_rest,befall_uterus,befall_uterus_rest,befall_bursa,befall_bursa_rest,befall_sonst,befall_sonst_text'}
   <table class='formtable msg'>
      <tr>
         <td class='head' colspan='3'>
           {#head_postop_situs#}
         </td>
      </tr>

      {getView field='tumorrest,tumorrest_groesse'}
      <tr>
         <td class='lbl'>{$_tumorrest_lbl}</td>
         <td class='edt' style='width:10%;'>{$_tumorrest}</td>
         <td class='edt'>
            {getView field='tumorrest_groesse'}
               {$_tumorrest_groesse_lbl} {$_tumorrest_groesse} {#lbl_mm#}
            {/getView}
         </td>
      </tr>
      {/getView}

      {getView field='tumorrest_detail'}
      <tr>
         <td class='lbl'>{$_tumorrest_detail_lbl}</td>
         <td class='edt' colspan="2">{$_tumorrest_detail}</td>
      </tr>
      {/getView}
   </table>

   {getView field='tumorrest_a3,tumorrest_b3,tumorrest_c3,tumorrest_a2,tumorrest_b2,tumorrest_c2,tumorrest_a1,tumorrest_b1,tumorrest_c1'}
      <table class='formtable msg'>
         <tr>
               <td class='subhead' colspan='6'>{#subhead_tumorrest#}</td>
            </tr>
         <tr>
            <td class='lbl' style='width:10%;' colspan='2'><!--  --></td>
            <td class='lbl' style='width:10%;' align='center'>{#lbl_a#}</td>
            <td class='lbl' style='width:10%;' align='center'>{#lbl_b#}</td>
            <td class='lbl' style='width:10%;' align='center'>{#lbl_c#}</td>
         </tr>
         <tr>
            <td class='lbl' rowspan='3'>{#lbl_sitz_tumorrest#}</td>
            <td class='lbl' align='center'>{#lbl_3#}</td>
            <td class='edt' align='center'>{$_tumorrest_a3}</td>
            <td class='edt' align='center'>{$_tumorrest_b3}</td>
            <td class='edt' align='center'>{$_tumorrest_c3}</td>
         </tr>
         <tr>
            <td class='lbl' align='center'>{#lbl_2#}</td>
            <td class='edt' align='center'>{$_tumorrest_a2}</td>
            <td class='edt' align='center'>{$_tumorrest_b2}</td>
            <td class='edt' align='center'>{$_tumorrest_c2}</td>
         </tr>
         <tr>
            <td class='lbl' align='center'>{#lbl_1#}</td>
            <td class='edt' align='center'>{$_tumorrest_a1}</td>
            <td class='edt' align='center'>{$_tumorrest_b1}</td>
            <td class='edt' align='center'>{$_tumorrest_c1}</td>
         </tr>
      </table>
   {/getView}

   {getView field='r0limit_aetas,r0limit_komplikation,r0limit_lokalisation,r0limit_wunsch,r0limit_sonstige,r0limit_wunsch_text,r0limit_lokalisation_text,r0limit_sonstige_text'}
      <table class='formtable msg'>
         <tr>
            <td class='lbl' rowspan='5'>{#lbl_limit_r0#}</td>
            <td class='edt'>{$_r0limit_aetas} {$_r0limit_aetas_lbl}</td>
         </tr>
         <tr>
            <td class='edt'>{$_r0limit_komplikation} {$_r0limit_komplikation_lbl}</td>
         </tr>

         <tr>
            <td class='edt'>{$_r0limit_lokalisation} {$_r0limit_lokalisation_lbl}{$_r0limit_lokalisation_text_lbl}  <br/> {$_r0limit_lokalisation_text}</td>
         </tr>
         <tr>
            <td class='edt'>{$_r0limit_wunsch} {$_r0limit_wunsch_lbl}{$_r0limit_wunsch_text_lbl}  <br/> {$_r0limit_wunsch_text}</td>
         </tr>
         <tr>
            <td class='edt'>{$_r0limit_sonstige} {$_r0limit_sonstige_lbl}{$_r0limit_sonstige_text_lbl}  <br/> {$_r0limit_sonstige_text}</td>
         </tr>
      </table>
   {/getView}

   {getView field='befall_ovar,befall_ovar_rest,befall_ovar_seite,befall_tube,befall_tube_rest,befall_tube_seite,befall_milz,befall_milz_rest,befall_lk,befall_lk_rest,befall_ureter,befall_ureter_rest,befall_douglas,befall_douglas_rest,befall_vaginalstumpf,befall_vaginalstumpf_rest,befall_duenndarm,befall_duenndarm_rest,befall_zwerchfell,befall_zwerchfell_rest,befall_magen,befall_magen_rest,befall_lsm,befall_lsm_rest,befall_blasenwand,befall_blasenwand_rest,befall_beckenwand,befall_beckenwand_rest,befall_mesenterium,befall_mesenterium_rest,befall_dickdarm,befall_dickdarm_rest,befall_dickdarmschleimhaut,befall_dickdarmschleimhaut_rest,befall_omentum_majus,befall_omentum_majus_rest,befall_bauchwand,befall_bauchwand_rest,befall_blasenschleimhaut,befall_blasenschleimhaut_rest,befall_uterus,befall_uterus_rest,befall_bursa,befall_bursa_rest,befall_vagina,befall_vagina_rest,befall_portio,befall_portio_rest,befall_cervix,befall_cervix_rest,befall_vulva,befall_vulva_rest,befall_urethra,befall_urethra_rest,befall_sonst,befall_sonst_text'}
      <table class='formtable msg'>
         <tr>
            <td class='subhead' style='width:150px'>{#subhead_befall#}</td>
            <td class='subhead' >{#subhead_rest#}</td>
            <td class='subhead' style='width:170px'>{#subhead_befall#}</td>
            <td class='subhead' >{#subhead_rest#}</td>
            <td class='subhead' style='width:180px'>{#subhead_befall#}</td>
            <td class='subhead' style='width:40px'>{#subhead_rest#}</td>
         </tr>
         {getView field='befall_ovar,befall_ovar_rest,befall_duenndarm,befall_duenndarm_rest,befall_dickdarm,befall_dickdarm_rest'}
         <tr>
            <td class='edt'>{$_befall_ovar} {$_befall_ovar_lbl} {$_befall_ovar_seite}</td>
            <td class='edt'>{$_befall_ovar_rest}</td>
            <td class='edt'>{$_befall_duenndarm} {$_befall_duenndarm_lbl}</td>
            <td class='edt'>{$_befall_duenndarm_rest}</td>
            <td class='edt'>{$_befall_dickdarm} {$_befall_dickdarm_lbl}</td>
            <td class='edt'>{$_befall_dickdarm_rest}</td>
         </tr>
         {/getView}
         {getView field='befall_tube,befall_tube_rest,befall_zwerchfell,befall_zwerchfell_rest,befall_dickdarmschleimhaut,befall_dickdarmschleimhaut_rest'}
         <tr>
            <td class='edt'>{$_befall_tube} {$_befall_tube_lbl} {$_befall_tube_seite}</td>
            <td class='edt'>{$_befall_tube_rest}</td>
            <td class='edt'>{$_befall_zwerchfell} {$_befall_zwerchfell_lbl}</td>
            <td class='edt'>{$_befall_zwerchfell_rest}</td>
            <td class='edt'>{$_befall_dickdarmschleimhaut}  {$_befall_dickdarmschleimhaut_lbl}</td>
            <td class='edt'>{$_befall_dickdarmschleimhaut_rest}</td>
         </tr>
         {/getView}
         {getView field='befall_milz,befall_milz_rest,befall_magen,befall_magen_rest,befall_omentum_majus,befall_omentum_majus_rest'}
         <tr>
            <td class='edt'>{$_befall_milz} {$_befall_milz_lbl}</td>
            <td class='edt'>{$_befall_milz_rest}</td>
            <td class='edt'>{$_befall_magen} {$_befall_magen_lbl}</td>
            <td class='edt'>{$_befall_magen_rest}</td>
            <td class='edt'>{$_befall_omentum_majus}  {$_befall_omentum_majus_lbl}</td>
            <td class='edt'>{$_befall_omentum_majus_rest}</td>
         </tr>
         {/getView}
         {getView field='befall_lk,befall_lk_rest,befall_lsm,befall_lsm_rest,befall_bauchwand,befall_bauchwand_rest'}
         <tr>
            <td class='edt'>{$_befall_lk} {$_befall_lk_lbl}</td>
            <td class='edt'>{$_befall_lk_rest}</td>
            <td class='edt'>{$_befall_lsm} {$_befall_lsm_lbl}</td>
            <td class='edt'>{$_befall_lsm_rest}</td>
            <td class='edt'>{$_befall_bauchwand}  {$_befall_bauchwand_lbl}</td>
            <td class='edt'>{$_befall_bauchwand_rest}</td>
         </tr>
         {/getView}
         {getView field='befall_ureter,befall_ureter_rest,befall_blasenwand,befall_blasenwand_rest,befall_blasenschleimhaut,befall_blasenschleimhaut_rest'}
         <tr>
            <td class='edt'>{$_befall_ureter} {$_befall_ureter_lbl}</td>
            <td class='edt'>{$_befall_ureter_rest}</td>
            <td class='edt'>{$_befall_blasenwand} {$_befall_blasenwand_lbl}</td>
            <td class='edt'>{$_befall_blasenwand_rest}</td>
            <td class='edt'>{$_befall_blasenschleimhaut} {$_befall_blasenschleimhaut_lbl}</td>
            <td class='edt'>{$_befall_blasenschleimhaut_rest}</td>
         </tr>
         {/getView}
         {getView field='befall_douglas,befall_douglas_rest,befall_beckenwand,befall_beckenwand_rest,befall_uterus,befall_uterus_rest'}
         <tr>
            <td class='edt'>{$_befall_douglas} {$_befall_douglas_lbl}</td>
            <td class='edt'>{$_befall_douglas_rest}</td>
            <td class='edt'>{$_befall_beckenwand} {$_befall_beckenwand_lbl}</td>
            <td class='edt'>{$_befall_beckenwand_rest}</td>
            <td class='edt'>{$_befall_uterus} {$_befall_uterus_lbl}</td>
            <td class='edt'>{$_befall_uterus_rest}</td>
         </tr>
         {/getView}
         {getView field='befall_vaginalstumpf,befall_vaginalstumpf_rest,befall_mesenterium,befall_mesenterium_rest,befall_bursa,befall_bursa_rest'}
         <tr>
            <td class='edt'>{$_befall_vaginalstumpf} {$_befall_vaginalstumpf_lbl}</td>
            <td class='edt'>{$_befall_vaginalstumpf_rest}</td>
            <td class='edt'>{$_befall_mesenterium} {$_befall_mesenterium_lbl}</td>
            <td class='edt'>{$_befall_mesenterium_rest}</td>
            <td class='edt'>{$_befall_bursa} {$_befall_bursa_lbl}</td>
            <td class='edt'>{$_befall_bursa_rest}</td>
         </tr>
         {/getView}
         {getView field='befall_vagina,befall_vagina_rest,befall_portio,befall_portio_rest,befall_cervix,befall_cervix_rest'}
         <tr>
            <td class='edt'>{$_befall_vagina} {$_befall_vagina_lbl}</td>
            <td class='edt'>{$_befall_vagina_rest}</td>
            <td class='edt'>{$_befall_portio} {$_befall_portio_lbl}</td>
            <td class='edt'>{$_befall_portio_rest}</td>
            <td class='edt'>{$_befall_cervix} {$_befall_cervix_lbl}</td>
            <td class='edt'>{$_befall_cervix_rest}</td>
         </tr>
         {/getView}
         {getView field='befall_vulva,befall_vulva_rest,befall_urethra,befall_urethra_rest'}
         <tr>
            <td class='edt'>{$_befall_vulva} {$_befall_vulva_lbl}</td>
            <td class='edt'>{$_befall_vulva_rest}</td>
            <td class='edt'>{$_befall_urethra} {$_befall_urethra_lbl}</td>
            <td class='edt'>{$_befall_urethra_rest}</td>
            <td class='edt'><!----></td>
            <td class='edt'><!----></td>
         </tr>
         {/getView}
         {getView field='befall_sonst,befall_sonst_text'}
         <tr>
            <td colspan='6' class='edt'>{$_befall_sonst} {$_befall_sonst_lbl} {$_befall_sonst_text}</td>
         </tr>
         {/getView}
      </table>
   {/getView}
{/getView}

<table class="formtable msg">

{html_set_header caption=#head_postop_verlauf#      class="head" field="beatmung,intensiv,antibiotika,thrombose,transfusion,datum_drainageentfernung,datum_katheterentfernung,cystogramm1,cystogramm2,leckage_primaer,leckage_sekundaer,cf_entfernung,dk_entfernung,dk_neuanlage,dk_entlassung,lymphocele,wundabstrich"}

{html_set_html field="beatmung" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='border-top:1px solid #fff'>
         <tr>
            <td class='lbl' style='width:35%;'>$_beatmung_lbl</td>
            <td class='edt' style='width:5%;'>$_beatmung</td>
            <td class='edt' align='right' style='width:12%;'>$_beatmung_dauer_lbl</td>
            <td class='edt'>$_beatmung_dauer `$smarty.config.lbl_stunden`</td>
         </tr>
         </table>
      </td>
   </tr>
"}


{html_set_html field="intensiv" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='border-top:1px solid #fff'>
         <tr>
            <td class='lbl' style='width:35%;'>$_intensiv_lbl</td>
            <td class='edt' style='width:5%;'>$_intensiv</td>
            <td class='edt' align='right' style='width:12%;'>$_intensiv_dauer_lbl</td>
            <td class='edt'>$_intensiv_dauer `$smarty.config.lbl_tage`</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_html field="antibiotika" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='border-top:1px solid #fff'>
         <tr>
            <td class='lbl' style='width:35%;'>$_antibiotika_lbl</td>
            <td class='edt' style='width:5%;'>$_antibiotika</td>
            <td class='edt' align='right' style='width:12%;'>$_antibiotika_dauer_lbl</td>
            <td class='edt'>$_antibiotika_dauer `$smarty.config.lbl_tage`</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_row field="thrombose"  caption=$_thrombose_lbl    input=$_thrombose }



{html_set_html field="transfusion" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='border-top:1px solid #fff'>
         <tr>
            <td class='lbl' style='width:35%;'>$_transfusion_lbl</td>
            <td class='edt' style='width:5%;'>$_transfusion</td>

            <td class='edt' align='right' style='width:10%;'>$_transfusion_anzahl_ek_lbl</td>
            <td class='edt'>$_transfusion_anzahl_ek</td>
            <td class='edt' align='right' style='width:10%;'>$_transfusion_anzahl_tk_lbl</td>
            <td class='edt'>$_transfusion_anzahl_tk</td>
            <td class='edt' align='right' style='width:10%;'>$_transfusion_anzahl_ffp_lbl</td>
            <td class='edt'>$_transfusion_anzahl_ffp</td>
         </tr>
         </table>
      </td>
   </tr>
"}


{html_set_row field="datum_drainageentfernung"  caption=$_datum_drainageentfernung_lbl    input=$_datum_drainageentfernung }
{html_set_row field="datum_katheterentfernung"  caption=$_datum_katheterentfernung_lbl    input=$_datum_katheterentfernung }
{html_set_row field="cystogramm1"               caption=$_cystogramm1_lbl                 input=$_cystogramm1              }
{html_set_row field="cystogramm2"               caption=$_cystogramm2_lbl                 input=$_cystogramm2              }
{html_set_row field="leckage_primaer"           caption=$_leckage_primaer_lbl             input=$_leckage_primaer          }
{html_set_row field="leckage_sekundaer"         caption=$_leckage_sekundaer_lbl           input=$_leckage_sekundaer        }
{html_set_row field="cf_entfernung"             caption=$_cf_entfernung_lbl               input=$_cf_entfernung            }
{html_set_row field="dk_entfernung"             caption=$_dk_entfernung_lbl               input=$_dk_entfernung            }
{html_set_row field="dk_neuanlage"              caption=$_dk_neuanlage_lbl                input=$_dk_neuanlage             }
{html_set_row field="dk_entlassung"             caption=$_dk_entlassung_lbl               input=$_dk_entlassung            }

{html_set_html field="lymphocele" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_lymphocele_lbl</td>
            <td class='edt' style='width:10%;'>$_lymphocele</td>
            <td class='edt'>$_lymphocele_detail_lbl $_lymphocele_detail</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_html field="wundabstrich" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_wundabstrich_lbl</td>
            <td class='edt' style='width:10%;'>$_wundabstrich</td>
            <td class='edt'>$_wundabstrich_ergebnis_lbl $_wundabstrich_ergebnis</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_header caption=#head_bem# class="head" }
{html_set_header caption=$_bem      class="edt"  }

</table>
{html_set_buttons modus=$button}

<div>
<input type="hidden" value="{$_eingriff_id_value}" name="form_id" />
{$_eingriff_id}
{$_patient_id}
{$_erkrankung_id}
</div>
