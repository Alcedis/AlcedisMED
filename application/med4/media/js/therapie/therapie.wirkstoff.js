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

$(function(){

    allocateData();

    $('input[name="zyklustag"]').keyup(function(){
    	var zyklustag = $('input[name="zyklustag"]').attr('value'),
		zyklusId  = $('input[name="therapie_systemisch_zyklus_id"]').attr('value');
	
    	loadTemplate(zyklusId, zyklustag)
    });
    
});


function loadTemplate(zyklusId, zyklusTag)
{
   $('table.append').empty();
   $('table.append').parent().parent().show();
   $('div.msgbox').parent().parent().hide();

   if (zyklusTag != '') {
      $.ajax({
         url      : location.href,
         dataType : 'html',
         type     : 'post',
         data     : {'ajax' : true, 'zyklustag' : zyklusTag, 'therapie_systemisch_zyklus_id' : zyklusId},
         success  : function(data) {
        	 
        	 $('table.append').empty();
        	 $('table.append').parent().parent().show(); 
        	 
            $('table.append').append(data);
         },
          dataFilter: function (data) {
            
            checkSessionExpired(data, this);
            
            return data;
         }
      });
   } else {
      $('table.append').parent().parent().hide();
      $('div.msgbox').parent().parent().show();
   }
}


function allocateData()
{
	var allocated_data = $('input[name="allocated_data"]').attr('value'),
       data = allocated_data.length ? $.evalJSON(allocated_data) : [],
       identifier = allocated_data.length ? data.identifier : null,     		   		
       errors = $('input[name="pos_errors"]').attr('value'),
       errors = errors.length ? $.evalJSON(errors) : {},
       alreadyError = '';
       
   $('input[name="allocated_data"]').remove();
   $('input[name="pos_errors"]').remove();

   if (identifier !== null && data.data.length > 0) {
	  $.each(data.data, function(index, dataset){
		   	
         var identifierId = dataset[identifier],
             $identifiedTr = $('tr[class~="extform-identifier-' + identifierId + '"]');
         	
         $.each(dataset, function(name, value){
        	 $(':input[name$="[' + name + '][]"]', $identifiedTr).attr('value', value);
         });
         
         //Special Case //Wenn aenderung dosis dokumentiert, dann original dosis und value ausblenden
         if (dataset.aenderung_dosis !== "") {
        	 $('span[class="dosis-value"]', $identifiedTr).hide();
         }
	   });
   }
   
   if (errors.length) {
       $('ul.pos_error_msg').empty();
   }

   $.each(errors, function(field, position){
      for (k in position) {
          for (l in position[k]) {
             var $field = $(':input[name$="[' + field + '][]"]:eq(' + k + ')');
             $field.parent().addClass('pos_error');
             if (alreadyError.indexOf(position[k][l]) == -1) {
                $('ul.pos_error_msg').append('<li>' + position[k][l] + '</li>');
                alreadyError += position[k][l];
             }
          }
      }
   });
}