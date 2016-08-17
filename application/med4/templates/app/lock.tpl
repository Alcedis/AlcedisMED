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

{if isset($single) === true}

    {if count($history) > 0}
     <table class="listtable no-filter" style="margin-bottom:15px">
        <tr>
            <td class="head" colspan="5">{#lbl_history#}</td>
        </tr>

         <tr>
            <td class="subhead" style="width:9%">{#lbl_date#}</td>
            <td class="subhead" style="width:8%" align="center">{#lbl_time#}</td>
            <td class="subhead" >{#lbl_user#}</td>
            <td class="subhead" style="width:60%" align="left">{#lbl_bem#}</td>
            <td class="subhead" style="width:6%" align="center">{#lbl_action#}</td>
         </tr>
        {foreach from=$history item=histo key=index}
            {html_odd_even var="class" key=$index}
            <tr>
                <td class="{$class}">
                    {$histo.date}
                </td>

                <td class="{$class}" align="center">
                    {$histo.time}
                </td>

                <td class="{$class}">
                        <strong>{$histo.user}</strong>
                </td>
                <td class="{$class}" align="left">
                    {$histo.bem}
                </td>
                <td class="{$class}" valign="top" align="center">
                    <img src="media/img/app/lock/{$histo.lock}.png" alt="{$histo.lock}">
                </td>
            </tr>
        {/foreach}
    </table>
   {/if}

   {if count($forms.unlocked)}
      <table class="listtable no-filter" >
        <tr>
           <td class="head" colspan="6">{$caption}</td>
        </tr>

         <tr>
            <td class="subhead" style="width:20px"> </td>
            <td class="subhead" style="width:150px"> {#lbl_form#} </td>
            <td class="subhead" style="width:80px" align="center"> {#lbl_date#} </td>
            <td class="subhead"> {#lbl_adds#} </td>
            <td class="subhead" style="width:45px" align="center"> {#lbl_status#} </td>
            <td class="subhead" style="width:90px" align="center"> {#lbl_lock#} </td>
         </tr>
         <tr>
            <td class="even">
               {if in_array($forms.unlocked.0.form, $exclusiveArray) == false}
                  <a href="index.php?page=rec.{$forms.unlocked.0.form}&amp;origin=true&amp;{$forms.unlocked.0.form}_id={$forms.unlocked.0.form_id}&amp;erkrankung_id={$forms.unlocked.0.erkrankung_id}&amp;patient_id={$patient_id}" class="edit"></a>
               {else}
                  <a href="index.php?page=rec.{$forms.unlocked.0.form}&amp;origin=true&amp;{$forms.unlocked.0.form}_id={$forms.unlocked.0.form_id}&amp;patient_id={$patient_id}" class="edit"></a>
               {/if}
            </td>
            <td class="even"><strong>{$forms.unlocked.0.form_config}</strong></td>
            <td class="even" align="center">{$forms.unlocked.0.form_date}</td>
            <td class="even">
               <span style="font-size:9pt"><!--  -->
                  {$forms.unlocked.0.form_data}
               </span>
            </td>
            <td class="even" align="center">
               <img src="media/img/app/ampel/{$forms.unlocked.0.form_status}.png" alt="" />
            </td>
            <td class="even" align="center">
               {if $forms.unlocked.0.lockable !== 0}
               	  <input class="add" type="checkbox" {if $forms.unlocked.0.form_status == 4}checked="checked"{/if} name="lock[{$forms.unlocked.0.status_id}]" />
               {/if}
            </td>
         </tr>
      </table>
   {else}
      <table class="listtable no-filter">
        <tr>
           <td class="head" colspan="6">{$caption}</td>
        </tr>
         <tr>
            <td class="subhead" style="width:20px"> </td>
            <td class="subhead" style="width:150px"> {#lbl_form#} </td>
            <td class="subhead" style="width:80px" align="center"> {#lbl_date#} </td>
            <td class="subhead"> {#lbl_adds#} </td>
            <td class="subhead" style="width:45px" align="center"> {#lbl_status#} </td>
            <td class="subhead" style="width:90px" align="center"> {#lbl_unlock#} </td>
         </tr>
         <tr>
            <td class="even">
               <img alt="1" src="media/img/app/lock/1.png" />
            </td>
            <td class="even"><strong>{$forms.locked.0.form_config}</strong></td>
            <td class="even" align="center">{$forms.locked.0.form_date}</td>
            <td class="even">
               <span style="font-size:9pt"><!--  -->
                  {$forms.locked.0.form_data}
               </span>
            </td>
            <td class="even" align="center">
               <img src="media/img/app/ampel/{$forms.locked.0.form_status}.png" alt="" />
            </td>
            <td class="even" align="center">
            	{if $forms.locked.0.unlockable == 1}
               	<input class="remove" type="checkbox" name="unlock[{$forms.locked.0.status_id}]" />
              	{/if}
            </td>
         </tr>
      </table>
   {/if}
{else}
   <div id="accordion">
      <h3><a href="#">{#lbl_closeable_forms#}</a></h3>
      <div>
         {if count($forms.unlocked)}
            <table class="listtable no-filter" >
               {if $list}
               <tr style="height:35px" class="no-hover">
                  <td colspan="4"></td>

                  <td align="right" colspan="2">
                     <span style="padding-right:37px;">
                        {#lbl_select_all#}
                        <input class="toggle-add" type="checkbox" name="add" />
                     </span>
                  </td>
               </tr>
               {/if}
               <tr>
                  <td class="head" style="width:20px"> </td>
                  <td class="head" style="width:150px"> {#lbl_form#} </td>
                  <td class="head" style="width:80px" align="center"> {#lbl_date#}</td>
                  <td class="head"> {#lbl_adds#} </td>
                  <td class="head" style="width:45px" align="center"> {#lbl_status#} </td>
                  <td class="head" style="width:90px" align="center"> {#lbl_lock#} </td>
               </tr>
               {foreach from=$forms.unlocked key=index item="form"}
                  {html_odd_even var="class" key=$index}
                  <tr>
                     <td class="{$class}">
                        {if in_array($form.form, $exclusiveArray) == false}
                           <a href="index.php?page=rec.{$form.form}&amp;origin=true&amp;{$form.form}_id={$form.form_id}&amp;erkrankung_id={$form.erkrankung_id}&amp;patient_id={$patient_id}" class="edit"></a>
                        {else}
                           <a href="index.php?page=rec.{$form.form}&amp;origin=true&amp;{$form.form}_id={$form.form_id}&amp;patient_id={$patient_id}" class="edit"></a>
                        {/if}
                     </td>
                     <td class="{$class}"><strong>{$form.form_config}</strong></td>
                     <td class="{$class}" align="center">{$form.form_date}</td>
                     <td class="{$class}">
                        <span style="font-size:9pt"><!--  -->
                           {$form.form_data}
                        </span>
                     </td>
                     <td class="{$class}" align="center">
                        <img src="media/img/app/ampel/{$form.form_status}.png" alt="" />
                     </td>
                     <td class="{$class}" align="center">
                        <input class="add" type="checkbox" {if $form.form_status == 4}checked="checked"{/if} name="lock[{$form.status_id}]" />
                     </td>
                  </tr>
               {/foreach}
            </table>
         {else}
            <div class="green-info">
               {#lbl_no_closeable_forms_a#}
            </div>
         {/if}
      </div>


      <h3><a href="#">{#lbl_closed_forms#}</a></h3>
      <div>
         {if count($forms.locked)}
            <table class="listtable no-filter" >
               {if $list}
               <tr style="height:35px">
                  <td colspan="3"></td>

                  <td align="right" colspan="3">
                     <span style="padding-right:37px;">
                        {#lbl_select_all#}
                        <input class="toggle-remove" type="checkbox" name="remove" />
                     </span>
                  </td>
               </tr>
               {/if}
               <tr>
                  <td class="head" style="width:20px"> </td>
                  <td class="head" style="width:150px"> {#lbl_form#} </td>
                  <td class="head" style="width:80px" align="center"> {#lbl_date#}</td>
                  <td class="head"> {#lbl_adds#} </td>
                  <td class="head" style="width:45px" align="center"> {#lbl_status#} </td>
                  <td class="head" style="width:90px" align="center"> {#lbl_unlock#} </td>
               </tr>
               {foreach from=$forms.locked key=index item="form"}
                  {html_odd_even var="class" key=$index}
                  <tr>
                     <td class="{$class}">
                        <img alt="1" src="media/img/app/lock/1.png" />
                     </td>
                     <td class="{$class}"><strong>{$form.form_config}</strong></td>
                     <td class="{$class}" align="center">{$form.form_date}</td>
                     <td class="{$class}">
                        <span style="font-size:9pt"><!--  -->
                           {$form.form_data}
                        </span>
                     </td>
                     <td class="{$class}" align="center">
                        <img src="media/img/app/ampel/{$form.form_status}.png" alt="" />
                     </td>
                     <td class="{$class}" align="center">
                     	{if $form.unlockable == 1}
                        	<input class="remove" type="checkbox" name="unlock[{$form.status_id}]" />
                        {/if}
                     </td>
                  </tr>
               {/foreach}
            </table>
         {else}
            <div class="green-info">
               {#lbl_no_closeable_forms_b#}
            </div>
         {/if}
      </div>
   </div>
{/if}


<table width="100%" class="formtable" style="margin-top:20px">
   <tr>
      <td class="head">{#lbl_bemerkung#}</td>
   </tr>
   <tr class="edt">
      <td align="center" >
         <textarea class="txtarea" name="bem" rows="3" cols="3"></textarea>
      </td>
   </tr>
   <tr>
      <td align="center" style="margin: 0px; padding: 0px;">

      	{if $single && ((count($forms.locked) > 0 && $forms.locked.0.unlockable == 1) || count($forms.unlocked) > 0) || $single == false}
      		<input type="submit" alt="Speichern" value="Speichern" name="action[status]" class="button" />
      	{/if}

         <input type="submit" alt="Abbrechen" value="Abbrechen" name="action[cancel]" class="button" />
      </td>
   </tr>
</table>
<div>
	<input type="hidden" value="{if $return == true}1{else}0{/if}" name="return" />
</div>