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
          'zahnarzt',
          'kh'
     )
     ->showFieldIn(
        array(
            'hyperthermie',
            'ziel_mediastinum'
        ),
        'lu'
    )
    ->showFieldIn(
        array(
            'andauernd',
            'ziel_mamma_r',
            'ziel_mamma_l',
            'ziel_mammaria_interna',
            'ziel_axilla_r',
            'ziel_axilla_l',
            'ziel_lk_supra',
            'ziel_lk_para'
        ),
        'b'
    )
    ->showFieldIn(
     array(
          'ziel_brustwand_r',
          'ziel_brustwand_l'
     ),
     array('b', 'lu')
    )
    ->showFieldIn(
     array(
          'therapieform',
          'ziel_ganzkoerper',
          'ziel_lk_zervikal_r',
          'ziel_lk_zervikal_l',
          'ziel_lk_hilaer',
          'ziel_lk_axillaer_r',
          'ziel_lk_axillaer_l',
          'ziel_lk_abdominell_o',
          'ziel_lk_abdominell_u',
          'ziel_lk_iliakal_r',
          'ziel_lk_iliakal_l',
          'ziel_lk_inguinal_r',
          'ziel_lk_inguinal_l'
     ),
     array('leu', 'ly', 'snst')
    )
    ->showFieldIn(
        array(
            'ziel_prostata',
            'ziel_lk',
            'seed_strahlung_90d',
            'seed_strahlung_90d_datum',
            'dosierung_icru',
            'imrt',
            'igrt',
            'beschleunigerenergie',
        ),
        'p'
    )
    ->showFieldIn(
        array(
            'ziel_becken',
            'ziel_abdomen',
            'ziel_vulva',
            'ziel_vulva_pelvin',
            'ziel_vulva_inguinal',
            'ziel_inguinal_einseitig',
            'ziel_ingu_beidseitig',
            'ziel_ingu_pelvin',
            'ziel_vagina',
            'ziel_paraaortal',
            'ziel_lk_iliakal'
        ),
        'gt'
    )
    ->hideFieldIn(
        array(
            'ziel_lymph',
            'ziel_primaertumor'
        ),
        array('b', 'gt', 'leu', 'ly', 'p', 'snst')
    );

?>
