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
   $('div.msgbox').parent().parent().hide();

   var $vorlageDD = $('select[name="vorlage_fragebogen_id"]');

   vorlageId = null;

   if ($vorlageDD.size()) {
      $vorlageDD.change(loadFragebogenTemplate).keyup(loadFragebogenTemplate);
      if (!$('.dyn_question').size())
          $('div.msgbox').parent().parent().show();
   }

});

function loadFragebogenTemplate()
{
   var $this = $(this);

   if (vorlageId == $this.attr('value'))
      return;

   vorlageId = $this.attr('value');

   if ($('tr.dyn_question', '.formtable').size() > 0 )
      $('tr.dyn_question', '.formtable').remove();

   if (vorlageId != '') {

      $('div.msgbox').parent().parent().hide();

      $.ajax({
         url      : location.href,
         dataType : 'json',
         type     : 'post',
         data     : {'ajax' : true, 'vorlage_fragebogen_id' : vorlageId},
         success  : function(data) {
            var htmlTags = '';
            $(data).each(function(index){
               htmlTags += '<tr class="dyn_question"><td class="lbl" style="width:50%">' + this.frage + '</td><td class="edt">'
                              + createSelectTag(index, this.val_min, this.val_max) +
                           '<input type="hidden" name="vorlage_fragebogen_frage_id[' + index + ']" value="'
                              + this.vorlage_fragebogen_frage_id +
                           '"/></td></tr>';
            });

            $('td.append', '.formtable').parent().after(htmlTags);
         },
         dataFilter: function (data) {
            
            checkSessionExpired(data, this);
            
            return data;
         }
      });
   } else {
      $('div.msgbox').parent().parent().show();
   }
}

function createSelectTag(id, min, max)
{
   var _return = '<select class="input" name="antwort[' + id + ']"><option value="">&nbsp;</option>';

   for(var a = parseInt(min); a <= parseInt(max); a++) {
      _return += '<option value="' + a + '">' + a + '</option>';
   }

   return _return += '</select>';
}