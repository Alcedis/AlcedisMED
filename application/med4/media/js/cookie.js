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

function loadCookieFilter(callback, noFunctions)
{
   var filterCookie = getCookieValue('filter'),
       noFunctions = noFunctions || false,
       pageInfo = getPageInfo();

   if (filterCookie) {
      try {
         var obj = JSON.parse(filterCookie);
      } catch (e) {
         var obj = {};
      }

      var cookieId = $('input[name="cookie_id"]').attr('value');

      if(obj[cookieId]) {
         if (obj[cookieId][pageInfo.page]) {

            if(obj[cookieId][pageInfo.page].entries) {
               $("select[name=entries] option:contains(" + obj[cookieId][pageInfo.page].entries + ")").attr("selected", true);
            }

            for (var i in obj[cookieId][pageInfo.page].forms) {

               var filterForm = obj[cookieId][pageInfo.page].forms[i],
                  $filterBtn = $('#filter-img-' + filterForm),
                  imgSrc = $filterBtn.attr('src');

               if (imgSrc !== undefined && filterForm !== null && filterForm.length > 0) {
                  if (imgSrc.indexOf('apply') == -1) {
                     $filterBtn.attr('src', imgSrc.split('filter.png').join('apply-filter.png')).addClass('applied');
                     $filterBtn.parent().addClass('filter-td-bg');
                  }
               }
            }

            if($('table.bfl').size() > 0) {
               if (typeof obj[cookieId][pageInfo.page].suche !== 'undefined') {
                  for(var i in obj[cookieId][pageInfo.page].suche) {
                     var $searchValue = decodeURIComponent(obj[cookieId][pageInfo.page].suche[i]);
                     $(':input[name="cookie-search-' + i + '"]').val(conformSearchChars($searchValue, true));
                  }
               }
            } else {
               if (typeof obj[cookieId][pageInfo.page].suche !== 'undefined') {
            	   var $searchValue = decodeURIComponent(obj[cookieId][pageInfo.page].suche);
                   $("input.search-filter").val(conformSearchChars($searchValue, true));
               }
            }

            if(pageInfo.page == 'auswertungen') {
               var accActive = obj[cookieId][pageInfo.page].accordion;

               return accActive;
            }

            if(pageInfo.page == 'report') {
            	var reportName = getParameter("name");

            	if(obj[cookieId][pageInfo.page][reportName]) {
	            	if (obj[cookieId][pageInfo.page][reportName].datumVon) {
	            	   if ($('input[name="datum_von"]').length > 0) {
	                		$('input[name="datum_von"]').val(obj[cookieId][pageInfo.page][reportName].datumVon);
	                	}
	            	}

	            	if (obj[cookieId][pageInfo.page][reportName].datumBis) {
	                	if ($('input[name="datum_bis"]').length > 0) {
	                		$('input[name="datum_bis"]').val(obj[cookieId][pageInfo.page][reportName].datumBis);
	                	}
	            	}

	            	if (obj[cookieId][pageInfo.page][reportName].jahr) {
	                	if ($('select[name="jahr"]').length > 0) {
	                		$('select[name="jahr"]').val(obj[cookieId][pageInfo.page][reportName].jahr);
	                	}
	            	}

	            	if (obj[cookieId][pageInfo.page][reportName].datumsbezug) {
	            	   if ($('select[name="datumsbezug"]').length > 0) {
	            	      $('select[name="datumsbezug"]').val(obj[cookieId][pageInfo.page][reportName].datumsbezug);
	            	   }
	            	}

                    if (obj[cookieId][pageInfo.page][reportName].kennzahlenjahr) {
	            	   if ($('select[name="kennzahlenjahr"]').length > 0) {
	            	      $('select[name="kennzahlenjahr"]').val(obj[cookieId][pageInfo.page][reportName].kennzahlenjahr);
	            	   }
	            	}
            	}
            }

            if(pageInfo.page == 'status') {
               var accActive = obj[cookieId][pageInfo.page].accstatus;
               return accActive;
            }

            if(obj[cookieId][pageInfo.page].sortbtn && obj[cookieId][pageInfo.page].scope) {
               if(isFinite(obj[cookieId][pageInfo.page].sortbtn) === true) {
                  var btnNr 	= obj[cookieId][pageInfo.page].sortbtn,
                     $listTable = $('table.listtable:not(.no-filter)', obj[cookieId][pageInfo.page].scope),
                     $btn		= $('td:eq(' + btnNr + ') .sort-img', $listTable);
               } else {
                  var btnName   = 'cookie-' + obj[cookieId][pageInfo.page].sortbtn,
                     $listTable = $('table.bfl', obj[cookieId][pageInfo.page].scope),
                     $btn       = $('td.' + btnName + ' .sort-img', $listTable);
               }

               if ($btn.size() > 0) {
                  if(obj[cookieId][pageInfo.page].sorttype == 'asc') {
                      $btn.attr('src').split('asc').join('desc');
                      $btn.removeClass('asc').addClass('desc');
                  }


                  if(isFinite(obj[cookieId][pageInfo.page].sortbtn) === false) {
               	   if (obj[cookieId][pageInfo.page].sorttype == 'desc') {
               		   $btn.attr('src', $btn.attr('src').split('asc').join('desc').split('deactive').join('active'));
               		   $btn.removeClass('asc').addClass('desc');
               	   } else {
               		   $btn.attr('src', $btn.attr('src').split('desc').join('asc').split('deactive').join('active'));
               		   $btn.removeClass('desc').addClass('asc');
               	   }
                  }

                  if (noFunctions != true) {
                      sortListTable($btn, obj[cookieId][pageInfo.page].scope);
                  }
               }
            }

            if(obj[cookieId][pageInfo.page].page) {
                $('#cur_page').html(obj[cookieId][pageInfo.page].page);
                $('#cur_page_bottom').html(obj[cookieId][pageInfo.page].page);
            }
         }

         if (callback != false) {
            callback();
         }

         var erkrankung = jQuery.trim($('.erkrankung-info-code').html());
         if(obj[cookieId][erkrankung]) {
            var featureVal = obj[cookieId][erkrankung];
            $('select[name="feature_interface"]').val(featureVal);

            highlightInterfaceFields(featureVal, 'form');
         }

         if($('table.bfl').size() == 0) {
            checkFilterInputState();
         }
      }
   }
}

function getCookieValue(name) {
   var cookies = document.cookie.split('; ');

   for (var i in cookies) {
       var cookie = cookies[i].split('=');
       if (cookie[0] === name) {
           return cookie[1];
       }
   }

   return '';
}

function createFilterCookie(filterForms, page, searchTerm, btn, scope, sortType) {

     var formArr       = new Array,
      searchArr        = new Array,
      cookieId         = $('input[name="cookie_id"]').attr('value'),
      activeCookie     = getCookieValue('filter'),
      countForms       = 0,
      countSearch      = 0,
      feature          = $('select[name="feature_interface"]'),
      reportName       = null
     ;

      if (activeCookie.length > 0) {
         try {
            var obj = JSON.parse(activeCookie);
         } catch (e) {
            var obj = {};
         }
      } else {
         var obj = new Object();
      }

      if (!obj[cookieId]) {
         obj[cookieId] = new Object();
      }

      if (!obj[cookieId][page]) {
         obj[cookieId][page] = new Object();
      }

      if(page == 'report') {
      	reportName = getParameter("name");

      	if (!obj[cookieId][page][reportName]) {
      		obj[cookieId][page][reportName] = new Object();
      	}
      }

      // Durchgehen der aktuellen, mitgegebenen Filterformulare
      if (filterForms != true) {
         for (var i in filterForms) {
            var filterJson = filterForms[i];

            if (jQuery.inArray(filterJson, formArr) == -1) {
               formArr[countForms] = filterJson;
               countForms++;
            }
         }
         if (formArr.length > 0) {
            obj[cookieId][page].forms = new Array();
            obj[cookieId][page].forms = formArr;
         } else {
            delete obj[cookieId][page].forms;
         }
      }

     //BFL existiert zwar, aber obj[cookieId][page].suche wird vorher geleert
      if (searchTerm != true) {
         if(searchTerm && searchTerm.length > 0) {
            obj[cookieId][page].suche = encodeURIComponent(conformSearchChars(searchTerm, true));
         } else {
            delete obj[cookieId][page].suche;
         }
      }

      //BFL existiert
      if($('table.listtable:not(.no-filter)').size() > 0) {
         $(':input.cookie-search-filter').each(function(index) {
        	 var val = encodeURIComponent(conformSearchChars($(this).val(), true));

        	 if(val !== null && val.length > 0) {
               if(!obj[cookieId][page].suche) {
                  obj[cookieId][page].suche = new Object();
               }

               var arrKey = $(this).attr('name').split('-')[2];

               obj[cookieId][page].suche[arrKey] = val;
            }
         });
      }

      if (isTreeBfl() === true) {
         obj[cookieId][page].list = "tree";
      } else {
         delete obj[cookieId][page].list;
      }

      if($('#cur_page').html() > 1) {
         obj[cookieId][page].page = $('#cur_page').html();
      } else {
         delete obj[cookieId][page].page;
      }

      if($('select[name=entries]').attr('value') != 25) {
         obj[cookieId][page].entries = $('select[name=entries]').attr('value');
      } else {
         delete obj[cookieId][page].entries;
      }

      if(page == 'auswertungen') {
         obj[cookieId][page].accordion = $("#accordion").accordion("option", "active");

         if($("#accordion").accordion("option", "active").length < 1) {
            delete obj[cookieId][page].accordion;
         }
      }

      if (page == 'report') {

    	  //Chart
    	  $.each($("input[name^='chart_']"), function(index, currentFilter) {
    		  obj[cookieId][page][reportName][$(currentFilter).attr('name')] = $(currentFilter).val();
	 	  });


          if($('input[name="datum_von"]').length > 0) {
        	  if($('input[name="datum_von"]').val() < 1) {
        		  delete obj[cookieId][page][reportName].datumVon;
        	  } else {
        		  obj[cookieId][page][reportName].datumVon = $('input[name="datum_von"]').val();
        	  }
          }

          if($('input[name="datum_bis"]').length > 0) {
        	  if($('input[name="datum_bis"]').val() < 1) {
        		  delete obj[cookieId][page][reportName].datumBis;
        	  } else {
        		  obj[cookieId][page][reportName].datumBis = $('input[name="datum_bis"]').val();
        	  }
          }

          if($('select[name="jahr"]').length > 0) {
        	  if($('input[name="jahr"]').val() < 1) {
        		  delete obj[cookieId][page][reportName].jahr;
        	  } else {
        		  obj[cookieId][page][reportName].jahr = $('select[name="jahr"]').val();
        	  }
          }

          if($('select[name="datumsbezug"]').length > 0) {
        	  if($('input[name="datumsbezug"]').val() < 1) {
        		  delete obj[cookieId][page][reportName].datumsbezug;
        	  } else {
        		  obj[cookieId][page][reportName].datumsbezug = $('select[name="datumsbezug"]').val();
        	  }
          }

          if($('select[name="kennzahlenjahr"]').length > 0) {
        	  if($('input[name="kennzahlenjahr"]').val() < 1) {
        		  delete obj[cookieId][page][reportName].kennzahlenjahr;
        	  } else {
        		  obj[cookieId][page][reportName].kennzahlenjahr = $('select[name="kennzahlenjahr"]').val();
        	  }
          }
      }

      if(page == 'status') {
         obj[cookieId][page].accstatus = $("#accordion").accordion("option", "active");

         if($("#accordion").accordion("option", "active").length < 1) {
            delete obj[cookieId][page].accstatus;
         }
      }

      if(btn && scope && sortType) {
         obj[cookieId][page].sortbtn  = btn;
         obj[cookieId][page].scope    = scope;
         obj[cookieId][page].sorttype = sortType;
      }

      if(page == 'view.patient') {
    	  if($('div.fotos-head').size() > 0) {
    		  obj[cookieId][page].list = "foto";
    	  } else if ($('div.dokumente-head').size() > 0) {
    		  obj[cookieId][page].list = "dokument";
    	  } else {
    		  delete obj[cookieId][page].list;
        }
      }

      //Sobald cookie bereicht nicht mehr vorhanden, cookie loeschen
      if(!obj[cookieId][page].accordion &&
         !obj[cookieId][page].accstatus &&
         !obj[cookieId][page].entries &&
         !obj[cookieId][page].page &&
         !obj[cookieId][page].list &&
         !obj[cookieId][page].suche &&
         !obj[cookieId][page].forms &&
         !obj[cookieId][page].sortbtn)
      {
    	  if (page == 'report') {
    		  /* Test - bei report nie löschen
    		  if (!obj[cookieId][page][reportName].datumVon &&
    			  !obj[cookieId][page][reportName].datumBis &&
	              !obj[cookieId][page][reportName].jahr &&
	              !obj[cookieId][page][reportName].datumsbezug
    		      ) {
    		     delete obj[cookieId][page];
    		  }*/

    	  } else {
    		  delete obj[cookieId][page];
    	  }
      }

      if(feature.length > 0) {
         var erkrankung = jQuery.trim($('.erkrankung-info-code').html());
         obj[cookieId][erkrankung] = feature.val();
      }

      testJson = JSON.stringify(obj);

      return testJson;
}
