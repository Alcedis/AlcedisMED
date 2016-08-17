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

_pickerOpen = true;

$(function(){
   _pickerOpen = false;
	
   if ($('.li-del-img').size()) {
      activateDeleteBtn();
   }
});

function openPicker(type, target, multi, plain, param)
{
   if (_pickerOpen === true) {
	      return;
   }
	
   var multi   = multi || false,
       $this   = $(this),
       page    = getPageInfo(),
       param   = param || [],
       plain   = plain || false,
       primKey = page.pageName + '_id',
       $target = $(':input[name^="' + target + '"]'),
       selected = false,
       preSelection = [];
   
   _pickerOpen = true;
   
   if ($target.size() > 0 && multi) {
      $target.each(function(){
         preSelection.push($(this).attr('value'));
      });
   } else if ($target.size() > 0 && $target.val() != '') {
	   selected = $target.val(); 
   }
   
   $('<div id="p_input"><div id="p_bottom"><div id="p_content"></div></div></div>')
      .prependTo('body')
      .ready(function(){
         $.ajax({
            url      : 'index.php?page=picker.' + type + '&' + primKey + '=' + page.form_id,
            dataType : 'html',
            type     : 'post',
            data     : {
               subdir            : 'picker',
               picker            : true,
               type              : type,
               'preSelection[]'  : preSelection,
               multi             : multi,
               parentPage        : $('input[name="page"]:last').attr('value'),
               targetField       : target,
               selected			 : selected,
               params            : param
            },
            success: function( responseText, textStatus, XMLHttpRequest ){
               $('#p_content').append(responseText);

               var $pCaption 	= $('#p_caption'),
               	dialogTitle = $pCaption.html();

               $pCaption.remove();

               $('#p_input').dialog({title: dialogTitle});

               initSort('#p_input');
               initSearch();
               initPages();

               setTableHover(selected);

               $('input[name="btn_cancel"]').click(function(){
                  $('#p_input').dialog('close').remove();
               });

               if (multi) {
                  $('input[name="add_entry"]').click(function(){
                      filterList();
                  });
                  $('input[name="btn_add"]').click(function(){
                     collectPickerData(target, type);
                  });
               } else {
                  $('tr:not(.lhead,.no-data)', '.listtable').click(function(){
                     setNewSelected($('img.picker-add-user', $(this)), target, plain);
                  });
                  
                  $('tr:not(.lhead,.no-data)', '.listtable:not(.no-filter)').addClass('cursor-pointer');
               }
            },
            dataFilter: function (data) {

             checkSessionExpired(data, this);

             $('#p_input').dialog({
            	  modal: true,
            	  resizable: false,
            	  width: 940,
            	  position: ['center', 139],
            	  close: function(){
               	 	$('#p_input').remove();
               	 	_pickerOpen = false;
                  }
              });

              return data;
            }
         });
      });
}

function collectPickerData(target, type)
{
   var $checkedBoxes = $('input[name="add_entry"]:checked', '#p_input .listtable'),
       values = [];

   $checkedBoxes.each(function(index, element){
      values.push($(element).prev().attr('value'));
   });

   showSelected(target, type, values);

   $('#p_input').dialog('close');
}

function showSelected(target, type, data)
{
   var data = data || [];

   $.ajax({
      url      : 'index.php?page=picker.' + type,
      dataType : 'json',
      type     : 'post',
      data     : {
           subdir : 'picker',
           picker : true,
           type : type,
           'values[]' : data,
           getData : true,
           parentPage   : $('input[name="page"]:last').attr('value'),
           targetField  : target
      },
      success  : function( responseText, textStatus, XMLHttpRequest ) {
         var $list = $('#plist_' + target);
         $list.fadeOut(function(){
            $list.empty();
            $(responseText).each(function(index){
               var delImg = '<img class="li-del-img" src="media/img/base/editdelete.png" alt="del_img"/>';
               var tag = "<li>" + this.prefix + " " + this.nachname + ", " + this.vorname + " " + delImg + "<input type='hidden' name='" + target + "[]' value='" + this.user_id + "'/></li>";
               $list.append(tag);

               if (index == $(responseText).size()-1) {
                  $list.fadeIn();
                  activateDeleteBtn();
               }
            });
         });
      },
      dataFilter: function (data) {
         checkSessionExpired(data, this);

         return data;
      }
   });
}

function activateDeleteBtn()
{
   $('.li-del-img').click(function(){
      $element = $(this).parent(),
      $element.fadeOut(function(){
         $element.remove();
      });
   });
}

//single user select case
function setNewSelected(img, target, plain)
{
   var $img = $(img);

   if (plain) {
      var value = [];
      $img.parent().parent().children('td:gt(0)').each(function(){
         value.push($(this).text());
      });
      value = value.join(' ');
   } else {
      var value = $img.prev().attr('value');
   }

   $(':input[name="' + target + '"]').attr('value', value);
   $('#p_input').dialog('close');
}