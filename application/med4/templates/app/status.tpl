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

<table border="0" class="statustable">
   <tr>
      <td class="head" colspan="3">{#lbl_disease#}</td>
   </tr>

   {section name=i loop=$erkrankungen}
      {html_odd_even var="class" key=%i.index%}

      {if $erkrankungen[i].status_id == $selected}
         {assign var="class" value="status-selected"}
      {/if}

      <tr>
         <td class="{$class}" style="width:16px" align="center">
           <a href="index.php?page=status&amp;patient_id={$patientId}&amp;selected={$erkrankungen[i].status_id}&amp;location={$location}">
               <img src="media/img/base/list_small.png" alt="" />
           </a>
         </td>

         <td class="{$class}">
            <a href="index.php?page=status&amp;patient_id={$patientId}&amp;selected={$erkrankungen[i].status_id}&amp;location={$location}">
               <strong>{$erkrankungen[i].form_data}</strong>
            </a>
         </td>

         <td class="{$class}" style="width:16px">
            <a href="index.php?page=status&amp;patient_id={$patientId}&amp;selected={$erkrankungen[i].status_id}&amp;location={$location}">
               <img src="media/img/app/ampel/{$erkrankungen[i].form_param}.png" alt="{$fields.status.bez[i]}" />
            </a>
         </td>
      </tr>
   {sectionelse}
      <tr>
         <td class="even no-data" >{#no_dataset#}</td>
      </tr>
   {/section}
</table>

<div style="margin: 0 10px; float:left; width:735px">
{if $selected}
      {if count($interfaceErrors)}
            <div id="accordion">
               {foreach from=$interfaceErrors key="interfaceName" item="interfaceErrors"}
                  <h3><a href="#">{$interfaceErrors.lbl}</a></h3>

                  <div>
                     {if isset($interfaceErrors.eCheck)}
                        <div style="text-align:center; padding: 10px 0px">
                              <strong>{#lbl_fehlende_formulare#}</strong>
                        </div>

                        <div class="warn" style="background-image:none; padding-left: 5px !important">
                           {foreach from=$interfaceErrors.eCheck item="errors"}

	                           <table width="100%">
	                           <tr>
	                           	<td colspan="2">
	                           		{$errors.msg}<br/><br/>
	                           	</td>
	                           </tr>
	                           {foreach from=$errors.forms key="form" item="formName"}
	                           	<tr>
	                                 <td align="center" style="width: 40px">
	                                    <a href="index.php?page=rec.{$form}&amp;patient_id={$patientId}&amp;erkrankung_id={$erkrankungId}&amp;interface_preselect={$interfaceName}&amp;origin=true">
	                                       <img src="media/img/base/add_normal.png" alt="" />
	                                    </a>
	                                 </td>
	                                 <td>
	                                    <strong>{$formName}</strong>
	                                 </td>
	                              </tr>
	                           {/foreach}
	                           </table>
	                           <br/>
                          	{/foreach}
                        </div>
                     {elseif (false)}
                        <div style="text-align:center; padding: 10px 0px">
                              <strong>{#lbl_fehlende_formulare#}</strong>
                        </div>
                        <div class="green-info">
                           {#lbl_alle_angelegt#}
                        </div>
                     {/if}

                     {if isset($interfaceErrors.validation)}
                        <div style="text-align:center; padding: 15px 0px 10px 0px">
                           <strong>{#lbl_unvoll_formulare#}</strong>
                        </div>

                        <div>
                           <table class="formtable listtable no-filter">
                              <tr>
                                 <td class="subhead" style="width:40px"></td>
                                 <td class="subhead" style="width:130px">{#lbl_formular#}</td>
                                 <td class="subhead" style="text-align:center;width:80px;">{#lbl_datum#}</td>
                                 <td class="subhead" >{#lbl_feld#}</td>

                              </tr>
                           {foreach from=$interfaceErrors.validation.data key="formName" item="formData"}
                                 {foreach from=$formData.data key="form_id" item="formFields" }

                                    <tr valign="top">
                                       <td class="edt" align="center" style="vertical-align:middle;">
                                          <a href="index.php?page=rec.{$formName}&amp;{$formName}_id={$form_id}&amp;erkrankung_id={$erkrankungId}&amp;interface_preselect={$interfaceName}&amp;origin=true" class="edit"></a>
                                       </td>
                                       <td class="edt" style="vertical-align:middle;">
                                          {$formData.lbl}
                                       </td>
                                       <td class="edt" style="text-align:center;vertical-align:middle;">
                                          {$formFields.form_date}
                                       </td>
                                       <td class="edt" style="vertical-align:middle;">
                                          <ul style="list-style: disc;padding:0px 0px 0px 18px;margin:0px;">
                                          {foreach from=$formFields.data key="fieldName" item="fieldLabel"}
                                             <li>{$fieldLabel}</li>
                                          {/foreach}
                                          </ul>
                                       </td>

                                    </tr>
                                 {/foreach}
                           {/foreach}
                           </table>
                        </div>
                     {else}
                        <div style="text-align:center; padding: 15px 0px 10px 0px">
                              <strong>{#lbl_unvoll_formulare#}</strong>
                        </div>

                        <div class="green-info">
                           {#lbl_no_errors#}
                        </div>
                     {/if}
                  </div>
               {/foreach}
            </div>
         {else}
            <div class="green-info">
               {$no_errors}
            </div>
         {/if}
{else}

   <div class="info">
      {#lbl_select_disease#}
   </div>

{/if}

</div>