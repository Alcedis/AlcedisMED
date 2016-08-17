{*
AlcedisMED
Copyright (C) 2010-2016  Alcedis GmbH

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*} 

{foreach from=$appMenuItems item=menuItem}
    {if $menuItem.type == 'item'}
        {html_menu_item href=$menuItem.href lbl=$menuItem.lbl color=$menuItem.color page=$menuItem.page}
    {elseif $menuItem.type == 'group'}
        {html_menu_item_group lbl=$menuItem.lbl color=$menuItem.color items=$menuItem.items}
    {else}
        {include file=$menuItem.source}
    {/if}
{/foreach}
