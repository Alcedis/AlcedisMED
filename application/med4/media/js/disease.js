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

_viewLoad = true;

$(function(){

   _viewLoad = false;

   var filterList = getCookieValue("filter");
   if (filterList) {
      try {
         var objList = JSON.parse(filterList);
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

      loadTemplate(listValue);
   } else {
      loadTemplate();
   }
});

function loadTemplate(tpl)
{
   if (_viewLoad)
      return;

   _viewLoad = true;

   var tpl = tpl || 'table';

   $('.view-content').hide();

   $.ajax({
            url      : 'index.php',
            data     : {ajax : true, page : 'view.erkrankung', tpl_page : tpl},
            dataType : 'html',
            type     : 'post',
            timeout  : 25000,
            success  : function(data) {

               var $div = $('<div/>').append(data);
               //.hack
               $(':input[name="page"]', $div).remove();
               var content = $('.d_frame', $div).html();

               $('.view-content').empty().append(content).ready(function(){

                  $('.view-content').fadeIn(function(){
                     _viewLoad = false;
                  });
                  if (tpl == 'table') {
                     //unset tree lsit events
                     unsetTreeFilter();
                     unsetTreeSearch();
                     //set table events
                     initSort();
                     initSearch();
                     initPages();
                     initFormFilter();
                     //load cookie values
                     loadCookieFilter(applyFilter);
                     $('img[alt="list"]').unbind();
                     $('img[alt="tree"]').unbind().click(function(){
                     loadTemplate('list');
                     });
                  } else {
                     //unset table events
                     unsetSort();
                     unsetSearch();
                     unsetPages();
                     unsetFormFilter();
                     //set tree list events
                     initTree();
                     setHover();
                     initTreeFilter();
                     initTreeSearch();
                     //load cookie values
                     loadCookieFilter(applyTreeFilter);
                     applyTreeFilter();
                     $('img[alt="tree"]').unbind();
                     $('img[alt="list"]').unbind().click(function(){
                        loadTemplate('table');
                     });
                  }
                  //addable forms popup
                  initAddForm();
                  initGlobalTableHover();
                  popupHover('.view-content');
               });
            },
            dataFilter: function (data) {
               checkSessionExpired(data, this);

               return data;
            }
         });
}

function setHover()
{
   $('li', '.view-content').hover(function(){
      $(this).addClass('li-hover');
   },function(){
      $(this).removeClass('li-hover');
   });
}

function initTree()
{
   $('ul.branch-tree', '.view-content').each(function(){
      var $ul = $(this),
          $li = $ul.prev('li').addClass('clickable');
      $li.click(function(event){
         var $this = $(this);
         if ($(event.target).hasClass('tree-li') || $(event.target).hasClass('tree-info')) {
            if ($ul.is(':hidden')) {
               $ul.slideDown('fast');
               $this.removeClass('closed-li');
            } else {
               $ul.slideUp('fast');
               $this.addClass('closed-li');
            }
         }
      });
   });

   var visibleEntries = $('li.tree-li:not(.hidden-li):not(.filtered-li)', 'ul.parent-tree').size(),
       entriesComplete = $('li.tree-li', 'ul.parent-tree').size(),
       $entryInfo = $('<div/>').addClass('entry-info')
         .append($('<span/>').addClass('visible-entries').text(visibleEntries))
         .append(' / ')
         .append($('<span/>').addClass('all-entries').text(entriesComplete));

   $('.entry-info', '.view-content').remove();
   $('.view-content').prepend($entryInfo);
}

function initAddForm()
{
   $('.add-ref-form').mouseenter(function(){
       $('.reference-list').hide();
       $(this).next().show();
   });
   $('.reference-list').mouseleave(function(event){
      $(this).hide();
   });
}

function initTreeFilter()
{
   $('img.filter-img', '#sidebar').click(setTreeFilter);
   $('#remove-filter', '#sidebar').click(removeTreeFilter);
}

function initTreeSearch()
{
   if ($('input[name="search-filter"]').size()) {
       $('input[name="search-filter"]').keyup(function(e){
           if (e.keyCode == 13) {
               applyTreeFilter();
           }
       }).select();

       $('#start-search').click(function(){
    	   applyTreeFilter();
       });
   }
}

function applyTreeFilter()
{
	checkTreeFilterInputState();

     var $filters = $('img.filter-img.applied', '#sidebar'),
      $filterList = $('ul.parent-tree:not(.no-filter)', '.tree-container'),
      filterForms = new Array,
      i = 0;

   if ($filters.size() > 0) {
      $('.tree-li', $filterList).hide().addClass('filtered-li');
      $filters.each(function(index, filter){
         var $formFilter = $(filter),
             filterClass = $formFilter.attr('id').split('filter-img-').join(''),
             $elements   = $filterList.children('li.tree-li');

         $elements.each(function(){
            var $element = $(this);
            if ($element.hasClass(filterClass) || ( $element.next().is('ul.branch-tree') && $('li.' + filterClass, $element.next()).size())) {
               $element.show().removeClass('filtered-li');
               if ($element.next().is('ul.branch-tree')) {
                  $('li', $element.next()).show().removeClass('filtered-li');
               }
            }
         });

         filterForms[i] = filterClass;
         i++;
      });
   } else {
      $('li.tree-li', $filterList).removeClass('filtered-li');
   }

   var $no_filters = $('img.filter-img:not(.applied)', '#sidebar');

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

   filterTree(filterForms);
}

function filterTree(filterForms)
{
   $list      = $('ul.parent-tree:not(.no-filter)').children('li.tree-li:not(.filtered-li):not(li:has(input:checked))'),
   searchTerm = $('input[name="search-filter"]:last').attr('value') || '';
   searchTerm = $.trim(searchTerm),
   cookieId   = $('input[name="cookie_id"]').attr('value');

   var pageInfo = getPageInfo(),
      filterArr = createFilterCookie(filterForms, pageInfo.page, searchTerm, '', '', '');

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

         if ($this.next().is('ul.branch-tree')) {
            var $branchText = $(':not(.no-search)', $this.next());
            $branchText.each(function(){
               text += $(this).text();
            });
         }

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
         if (show) {
            $this.show().removeClass('hidden-li');
            if ($this.next().is('ul.branch-tree')) {
               $('li.tree-li', $this.next()).show().removeClass('hidden-li');
            }
         } else {
            $this.hide().addClass('hidden-li');
            if ($this.next().is('ul.branch-tree')) {
               $('li.tree-li', $this.next()).hide().addClass('hidden-li');
            }
         }
      });

   } else {
      $list.show().removeClass('hidden-li');
      $('li:not(.filtered-li)','.parent-tree').show().removeClass('hidden-li');
   }

   $('.no-data-found').remove();
      var visibleEntries = $('li.tree-li:not(.hidden-li):not(.filtered-li)', 'ul.parent-tree').size();

   if (visibleEntries == 0) {
      $('ul.parent-tree').append('<li class="no-data-found">Es sind noch keine Daten vorhanden!</li>');
   }

   $('.visible-entries', '.entry-info').text(visibleEntries);
}

function unsetTreeFilter()
{
   $('img.filter-img', '#sidebar').unbind();
   $('#remove-filter', '#sidebar').unbind();
}

function unsetTreeSearch()
{
   $('input[name="search-filter"]').unbind();
}

function setTreeFilter()
{
   var $filter = $(this),
       typ     = $filter.attr('alt'),
       imgSrc  = $filter.attr('src');

   if (imgSrc.indexOf('apply') == -1) {
      $filter.attr('src', imgSrc.split('filter.png').join('apply-filter.png')).addClass('applied');
      $filter.parent().addClass('filter-td-bg');
   } else {
      $filter.attr('src', imgSrc.split('apply-filter.png').join('filter.png')).removeClass('applied');
      $filter.parent().removeClass('filter-td-bg');
   }
   applyTreeFilter();
}

function removeTreeFilter()
{
   $('.filter-img[alt="filter"]').attr('src', 'media/img/base/filter.png').removeClass('applied');
   $('.filter-td-bg').removeClass('filter-td-bg');

   applyTreeFilter();
}

function checkTreeFilterInputState()
{
    var $searchInput = $('input[name="search-filter"]');

    if ($searchInput.attr('value') != '') {
        $searchInput.addClass('filter-is-active');

        $('#start-search').attr({'src': 'media/img/base/editdelete.png', 'title' : 'Alle Suchkriterien entfernen'})
        .unbind().click(function(){
     	   $('input.search-filter').attr('value', '');
     	   $('#start-search').attr({'src': 'media/img/base/glass.png', 'title': 'Suche'});

     	  applyTreeFilter();
        });

    } else {
    	$('#start-search').attr({'src': 'media/img/base/glass.png', 'title': 'Suche'});
        $searchInput.removeClass('filter-is-active');
    }
}
