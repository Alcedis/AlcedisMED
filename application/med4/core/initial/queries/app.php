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

/* INFO:
 * Die Klasse dataCollector included alle query Dateien!
 * Wenn hier Variablen genutzt werden, muss sichergestellt werden,
 * dass diese auch in der Klasse vorbelegt werden!!!
 *
 * Gilt auch für die Top10 generierung der Codepicker!!!
 */

   $queryRechtErkrankung = isset($_SESSION['sess_recht_erkrankung']) ? implode("','", $_SESSION['sess_recht_erkrankung']) : '';

   //DIESE QUERY IST NUR EIN TEIL DER VOLLSTÄNDIGEN PATIENT QUERY
   $querys['patient'] = "
      SELECT
         p.patient_id,
         p.patient_nr,
         p.nachname,
         p.vorname,
         p.geburtsdatum,
         p.organisation,
         p.erkrankungen,
         DATE_FORMAT(p.createtime, '%d.%m.%Y') AS createtime,
         p.status,
         p.aufnahme_nr,
         p.behandler,
         p.krebsregister
      FROM (
            SELECT
               IFNULL(pa.krebsregister, '0') as krebsregister,
               pa.patient_id,
               pa.patient_nr,
               TRIM(pa.vorname)  AS 'vorname',
               TRIM(pa.nachname) AS 'nachname',
               CONCAT_WS(', ', TRIM(o.name), (o.namenszusatz)) AS 'organisation',
               pa.geburtsdatum,
               pa.erkrankungen,
               pa.createtime,
               COUNT(IF(s.form = 'erkrankung', 1, NULL)) > 0 AS 'erkrankung',
               IFNULL(MIN(IF(s.form = 'erkrankung', s.form_param, NULL)), 1) AS status,
               GROUP_CONCAT(DISTINCT IF(s.form = 'aufenthalt', s.report_param, NULL) SEPARATOR ', ') AS 'aufnahme_nr',
               COUNT(IF(s.form = 'behandler' AND s.report_param = 'BEHANDLER', 1, NULL)) > 0 AS 'behandler'
            FROM `status` s
                INNER JOIN patient pa ON pa.patient_id = s.patient_id AND pa.org_id = 'ORGID'
                INNER JOIN org o ON pa.org_id = o.org_id
            WHERE
                (s.form = 'erkrankung' AND s.report_param IN ('{$queryRechtErkrankung}')) OR s.form IN('aufenthalt','behandler')
            GROUP BY
                s.patient_id
            HAVING
                erkrankung = 1
      ) p
   ";

   $querys['patient_import'] = "
      SELECT
         p.patient_id,
         p.org_id,
         p.patient_id AS id,
         p.nachname,
         p.vorname,
         p.geburtsdatum,
         DATE_FORMAT(p.createtime, '%d.%m.%Y') AS createtime,
         p.createtime   AS createtime_en,
         p.patient_nr,
         e.erkrankung_id   AS erkrankung_id,
         GROUP_CONCAT(DISTINCT a.aufnahmenr SEPARATOR ', ') AS aufnahme_nr,
         CONCAT_WS('','-',
            GROUP_CONCAT(DISTINCT e.erkrankung SEPARATOR '-'),
            '-'
         )  AS erk,
         NULL AS erklist
      FROM patient p
         LEFT JOIN erkrankung e ON p.patient_id = e.patient_id
         LEFT JOIN aufenthalt a ON a.patient_id = p.patient_id
   ";

   $querys['erkrankung']                  = "SELECT * FROM erkrankung WHERE patient_id = '$patient_id' ORDER BY erkrankung";
   $querys['begleitmedikation']           = "SELECT * FROM begleitmedikation WHERE patient_id = '$patient_id'";

   $querys['konferenz'] = "
      SELECT
         konferenz.konferenz_id,
         konferenz.bez,
         konferenz.datum,
         konferenz.final,
         konferenz.uhrzeit_beginn,
         konferenz.uhrzeit_ende,
         teilnehmer                                                           AS teilnehmer,
         teilnehmer_bes                                                       AS teilnehmer_bes,
         CASE
            WHEN teilnehmer_bes = 0 AND teilnehmer > 0 THEN 'red'
            WHEN teilnehmer_bes > 0 AND teilnehmer_bes < teilnehmer THEN 'blue'
            WHEN teilnehmer_bes > 0 AND teilnehmer_bes = teilnehmer THEN 'green'
            ELSE
            'black'
         END                                                                  AS teilnehmer_class,
         CONCAT_WS(', ', moderator.nachname, moderator.vorname)               AS moderator_id,
         CONCAT_WS(' ', '/',
           CONCAT_WS(' - ', konferenz.uhrzeit_beginn, konferenz.uhrzeit_ende)
         )  AS uhrzeit,

         moderator.org                                                        AS moderator_org,
         COUNT(DISTINCT patient.konferenz_patient_id)                         AS konferenz_patienten,
         COUNT(DISTINCT dokument.konferenz_dokument_id)                       AS konferenz_dokumente
      FROM konferenz konferenz
         LEFT JOIN user moderator                        ON moderator.user_id = konferenz.moderator_id
         LEFT JOIN konferenz_patient    patient          ON patient.konferenz_id = konferenz.konferenz_id
         LEFT JOIN konferenz_dokument   dokument         ON konferenz.konferenz_id = dokument.konferenz_id
   ";

   $querys['konferenz_patient'] = "
      SELECT
         k_pat.*,
         CONCAT_WS(', ', patient.nachname, patient.vorname)       AS name,
         DATE_FORMAT(geburtsdatum, '%d.%m.%Y')                    AS geburtsdatum,
         CONCAT_WS(', ', org.name, org.ort)                       AS org,
         e_bez.bez                                                AS erkrankung,
         DATE_FORMAT(k_pat.datenstand_datum, '%d.%m.%Y')                AS datenstand_datum,
         CONCAT(DATE_FORMAT(k_pat.datenstand_datum, '%I.%i'), ' Uhr')   AS datenstand_uhrzeit,
         therapieplan.therapieplan_id                             AS therapieplan_id
      FROM konferenz_patient k_pat
         LEFT JOIN therapieplan  therapieplan   ON therapieplan.konferenz_patient_id = k_pat.konferenz_patient_id
         LEFT JOIN patient       patient        ON patient.patient_id = k_pat.patient_id
         LEFT JOIN org           org            ON org.org_id = patient.org_id
         LEFT JOIN erkrankung    erkrankung     ON k_pat.erkrankung_id = erkrankung.erkrankung_id
            LEFT JOIN l_basic       e_bez          ON e_bez.klasse = IF(erkrankung.erkrankung_detail IS NOT NULL, 'erkrankung_sst_detail', 'erkrankung') AND
                                                      e_bez.code = IF(erkrankung.erkrankung_detail IS NOT NULL, erkrankung.erkrankung_detail, erkrankung.erkrankung)
   ";

   $querys['konferenz_dokument'] = "
      SELECT
         kd.id,
         kd.iscodoc,
         kd.ispatdoc,
         kd.bez,
         kd.doc_type,
         kd.name,
         kd.org,
         kd.erkrankung,
         kd.geburtsdatum,
         kd.type,
         kd.konferenz_patient_id,
         kd.dokument_id,
         kd.dokid
      FROM (
           (SELECT
              CONCAT_WS('', 'k', kd.konferenz_dokument_id) AS 'id',
              1 AS 'iscodoc',
              null AS 'ispatdoc',
              kd.bez,
              IF (kd.dokument_id IS NOT NULL,
                 d.doc_type,
                 SUBSTRING(datei,-3)
              ) AS 'doc_type',
              CONCAT_WS(', ', p.nachname, p.vorname) AS 'name',
              org.name AS 'org',
              e_bez.bez AS 'erkrankung',
              p.geburtsdatum AS 'geburtsdatum',
              'konf' AS 'type',
              kd.konferenz_patient_id,
              kd.dokument_id,
              IF (kd.dokument_id IS NOT NULL,
                 kd.dokument_id,
                 kd.konferenz_dokument_id
              ) AS 'dokid'
           FROM konferenz_dokument kd
               LEFT JOIN dokument d ON d.dokument_id = kd.dokument_id
               LEFT JOIN patient p ON d.patient_id = p.patient_id
               LEFT JOIN org org            ON org.org_id = p.org_id

               LEFT JOIN erkrankung erk ON d.erkrankung_id = erk.erkrankung_id
                   LEFT JOIN l_basic e_bez ON e_bez.klasse = IF(erk.erkrankung_detail IS NOT NULL, 'erkrankung_sst_detail', 'erkrankung') AND
                                              e_bez.code = IF(erk.erkrankung_detail IS NOT NULL, erk.erkrankung_detail, erk.erkrankung)

           WHERE
               kd.konferenz_id = '%s'
           GROUP BY
              kd.konferenz_dokument_id
           )
           UNION
           (
           SELECT
              CONCAT_WS('', 'd', d.dokument_id) AS 'id',
              null AS 'iscodoc',
              1 AS 'ispatdoc',
              d.bez,
              d.doc_type,
              CONCAT_WS(', ', p.nachname, p.vorname) AS 'name',
              org.name AS 'org',
              e_bez.bez AS 'erkrankung',
              p.geburtsdatum,
              'pat' AS 'type',
              null AS 'konferenz_patient_id',
              null AS 'dokument_id',
              d.dokument_id AS 'dokid'
           FROM (
               SELECT
                  kp.erkrankung_id,
                  kp.konferenz_id
               FROM konferenz_patient kp
               WHERE
                   kp.konferenz_id = '%s'
               GROUP BY
                   kp.erkrankung_id
           ) e
               INNER JOIN dokument d ON e.erkrankung_id = d.erkrankung_id
                   INNER JOIN patient p ON p.patient_id = d.patient_id
                   INNER JOIN erkrankung erk ON d.erkrankung_id = erk.erkrankung_id
                       LEFT JOIN l_basic e_bez ON e_bez.klasse = IF(erk.erkrankung_detail IS NOT NULL, 'erkrankung_sst_detail', 'erkrankung') AND
                                                  e_bez.code = IF(erk.erkrankung_detail IS NOT NULL, erk.erkrankung_detail, erk.erkrankung)
                   INNER JOIN org org            ON org.org_id = p.org_id

               LEFT JOIN konferenz_dokument kp ON kp.dokument_id = d.dokument_id AND kp.konferenz_id = e.konferenz_id
           WHERE kp.konferenz_dokument_id IS NULL

           GROUP BY
               d.dokument_id
           )
      ) kd
    ";

   $querys['zweitmeinung'] = "
       SELECT
           z.zweitmeinung_id,
           z.patient_id,
           z.erkrankung_id,
           z.nachname,
           z.vorname,
           z.geburtsdatum,
           z.patient_nr,
           z.datum,
           z.erkrankung,
           z.vorlage_dokument_id,
           z.org
       FROM (
           SELECT
               z.zweitmeinung_id,
               z.erkrankung_id,
               z.patient_id,
               z.vorlage_dokument_id,
               p.nachname,
               p.vorname,
               p.geburtsdatum,
               p.patient_nr,
               e_bez.bez AS erkrankung,
               DATE_FORMAT(z.datenstand_datum, '%Y-%m-%d') AS 'datum',
               o.name AS 'org'
           FROM zweitmeinung z
               LEFT JOIN patient p      ON p.patient_id = z.patient_id
               LEFT JOIN org o          ON o.org_id = p.org_id
               LEFT JOIN erkrankung e   ON e.erkrankung_id = z.erkrankung_id
                   LEFT JOIN l_basic       e_bez          ON e_bez.klasse = IF(e.erkrankung_detail IS NOT NULL, 'erkrankung_sst_detail', 'erkrankung') AND
                                                      e_bez.code = IF(e.erkrankung_detail IS NOT NULL, e.erkrankung_detail, e.erkrankung)
           GROUP BY
               z.zweitmeinung_id
        ) z
   ";

   $querys['termin'] = "
      SELECT
         t.termin_id,
         t.brief_gesendet,
         t.erledigt,
         t.erinnerung,
         t.datum,
         t.uhrzeit,
         t.dauer,
         l.bez AS art,
         p.patient_nr                           AS 'patient_nr',
         CONCAT_WS(', ', p.nachname, p.vorname) AS 'patient_name'
      FROM termin t
         LEFT JOIN patient p     ON t.patient_id = p.patient_id
         LEFT JOIN erkrankung e  ON e.patient_id = p.patient_id
         LEFT JOIN l_basic l     ON l.klasse = 'termin_art' AND l.code = t.art
   ";

   $querys['patient_view'] = "
      SELECT
         s.status_id                                              AS 'status_id',
         s.patient_id                                             AS 'patient_id',

         CONCAT_WS('.',
            IF(s.form = 'erkrankung', 'view', 'rec'),
            s.form
         )                                                        AS 'form',
         s.form_id                                                AS 'form_id',

         s.form                                                   AS 'class',
         s.form                                                   AS 'config',
         IF(s.form = 'erkrankung', s.form_param, s.form_status)   AS 'status',
         IF(s.form = 'erkrankung',
            (SELECT
               IF(COUNT(kp.konferenz_patient_id) > 0, 1, NULL)
             FROM konferenz_patient kp
             WHERE
               kp.erkrankung_id = s.form_id AND
               kp.document_dirty IS NOT NULL
            )
         , NULL)                                                      AS 'kpdirty',
         CASE s.form
            WHEN 'erkrankung' THEN
               CONCAT_WS(', letzte Nachsorge am ',
                  s.form_data,
                  IF(MAX(sn.form_date) IS NOT NULL, DATE_FORMAT(MAX(sn.form_date), '%d.%m.%Y'), null)
               )

            WHEN 'abschluss' THEN
               (SELECT l.bez FROM l_basic l WHERE l.klasse = 'abschluss_grund' AND l.code = MIN(a.abschluss_grund))

            WHEN 'aufenthalt' THEN
            CONCAT_WS(', ',
                  MIN(af.aufnahmenr),
                  IF (af.aufnahmedatum IS NOT NULL OR af.entlassungsdatum IS NOT NULL,
                     CONCAT_WS(' - ',
                        DATE_FORMAT(MIN(af.aufnahmedatum),     '%d.%m.%Y'),
                        DATE_FORMAT(MIN(af.entlassungsdatum),  '%d.%m.%Y')
                     ),
                     NULL
                  )
               )

            WHEN 'behandler' THEN
               CONCAT_WS(', ',
                  CONCAT_WS(' ', MIN(u.titel), MIN(u.vorname), MIN(u.nachname)),
                  MIN(u.ort),
                  (SELECT l.bez FROM l_basic l WHERE l.klasse = 'arzt_funktion' AND l.code = MIN(b.funktion))
               )
         END                                                      AS 'info',

         IF(s.form = 'erkrankung',
            CASE
               WHEN ((MIN(erk_loc.status_lock) + IF(MIN(sn.status_lock) IS NULL, 0, MIN(sn.status_lock))) = 0 AND (MAX(erk_loc.status_lock) + IF(MAX(sn.status_lock) IS NULL, 0, MAX(sn.status_lock))) = 0) THEN 0
               WHEN ((MIN(erk_loc.status_lock) + IF(MIN(sn.status_lock) IS NULL, 1, MIN(sn.status_lock))) < 2 AND (MAX(erk_loc.status_lock) + IF(MAX(sn.status_lock) IS NULL, 1, MAX(sn.status_lock))) < 3) THEN 'p'
               WHEN ((MIN(erk_loc.status_lock) + IF(MIN(sn.status_lock) IS NULL, 1, MIN(sn.status_lock))) = 2 AND (MAX(erk_loc.status_lock) + IF(MAX(sn.status_lock) IS NULL, 1, MAX(sn.status_lock))) = 2) THEN 1
            ELSE
               0
            END,
            s.status_lock
         )                                                        AS 'status_lock',

         IF(s.form = 'erkrankung',
            COUNT(DISTINCT IF(erk_loc.status_lock = 1, erk_loc.status_id, NULL)) +
            COUNT(DISTINCT IF(sn.status_lock = 1, sn.status_id, NULL)),
            null
         )                                                        AS 'locked',

         IF(s.form = 'erkrankung',
            COUNT(DISTINCT erk_loc.status_id) + COUNT(DISTINCT sn.status_id),
            null
         )                                                        AS 'total'
      FROM `status` s
         LEFT JOIN erkrankung e     ON s.form = 'erkrankung' AND e.erkrankung_id = s.form_id
         LEFT JOIN status erk_loc   ON s.form = 'erkrankung' AND erk_loc.patient_id = s.patient_id AND
                                        ((erk_loc.erkrankung_id = s.form_id AND erk_loc.form NOT IN('qs_18_1_o', 'qs_18_1_brust')) OR (erk_loc.form = 'erkrankung' AND erk_loc.form_id = s.form_id))
         LEFT JOIN status sn        ON s.form = 'erkrankung' AND sn.patient_id = s.patient_id AND
                                       sn.form = 'nachsorge' AND LOCATE(CONCAT_WS('','-', s.form_id,'-'), s.report_param) > 0

         LEFT JOIN abschluss a      ON s.form = 'abschluss' AND a.abschluss_id = s.form_id
         LEFT JOIN aufenthalt af    ON s.form = 'aufenthalt' AND af.aufenthalt_id = s.form_id

         LEFT JOIN behandler b      ON s.form = 'behandler' AND b.behandler_id = s.form_id
            LEFT JOIN user u        ON u.user_id = b.user_id
      WHERE
         s.patient_id = '{$patient_id}' AND
         s.form IN ('abschluss', 'erkrankung', 'aufenthalt', 'behandler')
      GROUP BY
         s.status_id
      ORDER BY
         form DESC, info ASC
   ";

   $querys['erkrankung_view_tree'] = "
      SELECT
        status_id,
        patient_id,
        parent_status_id,
        relation,
        erkrankung_id,
        form_id,
        form,
        formcontent,
        form_date,
        form_date_de,
        form_data,
        IF(form = 'qs_18_1_b', form_param, form_status) AS 'form_status',
        form_param,
        IF(form LIKE 'dmp_brustkrebs_%_2013', DATE_FORMAT(report_param, '%d.%m.%Y %H:%i'),report_param) AS report_param,
        status_lock
      FROM (
          SELECT
             x.status_id,
             x.patient_id,
             x.parent_status_id,
             x.erkrankung_id,
             x.form_id,
             x.form,
             IF(x.form = 'eingriff' AND x.form_date = '0000-00-00', 'extern', x.form_date) AS 'form_date',
             IF(x.form = 'eingriff' AND x.form_date = '0000-00-00', 'extern', DATE_FORMAT(x.form_date,'%d.%m.%Y')) AS 'form_date_de',
             IFNULL(x.form_data, '-') AS 'form_data',
             CONCAT_WS(' ', x.form, IFNULL(x.form_data, '-')) AS 'formcontent',
             x.form_status,
             x.form_param,
             x.report_param,
             x.status_lock,
             CONCAT_WS('', '|', CONCAT_WS('|', xson.form, xsonson.form, x.form, xparent.form, xparenty.form), '|') AS 'relation'
          FROM `status` x
              LEFT JOIN `status` xparent ON xparent.status_id = x.parent_status_id
                LEFT JOIN `status` xparenty ON xparenty.status_id = xparent.parent_status_id

              LEFT JOIN `status` xson ON xson.erkrankung_id = x.erkrankung_id AND
                  xson.parent_status_id = x.status_id

              LEFT JOIN `status` xsonson ON xsonson.erkrankung_id = x.erkrankung_id AND
                  xsonson.parent_status_id = xson.status_id

          WHERE
                  x.patient_id = '{$patient_id}' AND
            ((x.form != 'nachsorge' AND x.erkrankung_id = '{$erkrankung_id}') OR
             (x.form = 'nachsorge' AND LOCATE(CONCAT_WS('','-',{$erkrankung_id},'-'), x.report_param) > 0)
            ) AND x.form NOT IN ('qs_18_1_brust', 'qs_18_1_o')
          GROUP BY x.status_id
      ) s
   ";

   $querys['erkrankung_view_table'] = "
      SELECT
        status_id,
        patient_id,
        parent_status_id,
        erkrankung_id,
        form_id,
        form,
        form_filter,
        formcontent,
        form_date,
        form_data,
        IF(form = 'qs_18_1_b', form_param, form_status) AS 'form_status',
        form_param,
        IF(form LIKE 'dmp_brustkrebs_%_2013', DATE_FORMAT(report_param, '%d.%m.%Y %H:%i'),report_param) AS report_param,
        status_lock
      FROM (
          SELECT
             x.status_id,
             x.patient_id,
             x.parent_status_id,
             x.erkrankung_id,
             x.form_id,
             x.form,
             IF(x.form = 'eingriff' AND x.form_date = '0000-00-00', 'extern', x.form_date) AS 'form_date',
             IFNULL(x.form_data, '-') AS 'form_data',
             CONCAT_WS(' ', x.form, IFNULL(x.form_data, '-')) AS 'formcontent',
             x.form_status,
             x.form_param,
             x.report_param,
             CONCAT_WS('', '|', x.form, '|') AS 'form_filter',
             x.status_lock
          FROM `status` x
          WHERE
                  x.patient_id = '{$patient_id}' AND
            ((x.form != 'nachsorge' AND x.erkrankung_id = '{$erkrankung_id}') OR
             (x.form = 'nachsorge' AND LOCATE(CONCAT_WS('','-',{$erkrankung_id},'-'), x.report_param) > 0)
            ) AND x.form NOT IN ('qs_18_1_brust', 'qs_18_1_o')
          GROUP BY x.status_id
      ) s
   ";

   $querys['vorlage_org'] = "SELECT * FROM org";

   $querys['vorlage_therapie'] = "
      SELECT
         vt.vorlage_therapie_id,
         vt.art,
         vt.erkrankung,
         vt.inaktiv,
         vt.bez,
         vt.freigabe
      FROM vorlage_therapie vt
   ";

   $querys['vorlage_studie'] = "
      SELECT
         vs.vorlage_studie_id,
         vs.studientyp,
         vs.bez,
         vs.erkrankung,
         vs.indikation,
         vs.ethikvotum,
         vs.freigabe,
         vs.inaktiv,
         vs.leiter,
         vs.telefax,
         vs.email
      FROM vorlage_studie vs
   ";

   $querys['vorlage_labor'] = "
      SELECT
         vl.vorlage_labor_id,
         vl.freigabe,
         vl.inaktiv,
         vl.bez,
         vl.gueltig_von,
         vl.gueltig_bis
      FROM vorlage_labor vl
   ";

   $querys['vorlage_fragebogen'] = "
      SELECT
         vf.vorlage_fragebogen_id,
         vf.bez,
         vf.art,
         vf.inaktiv,
         vf.freigabe
      FROM vorlage_fragebogen vf
   ";

   $querys['vorlage_krankenversicherung'] = "
      SELECT
         vk.vorlage_krankenversicherung_id,
         vk.bundesland,
         vk.gkv,
         vk.inaktiv,
         vk.name,
         vk.telefon,
         vk.telefax,
         vk.email,
         vk.iknr
      FROM vorlage_krankenversicherung vk
   ";

   $querys['vorlage_icd10'] = "
      SELECT
         vi.vorlage_icd10_id,
         vi.code,
         vi.bez
      FROM vorlage_icd10 vi
   ";

   $querys['vorlage_ops'] = "
      SELECT
         vo.vorlage_ops_id,
         vo.code,
         vo.bez
      FROM vorlage_ops vo
   ";

   $querys['vorlage_fallkennzeichen'] = "
      SELECT
        vf.vorlage_fallkennzeichen_id,
        vf.code,
        vf.bez,
        vf.pos
     FROM vorlage_fallkennzeichen vf
   ";

   $querys['vorlage_konferenztitel'] = "
      SELECT
        vk.vorlage_konferenztitel_id,
        vk.bez
     FROM vorlage_konferenztitel vk
   ";

   $querys['vorlage_icdo'] = "
      SELECT
         vi.vorlage_icdo_id,
         vi.code,
         vi.bez
      FROM vorlage_icdo vi
   ";

   $querys['vorlage_dokument']            = "SELECT * FROM vorlage_dokument ORDER BY bez";
   $querys['vorlage_query']               = "SELECT * FROM vorlage_query";

   $querys['vorlage_query'] = "
       SELECT
          *,
          IF(package IS NULL, 'xls', typ) AS typ
       FROM vorlage_query
   ";

   $querys['vorlage_arzt'] = "
      SELECT
         u.user_id,
         u.anrede,
         u.teilnahme_dmp,
         u.teilnahme_netzwerk,
         u.staat,
         u.efn_nz,
         u.pwd_change,
         u.candidate,
         u.strasse,
         u.hausnr,
         u.ort,
         u.plz,
         u.inaktiv,
         CONCAT_WS(', ', u.nachname, u.vorname)          AS name,
         fa.bez                                          AS fachabteilung,
         IF(GROUP_CONCAT(r.rolle) LIKE '%admin%', 1, 0)  AS admin,
         IF(COUNT(r.recht_id) > 0, 0, 1)                 AS editable
      FROM user u
         LEFT JOIN l_basic fa    ON fa.klasse='fachabteilung' AND fa.code=u.fachabteilung
         LEFT JOIN recht r       ON r.user_id = u.user_id AND r.rolle IN ('supervisor', 'dokumentar', 'datenmanager', 'kooperationspartner', 'admin') AND r.org_id = {$org_id}
   ";

?>
