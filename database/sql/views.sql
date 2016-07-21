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

CREATE VIEW `l_qs_2009`  AS  select `l_qs`.`klasse` AS `klasse`,`l_qs`.`code` AS `code`,`l_qs`.`bez` AS `bez`,`l_qs`.`pos` AS `pos` from `l_qs` where (`l_qs`.`jahr` like _latin1'%2009%') ;
CREATE VIEW `l_qs_2010`  AS  select `l_qs`.`klasse` AS `klasse`,`l_qs`.`code` AS `code`,`l_qs`.`bez` AS `bez`,`l_qs`.`pos` AS `pos` from `l_qs` where (`l_qs`.`jahr` like _latin1'%2010%') ;
CREATE VIEW `l_qs_2011`  AS  select `l_qs`.`klasse` AS `klasse`,`l_qs`.`code` AS `code`,`l_qs`.`bez` AS `bez`,`l_qs`.`pos` AS `pos` from `l_qs` where (`l_qs`.`jahr` like _latin1'%2011%') ;
CREATE VIEW `l_qs_2012`  AS  select `l_qs`.`klasse` AS `klasse`,`l_qs`.`code` AS `code`,`l_qs`.`bez` AS `bez`,`l_qs`.`pos` AS `pos` from `l_qs` where (`l_qs`.`jahr` like _latin1'%2012%') ;
CREATE VIEW `l_qs_2013`  AS  select `l_qs`.`klasse` AS `klasse`,`l_qs`.`code` AS `code`,`l_qs`.`bez` AS `bez`,`l_qs`.`pos` AS `pos` from `l_qs` where (`l_qs`.`jahr` like _latin1'%2013%') ;
CREATE VIEW `l_qs_2014`  AS  select `l_qs`.`klasse` AS `klasse`,`l_qs`.`code` AS `code`,`l_qs`.`bez` AS `bez`,`l_qs`.`pos` AS `pos` from `l_qs` where (`l_qs`.`jahr` like _latin1'%2014%') ;
