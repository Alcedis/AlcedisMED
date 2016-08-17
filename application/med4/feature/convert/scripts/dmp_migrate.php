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

$done = array(
    dlookup($db, 'dmp_brustkrebs_ed_2013', 'COUNT(dmp_brustkrebs_ed_2013_id)', 'dmp_brustkrebs_eb_id IS NOT NULL'),
    dlookup($db, 'dmp_brustkrebs_ed_pnp_2013', 'COUNT(dmp_brustkrebs_ed_pnp_2013_id)', 'dmp_brustkrebs_eb_id IS NOT NULL'),
    dlookup($db, 'dmp_brustkrebs_fd_2013', 'COUNT(dmp_brustkrebs_fd_2013_id)', 'dmp_brustkrebs_eb_id IS NOT NULL')
);

if ($done[0] > 0 || $done[1] > 0 || $done[2] > 0) {
    die('ERROR: DATA ALREADY MIGRATED!');
}


/**
 * include all relevant fields
 */
$fields = '';
include "fields/app/dmp_brustkrebs_ed_2013.php";
$fieldsEd = $fields;

$fields = '';
include "fields/app/dmp_brustkrebs_ed_pnp_2013.php";
$fieldsPnp = $fields;

$fields = '';
include "fields/app/dmp_brustkrebs_fd_2013.php";
$fieldsFd = $fields;


/**
 * SELECT all relevant tables
 */
$getFb = "
    SELECT
        dmp_brustkrebs_fb_id         AS 'dmp_brustkrebs_fb_id',
        dmp_brustkrebs_eb_id         AS 'dmp_brustkrebs_eb_id',
        erkrankung_id                AS 'erkrankung_id',
        patient_id                   AS 'patient_id',
        melde_user_id                AS 'melde_user_id',
        arztwechsel                  AS 'arztwechsel',
        doku_datum                   AS 'doku_datum',
        unterschrift_datum           AS 'unterschrift_datum',
        kv_iknr                      AS 'kv_iknr',
        versich_nr                   AS 'versich_nr',
        versich_status               AS 'versich_status',
        versich_statusergaenzung     AS 'versich_statusergaenzung',
        vk_gueltig_bis               AS 'vk_gueltig_bis',
        kvk_einlesedatum             AS 'kvk_einlesedatum',
        primaer_strahlen             AS 'primaer_strahlen',
        primaer_chemo                AS 'primaer_chemo',
        primaer_endo                 AS 'primaer_endo',
        neu_rezidiv_nein             AS 'neu_rezidiv_nein',
        neu_rezidiv_datum            AS 'neu_rezidiv_datum',
        neu_kontra_nein              AS 'neu_kontra_nein',
        neu_kontra_datum             AS 'neu_kontra_datum',
        neu_metast_nein              AS 'neu_metast_nein',
        neu_metast_leber             AS 'neu_metast_leber',
        neu_metast_lunge             AS 'neu_metast_lunge',
        neu_metast_knochen           AS 'neu_metast_knochen',
        neu_metast_andere            AS 'neu_metast_andere',
        neu_metast_datum             AS 'neu_metast_datum',
        lymphoedem                   AS 'lymphoedem',
        rez_status_cr                AS 'rez_status_cr',
        rez_status_pr                AS 'rez_status_pr',
        rez_status_nc                AS 'rez_status_nc',
        rez_status_pd                AS 'rez_status_pd',
        rez_th_praeop                AS 'rez_th_praeop',
        rez_th_exzision              AS 'rez_th_exzision',
        rez_th_mastektomie           AS 'rez_th_mastektomie',
        rez_th_strahlen              AS 'rez_th_strahlen',
        rez_th_chemo                 AS 'rez_th_chemo',
        rez_th_endo                  AS 'rez_th_endo',
        rez_th_andere                AS 'rez_th_andere',
        rez_th_keine                 AS 'rez_th_keine',
        metast_th_operativ           AS 'metast_th_operativ',
        metast_th_strahlen           AS 'metast_th_strahlen',
        metast_th_chemo              AS 'metast_th_chemo',
        metast_th_endo               AS 'metast_th_endo',
        metast_th_andere             AS 'metast_th_andere',
        metast_th_keine              AS 'metast_th_keine',
        metast_bip_ja                AS 'metast_bip_ja',
        metast_bip_nein              AS 'metast_bip_nein',
        metast_bip_kontra            AS 'metast_bip_kontra',
        termin_datum                 AS 'termin_datum',
        bem                          AS 'bem',
        xml                          AS 'xml',
        xml_protokoll                AS 'xml_protokoll',
        xml_status                   AS 'xml_status',
        createuser                   AS 'createuser',
        createtime                   AS 'createtime',
        updateuser                   AS 'updateuser',
        updatetime                   AS 'updatetime'

    FROM dmp_brustkrebs_fb fb
    ORDER BY doku_datum ASC
";
$dataFb = sql_query_array($db, $getFb);
/*

 */

$getPnp = "
    SELECT
        dmp_brustkrebs_eb_id     AS 'dmp_brustkrebs_eb_id',
        erkrankung_id            AS 'erkrankung_id',
        patient_id               AS 'patient_id',
        doku_datum               AS 'doku_datum',
        einschreibung_grund      AS 'einschreibung_grund',
        melde_user_id            AS 'melde_user_id',
        unterschrift_datum       AS 'unterschrift_datum',
        kv_iknr                  AS 'kv_iknr',
        versich_nr               AS 'versich_nr',
        versich_status           AS 'versich_status',
        versich_statusergaenzung AS 'versich_statusergaenzung',
        vk_gueltig_bis           AS 'vk_gueltig_bis',
        kvk_einlesedatum         AS 'kvk_einlesedatum',
        anam_brust_links         AS 'anam_brust_links',
        anam_brust_rechts        AS 'anam_brust_rechts',
        anam_brust_beidseits     AS 'anam_brust_beidseits',
        anam_op_bet              AS 'anam_op_bet',
        anam_op_mast             AS 'anam_op_mast',
        anam_op_sln              AS 'anam_op_sln',
        anam_op_axilla           AS 'anam_op_axilla',
        anam_op_anderes          AS 'anam_op_anderes',
        anam_op_keine            AS 'anam_op_keine',
        bef_pt_tis               AS 'bef_pt_tis',
        bef_pt_0                 AS 'bef_pt_0',
        bef_pt_1                 AS 'bef_pt_1',
        bef_pt_2                 AS 'bef_pt_2',
        bef_pt_3                 AS 'bef_pt_3',
        bef_pt_4                 AS 'bef_pt_4',
        bef_pt_x                 AS 'bef_pt_x',
        bef_pt_keine             AS 'bef_pt_keine',
        bef_pn_0                 AS 'bef_pn_0',
        bef_pn_1                 AS 'bef_pn_1',
        bef_pn_2                 AS 'bef_pn_2',
        bef_pn_3                 AS 'bef_pn_3',
        bef_pn_x                 AS 'bef_pn_x',
        bef_pn_keine             AS 'bef_pn_keine',
        bef_m                    AS 'bef_m',
        bef_g                    AS 'bef_g',
        bef_r_0                  AS 'bef_r_0',
        bef_r_1                  AS 'bef_r_1',
        bef_r_2                  AS 'bef_r_2',
        bef_r_unbekannt          AS 'bef_r_unbekannt',
        bef_r_keine              AS 'bef_r_keine',
        bef_rezeptorstatus       AS 'bef_rezeptorstatus',
        beh_strahlen             AS 'beh_strahlen',
        beh_chemo                AS 'beh_chemo',
        beh_endo                 AS 'beh_endo',
        rez_th_praeop            AS 'rez_th_praeop',
        rez_th_exzision          AS 'rez_th_exzision',
        rez_th_mastektomie       AS 'rez_th_mastektomie',
        rez_th_strahlen          AS 'rez_th_strahlen',
        rez_th_chemo             AS 'rez_th_chemo',
        rez_th_endo              AS 'rez_th_endo',
        rez_th_andere            AS 'rez_th_andere',
        rez_th_keine             AS 'rez_th_keine',
        metast_lok_leber         AS 'metast_lok_leber',
        metast_lok_lunge         AS 'metast_lok_lunge',
        metast_lok_knochen       AS 'metast_lok_knochen',
        metast_lok_andere        AS 'metast_lok_andere',
        metast_th_operativ       AS 'metast_th_operativ',
        metast_th_strahlen       AS 'metast_th_strahlen',
        metast_th_chemo          AS 'metast_th_chemo',
        metast_th_endo           AS 'metast_th_endo',
        metast_th_andere         AS 'metast_th_andere',
        metast_th_keine          AS 'metast_th_keine',
        metast_bip_ja            AS 'metast_bip_ja',
        metast_bip_nein          AS 'metast_bip_nein',
        metast_bip_kontra        AS 'metast_bip_kontra',
        lymphoedem               AS 'lymphoedem',
        termin_datum             AS 'termin_datum',
        bem                      AS 'bem',
        xml                      AS 'xml',
        xml_protokoll            AS 'xml_protokoll',
        xml_status               AS 'xml_status',
        createuser               AS 'createuser',
        createtime               AS 'createtime',
        updateuser               AS 'updateuser',
        updatetime               AS 'updatetime',
        fall_nr                  AS 'fall_nr'

    FROM dmp_brustkrebs_eb eb
    WHERE aktueller_status = 'pnp'
    ORDER BY doku_datum ASC
";
$dataPnp = sql_query_array($db, $getPnp);


$getEb = "
    SELECT
        dmp_brustkrebs_eb_id        AS 'dmp_brustkrebs_eb_id',
        erkrankung_id               AS 'erkrankung_id',
        patient_id                  AS 'patient_id',
        fall_nr                     AS 'fall_nr',
        doku_datum                  AS 'doku_datum',
        einschreibung_grund         AS 'einschreibung_grund',
        melde_user_id               AS 'melde_user_id',
        unterschrift_datum          AS 'unterschrift_datum',
        kv_iknr                     AS 'kv_iknr',
        versich_nr                  AS 'versich_nr',
        versich_status              AS 'versich_status',
        versich_statusergaenzung    AS 'versich_statusergaenzung',
        vk_gueltig_bis              AS 'vk_gueltig_bis',
        kvk_einlesedatum            AS 'kvk_einlesedatum',
        mani_primaer                AS 'mani_primaer',
        mani_kontra                 AS 'mani_kontra',
        mani_rezidiv                AS 'mani_rezidiv',
        mani_metast                 AS 'mani_metast',
        anam_brust_links            AS 'anam_brust_links',
        anam_brust_rechts           AS 'anam_brust_rechts',
        anam_brust_beidseits        AS 'anam_brust_beidseits',
        aktueller_status            AS 'aktueller_status',
        anam_op_bet                 AS 'anam_op_bet',
        anam_op_mast                AS 'anam_op_mast',
        anam_op_sln                 AS 'anam_op_sln',
        anam_op_axilla              AS 'anam_op_axilla',
        anam_op_anderes             AS 'anam_op_anderes',
        anam_op_keine               AS 'anam_op_keine',
        bef_pt_tis                  AS 'bef_pt_tis',
        bef_pt_0                    AS 'bef_pt_0',
        bef_pt_1                    AS 'bef_pt_1',
        bef_pt_2                    AS 'bef_pt_2',
        bef_pt_3                    AS 'bef_pt_3',
        bef_pt_4                    AS 'bef_pt_4',
        bef_pt_x                    AS 'bef_pt_x',
        bef_pt_keine                AS 'bef_pt_keine',
        bef_pn_0                    AS 'bef_pn_0',
        bef_pn_1                    AS 'bef_pn_1',
        bef_pn_2                    AS 'bef_pn_2',
        bef_pn_3                    AS 'bef_pn_3',
        bef_pn_x                    AS 'bef_pn_x',
        bef_pn_keine                AS 'bef_pn_keine',
        bef_m                       AS 'bef_m',
        bef_g                       AS 'bef_g',
        bef_r_0                     AS 'bef_r_0',
        bef_r_1                     AS 'bef_r_1',
        bef_r_2                     AS 'bef_r_2',
        bef_r_unbekannt             AS 'bef_r_unbekannt',
        bef_r_keine                 AS 'bef_r_keine',
        bef_rezeptorstatus          AS 'bef_rezeptorstatus',
        beh_strahlen                AS 'beh_strahlen',
        beh_chemo                   AS 'beh_chemo',
        beh_endo                    AS 'beh_endo',
        rez_th_praeop               AS 'rez_th_praeop',
        rez_th_exzision             AS 'rez_th_exzision',
        rez_th_mastektomie          AS 'rez_th_mastektomie',
        rez_th_strahlen             AS 'rez_th_strahlen',
        rez_th_chemo                AS 'rez_th_chemo',
        rez_th_endo                 AS 'rez_th_endo',
        rez_th_andere               AS 'rez_th_andere',
        rez_th_keine                AS 'rez_th_keine',
        metast_lok_leber            AS 'metast_lok_leber',
        metast_lok_lunge            AS 'metast_lok_lunge',
        metast_lok_knochen          AS 'metast_lok_knochen',
        metast_lok_andere           AS 'metast_lok_andere',
        metast_th_operativ          AS 'metast_th_operativ',
        metast_th_strahlen          AS 'metast_th_strahlen',
        metast_th_chemo             AS 'metast_th_chemo',
        metast_th_endo              AS 'metast_th_endo',
        metast_th_andere            AS 'metast_th_andere',
        metast_th_keine             AS 'metast_th_keine',
        metast_bip_ja               AS 'metast_bip_ja',
        metast_bip_nein             AS 'metast_bip_nein',
        metast_bip_kontra           AS 'metast_bip_kontra',
        lymphoedem                  AS 'lymphoedem',
        termin_datum                AS 'termin_datum',
        bem                         AS 'bem',
        xml                         AS 'xml',
        xml_protokoll               AS 'xml_protokoll',
        xml_status                  AS 'xml_status',
        createuser                  AS 'createuser',
        createtime                  AS 'createtime',
        updateuser                  AS 'updateuser',
        updatetime                  AS 'updatetime'
    FROM dmp_brustkrebs_eb eb
    WHERE (aktueller_status != 'pnp' OR aktueller_status IS NULL)
    ORDER BY doku_datum DESC
";

$dataEb = sql_query_array($db, $getEb);


$uniqueCheck      = array();
$orgId = '';
// migrate old ebs (without pnp fb)
foreach ($dataEb as $eb) {
    // checks if patient with same 'fall_nr' exists and step to next dataset
    foreach ($uniqueCheck as $unique) {
        if (isset($unique['patient_id']) && $unique['patient_id'] === $eb['patient_id'] &&
            isset($unique['fall_nr']) && $unique['fall_nr'] === $eb['fall_nr']) {
            continue 2;
        }
    }
    $uniqueCheck[] = array(
        'patient_id' => $eb['patient_id'],
        'fall_nr'    => $eb['fall_nr']
    );

    $orgId = dlookup($db, 'patient', 'org_id', "patient_id = '{$eb['patient_id']}'");

    $fields = dataArray2fields(array_merge(
        $eb,
        array(
             'bef_neoadjuvant' => getNeoadjuvant($db, $eb['erkrankung_id'], $eb['doku_datum']),
             'bef_her2'        => getBefHer2($db, $eb['erkrankung_id'], $eb['doku_datum'], $eb['anam_brust_links'], $eb['anam_brust_rechts'], $eb['anam_brust_beidseits']),
             'beh_ah'          => getBehAh($db, $eb['erkrankung_id'], $eb['doku_datum']),
             'org_id'          => $orgId)
        ),
        $fieldsEd);

    execute_insert($smarty, $db, $fields, 'dmp_brustkrebs_ed_2013', 'insert');
}


// get data from migrated ed-forms -> for reference new ed_id between ed->pnp->fd
$migratedEdQuery = "
    SELECT
        dmp_brustkrebs_ed_2013_id,
        dmp_brustkrebs_eb_id,
        erkrankung_id,
        patient_id,
        fall_nr,
        doku_datum,
        einschreibung_grund
    FROM
        dmp_brustkrebs_ed_2013
    WHERE
        dmp_brustkrebs_eb_id IS NOT NULL
";

$migratedEd = sql_query_array($db, $migratedEdQuery);


$tmp = array(
    'eb' => array(),
    'fb' => array(),
    'pnp' => array()
);


// arange arrays 2 one patients array
$patients = array();

foreach ($dataFb as $fb) {
    $patients[$fb['patient_id']]['fb'][] = $fb;
}

foreach ($dataPnp as $pnp) {
    $patients[$pnp['patient_id']]['pnp'][] = $pnp;
}

foreach ($migratedEd as $eb) {
    $patients[$eb['patient_id']]['eb'][] = $eb;
}

$pnps = array();

foreach ($patients as $patient) {
    // (pnp aber kein eb -> leeres ED-FORM)
    if (isset($patient['pnp']) === true && isset($patient['eb']) === false) {
        foreach ($patient['pnp'] as $pnp) {
            $orgId = dlookup($db, 'patient', 'org_id', "patient_id = '{$pnp['patient_id']}'");
            $newEb = array('erkrankung_id'        => $pnp['erkrankung_id'],
                           'patient_id'           => $pnp['patient_id'],
                           'fall_nr'              => $pnp['fall_nr'],
                           'einschreibung_grund'  => $pnp['einschreibung_grund'],
                           'doku_datum'           => $pnp['doku_datum'],
                           'dmp_brustkrebs_eb_id' => $pnp['dmp_brustkrebs_eb_id'],
                           'org_id'               => $orgId
            );

            $fields = dataArray2fields($newEb, $fieldsEd);
            execute_insert($smarty, $db, $fields, 'dmp_brustkrebs_ed_2013', 'insert');

            $oldId =  $pnp['dmp_brustkrebs_eb_id'];
            $id = dlookup($db, 'dmp_brustkrebs_ed_2013', 'dmp_brustkrebs_ed_2013_id', "dmp_brustkrebs_eb_id = '$oldId'");
            array_pop($pnp);
            $newPnp = array_merge (
                array(
                     'dmp_brustkrebs_ed_2013_id' =>  $id,
                     'aktueller_status'          => 'postoperativ',
                     'bef_neoadjuvant'           => getNeoadjuvant($db, $pnp['erkrankung_id'], $pnp['doku_datum']),
                     'bef_her2'                  => getBefHer2($db, $pnp['erkrankung_id'], $pnp['doku_datum'], $pnp['anam_brust_links'], $pnp['anam_brust_rechts'], $pnp['anam_brust_beidseits']),
                     'beh_ah'                    => getBehAh($db, $pnp['erkrankung_id'], $pnp['doku_datum'])),
                $pnp
            );

            $fields = dataArray2fields($newPnp, $fieldsPnp);
            execute_insert($smarty, $db, $fields, 'dmp_brustkrebs_ed_pnp_2013', 'insert');
        }
    }


    //nur ein eb und mindestens ein pnp
    if (isset($patient['pnp']) === true && isset($patient['eb']) === true && count($patient['pnp']) > 0 && count($patient['eb']) === 1) {
        foreach ($patient['pnp'] as $pnp) {
            array_pop($pnp);
            $newPnp = array_merge(
                array(
                    'dmp_brustkrebs_ed_2013_id' => $patient['eb']['0']['dmp_brustkrebs_ed_2013_id'],
                    'dmp_brustkrebs_eb_id'      => $patient['eb']['0']['dmp_brustkrebs_eb_id'],
                    'aktueller_status'          => 'postoperativ',
                    'bef_neoadjuvant'           => getNeoadjuvant($db, $eb['erkrankung_id'], $eb['doku_datum']),
                    'bef_her2'                  => getBefHer2($db, $pnp['erkrankung_id'], $pnp['doku_datum'], $pnp['anam_brust_links'], $pnp['anam_brust_rechts'], $pnp['anam_brust_beidseits']),
                    'beh_ah'                    => getBehAh($db, $pnp['erkrankung_id'], $pnp['doku_datum'])
                ),
                $pnp
            );
            $fields = dataArray2fields($newPnp, $fieldsPnp);
            execute_insert($smarty, $db, $fields, 'dmp_brustkrebs_ed_pnp_2013', 'insert');
        }
    }

    // mehrere ebs und ein pnp
    if (isset($patient['pnp']) === true && isset($patient['eb']) == true && count($patient['pnp']) === 1 && count($patient['eb']) > 1) {
        $sort = array();

        foreach ($patient['eb'] as $eb) {
            $sort[$eb['doku_datum']] = $eb;
        }

        ksort($sort);

        $pnp = reset($patient['pnp']);
        array_pop($pnp);
        $oldestEb = reset($sort);

        $newPnp = array_merge(
            array(
                 'dmp_brustkrebs_ed_2013_id' => $patient['eb']['0']['dmp_brustkrebs_ed_2013_id'],
                 'dmp_brustkrebs_eb_id'      => $patient['eb']['0']['dmp_brustkrebs_eb_id'],
                 'aktueller_status'          => 'postoperativ',
                 'bef_neoadjuvant'           => getNeoadjuvant($db, $eb['erkrankung_id'], $eb['doku_datum']),
                 'bef_her2'                  => getBefHer2($db, $pnp['erkrankung_id'], $pnp['doku_datum'], $pnp['anam_brust_links'], $pnp['anam_brust_rechts'], $pnp['anam_brust_beidseits']),
                 'beh_ah'                    => getBehAh($db, $pnp['erkrankung_id'], $pnp['doku_datum'])
            ),
            $pnp
        );

        $fields = dataArray2fields($newPnp, $fieldsPnp);
        execute_insert($smarty, $db, $fields, 'dmp_brustkrebs_ed_pnp_2013', 'insert');
    }

    // mehrere ebs und pnps
    if (isset($patient['pnp']) === true && isset($patient['eb']) === true && count($patient['pnp']) > 1 && count($patient['eb']) > 1) {
        $usedDates  = array();
        $sortEb     = array();
        $sortPnp    = array();

        foreach ($patient['eb'] as $eb) {
            $sortEb[$eb['doku_datum']] = $eb;
        }

        foreach ($patient['pnp'] as $pnp) {
            $sortPnp[$pnp['doku_datum']] = $pnp;
        }

        ksort($sortEb);
        ksort($sortPnp);

        foreach ($sortPnp as $pnp) {
            foreach ($sortEb as $eb) {
                if ((in_array($eb['doku_datum'], $usedDates) === false) && $eb['doku_datum'] < $pnp['doku_datum']) {
                    $usedDates[] = $eb['doku_datum'];
                    array_pop($pnp);
                    $newPnp = array_merge(
                        array(
                             'dmp_brustkrebs_ed_2013_id' => $eb['dmp_brustkrebs_ed_2013_id'],
                             'dmp_brustkrebs_eb_id'      => $eb['dmp_brustkrebs_eb_id'],
                             'aktueller_status'          => 'postoperativ',
                             'bef_neoadjuvant'           => getNeoadjuvant($db, $eb['erkrankung_id'], $eb['doku_datum']),
                             'bef_her2'                  => getBefHer2($db, $pnp['erkrankung_id'], $pnp['doku_datum'], $pnp['anam_brust_links'], $pnp['anam_brust_rechts'], $pnp['anam_brust_beidseits']),
                             'beh_ah'                    => getBehAh($db, $pnp['erkrankung_id'], $pnp['doku_datum'])
                        ),
                        $pnp
                    );

                    $fields = dataArray2fields($newPnp, $fieldsPnp);
                    execute_insert($smarty, $db, $fields, 'dmp_brustkrebs_ed_pnp_2013', 'insert');

                    continue 2;
                }
            }
        }
    }
}


//get inserted data
$migratedEd = array();
$migratedEd  = sql_query_array($db, $migratedEdQuery);
$migratedPnpQuery = "
    SELECT
        dmp_brustkrebs_ed_pnp_2013_id,
        dmp_brustkrebs_ed_2013_id,
        dmp_brustkrebs_eb_id,
        erkrankung_id,
        patient_id,
        doku_datum,
        einschreibung_grund
    FROM
        dmp_brustkrebs_ed_pnp_2013
    WHERE
        dmp_brustkrebs_eb_id IS NOT NULL
";
$migratedPnp = array();
$migratedPnp = sql_query_array($db, $migratedPnpQuery);

//// arange arrays 2 one patients array
$patients = array();

foreach ($migratedEd as $eb) {
    $patients[$eb['patient_id']]['pnp']  = array();
    $patients[$eb['patient_id']]['fb']   = array();
    $patients[$eb['patient_id']]['eb'][] = $eb;
}



foreach ($dataFb as $fb) {
    $patients[$fb['patient_id']]['fb'][] = $fb;
}

foreach ($migratedPnp as $pnp) {
    $patients[$pnp['patient_id']]['pnp'][] = $pnp;
}


foreach ($patients as $patient) {
    if (isset($patient['fb']) === true) {
        foreach ($patient['fb'] as $fb) {
            if ((array_key_exists('eb', $patient) === true) && count($patient['eb']) > 0) {
                foreach ($patient['eb'] as $eb) {
                    if (($fb['dmp_brustkrebs_eb_id'] == $eb['dmp_brustkrebs_eb_id'])) {
                        $newFb = array_merge(
                            array(
                                 'dmp_brustkrebs_ed_2013_id' => $eb['dmp_brustkrebs_ed_2013_id'],
                                 'dmp_brustkrebs_eb_id'      => $eb['dmp_brustkrebs_eb_id'],
                                 'dmp_brustkrebs_fb_id'      => $fb['dmp_brustkrebs_fb_id'],
                                 'einschreibung_grund'       => $eb['einschreibung_grund'],
                                 'primaer_ah'                => getBehAh($db, $fb['erkrankung_id'], $fb['doku_datum'])
                            ),
                            $fb
                        );
                        $fields = dataArray2fields($newFb, $fieldsFd);
                        execute_insert($smarty, $db, $fields, 'dmp_brustkrebs_fd_2013', 'insert');
                    }
                }
            }
        }
    }
}

// Migration beendet
// XML aktualisieren

require_once('feature/export/dmp/helper.dmp.php');
require_once('feature/export/base/helper.database.php');

$forms = array('ed', 'ed_pnp', 'fd');

$fieldSrc = array(
    'ed' => $fieldsEd,
    'ed_pnp' => $fieldsPnp,
    'fd' => $fieldsFd
);

$backup = $_REQUEST;

$parameters = array();

$parameters['user_id'] = isset($_SESSION['sess_user_id']) ? $_SESSION['sess_user_id'] : '';
$parameters['login_name'] = isset($_SESSION['sess_loginname']) ? $_SESSION['sess_loginname'] : '';

$parameters['export_id'] = isset($_REQUEST['export_id']) ? $_REQUEST['export_id'] : 0;

$parameters['von_datum'] = '0000-00-00';
$parameters['bis_datum'] = date("Y-m-d");

$parameters['melde_user_id'] = 1;
$parameters['empfaenger_aok'] = false;
$parameters['sw_version'] = appSettings::get("software_version");

foreach ($forms as $form) {
    $datasets = sql_query_array($db, "
        SELECT
            form.*,
            p.org_id
        FROM dmp_brustkrebs_{$form}_2013 form
            INNER JOIN patient p ON form.patient_id = p.patient_id
        WHERE
            form.dmp_brustkrebs_eb_id IS NOT NULL
        ");

    foreach ($datasets as $dataset) {
        $fields = $fieldSrc[$form];

        $tmpFields = $fields;

        array2fields($dataset, $tmpFields);

        fields2request($tmpFields);

        HDmp::initXml($fields, $_REQUEST);

        $parameters['org_id'] = $dataset['org_id'];

        // DMP-Formular mit Prüfmodul checken
        $result = HDmp::checkForm($parameters, $dataset["dmp_brustkrebs_{$form}_2013_id"], $form, $smarty, $db);

        // Ergebnis des Checks in den Request legen
        HDmp::setXml($result, $_REQUEST, $_SESSION);

        action_update($smarty, $db, $fields, "dmp_brustkrebs_{$form}_2013", $dataset["dmp_brustkrebs_{$form}_2013_id"], 'update');
    }
}

/**
 *
 *
 * @access
 * @param      $db
 * @param      $diseaseId
 * @param      $endDate
 * @param null $l
 * @param null $r
 * @param null $b
 * @return null
 */
function getBefHer2($db, $diseaseId, $endDate, $l = NULL, $r = NULL, $b = NULL) {
    $val = null;

    $side = "L' OR diagnose_seite = 'R";

    if ($l === '1' && $r === null) {
        $side = 'L';
    } elseif ($r === '1' && $l === null) {
        $side = 'R';;
    }

    $her2Query = "
        SELECT
            her2_urteil
        FROM
            tumorstatus
        WHERE
            her2_urteil IS NOT NULL AND
            erkrankung_id = '{$diseaseId}' AND
            datum_sicherung <= '{$endDate}' AND
            (diagnose_seite = '{$side}')
        ORDER BY
            datum_sicherung DESC,
            sicherungsgrad ASC,
            datum_beurteilung DESC
        LIMIT 1
    ";

    $result = sql_query_array($db, $her2Query);

    if (count($result) > 0) {
        $result = reset($result);

        $val = $result['her2_urteil'];
    }

    return $val;
}

/**
 *
 *
 * @access
 * @param $db
 * @param $diseaseId
 * @param $endDate
 * @return mixed
 */
function getBehAh($db, $diseaseId, $endDate)
{
    $ah = null;

    $query = "
        SELECT
            ts.therapie_systemisch_id,
            ts.vorlage_therapie_id,
            ts.vorlage_therapie_art,
            ts.beginn,
            ts.ende,
            ts.endstatus,
            vtw.wirkstoff
        FROM therapie_systemisch ts
            LEFT JOIN vorlage_therapie_wirkstoff vtw ON vtw.vorlage_therapie_id = ts.vorlage_therapie_id AND vtw.wirkstoff = 'trastuzumab'
        WHERE
            ts.erkrankung_id = '{$diseaseId}' AND
            ts.beginn <= '{$endDate}'
        ORDER BY
            ts.beginn DESC
    ";

    $result = sql_query_array($db, $query);

    foreach ($result as $entry) {
        if ($entry['endstatus'] === 'abbr') {
            $ah = 'vb';
            break;
        }
    }

    if ($ah === null) {
        foreach ($result as $entry) {
            if (strlen($entry['ende']) == 0) {
                $ah = 'a';
                break;
            }
        }
    }

    if ($ah === null && count($result) > 0) {
        $ah = 'ra';
    }

    if ($ah === null) {
        $g = dlookup($db, 'therapieplan', 'therapieplan_id', "immun = '1' AND erkrankung_id = '{$diseaseId}' AND datum <= '{$endDate}'");

        if (strlen($g) > 0) {
            $ah = 'g';
        } else {
            $ah = 'k';
        }
    }

    return $ah;
}






/*
"therapie_systemisch
strahlentherapie
sonstige_therapie
"	Intention

wenn mind. Eine Therapie dokumentiert mit der Intention "neoadjuvant kurativ" oder "präoperativ palliativ": "JA"
*/
/**
 *
 *
 * @access
 * @param $db
 * @param $diseaseId
 * @param $endDate
 * @return mixed
 */
function getNeoadjuvant($db, $diseaseId, $endDate)
{
    $neoadjuvant = null;

    foreach (array('therapie_systemisch', 'strahlentherapie', 'sonstige_therapie') as $table) {
        $query = "
            SELECT
                intention
            FROM {$table}
            WHERE
                intention IS NOT NULL AND
                erkrankung_id = '{$diseaseId}' AND
                beginn <= '{$endDate}'
            ORDER BY
                beginn DESC
        ";

        $result = sql_query_array($db, $query);

        if (count($result) > 0) {
            $result = reset($result);
            if ($result['intention'] === 'pal' || $result['intention'] === 'kurna') {
                $neoadjuvant = '1';

                break;
            }

        }
    }

    return $neoadjuvant;
}



