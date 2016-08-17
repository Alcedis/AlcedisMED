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

<div id="sidebar">
   <table class="sidebartable">
   <tr>
      <td class="subhead" colspan="3"><a id="remove-filter" href="#"><img class="remove-filter-img" src="media/img/base/remove-filter.png" alt="remove-filter"/> {#lbl_filter_entf#}</a></td>
   </tr>

   {html_sidebar_element lbl=#erkrankung# href='rec.erkrankung'  param="patient_id=`$smarty.session.sess_patient_data.patient_id`" ref='erkrankung' permission=$alcPermission}
   {html_sidebar_element lbl=#behandler#  href='rec.behandler'   param="patient_id=`$smarty.session.sess_patient_data.patient_id`" ref='behandler'  permission=$alcPermission}
   {html_sidebar_element lbl=#aufenthalt# href='rec.aufenthalt'  param="patient_id=`$smarty.session.sess_patient_data.patient_id`" ref='aufenthalt' permission=$alcPermission}
   {html_sidebar_element lbl=#abschluss#  href='rec.abschluss'   param="patient_id=`$smarty.session.sess_patient_data.patient_id`" ref='abschluss'  permission=$alcPermission}

	<tr>
		<td class="subhead" colspan="3">{#subhead_weitere_info#}</td>
	</tr>
	{if $fotoExists === true || $dokumentExists === true}
	   {if $fotoExists === true}
    	   <tr>
    	      <td class="lbl" style='width:15%;'>
    	        <img class="filter-img-new" alt="pictures" src="media/img/base/images.png" title=""/>
    	      </td>
    	      <td class="edt" style="width:100%;" colspan="2">
    	          {#lbl_foto#}
    	      </td>
    	   </tr>
    	{/if}

    	{if $dokumentExists === true}
           <tr>
              <td class="lbl" style='width:15%;'>
                <img class="filter-img-new" alt="documents" src="media/img/base/package.png" title=""/>
              </td>
              <td class="edt" style="width:100%;" colspan="2">
                  {#lbl_dokument#}
              </td>
           </tr>
        {/if}
   {else}
      <tr>
         <td class="edt" style="width:100%; font-size:8pt" colspan="3">
             {#no_info#}
         </td>
      </tr>
   {/if}
   </table>
</div>