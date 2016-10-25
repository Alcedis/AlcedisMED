/*
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
*/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;





Attention: Please replace placeholder '@supportMail' with your own your support mail and delete this line afterwards.








INSERT INTO `email` (`email_id`, `bez`, `subject`, `email_from`, `template`) VALUES
(1, 'invitation_conference', 'Einladung zur Online-Tumorkonferenz am {$konferenz_datum}', NULL, '{if $anrede == \'H\'} Sehr geehrter Herr {$titel} {$name}<br> {elseif $anrede == \'F\'} Sehr geehrte Frau {$titel} {$name}<br> {else} Sehr geehrte Damen und Herren,<br> {/if} <br> hiermit lade ich Sie herzlich zur Online Konferenz am {$konferenz_datum} ein.<br> Die Konferenz wird {if $konferenz_uhrzeit_ende} von {$konferenz_uhrzeit_beginn} - ca. {$konferenz_uhrzeit_ende}{else} um {$konferenz_uhrzeit_beginn}{/if} Uhr stattfinden.<br> <br> Das Thema lautet:<br> {$konferenz_bez}<br> <br> Die Fragestellung dieser Konferenz ist:<br> {$konferenz_bem}<br> <br> {$konferenz_bem_einladung}<br> <br> Ich hoffe auf Ihre Teilnahme und bitte Sie, falls Sie verhindert sind, mir telefonisch oder via E-Mail abzusagen:<br> Telefon: {$moderator_telefon}<br> E-Mail: {$moderator_email}<br> <br> <br> Mit freundlichem Gru�<br> <br> {$moderator_fullname}<br> (Moderator)'),
(2, 'password_reset', 'Passwort zur�ckgesetzt', '@supportMail', '{if $anrede == \'h\'}\r\nSehr geehrter Herr {$titel} {$nachname},<br>\r\n{elseif $anrede == \'f\'}\r\nSehr geehrte Frau {$titel} {$nachname},<br>\r\n{else}\r\nSehr geehrte Damen und Herren,<br>\r\n{/if}\r\n<br>\r\nhiermit best�tigen wir, dass das Passwort f�r Ihren Account erfolgreich im System der Online Tumorkonferenz zur�ckgesetzt wurde.<br/>\r\n<br/>\r\nIhre aktuellen Zugangsdaten:<br/>\r\n<br/>\r\nAccount: <b>{$loginname}</b><br/>\r\nPasswort: <b>{$newpw}</b><br/>\r\n<br/>\r\nAus Sicherheitsgr�nden melden Sie sich bitte umgehend am System an und �ndern Sie dieses Passwort.<br/> \r\n<br/>\r\nMit freundlichen Gr��en<br/>\r\n<br/>\r\nIhr Support'),
(3, 'account_activated', 'Account freigeschaltet', '@supportMail', '{if $anrede == \'h\'}\r\nSehr geehrter Herr {$titel} {$nachname},<br>\r\n{elseif $anrede == \'f\'}\r\nSehr geehrte Frau {$titel} {$nachname},<br>\r\n{else}\r\nSehr geehrte Damen und Herren,<br>\r\n{/if}\r\n<br>\r\nhiermit best�tigen wir, dass Ihr Account <b>"{$loginname}"</b> erfolgreich im System der Online Tumorkonferenz freigeschaltet wurde.<br>\r\n<br/>\r\nSie k�nnen sich nun am System anmelden.<br/>\r\n<br/>\r\nMit freundlichen Gr��en<br/>\r\n<br/>\r\nIhr Support'),
(4, 'registration_success', 'Registrierung erfolgreich', '@supportMail', '{if $anrede == \'h\'}\r\nSehr geehrter Herr {$titel} {$nachname},<br>\r\n{elseif $anrede == \'f\'}\r\nSehr geehrte Frau {$titel} {$nachname},<br>\r\n{else}\r\nSehr geehrte Damen und Herren,<br>\r\n{/if}\r\n<br>\r\nhiermit best�tigen wir, dass Sie erfolgreich im System der Online Tumorkonferenz registriert wurden.<br>\r\n<br/>\r\nIhr Account <b>"{$user_loginname}"</b> ist aktuell noch inaktiv.<br/>\r\n<br/>\r\nSobald Sie freigeschaltet wurden, erhalten Sie eine weitere E-Mail zur Best�tigung.<br/>\r\n<br/>\r\n<br/>\r\nMit freundlichen Gr��en<br/>\r\n<br/>\r\nIhr Support'),
(5, 'registration_confirm', 'Registrierungsanfrage', '@supportMail', '{if $anrede == \'h\'}\r\nSehr geehrter Herr {$titel} {$nachname},<br>\r\n{elseif $anrede == \'f\'}\r\nSehr geehrte Frau {$titel} {$nachname},<br>\r\n{else}\r\nSehr geehrte Damen und Herren,<br>\r\n{/if}\r\n<br>\r\nsoeben wurde eine neue Registrierungsanfrage gestellt.<br/>\r\n<br/>\r\nDer Account <b>"{$user_loginname}"</b> ist aktuell noch inaktiv.<br/>\r\n<br/>\r\nBitte pr�fen Sie die Angaben und schalten den Benutzer frei.<br/>\r\n<br/>\r\n<br/>\r\nMit freundlichen Gr��en<br/>\r\n<br/>\r\nIhr Support');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
