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
    pollStatus();
});

function pollStatus(){
    $.ajax({
        type: "GET",
        url: 'index.php?page=validationstatus&feature=status&ajax=true',
        dataType: "html",
        timeout: 20000,
        async: true,
        success: function(responseText, textStatus, XMLHttpRequest) {
            $('#validationstatus').html(responseText);

            setTimeout(pollStatus, 3000);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {}
    });
}
