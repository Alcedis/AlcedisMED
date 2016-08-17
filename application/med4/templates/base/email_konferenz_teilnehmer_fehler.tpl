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

Dem folgenden Teilnehmer der Tumorkonferenz am {$now_date} um {$now_time} Uhr konnte keine E-Mail zugesandt werden:

Benutzername: {$name}
E-Mail:       {if strlen($email_reciver)}{$email_reciver}{else}keine E-Mail Adresse vorhanden!{/if}


Bitte benachrichtigen Sie diese(n) Teilnehmer telefonisch.
