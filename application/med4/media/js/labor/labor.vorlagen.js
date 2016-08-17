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

   _laborVorlageId = null;

   allocateData();

   $(':input[name="vorlage_labor_id"]')
      .change(loadLabValues)
      .keyup(loadLabValues);
});

function loadLabValues()
{
   var $this = $(this);
   if (_laborVorlageId == $this.attr('value'))
      return;
   _laborVorlageId = $this.attr('value');

   $('table.append').empty();
   $('table.append').parent().parent().show();
   $('div.msgbox').parent().parent().hide();

   if (_laborVorlageId != '') {
      $.ajax({
         url      : location.href,
         dataType : 'html',
         type     : 'post',
         data     : {'ajax' : true, 'vorlage_labor_id' : _laborVorlageId},
         success  : function(data) {
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
        	 $(':input[name$="[' + name + '][]"]', $identifiedTr).val((value != null ? value : ''));
         });
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