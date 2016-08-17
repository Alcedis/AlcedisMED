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

//set true to avoid event trigger before page is loaded
_codepickerOpen = true;
_codepickerScope = null;

_history = [];

$(function(){
   _codepickerOpen = false;
   checkNci();
});

function openCodepicker(url, src)
{
   var $inDialog = Boolean($(src).closest('#d_input').length);

   _codepickerScope = $inDialog === true ? '#d_input' : '#content-center';

   if (_codepickerOpen === true) {
      return;
   }

   url += '&parentform=' + $('input[name="page"]:last').attr('value');

   _codepickerOpen = true;

   var dialogHeight = (($(window).height() - 100) < 300 ? 300 : ($(window).height() - 100));

   _history = [];

   //History init
   _history.push({
      'page' : url.match(/page=([a-z_A-Z0-9]{1,})&/)[1],
      'code' : (url.indexOf('o3_type=t') ? 'diag': ''),
      'history' : 1
   });

   var dialogHeight = (($(window).height() - 100) < 300 ? 300 : ($(window).height() - 100)),
       scrollContentHeight = dialogHeight - 150;

	$('<div id="c_input"><div id="c_bottom"><div id="c_content"></div></div></div>')
 	  .prependTo('body')
 	  .ready(function(){
 		 $.ajax({
           type: "POST",
           url: url,
           dataType: "html",
           success: function( responseText, textStatus, XMLHttpRequest ){
              $('#c_content').append(responseText);
              var dialogTitle = $('#c_caption').html();
              $('#c_caption').remove();
              $('#c_input').dialog({title: dialogTitle});

              $('input[name="suche"]', '#c_content').keyup(function(e){
                  if (e.keyCode == 13) {
                     searchCodepickerCode();
                  }
              });

              alignScrollContent(150);

              $('.codepicker-link', '#c_search').click(function(e){

                 e.preventDefault();

                 switchCodepicker($(this).attr('hreflang'), 0);
              });
           },
           dataFilter: function (data) {
              checkSessionExpired(data, this);

          	 $('#c_input').dialog({
        			  modal: true,
        			  resizable: false,
        			  width: 940,
        			  height : dialogHeight,
        			  position: ['center', 50],
        			  close: function(){
              	 	   $('#c_input').remove();
              	 	   _codepickerOpen = false;
                  }
        		  });
              return data;
           }
        });
   });
}


function changeCodepicker(data, callback, index)
{
	var
	   vorauswahl   = $(':input[name="vorauswahl"]', $('#c_input')).size() ? '&vorauswahl=' + $(':input[name="vorauswahl"]', $('#c_input')).attr('value') : null,
	   gruppen      = $(':input[name="gruppen"]',    $('#c_input')).size() ? '&gruppen='    + $(':input[name="gruppen"]',    $('#c_input')).attr('value') : null,
		r_exp        = $(':input[name="r_exp"]',      $('#c_input')).size() ? '&r_exp='      + $(':input[name="r_exp"]',      $('#c_input')).attr('value') : null,
      parentform   = $(':input[name="parentform"]', $('#c_input')).size() ? '&parentform=' + $(':input[name="parentform"]', $('#c_input')).attr('value') : null,
      o3_type      = $(':input[name="o3_type"]',    $('#c_input')).size() ? '&o3_type='    + $(':input[name="o3_type"]',    $('#c_input')).attr('value') : null,
      txtfield     = $(':input[name="txtfield"]',   $('#c_input')).size() ? '&txtfield='   + $(':input[name="txtfield"]',   $('#c_input')).attr('value') : null
   ;

   var
      url = 'index.php?codepicker=true&ajax=true&subdir=codepicker' + vorauswahl + gruppen + r_exp + parentform + o3_type + txtfield;

   index = index !== undefined ? index : 0;

   $.each(data, function(key, value){
      url += '&' + key + '=' + value;
   });

	$.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        timeout: 25000,
        success: function(responseText, textStatus, XMLHttpRequest){

         if (callback) {
            callback();
         }

         $('#codepicker-form').empty().append(responseText).ready(function(){
            $('#d_caption').remove();

            if (index > 0) {
               writeHistory(index, data);
            } else {
               $('#c_history').empty();
            }

            alignScrollContent(150);
         });
      },
      dataFilter: function (data) {
         checkSessionExpired(data, this);
         return data;
      }
   });
}

function writeHistory(index, data){
   var
      lbl = $('#codepicker-form tbody tr:first td.head:first', '#c_body').text(),
      lbl = lbl.length > 30 ? lbl.slice(0,30) + '...' : lbl
   ;

   data['lbl'] = lbl;

   _history[index] = data;
   _history = _history.slice(0, index+1);

   $('#c_history').empty();

   $.each(_history, function(i, history){
      if (i > 0 && i < index) {
         var $historyLink = $('<a/>')
            .addClass('codepicker-link link')
            .append('<span>&rarr;  ' + history['lbl'] + '</span>')
            .click(function(){
                changeCodepicker({page: history['page'], code: history['code']}, function(){switchCodepicker("catalogue");}, i);
            });

         $('#c_history').append($historyLink);
      }
   });
}


function searchCodepickerCode() {
	var searchString = $(':input[name="suche"]', $('#c_search')).attr('value');

	if (searchString.length > 0){

	   searchString = '&suche=' + escape(searchString);

	   var
         vorauswahl   = $(':input[name="vorauswahl"]', $('#c_input')).size() ? '&vorauswahl=' + $(':input[name="vorauswahl"]', $('#c_input')).attr('value') : null,
         gruppen      = $(':input[name="gruppen"]',    $('#c_input')).size() ? '&gruppen='    + $(':input[name="gruppen"]',    $('#c_input')).attr('value') : null,
         r_exp        = $(':input[name="r_exp"]',      $('#c_input')).size() ? '&r_exp='      + $(':input[name="r_exp"]',      $('#c_input')).attr('value') : null,
         parentform   = $(':input[name="parentform"]', $('#c_input')).size() ? '&parentform=' + $(':input[name="parentform"]', $('#c_input')).attr('value') : null,
         o3_type      = $(':input[name="o3_type"]',    $('#c_input')).size() ? '&o3_type='    + $(':input[name="o3_type"]',    $('#c_input')).attr('value') : null,
         txtfield     = $(':input[name="txtfield"]',   $('#c_input')).size() ? '&txtfield='   + $(':input[name="txtfield"]',   $('#c_input')).attr('value') : null,
         page         = $(':input[name="type"]',       $('#c_input')).size() ? '&page=code_'  + $(':input[name="type"]', $('#c_input')).attr('value') + '_suche' : null
      ;

      var
        url = 'index.php?codepicker=true&ajax=true&subdir=codepicker' + vorauswahl + gruppen + r_exp + parentform + o3_type + txtfield + searchString + page;

		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: "html",
	        timeout: 25000,
	        success: function( responseText, textStatus, XMLHttpRequest ){
				$('#codepicker-form').empty().append(responseText).ready(function(){

               alignScrollContent(150);

               switchCodepicker('catalogue');

               $('#d_caption').remove();
   			 });
	        },
	        dataFilter: function (data) {
              checkSessionExpired(data, this);

              return data;
           }
	     });
	} else {
	    $(':input[name="suche"]', $('#c_search')).addClass('input-search-error').focus(function(){
	        $(':input[name="suche"]', $('#c_search')).removeClass('input-search-error').unbind('focus');
	    });
	    setTimeout(function(){
	        $(':input[name="suche"]', $('#c_search')).removeClass('input-search-error').unbind('focus');
	    }, 1000);
	}
}


function switchCodepicker(to, history)
{
   if (to === 'top') {
      $('#codepicker-form').hide();
      $('#top10table').show();
   } else {

      history = history !== undefined ? history : false;

      if (history !== false) {
         changeCodepicker(_history[history], function(){
            $('#codepicker-form').show();
            $('#top10table').hide();
         }, history);
      } else {
         $('#codepicker-form').show();
         $('#top10table').hide();
      }
   }
}


function selectCodepickerCode(code, bez) {
   var field = $(':input[name="txtfield"]', $('#c_content')).attr('value');

   $(':input[name="' + field + '"]', _codepickerScope).val(code);
   $(':input[name="' + field + '_text"]', _codepickerScope).val(bez);
   $(':input[name="' + field + '_version"]', _codepickerScope).val($(':input[name="' + field + '_default_version"]', _codepickerScope).val());

   $('span[id="' + field + '_text"]', _codepickerScope).html('<strong>' + bez + '</strong>');

   //nci grad angaben
   if (field == 'nci_code') {
      if (bez.indexOf('Other, specify') !== -1){
         $(':input[name="' + field.split('code').join('text') + '"]', _codepickerScope).show();
      } else {
         $(':input[name="' + field.split('code').join('text') + '"]', _codepickerScope).hide().empty();
      }

      $('td.small:eq(0)', 'tr.grad-info').empty().text($('.grad-1','.grad-info-' + code).text());
      $('td.small:eq(1)', 'tr.grad-info').empty().text($('.grad-2','.grad-info-' + code).text());
      $('td.small:eq(2)', 'tr.grad-info').empty().text($('.grad-3','.grad-info-' + code).text());
      $('td.small:eq(3)', 'tr.grad-info').empty().text($('.grad-4','.grad-info-' + code).text());
      $('td.small:eq(4)', 'tr.grad-info').empty().text($('.grad-5','.grad-info-' + code).text());
      $('tr.grad-info').show();
   }

   $('#c_input').dialog("close");
}


function searchCodepickerKtst() {

   var name = $('input[name="suche_name"]', '#c_body').attr('value');
       iknr = $('input[name="suche_iknr"]', '#c_body').attr('value');
       plz  = $('input[name="suche_plz"]', '#c_body').attr('value');
       vknr = $('input[name="suche_vknr"]', '#c_body').attr('value');

	if (name != '' || iknr != '' || plz != '' || vknr != ''){
		$.ajax({
	        type     : "post",
	        url      : 'index.php',
	        dataType : "html",
	        data     : { ajax      : true,
	                     subdir    : 'codepicker',
	                     page      : 'code_ktst_suche',
	                     'suche[]' : [ name, iknr, plz, vknr]
	        },
	        timeout  : 25000,
	        success: function( data ){
               $('.d_frame', '#c_body').remove();
               $('#c_body').append(data).ready(function(){
                  $('#d_caption', '#c_body').remove();
                  alignScrollContent(235);
                  alignKtstCodepicker();
               });
	        },
	        dataFilter: function (data) {
              checkSessionExpired(data, this);

              return data;
           }
      });
	}
}

function checkKtstSearch(e) {
   if (e.keyCode == 13)
      searchCodepickerKtst();
}

function alignScrollContent(space)
{
    var dialogHeight = (($(window).height() - 100) < 300 ? 300 : ($(window).height() - 100)),
    scrollContentHeight = dialogHeight - space;
    $('.scroll-content', '#c_content').css('height', scrollContentHeight);
}

function alignKtstCodepicker()
{
    $('table.formtable:first tbody tr:first', '#c_body').unbind().css('cursor','pointer').toggle(function(){
        $('table.formtable:first tbody tr:gt(0)', '#c_body').hide();
        alignScrollContent(109);
    }, function(){
        $('table.formtable:first tbody tr:gt(0)', '#c_body').show();
        alignScrollContent(235);
    });
}

function checkNci()
{
   //nci: hide other, specify text field
   if (
      $('#nci_code_text').size() &&
      $('#nci_code_text').text().indexOf('Other, specify') == -1 &&
      $(':input[name="nci_text"]').text() == '') {
      $(':input[name="nci_text"]').hide();
      $('tr.grad-info').hide();
   }

   if ($(':input[name="nci_code"]').attr('value') == '')
      $('span[id="nci_code_text"]').empty();

   if ($('td.small', 'tr.grad-info').text() != '') {
      $('tr.grad-info').show();
   }
}

function resetCodepickerCode(field, src) {

   var $inDialog = Boolean($(src).closest('#d_input').length);

   _codepickerScope = $inDialog === true ? '#d_input' : '#content-center';

   $(':input[name="' + field + '"]', _codepickerScope).val('');
   $(':input[name="' + field + '_seite"]', _codepickerScope).val('');
   $(':input[name="' + field + '_text"]', _codepickerScope).val('');
   $(':input[name="' + field + '_version"]', _codepickerScope).val('');
   $('span[id="' + field + '_text"]', _codepickerScope).empty();

   if (field.indexOf('nci') !== -1) {
      $(':input[name="' + field.split('code').join('text') + '"]', _codepickerScope).hide().empty();
      $('tr.grad-info').hide();
      $('td.small', 'tr.grad-info').empty();
   }
}
