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

$(function () {
	loadBubbles();
});

function loadBubbles(scope)
{
	var scope = scope || 'body';
	$('.bubbleTrigger', scope).each(function () {
	    var $this    = $(this),
	        $trigger = $('.trigger', $this),
	        $popup   = $('.bubbleInfo', $this);


	    $trigger.unbind().mouseenter(function(){
	        var position = $trigger.position();
	        $popup.css({
	            'top'  : position.top-5,
	            'left' : position.left+30
	        });

	        $('.bubbleInfo', scope).fadeOut('fast');
	        if ($.browser.msie) {
	            $popup.show();
	        } else {
	            $popup.fadeIn('fast');
	        }
	    }).mouseleave(function(){
	        if ($.browser.msie) {
                $popup.hide();
            } else {
                $popup.fadeOut('fast');
            }
        });

	});
}