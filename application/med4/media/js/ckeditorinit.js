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

$(function() {

	if ($('#editor').length == 1) {
		var pageInfo = getPageInfo();
		
		CKEDITOR.replace( 'editor', {height:"400"});
		CKEDITOR.config.baseHref 	= pageInfo.baseHref;
		CKEDITOR.config.contentsCss = [
	       CKEDITOR.config.contentsCss, 
	       'index.php?feature=convert&page=customcss&id=' + pageInfo.form_id + '&type=' + pageInfo.pageName
	    ];
	}

});