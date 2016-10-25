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

------------------------------------------------------------------------------------------------------------------------

# AlcedisMED4 - Database Setup Guide

* Attention: All files must be imported with ISO-8859-1 Charset

## 1. Initial Setup

1: Create a MySQL database (e.g. "med4") with "latin1_german1_ci" collation on your server

2: Execute SQL statements in following order for this database:

    sql/{VERSION}/initial/structure.sql
    sql/{VERSION}/initial/views.sql
    sql/{VERSION}/initial/settings.sql
    sql/{VERSION}/initial/data.sql
    sql/{VERSION}/initial/email.sql*
    
        * Please notice that this file must be configured before execution

## 2. Update

1: Choose desired version for update and execute SQL statements in following order:

    sql/{VERSION}/update/change.sql*
    sql/{VERSION}/update/data.sql*
    sql/{VERSION}/update/views.sql*
    
        * Files may or may not exists as part of the change/update
