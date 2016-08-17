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
	pollReport();
});

function pollReport(param) {
	var param = param || $('input[name="convertdoc"]').val(),
		poll = "pollReport('" + param + "')",
		pageInfo = getPageInfo();

	$.ajax({
		type: "POST",
		url: 'index.php?page=convert&feature=convert',
		data: {
			convert : param
		},
		dataType: "json",
		timeout: 25000,
		success: function( responseText, textStatus, XMLHttpRequest ){

			if (pageInfo.page === 'rec.konferenz_patient' || pageInfo.page === 'rec.brief' || pageInfo.page === 'rec.zweitmeinung') {
				switch(responseText.status) {
					case 'waiting':
					case 'process':
						setTimeout(poll, 2000);
						break;

					case 'finished':
						$('#load-report').fadeOut(400, function(){
							$('#show-report').fadeIn();
							$('#gen-report').fadeIn();
							$('#save-report').fadeIn();
						});
					break;
				}
			}
		}
	});
}