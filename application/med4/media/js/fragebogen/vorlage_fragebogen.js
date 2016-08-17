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

$(document).ready(function(){

  // bind the function to add a new questions and submit the form

  $('#add_question').click( addQuestionField );

  $('img.img_del_btn:last-child').bind('click',removeQuestionField);

   jsValidator();

});

// adds a new question-definiton block

function addQuestionField(){

     $('#append tbody').append(' <tr><td class="lbl" style="width:15%;">Fragestellung<span style="color: red;">*</span></td>' +
                         '     <td class="edt" align="center"><input style="width:460px;" class="input" name="question[frage][]" type="text"/></td>' +
                         '     <td class="edt" align="center" style="width:20%;"><input class="input" name="question[min][]" type="text" size="1" maxlength="3"> min <input class="input" name="question[max][]" type="text" size="1" maxlength="3"/> max</td>' +
                         '     <td class="edt" align="center" style="width:10%;"><img class="img_del_btn" src="media/img/base/btn_code_reset.png" style="cursor:pointer;" alt="Entfernen"/></td>' +
                         '   </tr>');

  $('img.img_del_btn:last-child').bind('click',removeQuestionField);

}

// removes a questions-definition block

function removeQuestionField(){
  $(this).parent().parent().remove();
}

// function to check and validate every input field

function jsValidator(){

  $('input:not(input[name="bez"])').each(function(){

     if($(this).attr('value') == '' )   {

        $(this).addClass('err_input');

     }

     if($(this).attr('name') == 'question[min][]' && isNaN($(this).attr('value') ) ){
        $(this).addClass('err_input');
     }

     if($(this).attr('name') == 'question[max][]' && isNaN($(this).attr('value') ) ){
        $(this).addClass('err_input');
     }

     if($(this).attr('name') == 'question[min][]' && parseInt($(this).attr('value')) >= parseInt($(this).next().attr('value')) ){

           $(this).addClass('err_input');

     }
  });
}

function getGETParameter(name){

  name        = name.replace(/[\[]/,'\\\[').replace(/[\]]/,'\\\]');
  var regexS  = '[\\?&]'+ name +'=([^&#]*)';
  var regex   = new RegExp(regexS);
  var results = regex.exec(window.location.href);

  if(results !== null)
      return results[1];

}
