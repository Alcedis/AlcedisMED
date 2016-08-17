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

 $().ready(function(){
   //Entfernt bei einem Klick in ein Inputfeld die Error/Warn Messages
   $(':input').click(function(){
      check_open_error($(this), 'input');
   });

   $(':button').click(function(){
      remove_err_warn();

      switch($(this).attr('name')){
         case 'insert':
            validate_data('insert');
         break;

         case 'update':
            validate_data('update');
         break;

         case 'cancel':
        	 $('#d_input').dialog("close");
         break;
      }
   });
   
   
});