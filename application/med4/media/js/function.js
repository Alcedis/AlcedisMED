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

/*
 *
 * Funktionen im ->
 *
 * Hauptformular:
 *					get_page_info        (ajax)                                                - Übergibt Name und Pfad(ab der index) des derzeitigen Forms
 *             find_lists           ()                                                    - Findet alle Pos-Listen im Hauptformular
 *             show_list            (list_array)                                          - Verarbeitet Listen (Listen müssen ein array sein)
 *                                                                                          lädt ausserdem das Template
 *             get_specific_template(name, source)                                        - bezieht Eintrag aus dem Template
 *             initialize_list      (name, source)                                        - Steuert den Fadingeffekt (what to do)
 *             build_list           (name, source)                                        - erstellt die Listen (noch kein Einbau)
 *             get_pos_content      (field, template, posform, pos_id)                    - Entscheidet ob Button oder Content
 *             insert_pos_content   (name, list_content, template)                        - Fügt die Listen ins Hauptformular ein
 *             execute_request      (button, target_page, dropdown, parm1, parm2, parm3, parm4)
 *                                                                                        - Aufruf zum laden des Posformulars
 *             open_pos_form        (button, location, parameter)                         - Positioniert und öffnet das Posformular
 *             close_pos_form       ()                                                    - Schliesst das Posformular
 *             activate_buttons     (button, action, location)                            - Initialisert Vorgänge beim drücken vom Edit und Delete Button
 *                                                                                          im Hauptformular
 * Posformular:
 *             collect_data         ()                                                    - Sammelt Daten und verkettet sie zu einem fast fertigen JSON
 *                                                                                          String
 *             validate_data        (action)                                              - Schickt eine Validierungsanfrage
 *             remove_err_warn      ()                                                    - Löscht alle Error und Warnmessages
 *             throw_errors         (data)                                                - zeigt dem User Fehler an, sobald die Validierung sich meldet
 *             check_open_error     (src_obj, type)                                       - Checkt beim Klicken auf ein Inputfeld oder die Err Lampe ob sich
 *                                                                                          in dem Bereich schon error Messages befinden und löscht diese dann
 *             open_msg             (feld, msg, type, mode)                               - Erweitert die Fehlermeldungen um die Popups
 *
 * Hilfsfunktionen:
 *             prevent_doubleclick       ()                                               - verhindert den Doppelklick (einfache Methode(div über die gesammte Seite))
 *             undo_prevent_doubleclick  ()                                               - entfernt den Schutz für das Doppelklicken
 *             $.evalJSON                (src)                                            - erstellt einen JSON String
 *             get_browser_version       ()                                               - gibt die aktuelle Browserversion aus
 *             check_data_exist          (object)                                         - Schaut ob Daten in einer Posliste vorhanden sind / gibt "true" zurück wenn Daten vorhanden sind
 *             getPageSize               ()                                               - übergibt die aktuelle Fenstergröße des Browsers usw.
 *             getScrollXY               ()                                               - Findet die derzeitge Position des Browserfenster heraus. (Abstand oben/links)
 *
 * Formularspezifische Funktionen:
 *             check_karzi               ()                                               - Beim drücken das "Hinzufügen" Buttons im Anamneseformular,
 *                                                                                          wird der Radio Button "familien_karzinom" automatisch auf true gesetzt
 *
 * -------------------------------------------------------------
 */

function str_replace(search, replace, subject) {
	return subject.split(search).join(replace);
}

function strpos (haystack, needle, offset) {
   var i = (haystack+'').indexOf(needle, (offset || 0));
   return i === -1 ? false : i;
 }



var posFormOpened = false;

function getPageInfo(ajax)
{
   ajax = ajax || false;

   var location     = 'index.php',
       par_page_val = $("input[name='page']").attr('value'),
       pageName     = par_page_val.replace(/rec.|list.|view./, ''),
       page_id      = pageName + '_id',
       form_id      = $("input[name='" + page_id + "']").attr('value'),
       dlist_param  = $("input[name='dlist_param']").attr('value'),
       primaryKey   = '&' + page_id + '=' + form_id;

   if (typeof dlist_param != "undefined"){
	   primaryKey += '&dlist_param=' + dlist_param;
   }

   var parent_page   = location + '?page=' + par_page_val  + primaryKey;
   var baseHref	     = window.location.protocol + '//' +  window.location.hostname + window.location.pathname;


   var info = {
	  page     : par_page_val,
	  parent   : parent_page,
	  pageName : pageName,
      form_id  : form_id,
      baseHref : str_replace('index.php', '', baseHref)
   };

   if (ajax === true) {
      var ajaxPage = $(':input[name="page"]', $('div.d_frame')).val(),
	      ajaxPage = location + '?page=' + ajaxPage;

	  info.ajax = ajaxPage;
   }

   return info;
};



function get_specific_template(name, source)
{
   var template = new Array();

   $.each(source, function(index, typ){
      if(name == source[index]['name']){
         template = source[index];
      }
   });
   return template;
};




function show_list(listName)
{
   var pageInfo = getPageInfo();

   $.get(pageInfo.parent, {ajax: true, list_template: true}, function(settings){
 	     initialize_list(listName, settings);
   }, "json");
};


function initialize_list(name, source)
{
	//Initialisieren
   if ($('#dlist_'+ name + ' table tbody').length == 1){
	   $('table.dlisttable', '#dlist_'+ name).fadeOut('fast', function(){
		   $('table.dlisttable', '#dlist_'+ name).remove();
		   $('div.dlistloading', '#dlist_'+ name).fadeIn('fast', function(){
			   build_list(name, source);
		   });
      });
   } else {
	  //erster aufruf
      $('#dlist_'+ name).append('<div class="dlistloading"><!-- --></div>');
      build_list(name, source);
   }
};


function build_list( name , source )
{
   var template      =  get_specific_template(name, source);
       pull          =  $.evalJSON('{ajax: true, show_dlist: "' + name + '"}'),
       pageInfo      =  getPageInfo(),
       fixed_width   =  40;

   $('#dlist_'+ name).append('<table class="dlisttable"><thead><tr></tr></thead><tbody></tbody></table>');

   $('table.dlisttable', '#dlist_'+ name).hide();

    //Header bauen
    $.each(template['head_content'], function(index, content){
        var headTags = content.tag || 'class="subhead"';

        $('#dlist_'+ name + ' thead tr').append('<td ' + headTags + '>' + content.label + '</td>');
    });

    //Body bauen
    $.get(pageInfo.parent, pull ,function(pos_data){
        if(pos_data.no_ajax_data){
            var tds = $('#dlist_'+ name + ' table thead tr td').size();

            $('#dlist_'+ name + ' tbody').append('<tr><td colspan="99" class="edt" ><span class="no_data">' + pos_data.no_ajax_data + '</span></td></tr>');
        }else {
            insert_pos_content(name, pos_data, template);

            $('#dlist_'+ name + ' table tbody tr:odd') .addClass('odd');
      	    $('#dlist_'+ name + ' table tbody tr:even').addClass('even');
        }

        $('div.dlistloading' ,'#dlist_'+ name).fadeOut('fast', function(){
            $('table.dlisttable', '#dlist_'+ name).fadeIn('fast');
        });

        initDeleteDialog();
    },"json");
};

function get_pos_content(field, template, posform, pos_id)
{
   var target_page = 'index.php' + template['target_page'];

   switch(field){
      case 'BTN_EDT':
         var content = '<img src="media/img/base/edit.png" onclick="activate_buttons(this, \'edit\',\''+ target_page +'\')" class="dlistbutton" name="' + pos_id  +'">';
      break;

      case 'BTN_DELETE':
         var content = '<img src="media/img/base/btn_code_reset.png"' +
         'onclick="openDeleteDialog(this, function(){ activate_buttons(\'' + pos_id +
         '\', \'delete\', \'' + target_page + '\')}, true, true)" class="dlistbutton btndelete" name="' + pos_id +'">';
      break;
      default:
    	 try {
    		 var content = eval('posform.' + field);

    		 if (content === undefined) {
    			 content = field;
    		 }
    	 } catch (e) {
    		 var content = field;
    	 }

      break;
   }
   return content;
};

function insert_pos_content(name, list_content, template)
{
    $.each(list_content, function(index, posform){
        var
            kind_id = 'sess_pos_' + posform.sess_pos,
            tr      = $('<tr/>')
        ;

        $.each(template['body_content'], function(bi, column){
            var
                multiple = typeof column.field == 'object',
                add = column.add || '',
                columnStyle = column.style || 'padding:8px',
                columnTags  = column.tag || ''
            ;

            if (multiple == true) {
                var
                    columnContent = [],
                    separator = column.separator || ''
                ;

                $.each(column.field, function(cfi, columnField) {
                    var tmpColumnContent = get_pos_content(columnField, template, posform, kind_id);

                    if (tmpColumnContent.length > 0){
                        columnContent.push(tmpColumnContent + add);
                    }
                });

                tr.append('<td ' + columnTags + ' style="' + columnStyle + '">' + columnContent.join(' ' + separator + ' ') + '</td>');
            } else {
                var columnContent = get_pos_content(column.field, template, posform, kind_id);

                if (column.field == 'BTN_EDT' || column.field == 'BTN_DELETE' ){
                    tr.append('<td width="45px"' + columnTags + ' style="' + columnStyle + '">' + columnContent + '</td>');
                } else {
                    tr.append('<td ' + columnTags + ' style="' + columnStyle + '">' + columnContent + add + '</td>');
                }
            }
      });

      $('#dlist_'+ name + ' tbody').append(tr);
   });

   return true;
};

function execute_request( button, target_page, dropdown, params)
{
   var location      = 'index.php?ajax=true&page=' + target_page;

   if (dropdown != null ) {
      location = location + "&preselected_value=" + $("#content-center :input[name=" + dropdown +"]").attr('value');
   }

   $(params).each(function(i, param) {
      var paramValue = $(':input[name="' + param + '"]').val();

      if (paramValue.length != 0) {
    	  location = location + "&" + param + "=" + paramValue;
      }
   });

   open_pos_form($(button), location, null, $(button).parents('.dlist:first').hasClass('scroll'));
};

function open_pos_form( button, location, parameter, fixedHeight )
{
   if (posFormOpened == true) {
	   return false;
   }

   posFormOpened = true;

   $('#d_input').remove();

   var but_position  =  button.position(),
       fixedHeight   = fixedHeight || false,
       dialogHeight = (($(window).height() - 100) < 300 ? 300 : ($(window).height() - 100)),
       scrollContentHeight = dialogHeight - 120;

   $('<div id="d_input"><div id="d_bottom"><div id="d_content"></div></div></div>')
   	  .prependTo('body')
   	  .ready(function(){

   		 $.ajax({
             type: "POST",
             url: location,
             data: parameter,
             dataType: "html",
             timeout: 25000,
             error: function( XMLHttpRequest, textStatus, errorThrown ){

	   			$('#d_input').dialog({
	      		  bgiframe: true,
	    			  modal: true,
	    			  resizable: false,
	    			  height: fixedHeight ? dialogHeight : 'auto',
	    			  width: 940,
	    			  title: 'Verbindungsfehler',
	    			  open : function(){
	      	         hideDD('#d_input');
	      	      },
	    			  closeOnEscape : false,
	    			  position: ['center', (fixedHeight ? 50 : 130)],
	    			  close: function(){
	      	        posFormOpened = false;
	      	    	showDD();
	          	 	$('#d_input').remove();
	           	  }
	   			});

   			 $('#d_content').append('<div id="transfer_error"></div>');

                //Fehler erstellen
                $('#transfer_error').append('<div id="transfer_error_content" style="display:none; width:100%; text-align:center">' +
                '<br/>Es ist ein Verbindungsfehler aufgetreten.<br/><br/>' +
                '<input class="button" type="button" value="Wiederholen" name="again"/>' +
                '<input style="margin-left:7px" class="button" type="button" alt="Abbrechen" value="Abbrechen" name="cancel"/>' +
                '</div>')
                .ready( function(){
                   $('#transfer_error_content').fadeIn('normal', function(){
                      $(':button').click(function(){
                         switch($(this).attr('name')){
                            case 'cancel':
                            	$('#d_input').dialog("close");
                            break;
                            case 'again':
                               $("#d_content :button").attr({disabled: "disabled"});
                               $('#d_input').fadeOut('fast', function(){
                                  $('#d_input').dialog('close');
                                  posFormOpened = false;
                                  open_pos_form( button, location, parameter );
                               });
                            break;
                         }
                      });
                   });
                });
             },
             success: function( responseText, textStatus, XMLHttpRequest ){
                $('#d_content').append(responseText).ready(function(){

                	loadDatepicker(this);

                    $(".tabs").tabs({
               	   	   cache: true
               	    });

                    highlightInterfaceFields($('select[name="feature_interface"]').val(), '#d_input');

               	    $.getScript('media/js/picker/picker.js');

                    $("#d_content table:last tr td")
                    .append('<div style="margin-top:2px; height:19px"> <div class=\"validate_data\"></div> </div>')
                    .append('<div id="transfer_error"></div>');

                    $.getScript('media/js/dlist/ajax_pos_content.js');

                    var $dCaption 	= $('#d_caption'),
                    	dialogTitle = $dCaption.html();

                    $dCaption.remove();

                    $('#d_input').dialog({title: dialogTitle});

                    if (fixedHeight) {
                        var $buttons = $('.ajax-button-table', '#d_input').clone();
                        $('.ajax-button-table', '#d_input').remove();
                        $('#d_input').addClass('d_input-scroll');
                        $('.d_frame:first', '#d_input')
                            .css('height', scrollContentHeight)
                            .addClass('d_frame-scroll')
                            .after($buttons);
                        $('.d-frame-content').css('width', '908px');
                        $('.ajax-button-table').css('height', '55px');

                    }
                });
             },
             dataFilter: function (data) {
                checkSessionExpired(data, this);

            	 $('#d_input').dialog({
            		  bgiframe: true,
          			  modal: true,
          			  resizable: false,
          			  height: fixedHeight ? dialogHeight : 'auto',
          			  width: 940,
          			  open : function(){
            	         hideDD('#d_input');
            	      },
          			  closeOnEscape : false,
          			  position: ['center', (fixedHeight ? 50 : 130)],
          			  close: function(){
            	        posFormOpened = false;
            	    	showDD();
                	 	$('#d_input').remove();
                 	  }
          		  });

                 return data;
             }
          });
   });
};

//Im Falle von Edit oder Delete
function activate_buttons(button, action, location)
{
   //prevent_doubleclick(action);

   if (action === 'delete') {
	   button = 'img[name="' + button + '"][class*="btndelete"]';
   }

   var button        = $(button);
   var typ_id        = button.attr('name').substr(9);
   var seperator     = typ_id.indexOf('_');
   var str_length    = typ_id.length;
   var fixedHeight   = button.parents('.dlist:first').hasClass('scroll');

   var typ           = typ_id.substr(0, seperator);
   var id            = typ_id.substr((seperator +1), str_length);

   switch (action){
      case 'edit':
         var parameter = $.evalJSON('{ajax: true, "sess_pos":"'+ typ+ '_' + id + '"}');
         open_pos_form(button, location, parameter, fixedHeight);
      break;

      case 'delete':
        $('#dlist_' + typ + ' img').fadeOut('normal');

         var valid_suc      = 1;
         var pos2del        = '"pos_delete" : "' + typ_id + '"';

         eval_json = $.evalJSON('{ajax: true, action:"delete", "ajax_valid_success": '+ valid_suc +',' + pos2del + '}');

         //Aktualisieren der pos tables
         $.get( location, eval_json, function(){
            var postyp = [typ];

            show_list(postyp);
         });
      break;
   }
   return true;
};

//******************************************************************************************
//******************************************************************************************
//Ab hier kommen alle Funktionen die für das Unterformular wichtig sind


//*******************************************************************************************
//******************************************************************************************/

function collect_data(target)
{
	var target = target || '.d_frame';
	var json_post = '{';

	$(target + ' :input:not(.button)').each(function(i) {
      if ($(this).attr('type') == 'checkbox'){
         //Checkbox wird standardmäßig mit value="1" aktiviert. Hier wird überprüft ob die Checkbox gecheckt ist
         if($(this).is(':checked')){
            json_post = json_post + ' "' + $(this).attr('name')+ '" : "' +  $(this).attr('value') + '",';
         }
      }else if ($(this).attr('type') == 'radio'){                                                                    //#2#
         if($(this).is(':checked')){
            json_post = json_post + ' "' + $(this).attr('name')+ '" : "' +  $(this).attr('value') + '",';
         }
      }else{
         //Standard
         json_post = json_post + ' "' + $(this).attr('name')+ '" : "' +  escape($(this).attr('value')) + '",';
      }
   });

   return json_post;
};

function validate_data(action)
{
   var pageInfo = getPageInfo(true);

   $(".d_frame :button").attr({disabled: "disabled"});

   //Show loading bar
   $('.validate_data').fadeIn();

   var json_post  = collect_data();
   eval_json      = $.evalJSON( json_post + '"action":"' + action + '", ajax: true }');

   $.ajax({
            type: "POST",
            url: pageInfo.ajax,
            data: eval_json,
            dataType: "json",
            timeout: 25000,
            error: function( XMLHttpRequest, textStatus, errorThrown ){
               $('.validate_data').fadeOut();

               //Div erstellen
               $('#transfer_error').append('<div id="transfer_error_content" style="display:none; padding-bottom:15px;">'          +
                                            'Es ist ein Verbindungsfehler aufgetreten.<br/><br/>'                                   +
                                            '<input class="button" type="button" value="Wiederholen" name="again"/>'                +
                                            '<input style="margin-left:7px" class="button" type="button" alt="Abbrechen" value="Abbrechen" name="cancel"/>' +
                                            '</div>')
                                    .ready( function(){
                                       $('#transfer_error_content').fadeIn();

                                       $(':button').click(function(){
                                          switch($(this).attr('name')){
                                             case 'cancel':
                                            	 $('#d_input').dialog("close");
                                             break;
                                             case 'again':
                                                $("#content :button").attr({disabled: "disabled"});

                                                $('#transfer_error_content').fadeOut('fast', function(){
                                                   $('#transfer_error_content').remove();
                                                   validate_data(action);
                                                });
                                             break;
                                          }
                                       });
                                    });
            },
            success: function( data ){
               if( data[0] == 'success' ){
            	  $('.validate_data').hide();
            	  $('#d_input').dialog("close");

            	  var postyp = [data[1]];

            	  //Flash Konferenz workaround
            	  if (pageInfo.pageName == 'konferenz') {
            		  var flashkonferenz = document.getElementById("flashkonferenz");

            		  flashkonferenz.dataUpdated(eval_json.konferenz_patient_id);
            	  }

            	  show_list(postyp);
               }else{
            	   $('.validate_data').fadeOut();
            	   $(".d_frame :button").removeAttr("disabled");

            	   throw_errors(data);
               }
            }
   });
};

function remove_err_warn() //Beim schliessen des Dokuments / Beim Validieren des Dokuments
{
   $("span[id^='button_err_']") .remove();
   $('.d_frame .ajax_error_edt').removeClass().addClass('edt');
   $('.d_frame .ajax_warn_edt') .removeClass().addClass('edt');

   return true;
};



function throw_errors(data)
{
   $.each(data , function(feld, errors)
   {
      var err_obj = $("#d_input .formtable *[name=" + feld + "]");

      switch(errors.type){
         case 'err':
            $(err_obj).each(function(index, unique_err_ob) {

            	var indexed_err = feld + '_' + index;

            	var msg = $('<span/>').html(errors.msg).text(),
            	msg = $(msg).children('li').size() ? $('li', msg).html() : msg;

	            $(unique_err_ob).after(
	               "<span id='button_err_" + indexed_err + "' class='bubbleTrigger'>" +
				      "<button class='trigger trigger-err' type='button'><!-- --></button>" +
			            "<div class='bubbleInfo border-err'>" +
			               "<div style='min-width:100px;max-width:325px;float:left;'><img alt='Error' src='./media/img/base/editdelete.png'>" + msg +
				      "</div></div>" +
				   "</span>"
			     );

		        //Hintergrund einfärben
		        err_obj.closest("td").addClass('ajax_error_edt');
            });
         break;
      }
   });

   loadBubbles($("#d_input"));

   return true;
};


function check_open_error(src_obj, type)
{
   switch(type){
      case 'input':
         if(typeof src_obj == 'object'){
            var src_obj = $(src_obj).attr('name');

            $("span[id^='button_err_']", $('#button_err_' + src_obj).parent()).each(function() {
               var to_check   = $(this).attr('id');

               if($('#msg_err_' + to_check ).length != 0){
                  $('#msg_err_' + to_check ).fadeOut('normal', function(){
                      $('#msg_err_' + to_check).remove();
                  });
               }
            });
         }
      break;

      case 'message':
         $("span[id^='button_err_']", $('#' + src_obj).parent()).each(function() {
             var to_check   = $(this).attr('id');

               if($('#msg_err_' + to_check ).length != 0){
                  $('#msg_err_' + to_check ).fadeOut('normal', function(){
                      $('#msg_err_' + to_check).remove();
                  });
               }
         });
      break;
   }
   return true;
};

//*******************************************************************************************
//******************************************************************************************/
function prevent_doubleclick( action )
{
   if(action != 'delete'){
      $(':button').attr('disabled', 'disabled');
      var arrayPageSize = getPageSize();
      var blur_style= 'width:' + (arrayPageSize[0]) + 'px;' + 'height:'+ ( 2 * arrayPageSize[1] ) + 'px';
      var browserv   = get_browser_version();

      if($('#double_safe').length == 0){
         $('<div>').attr('id',    'double_safe').attr('style',  blur_style ).addClass("double_safe").appendTo('body');
      }

      if($('#window_blur').length == 0){
         //Internet Explorer
         if (browserv[0] == "Microsoft Internet Explorer"){
            $('<div>').attr('id',    'window_blur')
            .attr('style',  blur_style )
            .addClass("window_blur_ie")
            .appendTo('body');

            if(browserv[1] == '6'){
               $('.table_main select').hide();
               $('#pos_input select').show();
            }
         }else{
            $('<div>').attr('id',    'window_blur')
            .attr('style',  blur_style )
            .addClass("window_blur")
            .appendTo('body')
            .fadeIn('fast');
         }
      }
   }else{
      $(':button').attr('disabled', 'disabled');
      var arrayPageSize = getPageSize();
      var blur_style    = 'width:' + (arrayPageSize[0]) + 'px;' + 'height:'+ ( 2 * arrayPageSize[1] ) + 'px';

      if($('#double_safe').length == 0){
         $('<div>').attr('id',    'double_safe').attr('style',  blur_style ).addClass("double_safe").appendTo('body');
      }
  }

  return true;
};


function undo_prevent_doubleclick(){
   $('#double_safe') .remove();
   $(':button').removeAttr('disabled');

   return true;
};

$.evalJSON = function(src)
{
   return eval("(" + src + ")");
};


function get_browser_version(){
   var browserName = navigator.appName;
   var browserv    = navigator.appVersion;
   var version     = '';

   if(browserName == "Microsoft Internet Explorer"){
     var gefunden_an_pos = (browserv.indexOf("MSIE") + 5);
         version         =  browserv.slice(gefunden_an_pos, (gefunden_an_pos + 1 ));
   }

   var information = [ browserName, version ];

   return information;
};


function check_data_exist(object){
   if($("#" + object + " span[class^='no_data']").length)
   {
      return false;
   }

   return true;
};


function getPageSize()
{
   var xScroll, yScroll;

	if (window.innerHeight && window.scrollMaxY) {
		xScroll = window.innerWidth + window.scrollMaxX;
		yScroll = window.innerHeight + window.scrollMaxY;
	} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
		xScroll = document.body.scrollWidth;
		yScroll = document.body.scrollHeight;
	} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
		xScroll = document.body.offsetWidth;
		yScroll = document.body.offsetHeight;
	}

	var windowWidth, windowHeight;

	if (self.innerHeight) {	// all except Explorer
		if(document.documentElement.clientWidth){
			windowWidth = document.documentElement.clientWidth;
		} else {
			windowWidth = self.innerWidth;
		}
		windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
		windowWidth = document.documentElement.clientWidth;
		windowHeight = document.documentElement.clientHeight;
	} else if (document.body) { // other Explorers
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}

	// for small pages with total height less then height of the viewport
	if(yScroll < windowHeight){
		pageHeight = windowHeight;
	}else{
		pageHeight = yScroll;
	}

	// for small pages with total width less then width of the viewport
	if(xScroll < windowWidth){
		pageWidth = xScroll;
	}else{
		pageWidth = windowWidth;
	}

   return [pageWidth, pageHeight, windowHeight];
};

function getScrollXY() {               //#3.1#
  var scrOfX = 0, scrOfY = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    //Netscape compliant
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    //DOM compliant
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    //IE6 standards compliant mode
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return [ scrOfX, scrOfY ];
};

function getParameter(paramName) {
  var searchString = window.location.search.substring(1),
      i, val, params = searchString.split("&");

  for (i=0;i<params.length;i++) {
    val = params[i].split("=");
    if (val[0] == paramName) {
      return unescape(val[1]);
    }
  }
  return null;
}



//******************************************************************************************
//******************************************************************************************
//Formularspezifische Funktionen

//******************************************************************************************/
//für das Anamnesefomular in MED
//Feature
function check_karzi()
{
   $("input[name='familien_karzinom'][value='t']").attr('checked','checked');

   return true;
};
