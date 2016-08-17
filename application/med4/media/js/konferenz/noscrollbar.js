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

var bname;
var NoScrollbar = {

initialize: function(){
this.debugging = false;
this.busyCount = 0;
this._debug('initialize');

bname = navigator.appName;
//alert(bname +  'is browser');

document.getElementById('flashDiv').onload = this.start;

if(window.addEventListener)
/** DOMMouseScroll is for mozilla. */

window.addEventListener('DOMMouseScroll', this.wheel, false);

/** IE/Opera. */
window.onmousewheel = document.onmousewheel = this.wheel;

if (window.attachEvent) //IE exclusive method for binding an event
window.attachEvent("onmousewheel", this.wheel);

},

start: function(){
window.document.network_map.focus();
},
//caputer event and do nothing with it.
wheel: function(event){

if(this.bname == "Netscape"){
//alert(this.bname);
if (event.detail)
delta = 0;

if (event.preventDefault){
//console.log('prevent default exists');
event.preventDefault();
event.returnValue = false;
}

}
return false;

},

_debug: function(msg){
if( this.debugging ) console.log(msg);
}
};
