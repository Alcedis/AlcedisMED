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

statusReportParam::setDb($db);

statusReportParam::setCall('studie', "
        UPDATE `status` s
            INNER JOIN studie stud ON stud.studie_id = s.form_id
            LEFT JOIN vorlage_studie v ON stud.vorlage_studie_id = v.vorlage_studie_id AND v.ethikvotum = '1'
        SET
            s.report_param = IF(v.vorlage_studie_id IS NOT NULL, IFNULL(stud.date, stud.beginn), NULL)
        WHERE
            s.form = 'studie' (ident)")
     ->setCall('aufenthalt', "
        UPDATE `status` s
            INNER JOIN aufenthalt a ON a.aufenthalt_id = s.form_id
        SET
            s.report_param = a.aufnahmenr
        WHERE
            s.form = 'aufenthalt' (ident)
    ")->setCall('erkrankung', "
        UPDATE `status` s
            INNER JOIN erkrankung e ON e.erkrankung_id = s.form_id
        SET
            s.report_param = e.erkrankung
        WHERE
            s.form = 'erkrankung' (ident)
    ")->setCall('fragebogen', "
        UPDATE `status` s
            INNER JOIN fragebogen e ON e.fragebogen_id = s.form_id
        SET
            s.report_param = e.vorlage_fragebogen_id
        WHERE
            s.form = 'fragebogen' (ident)
    ")
    ->setCall('behandler', "
        UPDATE `status` s
            INNER JOIN behandler b ON b.behandler_id = s.form_id
        SET
            s.report_param = b.user_id
        WHERE
            s.form = 'behandler' (ident)
    ")
    ->setCall('nachsorge', "
        UPDATE `status` s
            INNER JOIN (
                SELECT
                   ne.nachsorge_id,
                   CONCAT_WS('', '-', GROUP_CONCAT(ne.erkrankung_weitere_id SEPARATOR '-'), '-') AS erkrankungen
                FROM nachsorge_erkrankung ne
                GROUP BY
                   ne.nachsorge_id
            ) erk ON erk.nachsorge_id = s.form_id
        SET
            s.report_param = erk.erkrankungen
        WHERE
            s.form = 'nachsorge' (ident)
    ")->setCall('tumorstatus', "
        UPDATE `status` s
            INNER JOIN (
                SELECT
                    t.tumorstatus_id,
                    CONCAT_WS('', t.datum_sicherung, '|', GROUP_CONCAT(DISTINCT tm.lokalisation SEPARATOR ' ')) AS codes
                FROM tumorstatus t
                    LEFT JOIN tumorstatus_metastasen tm ON tm.tumorstatus_id = t.tumorstatus_id
                GROUP BY
                    t.tumorstatus_id
            ) icdo3t ON icdo3t.tumorstatus_id = s.form_id
        SET
            s.report_param = icdo3t.codes
        WHERE
            s.form = 'tumorstatus' (ident)
    ")->setCall('untersuchung', "
        UPDATE `status` s
            INNER JOIN (
                SELECT
                    u.untersuchung_id,
                    CONCAT_WS('', u.datum, '|', GROUP_CONCAT(DISTINCT ul.lokalisation SEPARATOR ' ')) AS codes
                FROM untersuchung u
                    INNER JOIN erkrankung erk ON              erk.erkrankung_id = u.erkrankung_id
                    LEFT JOIN untersuchung_lokalisation ul ON ul.untersuchung_id = u.untersuchung_id AND
                                                              IF (u.art_seite = 'B',
                                                                    ul.lokalisation_seite IN ('R', 'L', 'B'),
                                                                    ul.lokalisation_seite IN ('B', u.art_seite)
                                                               )
                GROUP BY
                    u.untersuchung_id
            ) ops ON ops.untersuchung_id = s.form_id
        SET
            s.report_param = ops.codes
        WHERE
            s.form = 'untersuchung' (ident)
    ")->setCall('eingriff', "
        UPDATE `status` s
            INNER JOIN (
                SELECT
                    op.eingriff_id,
                    CONCAT_WS('',
                        IF(1 IN (op.art_primaertumor, op.art_rezidiv), 1, 0),'|',IFNULL(op.diagnose_seite, '-'),'|',
                        GROUP_CONCAT(DISTINCT ops.prozedur SEPARATOR ' ')
                    ) AS codes
                FROM eingriff op
                    INNER JOIN erkrankung erk  ON erk.erkrankung_id = op.erkrankung_id
                    LEFT JOIN eingriff_ops ops ON op.eingriff_id = ops.eingriff_id AND
                        IF(erk.erkrankung = 'b',
                            IF (op.diagnose_seite = 'B',
                                ops.prozedur_seite IN ('R', 'L', 'B'),
                                ops.prozedur_seite IN ('B', op.diagnose_seite)
                            ),
                            1
                        )
                GROUP BY
                    op.eingriff_id
            ) ops ON ops.eingriff_id = s.form_id
        SET
            s.report_param = ops.codes
        WHERE
            s.form = 'eingriff' (ident)
    ")->setCall('qs_18_1_b', "
        UPDATE `status` s
            INNER JOIN (
                SELECT
                    qsb.status_id,
                    IF(MIN(qsbrust.form_status) IS NULL, qsb.form_status,
                        IF(qsb.form_status < MIN(qsbrust.form_status),
                            IF(MIN(qso.form_status) IS NOT NULL,
                                IF(qsb.form_status < MIN(qso.form_status), qsb.form_status, MIN(qso.form_status)),
                                qsb.form_status
                            ),
                            IF(MIN(qso.form_status) IS NOT NULL,
                                IF(MIN(qsbrust.form_status) < MIN(qso.form_status), MIN(qsbrust.form_status), MIN(qso.form_status)),
                                MIN(qsbrust.form_status)
                            )
                        )
                    ) AS 'state'
                FROM `status` qsb
                    LEFT JOIN `status` qsbrust ON qsbrust.form = 'qs_18_1_brust' AND qsbrust.parent_status_id = qsb.status_id
                        LEFT JOIN `status` qso ON qso.form = 'qs_18_1_o' AND qso.parent_status_id = qsbrust.status_id
                WHERE qsb.form = 'qs_18_1_b'
                GROUP BY
                    qsb.status_id
            ) qs ON qs.status_id = s.status_id
        SET
            s.form_param = qs.state
        WHERE
            s.form = 'qs_18_1_b' (ident)
    ")
    ->setRelation('eingriff_ops', 'eingriff')
    ->setRelation('untersuchung_lokalisation', 'untersuchung')
    ->setRelation('nachsorge_erkrankung', 'nachsorge')
    ->setRelation('tumorstatus_metastasen', 'tumorstatus')
    ->setRelation('qs_18_1_brust', 'qs_18_1_b')
    ->setRelation('qs_18_1_o', 'qs_18_1_b')
;

?>
