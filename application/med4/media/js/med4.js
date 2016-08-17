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

_viewPatientLoad = true;

$(function() {

    warnSlider();

    var $form = $('#content form'),
        pageInfo = getPageInfo();

    ajaxWaitNotification();

	//DListen
    $('div.dlist', $form).each(function(){
         show_list($(this).attr('id').substr(6));
    });


    if ($('table.listtable:not(.no-filter)').size() > 0 ) {
        if ($('table.bfl').size() > 0 ) {
            $.getScript('media/js/bfl.js');
        } else {
            initSort();
            initPages();
            loadCookieFilter(applyFilter);
            initSearch();
        }
    }

    initGlobalTableHover();

    if ($('#sidebar:not(.bfl)').size() > 0) {
        initFormFilter();
    }

    impressLink();
    popupHover();
    blankLinkTarget();

   //laborvorlagen
   if (pageInfo.page == 'rec.labor')
      $.getScript('media/js/labor/labor.vorlagen.js');

   if (pageInfo.page == 'rec.histologie')
      $.getScript('media/js/histologie/histologie.js');

   //report
   if (pageInfo.page == 'report' || pageInfo.page == 'list.konferenz' || pageInfo.page == 'list.konferenz_patient' || pageInfo.page == 'list.zweitmeinung' || pageInfo.page == 'rec.konferenz_archiv') {
      $.getScript('media/js/report/report.js');
   }

   //wirkstoffgabe
   if (pageInfo.page == 'rec.therapie_systemisch_zyklustag')
      $.getScript('media/js/therapie/therapie.wirkstoff.js');

  if (pageInfo.page == 'rec.settings')
      $.getScript('media/js/settings/settings.js');

   //fragebogen vorlagen
   if (pageInfo.page == 'rec.vorlage_fragebogen')
      $.getScript('media/js/fragebogen/vorlage_fragebogen.js');

   if (pageInfo.page == 'rec.fragebogen')
      $.getScript('media/js/fragebogen/fragebogen.js');

   preventDoubleSave();
   initDeleteDialog();
   initConfirmDialog();

   imgPreload();

   loadDatepicker();

   if ($('img[alt^="picker-"]').size() > 0) {
      $.getScript('media/js/picker/picker.js');
   }

   //table align & style
   $('td.lbl:first', 'table:not(.no-align).formtable tbody tr').css('width','35%');
   $('td.edt:last', 'table.inline-table tbody tr').css('borderRight','0px');
   $('tr:last', '.inline-table').css('borderBottom', '0px');
   $(':input[name="bem"]').parent('td').attr('align', 'center');

	if(pageInfo.page == 'auswertungen') {
	  var cook = loadCookieFilter(false),
          accActive = (cook === undefined) ? 0 : cook;
	}
	if(pageInfo.page == 'status') {
	  var cook = loadCookieFilter(false),
          accActive = (cook === undefined) ? 0 : cook;
	}

   //Accordion of the standard list
   $( "#accordion" ).accordion({
	   autoHeight: false,
	   collapsible: true,
	   active: accActive
   });

   $( "#tabs" ).tabs({
	   cache: true
   });

   $('#accordion').click(function() {
      filterList('');
   });

   $('img','.show-interface-list').mouseenter(function(){
	  $(this).next().show();
   });
   $('img','.show-interface-list').mouseleave(function(event){
	   $(this).next().hide();
   });

   if ($('.role-info-box').size()) {
       $('.role-info-box').hide();
       $('.role-info-img').mouseenter(function(){
           var infoBoxPos = $('.role-info-img').position();
           $('.role-info-box').css({
               'top'  : infoBoxPos.top-1,
               'left' : infoBoxPos.left-135
           });
           $('.role-info-box').show();
       });
       $('.role-info-box').mouseleave(function(){
           $(this).hide();
       });
   }

   //prevent submit on enter in input field
   if (pageInfo.page != 'login') {
       $('input', 'form').keypress(function(e){
           return (e.keyCode || e.which || e.charCode || 0) !== 13;
          }
       );
    }

   if($('select[name="feature_interface"]').length > 0) {

       _currentInterface = null;

      if($('select[name="feature_interface"]').val() == '') {
         loadCookieFilter(false);

         $('select[name="feature_interface"]').keyup(initHighlightCookie).change(initHighlightCookie);
      } else {
         $('select[name="feature_interface"]').change(initHighlight).keyup(initHighlight);

	      $('select[name="feature_interface"]').trigger('change');
      }
   }

   $('img.sort-img').each(function(index, element){
       $(element).attr('alt', index);
   });

   if(pageInfo.page == 'view.patient') {
	  _viewPatientLoad = false;

      var filterListFoto = getCookieValue("filter");

      if (filterListFoto) {
         try {
            var objList = JSON.parse(filterListFoto);
         } catch (e) {
            var objList = {};
         }

         var cookieId = $('input[name="cookie_id"]').attr('value'),
		       curPage = getPageInfo().page;

		   if (objList[cookieId])
		      if (objList[cookieId][curPage])
		         if (objList[cookieId][curPage].list)
		            var listValue = objList[cookieId][curPage].list;

		   var listValue = listValue || 'table';

		   loadPatientViewer(listValue);
      } else {
	      loadPatientViewer();
      }
   }
});

function isTreeBfl() {
   return ( $('table.listtable.bflsub-tree').size() == 1);
}


function checkSessionExpired(data, ajaxRequest) {

   try {
      var objList = JSON.parse(data);

      if (objList['session_expired']) {
         window.location = "index.php?page=login&state=nopassed";

         ajaxRequest.success  = function(){};
         ajaxRequest.error    = function(){};
      }
   } catch (e) {
   }
}


function loadDatepicker(scope) {

	scope = scope || 'body';

	$('input.datepicker', scope).datepicker({
		showOn: "button",
		buttonImage: "media/img/base/date.png",
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		yearRange: "1900:2025"
   });
}


//This function polls the system for the information about the current protocoll situation
function activateProtocollPolling(pageName) {

	var protokoll = '';

	$('input[name^="action[report]"]').each(function(){
		var id = $(this).attr('name').replace(/action\[report\]\[/, "").replace(/\]/, "");

		protokoll += protokoll.length > 0 ? '~' + id : id;
	});

	if (protokoll.length > 0) {

		$.ajax({
	        type: "POST",
	        url: 'index.php?page=protokoll_info&feature=konferenz',
	        data: {
			  		page_name: pageName,
			  		protokoll: protokoll
		  	},
	        dataType: "json",
	        timeout: 25000,
	        success: function(responseText, textStatus, XMLHttpRequest) {

		  		$.each(responseText, function(){
		  			var fieldId 		= this.id,
		  				fieldStatus 	= this.status,
		  				$printer    	= $('input[name="action[report][' + fieldId  + ']"]'),
		  				$waiting    	= $('#waiting-' + fieldId),
		  				$unavailable    = $('#unavailable-' + fieldId);

		  			switch (fieldStatus) {
		  				case 'waiting':

		  					if ($printer.hasClass('visible') == true) {
		  						$printer.fadeOut('normal', function(){
		  							$printer.removeClass('visible');
		  							$waiting.addClass('visible').fadeIn('fast');
		  						});
		  					} else if ($unavailable.hasClass('visible') == true) {
		  						$unavailable.fadeOut('normal', function(){
		  							$unavailable.removeClass('visible');
		  							$waiting.addClass('visible').fadeIn('fast');
		  						});
		  					}

		  					break;

		  				case 'unavailable':

		  					if ($printer.hasClass('visible') == true) {
		  						$printer.fadeOut('normal', function(){
		  							$printer.removeClass('visible');
		  							$unavailable.addClass('visible').fadeIn('fast');
		  						});
		  					} else if ($waiting.hasClass('visible') == true) {
		  						$waiting.fadeOut('normal', function(){
		  							$waiting.removeClass('visible');
		  							$unavailable.addClass('visible').fadeIn('fast');
		  						});
		  					}

		  					break;

		  				case 'finished':

		  					if ($unavailable.hasClass('visible') == true) {
		  						$unavailable.fadeOut('normal', function(){
		  							$unavailable.removeClass('visible');
		  							$printer.addClass('visible').fadeIn('fast');
		  						});
		  					} else if ($waiting.hasClass('visible') == true) {
		  						$waiting.fadeOut('normal', function(){
		  							$waiting.removeClass('visible');
		  							$printer.addClass('visible').fadeIn('fast');
		  						});
		  					}

		  					break;
		  			}
		  		});

		  		var poll = "activateProtocollPolling('" + pageName + "')";

		  		setTimeout(poll, 7500);
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown) {}
		});
	}
}



function initHighlight(){
    var selected = $(this).val();
    if (_currentInterface != selected) {
        _currentInterface = selected;
        highlightInterfaceFields(selected, 'form');
    }
}

function initHighlightCookie(){
    var selected = $(this).val();
    if (_currentInterface != selected) {
        _currentInterface = selected;
        highlightInterfaceFields(selected, 'form');
        var newCookieArr = createFilterCookie(true, getPageInfo().page, true);
        document.cookie = (newCookieArr && newCookieArr.length > 0) ? ('filter=' + newCookieArr + ';') : ('filter=""; expires=Thu, 01-Jan-70 00:00:01 GMT;');
    }
}


function highlightInterfaceFields(selector, scope) {

	selector = selector || null;

	$('.active_interface', scope).removeClass('active_interface');
	$('.active_interface-chkbx', scope).replaceWith($('.active_interface-chkbx').html());


	if (selector !== null) {
		$(':input[type!="checkbox"].i_' + selector, scope).addClass('active_interface');

		$('input[type="checkbox"].i_' + selector, scope).wrap('<div class="active_interface-chkbx"></div>');
	}
}


function initConfirmDialog() {
	$('input.btnconfirm', '#content-center').unbind('click').click(function(event){
		openConfirmDialog($(this));

		event.preventDefault();
	});
}

function initDeleteDialog() {
	$('input.btndelete', '#content-center').unbind('click').click(function(event){
		openDeleteDialog($(this));

		event.preventDefault();
	});
}

function openConfirmDialog($element, callback, ajax, posdelete) {
	if (posFormOpened == true) {
		   return false;
	   }

	posFormOpened = true;

	$('#confirm_dialog').remove();

	var elementName = $element.attr('name'),
	button = elementName.substr(7).replace(/]/g, '');

	var submitConfirm = function(){
		var input = $("<input>").attr("type", "hidden").attr("name", "action").val(button);

		$('form').append($(input));
	};

	posdelete = posdelete == true ? 'true' : 'false' || false;
	callback = callback || submitConfirm;
	ajax     = ajax || false;

	var pageInfo = getPageInfo();

	$('<div id="confirm_dialog"></div>')
	  .prependTo('body')
	  .ready(function(){

 		  $.ajax({
	           type: "POST",
	           url: 'index.php?page=confirm&ajax=true&confirmdialog=true&d_form=' + posdelete,
	           data: {
                    element: elementName,
 			  		page_name: pageInfo.pageName,
 			  		form_id: pageInfo.form_id,
 			  		data: collect_data('#content-center') + '"confirm" : "1"}'
 		  	   },
	           dataType: "html",
	           timeout: 25000,
	           success: function( responseText, textStatus, XMLHttpRequest ){
			  	  $('#confirm_dialog').append(responseText);

	              var $caption 	= $('#confirm_dialog_caption'),
	              	dialogTitle = $caption.html();

	              $caption.remove();

	              $('#confirm_dialog').dialog({
	            	  title: dialogTitle
	              });

	              if ($('input[name="passtrough"]', '#confirm_dialog').size() == 1) {
	            	  $('#confirm_dialog').dialog('close');

	            	  $('form').bind('submit', function(event){
	            		  callback();
	            	  }).trigger('submit');
	              }

	              $(':input[name="confirm"]').click(function(){
	            	  $('#confirm_dialog').dialog('close');

	            	  if (ajax === true) {
	            		  callback();
	            	  } else {
		            	  $('form').bind('submit', function(event){
		            		  callback();
		            	  }).trigger('submit');
	            	  }
	              });

	              $(':input[name="cancel"]').click(function(){
	            	  $('#confirm_dialog').dialog('close');
	              });
	           },
	           error: function( XMLHttpRequest, textStatus, errorThrown ){
	        	   posFormOpened = false;
	        	   $('#confirm_dialog').dialog('close');
	           },
	           dataFilter: function (data) {

	              checkSessionExpired(data, this);

   	        	  $('#confirm_dialog').dialog({
   	        		 modal: true,
   	        		 resizable: false,
                        open : function(){
                            hideDD('#confirm_dialog');
                        },
   	        		 width: 600,
   	        		 position: ['center', 'center'],
   	        		 close: function(){
   	         		 	$('#confirm_dialog').remove();

   	         		 	posFormOpened = false;

   	         		 	showDD();
   	        	  	 }
   	        	  });

   	        	  return data;
	           }
	        });
	  });
}


function openDeleteDialog($element, callback, ajax, posdelete) {

	if (posFormOpened == true) {
		   return false;
	   }

	posFormOpened = true;

	$('#delete_dialog').remove();

	var submitDelete = function(){
		var input = $("<input>").attr("type", "hidden").attr("name", "action").val("delete");

		$('form').append($(input));
	};

	posdelete = posdelete == true ? 'true' : 'false' || false;
	callback = callback || submitDelete;
	ajax     = ajax || false;

	var pageInfo = getPageInfo();

	$('<div id="delete_dialog"></div>')
	  .prependTo('body')
	  .ready(function(){

 		  $.ajax({
	           type: "POST",
	           url: 'index.php?page=delete&ajax=true&deletedialog=true&d_form=' + posdelete,
	           data: {
 			  		page_name: pageInfo.pageName,
 			  		form_id: pageInfo.form_id
 		  	   },
	           dataType: "html",
	           timeout: 25000,
	           success: function( responseText, textStatus, XMLHttpRequest ){
			  	  $('#delete_dialog').append(responseText);

	              var $caption 	= $('#delete_dialog_caption'),
	              	dialogTitle = $caption.html();

	              $caption.remove();

	              $('#delete_dialog').dialog({
	            	  title: dialogTitle
	              });

	              $(':input[name="delete"]').click(function(){
	            	  $('#delete_dialog').dialog('close');

	            	  if (ajax === true) {
	            		  callback();
	            	  } else {
		            	  $('form').bind('submit', function(event){
		            		  callback();
		            	  }).trigger('submit');
	            	  }
	              });

	              $(':input[name="cancel"]').click(function(){
	            	  $('#delete_dialog').dialog('close');
	              });
	           },
	           error: function( XMLHttpRequest, textStatus, errorThrown ){
	        	   posFormOpened = false;
	        	   $('#delete_dialog').dialog('close');
	           },
	           dataFilter: function (data) {

	              checkSessionExpired(data, this);

	        	  $('#delete_dialog').dialog({
	        		 modal: true,
	        		 resizable: false,
                     open : function(){
                         hideDD('#delete_dialog');
                     },
	        		 width: 600,
	        		 position: ['center', 'center'],
	        		 close: function(){
	         		 	$('#delete_dialog').remove();

	         		 	posFormOpened = false;

	         		 	showDD();
	        	  	 }
	        	  });

	        	  return data;
	           }
	        });

	  });
}




/*
 *
 * Sortierfunktion
 *
 */

function initSort(scope)
{
   var scope = scope || '#content';

   var $listTable = $('table.listtable:not(.no-filter)', scope),
       $filterRows = $('tr:eq(0) td:not(.unsortable)', $listTable),
       $sortImg = $('<img/>').attr({
         'src'   : 'media/img/base/sort-asc-deactive.png',
         'class' : 'sort-img asc'
       });

   if ($filterRows.size() > 0 ) {
      $filterRows.append($sortImg);
      $('.sort-img').click(function(){
         sortListTable(this,scope);
      });
   }
}

function unsetSort(scope) {
   $('.sort-img').unbind();
}

function sortListTable(btn, scope)
{
   var $sortBtn = $(btn);

   if ($sortBtn.size() > 0) {
      var scope        = scope || 'body',
          $filterTable = $sortBtn.parents('table:first'),
          $filterList  = $('tr:gt(0)', $filterTable),
          sortType     = $sortBtn.attr('class').split(' ')[1];

         //global, damit in asc() und desc() zugriff darauf hat!!
         _sortRow = 0;

         var sortRowIndex = $sortBtn.parent()[0].cellIndex;
         //look for colspans and get sortRow
         $sortBtn.parent().parent().children('td:lt(' + (sortRowIndex) + ')').each(function(){
            _sortRow += parseInt($(this).attr('colspan')) || 1;
         });

         resetBtn();

      if (sortType == 'asc') {
         $sortBtn.attr('src', $sortBtn.attr('src').split('asc').join('desc').split('deactive').join('active'));
         sortType = asc;
         $sortBtn.removeClass('asc').addClass('desc');
      } else {
         $sortBtn.attr('src', $sortBtn.attr('src').split('desc').join('asc').split('deactive').join('active'));
         sortType = desc;
         $sortBtn.removeClass('desc').addClass('asc');
      }

      $sortFilterList = $filterList.sort(sortType);
      $filterList.parent().append($sortFilterList);

      //realignTable(scope);
      var newCookieArr = createFilterCookie(true, getPageInfo().page, true, sortRowIndex, scope, $sortBtn.attr('class').split(' ')[1]);
      document.cookie = (newCookieArr && newCookieArr.length > 0) ? ('filter=' + newCookieArr + ';') : ('filter=""; expires=Thu, 01-Jan-70 00:00:01 GMT;');

      pageAlign(scope);
   }
}

function asc(a, b)
{
   var contentA = $(a).children('td:eq(' + _sortRow + ')').text().toLowerCase().split('ü').join('u').split('ä').join('a').split('ö').join('o').split('ß').join('s'),
       contentB = $(b).children('td:eq(' + _sortRow + ')').text().toLowerCase().split('ü').join('u').split('ä').join('a').split('ö').join('o').split('ß').join('s');

   if (contentA.match(/[0-9]{2}.[0-9]{2}.[0-9]{4}/)) {
      contentA = contentA.replace(/([0-9]{2}).([0-9]{2}).([0-9]{4})/,'$3-$2-$1');
      contentB = contentB.replace(/([0-9]{2}).([0-9]{2}).([0-9]{4})/,'$3-$2-$1');
   }

   if (contentA < contentB) {
      return 1;
   } else if (contentA > contentB) {
      return -1;
   } else {
      return 0;
   }
}

function desc(a, b)
{
   var contentA = $(a).children('td:eq(' + _sortRow + ')').text().toLowerCase().split('ü').join('u').split('ä').join('a').split('ö').join('o').split('ß').join('s'),
       contentB = $(b).children('td:eq(' + _sortRow + ')').text().toLowerCase().split('ü').join('u').split('ä').join('a').split('ö').join('o').split('ß').join('s');

   if (contentA.match(/[0-9]{2}.[0-9]{2}.[0-9]{4}/)) {
      contentA = contentA.replace(/([0-9]{2}).([0-9]{2}).([0-9]{4})/,'$3-$2-$1');
      contentB = contentB.replace(/([0-9]{2}).([0-9]{2}).([0-9]{4})/,'$3-$2-$1');
   }

   if (contentA < contentB) {
      return -1;
   } else if (contentA > contentB) {
      return 1;
   } else {
      return 0;
   }
}

function resetBtn()
{
   $('.sort-img')
      .removeClass('desc')
      .removeClass('asc')
      .addClass('asc')
      .attr('src', 'media/img/base/sort-asc-deactive.png');
}




function conformSearchCharsIn(e)
{
    var forbidden = [34,39,47,92];
    var accept = true;

    if ($.inArray(e.which, forbidden) != -1) {
        accept = false;
    }

    return accept;
}


function conformSearchChars(e, convertOnly)
{
    convertOnly = convertOnly | false;

    if (convertOnly === false) {
        var $target = $(e.target),
            val = ($target.val())
        ;
    } else {
        val = e == null ? '' : e;
    }

    var retu = val;

    if (val !== null && (strpos(val, '"') !== false || strpos(val, "'") !== false || strpos(val, "\\") !== false || strpos(val, "/") !== false)) {
        retu = val.split('"').join('').split("'").join('').split("\\").join('').split("/").join('');

        if (convertOnly === false) {
            $target.val(retu);
        }
    }

    return retu;
}


/*
 *
 * Filterfunktion initialisieren
 *
 */

function initSearch()
{
    $('input[name="search-filter"]').click(function(){
        this.select();
    }).keypress(function(e){
        return conformSearchCharsIn(e);
    }).keyup(function(e){
        conformSearchChars(e);

        if (e.keyCode == 13) {
            applyFilter();
        }
   });

   $('#start-search').click(function(){
        applyFilter();
   });
}

function unsetSearch()
{
   $('input[name="search-filter"]').unbind();
}

function filterList(filterForms)
{
   $list = $('tr:gt(0):not(.filtered-tr):not(tr:has(input:checked))', 'table.listtable:not(.no-filter) tbody'),
   searchTerm = $('input[name="search-filter"]:last').attr('value') || '';
   searchTerm = $.trim(searchTerm),
   cookieId   = $('input[name="cookie_id"]').attr('value');

   var pageInfo = getPageInfo(),
      filterArr = createFilterCookie(filterForms, pageInfo.page, searchTerm);

   document.cookie = (filterArr && filterArr.length > 0) ? ('filter=' + filterArr + ';') : ('filter=""; expires=Thu, 01-Jan-70 00:00:01 GMT;');

   $('#no_data').remove();
   if (searchTerm.length > 0){
      var searchTerms = searchTerm.split(' ');
      $list.each(function(index, element){
         var $this = $(element),
             show  = true,
             $text = $this.children(':not(.no-search)'),
             text  = '';
         $text.each(function(){
            text += $(this).text();
         });
         for (var a = 0 ; a < searchTerms.length ; a++) {
            var term = searchTerms[a],
                not = term.indexOf('/') == 0 ? true : false;
            if (term.slice(0,1) == '/' && term.length <= 2) continue;

            if (
                term.match(/^[\d]{2}.[\d]{2}.[\d]{4}-$/) ||
                term.match(/^-[\d]{2}.[\d]{2}.[\d]{4}$/) ||
                term.match(/^[\d]{2}.[\d]{2}.[\d]{4}-[\d]{2}.[\d]{2}.[\d]{4}$/) ||
                term.match(/^[\d]{4}-$/) ||
                term.match(/^-[\d]{4}$/) ||
                term.match(/^[\d]{4}-[\d]{4}$/)
            ) {
               var dateFound = text.match(/[\d]{2}.[\d]{2}.([\d]{4})/);

               if (dateFound != null) {
                  var dateParts = dateFound[0].split('.'),
                      dateNum   = parseInt(dateParts[2] + dateParts[1] + dateParts[0]),
                      dateYear  = dateFound[1];
                   //till
                   if (term.match(/^-[\d]{2}.[\d]{2}.[\d]{4}$/)) {
                      var till    = term.match(/[\d]{2}.[\d]{2}.[\d]{4}$/)[0].split('.'),
                          tillNum = parseInt(till[2] + till[1] + till[0]);
                      if (dateNum > tillNum)
                         show = false;
                   //from
                   } else if (term.match(/^[\d]{2}.[\d]{2}.[\d]{4}-$/)) {
                      var from    = term.match(/^[\d]{2}.[\d]{2}.[\d]{4}/)[0].split('.'),
                          fromNum = parseInt(from[2] + from[1] + from[0]);
                      if (dateNum < fromNum)
                         show = false;
                   //from-till
                   } else if (term.match(/^[\d]{2}.[\d]{2}.[\d]{4}-[\d]{2}.[\d]{2}.[\d]{4}$/)) {
                      var from    = term.match(/^[\d]{2}.[\d]{2}.[\d]{4}/)[0].split('.'),
                          fromNum = parseInt(from[2] + from[1] + from[0]);
                      var till    = term.match(/[\d]{2}.[\d]{2}.[\d]{4}$/)[0].split('.'),
                          tillNum = parseInt(till[2] + till[1] + till[0]);
                      if (dateNum < fromNum || dateNum > tillNum)
                         show = false;
                   } else if (term.match(/^[\d]{4}-$/)) {
                       var fromYear = term.split('-')[0];
                       if (fromYear > dateYear) {
                           show = false;
                       }
                   } else if (term.match(/^-[\d]{4}$/)) {
                       var tillYear = term.split('-')[1];
                       if (tillYear < dateYear) {
                           show = false;
                       }
                   } else if (term.match(/^[\d]{4}-[\d]{4}$/)) {
                       var yearParts = term.split('-'),
                           fromYear = yearParts[0],
                           tillYear = yearParts[1];
                       if (dateYear < fromYear || dateYear > tillYear) {
                           show = false;
                       }
                   }
               } else {
                  show = false;
               }
            } else {

               if (not) {
                  if (text.toLowerCase().indexOf(term.split('/').join('').toLowerCase()) != -1)
                     show = false;
               } else {
                  if (text.toLowerCase().indexOf(searchTerms[a].toLowerCase()) == -1)
                     show = false;
               }
            }
         }
         if (show) $this.show().removeClass('hidden-tr');
         else $this.hide().addClass('hidden-tr');
      });

   } else {
      $list.show().removeClass('hidden-tr');
      $('.no-data-found').remove();
   }

   $('.no-data-found').remove();
      var visibleEntries = $('tr:gt(0):not(.hidden-tr):not(.filtered-tr)', 'table.listtable:not(.no-filter)').size();

   if (visibleEntries == 0) {
      $('table.listtable:not(.no-filter) tbody').append('<tr class="no-data-found"><td class="edt" colspan="20">Es sind noch keine Daten vorhanden!</td></tr>');
   }

   pageAlign();
}

/*
 *
 * Seitenfunktion
 *
 */

function initPages(scope)
{
      var scope = scope || 'body';
      $('select[name="entries"]', scope).change(function(){
         pageAlign(scope);
         applyFilter();
      });
      $('img.page-arrow[alt="prev"]', scope).click(function(){
         lastPage(scope);
         applyFilter();
      });
      $('img.page-arrow[alt="next"]', scope).click(function(){
         nextPage(scope);
         applyFilter();
      });

      $('img.page-arrow[alt="first"]', scope).click(function(){
    	  firstPage(scope);
          applyFilter();
      });

      $('img.page-arrow[alt="last"]', scope).click(function(){
    	  endPage(scope);
          applyFilter();
      });

      pageAlign(scope);

}

function unsetPages(scope)
{
   $('img.page-arrow', scope).unbind();
   $('select[name="entries"]', scope).unbind();
}

function firstPage(scope)
{
	var curPage = parseInt($('#cur_page', scope).text());

	if(curPage != 1) {
		$('#cur_page', scope).text("1");
		$('#cur_page_bottom', scope).text("1");
		pageAlign(scope);
	}
}

function endPage(scope)
{
    var curPage = parseInt($('#cur_page', scope).text()),
    	maxPage = parseInt($('#max_page', scope).text());

    if(curPage != maxPage) {
        $('#cur_page', scope).text(maxPage);
        $('#cur_page_bottom', scope).text(maxPage);
        pageAlign(scope);
    }
}

function nextPage(scope)
{
   var $maxPage = $('#max_page', scope),
       $curPage = $('#cur_page', scope),
       maxPage  = parseInt($maxPage.text()),
       curPage  = parseInt($curPage.text());

   if (curPage < maxPage) {
      $curPage.text((curPage+1));
      $('#cur_page_bottom', scope).text((curPage+1));
      pageAlign(scope);
   }
}

function lastPage(scope)
{
   var $maxPage = $('#max_page', scope),
       $curPage = $('#cur_page', scope),
       maxPage  = parseInt($maxPage.text()),
       curPage  = parseInt($curPage.text());

   if (curPage > 1) {
      $curPage.text((curPage-1));
      $('#cur_page_bottom', scope).text((curPage-1));
      pageAlign(scope);
   }
}

function pageAlign(scope)
{
   var
      scope         = scope || 'body',
      $filterTable  = $('table.listtable:not(.no-filter) tbody', scope),
      $filterList   = $filterTable.children('tr:gt(0):not(.hidden-tr):not(.filtered-tr):not(tr:has(input:checked))'),
      entryCount    = $filterList.size(),
      $entryDD      = $('select[name="entries"]', scope),
      maxEntries    = parseInt($entryDD.attr('value')),
      pages         = entryCount / maxEntries,
      $maxPage      = $('#max_page', scope),
      $maxPageBottom = $('#max_page_bottom', scope),
      $curPage      = $('#cur_page', scope),
      $curPageBottom = $('#cur_page_bottom', scope),
      curPage       = parseInt($curPage.text()) || 1,
      $arrows       = $('.page-arrow', scope);

      $filterList.show();

      if (pages > parseInt(pages) || pages == 0) {
         pages = parseInt(pages)+1;
      }

      if (curPage > pages) {
         curPage = pages;
         $curPage.text(curPage);
         $curPageBottom.text(curPage);
      }

      $maxPage.text(pages);
      $maxPageBottom.text(pages);

      var startEntry = (curPage-1)*maxEntries,
          endEntry   = maxEntries,
          $showEntries = $filterTable.children('tr:not(.hidden-tr):not(.filtered-tr):gt(' + startEntry + '):lt(' + endEntry + ')');

      $filterList.hide();
      $showEntries.show();

      realignTable(scope);
}

/*
 *
 * Formfilter
 *
 */

function initFormFilter()
{
   $('img.filter-img', '#sidebar:not(.bfl)').click(setFormFilter);
   $('#remove-filter', '#sidebar:not(.bfl)').click(removeFilter);
}

function unsetFormFilter()
{
   $('img.filter-img', '#sidebar:not(.bfl)').unbind();
   $('#remove-filter', '#sidebar:not(.bfl)').unbind();
}

function setFormFilter()
{
   var $filter = $(this),
      typ = $filter.attr('alt'),
      imgSrc = $filter.attr('src');

   if (imgSrc.indexOf('apply') == -1) {
      $filter.attr('src', imgSrc.split('filter.png').join('apply-filter.png')).addClass('applied');
      $filter.parent().addClass('filter-td-bg');
   } else {
      $filter.attr('src', imgSrc.split('apply-filter.png').join('filter.png')).removeClass('applied');
      $filter.parent().removeClass('filter-td-bg');
   }


   applyFilter();
}

function removeFilter()
{
   $('.filter-img[alt="filter"]').attr('src', 'media/img/base/filter.png').removeClass('applied');
   $('.filter-td-bg').removeClass('filter-td-bg');

   applyFilter();
}

function checkFilterInputState()
{
    var $searchInput = $('input[name="search-filter"]');

    if ($searchInput.attr('value') != '') {
        $searchInput.addClass('filter-is-active');

        $('#start-search').attr({'src': 'media/img/base/editdelete.png', 'title' : 'Alle Suchkriterien entfernen'})
        .unbind().click(function(){
     	   $('input.search-filter').attr('value', '');
     	   $('#start-search').attr({'src': 'media/img/base/glass.png', 'title': 'Suche'});

     	  applyFilter();
        });

    } else {
    	$('#start-search').attr({'src': 'media/img/base/glass.png', 'title': 'Suche'});
        $searchInput.removeClass('filter-is-active');
    }
}

function applyFilter()
{
   checkFilterInputState();

   var $filters = $('img.filter-img.applied', '#sidebar:not(.bfl)'),
      $filterTable = $('table.listtable:not(.no-filter) tbody'),
      filterForms = new Array,
      i = 0;

   if ($filters.size() > 0) {
      $filterTable.children('tr:gt(0)').hide().addClass('filtered-tr');
      $filters.each(function(index, filter){
         var $formFilter = $(filter),
            filterClass  = $formFilter.attr('id').split('filter-img-').join('');

         $filterTable.children('tr.' + filterClass + '').show().removeClass('filtered-tr');

         filterForms[i] = filterClass;
         i++;

      });

   } else {
      $filterTable.children('tr:gt(0):not(.hidden-tr)').show().removeClass('filtered-tr');
   }

   var $no_filters = $('img.filter-img:not(.applied)', '#sidebar:not(.bfl)');

   if($no_filters.size() > 0) {
      $no_filters.each(function(index, filter) {
         var $formFilter = $(filter),
            filterClass = $formFilter.attr('id').split('filter-img-').join('');

            for (var i in filterForms) {
               if (filterForms[i] == filterClass) {
                  filterForms = filterForms.splice(i, 1);
               }
            }
      });

   }

   filterList(filterForms);
}

/*
 *
 * Tabellenformatierung
 *
 */


function realignTable(scope)
{
   var scope = scope || 'body';
   $('table.listtable:not(.no-filter):not(.formtable) tbody', scope).children('tr:gt(0):not(.hidden-tr):not(.filtered-tr):odd').children('td').removeClass('edt').removeClass('odd').removeClass('even').addClass('odd');
   $('table.listtable:not(.no-filter):not(.formtable) tbody', scope).children('tr:gt(0):not(.hidden-tr):not(.filtered-tr):even').children('td').removeClass('edt').removeClass('odd').removeClass('even').addClass('even');

   var $searchInput = $('input.search-filter');
}


/*
 *
 * Listtablehover
 *
 */

function setTableHover(selected)
{
   selected = selected || false;

   $('tr:gt(0)', '.listtable:not(.no-filter)').hover(function(){
      $(this).children('td').addClass('td-hover');
   }, function(){
      $(this).children('td').removeClass('td-hover');
   });

   //activate selected highlighting
   if (selected !== false && $('input[name="add_value"]').size() > 0) {
	   $('input[name="add_value"]').each(function(){
		   if ($(this).val() == selected) {
			   $(this).parents('tr').children('td').attr('class', 'td-selected');
		   }
	   });
   }
}

$(document).ready(function(){

  $(':input[class^="toggle-"]').click(function(){
     var name = $(this).attr('class').split('-')[1];

     if ($(this).is(":checked") === false) {
        //remove
        $("input." + name + ':checked').trigger('click');
     } else {
        $("input." + name + ':not(:checked)').trigger('click');
     }
  });

   $('.btn_unset_file').click(function() {
      var parent = $(this).closest('td'),
      	  $field = $('input[type="hidden"]', parent),
      	  fieldName = $field.attr('name'),
      	  $newField = $('<input/>').attr({
        	'type' : 'file',
        	'name' : fieldName,
            'value': '',
            'size' : 50
         });

      parent.html($newField);
   });
});

/*
   Vorladen von hover-bildern
*/

function imgPreload()
{
   var pageType = getPageInfo().page.split('.')[0] || null;

   if ($('.folder').size()) {
      var img = new Image;
      img.src = 'media/img/base/folder-hover.png';
   }

   if (pageType == 'view' || pageType == 'list') {
      var img = new Image;
      img.src = 'media/img/base/sort-asc-active.png';
      img.src = 'media/img/base/sort-desc-active.png';
   }

   if ($('.filter-img').size()) {
      var img = new Image;
      img.src = 'media/img/base/apply-filter.png';
   }
}

function impressLink()
{
    var dialogOptions = {
        modal:true,
        title:'Impressum',
        autoOpen : false,
        dialogClass : 'impress-bg',
        position : ['center',50],
        open : function(){
            hideDD('#impressum');

            setTimeout(function(){
                $('.hidden-impress', '#impressum').fadeIn(250);
            }, 255);
        },
        show : {effect : 'puff', duration : 250, percent : 95},
        hide : {effect : 'puff', duration : 250, percent : 95},
        beforeClose : function(){

            if ($('.hidden-impress', '#impressum').is(':hidden')) {
                return true;
            } else {
                $('.hidden-impress', '#impressum').fadeOut(250);
                setTimeout(function(){
                    $('#impressum').dialog('close');
                }, 250);
                return false;
            }
        },
        close : function(){
            $('#impressum').dialog('destory').dialog(dialogOptions);
            showDD();
        },
        draggable:false,
        resizable:false,
        width: 470,
        height: 350
    };

    $('#impressum').dialog(dialogOptions);

    $('#impressum-link').click(function(){
        $('#impressum').dialog('open');
        return false;
    });
}

/*
   Doppelklicken von Buttons verhinden
   kurze Info: Beim Klicken auf einen submit-button im Hauptformular (!!!)
   wird die action in ein hidden input gelegt und der button disabled
*/

function preventDoubleSave()
{
   //alle submit buttons auï¿½er report-gen buttons /TODO
   var $submitBtn = $('input[type="submit"][name^="action"]:not(input[name*="report"])');
   $submitBtn
      .removeAttr('disabled','disabled')
      .click(function(){
    	 var $this = $(this);

    	 if ($this.hasClass('dont_prevent_double_save') == false) {
			 $('body').addClass('waiting');

		     $fakeInput = $('<input>').attr({
		        'name'  : $this.attr('name'),
		        'value' : $this.attr('value'),
		        'type'  : 'hidden'
		     });

		     $submitBtn.attr('disabled', 'disabled').addClass('button-disabled');

		     $this.after($fakeInput);
		     $this.parents('form:first').submit();
      	 }
      });
}

/*
   Anzeige, dass Anfrage läuft
*/

function ajaxWaitNotification()
{
   $('body').ajaxStart(function(){
      $('body').addClass('waiting');
   });

   $('body').ajaxStop(function(){
      $('.waiting').removeClass('waiting');
   });
}


function send(action, selector) {

	var $submitBtn = $('input[type="button"][name^="action"]');
	$submitBtn.attr('disabled', 'disabled').addClass('button-disabled');

	$(selector)
		.append('<input type="hidden" name="action[' + action + ']"/>')
		.parents('form:first')
		.submit();
}

function sendMail(button) {
	$(button)
	.append('<input type="hidden" name="action[email]"/>')
	.parents('form:first')
	.submit();
}

function sendConfirm(button) {
	$(button)
	.append('<input type="hidden" name="action[confirm]"/>')
	.parents('form:first')
	.submit();
}

function sendAllocation(button) {
	$(button)
	.after('<input type="hidden" name="action[allocate]"/>')
	.parents('form:first')
	.submit();
}

function sendRemove(button) {
	$(button)
	.append('<input type="hidden" name="action[remove]"/>')
	.parents('form:first')
	.submit();
}

function printLetter(button) {
	$(button)
	.append('<input type="hidden" name="action[letter]"/>')
	.parents('form:first')
	.submit();
}

function initGlobalTableHover(bfl)
{
    bfl = typeof(bfl) === 'undefined' ? ':not(.bfl)' : '';

    $('table.listtable:not(.no-hover)' + bfl).each(function(){
        $('tr:not(.no-hover)', this).each(function(){
            if ($('td.head', this).size() == 0) {
                $(this).mouseenter(function(){
                    $('td', this).addClass('listtable-tr-hover');
                }).mouseleave(function(){
                    $('td', this).removeClass('listtable-tr-hover');
                });
            }
        });
    });
}

function popupHover()
{
    $('.info-popup').each(function(index, element){

        var $el = $(element),
            $trigger = $('.popup-trigger:eq(' + index + ')');

        $el.hide();
        $trigger.unbind('mouseenter mouseleave').mouseenter(function(){
            var topMod =  $el.hasClass('below')  ? $trigger.height()+8  : ($el.hasClass('above') ? -1*($el.height()+10)  : 0),
                leftMod = $el.hasClass('before') ? -1*($el.width()+5)+$trigger.width()   :
                            ($el.hasClass('behind') ? $trigger.width()+5  :
                                ($el.hasClass('center') ? $trigger.width()/2-$el.width()/2 : 0 ) );
            $el.css({
                top  : $trigger.position().top + topMod,
                left : $trigger.position().left + leftMod
            });
            $('.role-info-box').hide();
            $el.show();
        }).mouseleave(function(){
            $el.hide();
        });
    });
}

function hideDD(el)
{
    if ($.browser.msie && $.browser.version <= 6) {
        $('select').each(function(){
            if ( ! $(this).parents(el).size()) {
                $(this).hide();
            }
        });
    }
}

function showDD()
{
    if ($.browser.msie && $.browser.version <= 6) {
        $('select').show();
    }
}

function warnSlider()
{
    if ($('#message-slider').size() > 0 ) {
        $('#message-slider').toggle(function(){
            $('#slider-message').slideDown();
        },function(){
            $('#slider-message').slideUp();
        });
    }
}


function loadPatientViewer(tpl)
{
   if (_viewPatientLoad)
      return;

   _viewPatientLoad = true;

   var tpl = tpl || 'table';

   $('.loader').show();
   $('.view-content').hide();

   $.ajax({
        url      : 'index.php',
        data     : {ajax : true, page : 'view.patient', tpl_page : tpl},
        dataType : 'html',
        type     : 'post',
        timeout  : 25000,
        success  : function(data) {

           var $div = $('<div/>').append(data);
           //.hack
           $(':input[name="page"]', $div).remove();
           var content = $('.d_frame', $div).html();

           $('.view-content').empty().append(content).ready(function(){
              $('.loader').hide();
              $('.view-content').fadeIn(function(){
            	  _viewPatientLoad = false;
              });

              if (tpl == 'table') {
            	 //set table events
                 initSort();
                 initSearch();
                 initPages();
                 initFormFilter();
                 loadCookieFilter(applyFilter);

                 $('img[alt="table"]').unbind();
                 $('img[alt="pictures"]').unbind().click(function(){
                	 loadPatientViewer('foto');
                 });
                 $('img[alt="documents"]').unbind().click(function(){
                	 loadPatientViewer('dokument');
                 });
              } else if (tpl == 'dokument') {
            	 loadCookieFilter(applyFilter);

            	 $('img[alt="documents"]').unbind();
            	 $('img[alt="table"]').unbind().click(function(){
                	 loadPatientViewer('table');
                 });
            	 $('img[alt="pictures"]').unbind().click(function(){
                	 loadPatientViewer('foto');
                 });
              } else {
                 loadCookieFilter(applyFilter);

                 $('img[alt="pictures"]').unbind();
                 $('img[alt="table"]').unbind().click(function(){
                	 loadPatientViewer('table');
                 });
                 $('img[alt="documents"]').unbind().click(function(){
                	 loadPatientViewer('dokument');
                 });
              }

              initGlobalTableHover();
              popupHover();
           });
        },
        dataFilter: function (data) {

           checkSessionExpired(data, this);

           return data;
        }
     });
}

function blankLinkTarget()
{
   $('a.target-blank').attr('target', '_blank');
}
