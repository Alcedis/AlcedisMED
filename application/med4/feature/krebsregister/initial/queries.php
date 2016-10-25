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

$queries['kr_register_patient'] = "
    SELECT
        p.patient_id as id,
        p.nachname,
        p.vorname,
        DATE_FORMAT(p.geburtsdatum, '%d.%m.%Y') as geburtsdatum,
        p.patient_nr,
        p.geschlecht
    FROM
        patient p
        INNER JOIN export_case_log ecl ON p.patient_id = ecl.patient_id
        INNER JOIN export_log el ON ecl.export_log_id = el.export_log_id
    WHERE
        el.export_name = 'kr_{#type#}' AND
        p.patient_id = '{#patient_id#}'
    GROUP BY
        p.patient_id
";

$queries['kr_list'] = "
    SELECT
        *
    FROM (SELECT
        p.patient_id,
        p.nachname,
        p.vorname,
        p.geburtsdatum,
        {#pds#} COLLATE latin1_german1_ci as erkrankung,
        p.patient_nr,
        IFNULL((SELECT
            MIN(
                IF(esl.valid IN (2,21,32,321),
                   1,
                   IF(esl.valid IN (1,21,31,321), 2, 4)
                )
            )
         FROM export_log el
            INNER JOIN export_case_log ecl ON ecl.export_log_id = el.export_log_id
            INNER JOIN export_section_log esl ON esl.export_case_log_id = ecl.export_case_log_id
         WHERE
            el.export_log_id = '{#exportLogId#}' AND
            ecl.patient_id = p.patient_id
         LIMIT 1
        ), 0) as status,
        (SELECT
            IFNULL(MAX(el.updatetime), MAX(el.createtime))
         FROM export_log el
            INNER JOIN export_case_log ecl ON ecl.export_log_id = el.export_log_id
         WHERE
            el.export_name = 'kr_{#type#}' AND
            el.org_id = p.org_id AND
            el.export_unique_id = p.org_id AND
            el.finished = 1 AND
            ecl.patient_id = p.patient_id
        ) as lexport,
        IFNULL((SELECT
            1
         FROM export_log el
            INNER JOIN export_case_log ecl ON ecl.export_log_id = el.export_log_id
            INNER JOIN export_section_log esl ON esl.export_case_log_id = ecl.export_case_log_id AND esl.valid IN (2,21,32,321)
         WHERE
            el.export_log_id = '{#exportLogId#}' AND
            ecl.patient_id = p.patient_id
         LIMIT 1
        ), 0) as errors,
        IFNULL((SELECT
            1
         FROM export_log el
            INNER JOIN export_case_log ecl ON ecl.export_log_id = el.export_log_id
            INNER JOIN export_section_log esl ON esl.export_case_log_id = ecl.export_case_log_id AND esl.valid IN (1,21,31,321)
         WHERE
            el.export_log_id = '{#exportLogId#}' AND
            ecl.patient_id = p.patient_id
         LIMIT 1
        ), 0) as warnings
    FROM patient p
    WHERE p.patient_id IN ({#patientIds#})
    ) x
";

$queries['kr_history'] = "
    SELECT
        el.export_log_id as export_id,
        el.updatetime as createtime
    FROM export_log el
        INNER JOIN export_case_log ecl ON ecl.export_log_id = el.export_log_id
    WHERE
        el.export_name = 'kr_{#type#}' AND
        el.org_id = '{#org_id#}' AND
        el.export_unique_id = '{#export_unique_id#}' AND
        el.finished = 1 AND
        ecl.patient_id = '{#patient_id#}'
    GROUP BY
        export_id
    ORDER BY
        el.createtime DESC
";

$queries['kr_error'] = "
    SELECT
        esl.export_case_log_id,
        esl.export_section_log_id,
        lb.bez as erkrankung,
        ecl.diagnose_seite,
        ecl.erkrankung_id,
        ecl.anlass,
        esl.errors,
        esl.block,
        esl.daten,
        esl.valid,
        esl.createtime
    FROM export_log el
        INNER JOIN export_case_log ecl ON el.export_log_id = ecl.export_log_id
        INNER JOIN export_section_log esl ON esl.export_case_log_id = ecl.export_case_log_id AND esl.valid IN (2,21,32,321)
        INNER JOIN erkrankung er ON ecl.erkrankung_id = er.erkrankung_id
        INNER JOIN l_basic lb ON lb.klasse = 'erkrankung' AND lb.code = er.erkrankung
    WHERE
        el.export_log_id = '{#export_log_id#}' AND
        el.export_name = 'kr_{#type#}' AND
        ecl.patient_id = '{#patient_id#}' {#sectionLogId#}
";

$queries['kr_warning'] = "
    SELECT
        esl.export_case_log_id,
        esl.export_section_log_id,
        lb.bez as erkrankung,
        ecl.diagnose_seite,
        ecl.erkrankung_id,
        ecl.anlass,
        esl.errors,
        esl.block,
        esl.daten,
        esl.valid,
        esl.createtime
    FROM export_log el
        INNER JOIN export_case_log ecl ON el.export_log_id = ecl.export_log_id
        INNER JOIN export_section_log esl ON esl.export_case_log_id = ecl.export_case_log_id AND esl.valid IN (1,21,31,321)
        INNER JOIN erkrankung er ON ecl.erkrankung_id = er.erkrankung_id
        INNER JOIN l_basic lb ON lb.klasse = 'erkrankung' AND lb.code = er.erkrankung
    WHERE
        el.export_log_id = '{#export_log_id#}' AND
        el.export_name = 'kr_{#type#}' AND
        ecl.patient_id = '{#patient_id#}' {#sectionLogId#}
";
