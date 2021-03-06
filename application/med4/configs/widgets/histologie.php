<?php

/*
 * AlcedisMED
 * Copyright (C) 2010-2016  Alcedis GmbH
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */ 

$this
    ->showFieldIn(
        array(
            'hpv',
            'hpv_typ01',
            'hpv_typ02',
            'hpv_typ03',
            'hpv_typ04',
            'hpv_typ05',
            'hpv_typ06',
            'hpv_typ07',
            'hpv_typ08',
            'hpv_typ09',
            'hpv_ergebnis01',
            'hpv_ergebnis02',
            'hpv_ergebnis03',
            'hpv_ergebnis04',
            'hpv_ergebnis05',
            'hpv_ergebnis06',
            'hpv_ergebnis07',
            'hpv_ergebnis08',
            'hpv_ergebnis09'
        ),
        'kh'
    )
    ->showFieldIn(
      array(
         'mercury',
         'msi',
         'msi_mutation',
         'msi_stabilitaet',
         'egf',
         'vegf',
         'chromogranin',
         'resektionsrand_oral',
         'resektionsrand_aboral',
         'resektionsrand_lateral'
      ),
      'd'
   )
    ->showFieldIn(
        array(
            'kras'
        ),
        array('d', 'lu')
    )
   ->showFieldIn(
      array(
         'kapselueberschreitung',
         'her2',
         'her2_methode',
         'her2_fish',
         'her2_fish_methode',
         'her2_urteil',
         'lk_bef_makro',
         'lk_bef_mikro',
      ),
      array('b', 'p')
   )
   ->showFieldIn(
      array(
         'ki67',
         'pai1',
         'upa'
      ),
      array('b', 'h', 'p')
   )
   ->showFieldIn(
      array(
         'status_resektionsrand_organ',
         'status_resektionsrand_circumferentiell',
         'resektionsrand_circumferentiell'
      ),
      array('pa')
   )
   ->showFieldIn(
      array(
         'ki67_index',
      ),
      array('b', 'd', 'h', 'p')
   )
   ->showFieldIn(
      array(
         'parametrienbefall_r',
         'parametrienbefall_r_infiltration',
         'parametrienbefall_l',
         'parametrienbefall_l_infiltration',
         'lk_pelvin_entf',
         'lk_pelvin_bef',
         'lk_para_entf',
         'lk_para_bef',
         'lk_inguinal_l_entf',
         'lk_inguinal_l_bef',
         'lk_inguinal_r_entf',
         'lk_inguinal_r_bef',
         'lk_andere1',
         'lk_andere1_entf',
         'lk_andere1_bef',
         'lk_andere2',
         'lk_andere2_entf',
         'lk_andere2_bef',
         'lk_pelvin_externa_l_entf',
         'lk_pelvin_externa_l_bef',
         'lk_pelvin_interna_l_entf',
         'lk_pelvin_interna_l_bef',
         'lk_pelvin_fossa_l_entf',
         'lk_pelvin_fossa_l_bef',
         'lk_pelvin_communis_l_entf',
         'lk_pelvin_communis_l_bef',
         'lk_pelvin_externa_r_entf',
         'lk_pelvin_externa_r_bef',
         'lk_pelvin_interna_r_entf',
         'lk_pelvin_interna_r_bef',
         'lk_pelvin_fossa_r_entf',
         'lk_pelvin_fossa_r_bef',
         'lk_pelvin_communis_r_entf',
         'lk_pelvin_communis_r_bef',
         'lk_para_paracaval_entf',
         'lk_para_paracaval_bef',
         'lk_para_interaortocaval_entf',
         'lk_para_interaortocaval_bef',
         'lk_para_cranial_ami_entf',
         'lk_para_cranial_ami_bef',
         'lk_para_caudal_ami_entf',
         'lk_para_caudal_ami_bef',
         'lk_para_cranial_vr_entf',
         'lk_para_cranial_vr_bef',
         'konisation_exzision',
         'konisation_x',
         'konisation_y',
         'konisation_z',
         'invasionstiefe',
         'invasionsbreite',
         'groesste_ausdehnung',
         'kapseldurchbruch'
      ),
      'gt'
   )
   ->showFieldIn(
      array(
         'l_beurteilung',
         'l_anz',
         'l_anz_positiv',
         'l_laenge',
         'l_tumoranteil',
         'r_beurteilung',
         'r_anz',
         'r_anz_positiv',
         'r_laenge',
         'r_tumoranteil',
         'lk_l_entf',
         'lk_l_bef_mikro',
         'lk_l_bef_makro',
         'lk_r_entf',
         'lk_r_bef_mikro',
         'lk_r_bef_makro',
         'psa',
         'pcna',
         'epca2',
         'anz_rand_positiv',
         'p53',
         'ps2',
         'kathepsin_d',
         'stanzen_ges_anz',
         'stanzen_ges_anz_positiv',
         'gleason1',
         'gleason2',
         'gleason3',
         'gleason4_anteil',
         'tumoranteil_turp',
         'prostatagewicht'
      ),
      'p'
   )
   ->showFieldIn(
      'diagnose_seite',
       array('b', 'lu', 'sst')
   )
   ->showFieldIn(
      array(
         'lk_12_entf',
         'lk_12_bef_makro',
         'lk_12_bef_mikro',
         'lk_3_entf',
         'lk_3_bef_makro',
         'lk_3_bef_mikro',
         'lk_ip_entf',
         'lk_ip_bef_makro',
         'lk_ip_bef_mikro',
         'dcis_grading',
         'dcis_groesse',
         'dcis_resektionsrand',
         'dcis_van_nuys',
         'dcis_vnpi',
         'dcis_morphologie',
         'dcis_morphologie_text',
         'dcis_kerngrading',
         'dcis_nekrosen'
      ),
      'b'
   )
   ->showFieldIn(
      array(
         'lk_hilus_entf',
         'lk_hilus_bef_mikro',
         'lk_hilus_bef_makro',
         'lk_interlobaer_entf',
         'lk_interlobaer_bef_mikro',
         'lk_interlobaer_bef_makro',
         'lk_lobaer_entf',
         'lk_lobaer_bef_mikro',
         'lk_lobaer_bef_makro',
         'lk_segmental_entf',
         'lk_segmental_bef_mikro',
         'lk_segmental_bef_makro',
         'lk_lig_pul_entf',
         'lk_lig_pul_bef_mikro',
         'lk_lig_pul_bef_makro',
         'lk_paraoeso_entf',
         'lk_paraoeso_bef_mikro',
         'lk_paraoeso_bef_makro',
         'lk_subcarinal_entf',
         'lk_subcarinal_bef_mikro',
         'lk_subcarinal_bef_makro',
         'lk_paraaortal_entf',
         'lk_paraaortal_bef_mikro',
         'lk_paraaortal_bef_makro',
         'lk_subaortal_entf',
         'lk_subaortal_bef_mikro',
         'lk_subaortal_bef_makro',
         'lk_unt_paratrach_entf',
         'lk_unt_paratrach_bef_mikro',
         'lk_unt_paratrach_bef_makro',
         'lk_prae_retro_trach_entf',
         'lk_prae_retro_trach_bef_mikro',
         'lk_prae_retro_trach_bef_makro',
         'lk_ob_paratrach_entf',
         'lk_ob_paratrach_bef_mikro',
         'lk_ob_paratrach_bef_makro',
         'lk_mediastinum_entf',
         'lk_mediastinum_bef_mikro',
         'lk_mediastinum_bef_makro',
         'egfr_mutation',
         'nse',
         'ercc1',
         'ttf1',
         'alk',
         'ros',
         'anzahl_praeparate'
      ),
      'lu'
   )
   ->showFieldIn(
      array(
         'referenzpathologie'
      ),
      array('leu', 'ly', 'snst')
   )
   ->showFieldIn(
      array(
         'estro',
         'estro_irs',
         'estro_urteil',
         'prog',
         'prog_irs',
         'prog_urteil'
      ),
      array('b', 'gt')
   )
   ->showFieldIn(
      array(
         'lk_sentinel_entf',
         'lk_sentinel_bef'
      ),
      array('b', 'gt', 'h')
   )
   ->showFieldIn('egfr', array('lu', 'p'))
   ->showFieldIn(
      array(
         'hmb45',
         'melan_a',
         's100',
         'braf'
      ),
      'h'
   )
   ->hideFieldIn(
      array(
         'morphologie',
         'morphologie_text',
         'morphologie_erg1',
         'morphologie_erg1_text',
         'morphologie_erg2',
         'morphologie_erg2_text',
         'morphologie_erg3',
         'morphologie_erg3_text',
         'unauffaellig',
      ),
      array('h')
   )
   ->hideFieldIn(
      array(
         'groesse_x',
         'multizentrisch',
         'multifokal',
         'ptnm_praefix',
         'pt',
         'g',
         'l',
         'v',
         'r',
         'resektionsrand'
      ),
      array('leu', 'ly', 'snst', 'h')
   )
   ->hideFieldIn(
      array(
         'groesse_y',
         'groesse_z'
      ),
      array('d', 'h', 'leu', 'ly', 'snst')
   )
   ->showFieldIn(
      array(
         'blasteninfiltration',
         'blasteninfiltration_prozent'
      ),
      array('leu', 'ly', 'snst')
   )
   ->showFieldIn(
      array(
        'lk_inguinal_entf',
        'lk_inguinal_bef',
        'lk_inguinal_makro',
        'lk_inguinal_mikro',
        'lk_iliakal_entf',
        'lk_iliakal_bef',
        'lk_iliakal_makro',
        'lk_iliakal_mikro',
        'lk_axillaer_entf',
        'lk_axillaer_bef',
        'lk_axillaer_makro',
        'lk_axillaer_mikro',
        'lk_zervikal_entf',
        'lk_zervikal_bef',
        'lk_zervikal_makro',
        'lk_zervikal_mikro'
      ),
      array('h', 'leu', 'ly', 'snst')
   )
   ->hideFieldIn(
      array(
         'pn',
         'pm',
         'ppn',
         'tubulusbildung',
         'kernpolymorphie',
         'mitoserate'
      ),
      array('leu', 'ly', 'snst')
   )
   ->hideFieldIn(
      array(
         'lk_mikrometastasen'
      ),
      array('b')
   )
   ->hideFieldIn(
      array(
         'lk_bef'
      ),
      array('p', 'b')
   );

?>
