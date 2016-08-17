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

{if $page != 'code_ktst'}
<table class="formtable msg" border="1">
   <tr>
      {if count($top10) > 0}
         <td style="width:100px">
            <table class="button-container">
               <tr>
                  <td>
                     <a hreflang="top" class="codepicker-link">
                        <img src="media/img/app/codepicker/top.png" alt="" />
                     </a>
                  </td>
                  <td>
                     <a hreflang="top" class="codepicker-link"><span>Top {$top10|@count}</span></a>
               </tr>
            </table>

         </td>
      {/if}

      <td style="width:82px">
         <table class="button-container">
            <tr>
               <td>
                  <a hreflang="catalogue" class="codepicker-link">
                     <img src="media/img/app/codepicker/catalogue.png" alt="" />
                  </a>
               </td>
               <td>
                  <a hreflang="catalogue" class="codepicker-link"><span>Katalog</span></a>
            </tr>
         </table>
      </td>
      <td style="padding:0 !important">
         <span id="c_history" class="code-history"><!--  --></span>
      </td>
      <td align="right"><input type="text" class="input" name="suche" value="{$suche}"></td>
   	<td width="1%"><input style="margin:0px;" type="button"class="button" name="search" value="Suchen" onClick="searchCodepickerCode();"></td>
   </tr>
</table>
{/if}