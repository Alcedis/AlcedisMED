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

/*
 * INFO:
 * Die Klasse dataCollector included alle query Dateien!
 * Wenn hier Variablen genutzt werden, muss sichergestellt werden,
 * dass diese auch in der Klasse vorbelegt werden!!!
 *
 * Gilt auf für die Top10 generierung der Codepicker!!!
 */

$querys['user'] = "
   SELECT
      u.user_id,
      u.nachname,
      u.vorname,
      u.anrede,
      u.fachabteilung,
      u.teilnahme_dmp,
      u.teilnahme_netzwerk,
      u.staat,
      u.pwd_change,
      u.efn_nz,
      u.inaktiv,
      u.candidate,
      CONCAT_WS(', ', creator.nachname, creator.vorname) AS created_by,
      IF(u.candidate IS NOT NULL, '', u.loginname) AS loginname
   FROM user u
      LEFT JOIN user creator  ON creator.user_id = u.createuser
";

$querys['recht'] = "
   SELECT
      recht.recht_id,
      recht.behandler,
      recht.rolle,
      recht.recht_global,
      CONCAT_WS(', ', user.nachname, user.vorname)                AS user_id,
      user.loginname                                              AS loginname,
      CONCAT_WS(', ', created_by.nachname, created_by.vorname)    AS created_by,
      org.name                                                    AS org_id,
      rolle.bez                                                   AS rolle_bez
   FROM recht
     LEFT JOIN user user          ON user.user_id       = recht.user_id
     LEFT JOIN user created_by    ON recht.createuser   = created_by.user_id
     LEFT JOIN org                ON recht.org_id       = org.org_id
     LEFT JOIN l_basic rolle      ON rolle.klasse = 'rolle' AND rolle.code = recht.rolle
";

$querys['org'] = "SELECT * FROM org WHERE org_id > 0";

$querys['settings_pacs'] = "
   SELECT
      pacs.*,
      org.org_id  AS org_id,
      org.name    AS org_name,
      org.ort     AS org_ort
   FROM org
      LEFT JOIN settings_pacs pacs ON org.org_id = pacs.org_id
   WHERE org.org_id > 0
   GROUP BY org.org_id
";

$querys['settings_export'] = "
   SELECT
      settings_export_id,
      name
   FROM settings_export
";

$querys['settings_import'] = "
   SELECT
      settings_import_id,
      name
   FROM settings_import
";

$querys['settings_report'] = "
   SELECT
      settings_report_id,
      name,
      erkrankung
   FROM settings_report
";


$querys['user_log'] = "
   SELECT
      u.user_id,
      h.loginname       AS 'loginname',
      u.nachname        AS 'nachname',
      u.vorname         AS 'vorname',
      login_time,
      DATE_FORMAT(h.login_time, '%d.%m.%Y %H:%i:%s') AS login_time_de
   FROM history h
      LEFT JOIN user u ON u.loginname = h.loginname
         LEFT JOIN recht r ON r.user_id = u.user_id
";

$querys['user_log_detail'] = "
   SELECT
      history_id,
      login_ip,
      login_acc,
      DATE_FORMAT(login_time, '%Y-%m-%d') AS 'login_date',
      DATE_FORMAT(login_time, '%H:%i:%s') AS 'login_time',
      DATE_FORMAT(login_time, '%d.%m.%Y') AS 'login_date_de',
      DATE_FORMAT(login_time, '%H:%i:%s') AS 'login_time_de'
   FROM history
";

?>
