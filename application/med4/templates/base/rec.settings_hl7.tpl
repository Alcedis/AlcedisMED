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

{if $hl7Active === true}
    <table width="100%" >
       <tr>
          <td>
             <input type="submit" alt="" value="HL7 Layout" name="action[show]" class="button"/>
          </td>
       </tr>
    </table>
{/if}
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">{#head_hl7#}</a></li>
		<li><a href="#tabs-2">{#head_hl7_field#}</a></li>
	</ul>
	<div id="tabs-1">
		<table class="formtable msg">
		   {html_set_header caption=#head_bem# class="head"}
           {html_set_header caption=$_bem class="edt"}
           {html_set_header caption=#head_config# class="head"}
		   {html_set_row caption=$_active_lbl                       input=$_active}
		   {html_set_row caption=$_import_mode_lbl                  input=$_import_mode}
           {html_set_row caption=$_update_patient_due_caching_lbl   input=$_update_patient_due_caching}
		   {html_set_row caption=$_patient_ident_lbl                input=$_patient_ident}
		   {html_set_row caption=$_user_ident_lbl                	input=$_user_ident}
		   {html_set_row caption=$_max_log_time_lbl           input=$_max_log_time add=#lbl_tage#}
		   {html_set_row caption=$_max_usability_time_lbl           input=$_max_usability_time add=#lbl_tage#}
		   {html_set_row caption=$_cache_dir_lbl                    input=$_cache_dir}
		   {html_set_row caption=$_valid_event_types_lbl            input=$_valid_event_types}
		</table>
        <br/>
		<table class="formtable msg">
			{html_set_header caption=#head_hl7_chache_filter# class=head colspan=4}
		   <tr>
		   	<td class="subhead">{#lbl_aktiv#}</td>
		   	<td class="subhead">{#lbl_name#}</td>
		   	<td class="subhead">{#lbl_segment#}</td>
		   	<td class="subhead">{#lbl_filter#}</td>
		   </tr>
           <tr>
                <td class="edt" align="center">{$_cache_diagnosetyp_active}</td>
                <td class="edt">{$_cache_diagnosetyp_active_lbl}</td>
                <td class="edt">{$_cache_diagnosetyp_hl7}</td>
                <td class="edt">{$_cache_diagnosetyp_filter}</td>
           </tr>
           <tr>
               <td colspan="4" style="padding:0">
                   <div class="info-msg" style="margin-bottom:0 !important">
                      {#info_diagnose#}
                   </div>
               </td>
           </tr>
		   <tr>
                <td class="edt" align="center">{$_cache_diagnose_active}</td>
                <td class="edt">{$_cache_diagnose_active_lbl}</td>
                <td class="edt">{$_cache_diagnose_hl7}</td>
                <td class="edt">{$_cache_diagnose_filter}</td>
		   </tr>
            <tr>
                <td class="subhead">{#lbl_aktiv#}</td>
                <td class="subhead">{#lbl_name#}</td>
                <td class="subhead">{#lbl_segment#}</td>
                <td class="subhead">{#lbl_filter#}</td>
            </tr>
		   <tr>
		   	<td class="edt" align="center">{$_cache_abteilung_active}</td>
		   	<td class="edt">{$_cache_abteilung_active_lbl}</td>
		   	<td class="edt">{$_cache_abteilung_hl7}</td>
		   	<td class="edt">{$_cache_abteilung_filter}</td>
		   </tr>
        </table>
        <br/>
        <table class="formtable msg">
		   {html_set_header caption=#head_hl7_import_filter# class=head colspan=4}
		   <tr>
                <td class="subhead">{#lbl_aktiv#}</td>
                <td class="subhead">{#lbl_name#}</td>
                <td class="subhead">{#lbl_segment#}</td>
                <td class="subhead">{#lbl_filter#}</td>
		   </tr>
           <tr>
                <td class="edt" align="center">{$_import_diagnosetyp_active}</td>
                <td class="edt">{$_import_diagnosetyp_active_lbl}</td>
                <td class="edt">{$_import_diagnosetyp_hl7}</td>
                <td class="edt">{$_import_diagnosetyp_filter}</td>
           </tr>
           <tr>
		   	<td class="edt" align="center">{$_import_diagnose_active}</td>
		   	<td class="edt">{$_import_diagnose_active_lbl}</td>
		   	<td class="edt">{$_import_diagnose_hl7}</td>
		   	<td class="edt">{$_import_diagnose_filter}</td>
		   </tr>
		</table>
	</div>
	<div id="tabs-2">
		<div class="dlist" id="dlist_hl7field">
		   <div class="add">
		      <input class="button" type="button" name="recht_erkrankung" value="Hinzuf&uuml;gen" onclick="execute_request(this,'rec.settings_hl7field', null, ['settings_hl7_id'])"/>
		   </div>
		</div>
	</div>
</div>

{html_set_buttons modus=$button}

<div>
{$_settings_hl7_id}
</div>

{literal}<script type="text/javascript">
   $(function(){$(window).bind('mousemove.hide', function(){
      $('.dlistloading').size() && $('.dlistloading').hide() && $(this).unbind('mousemove.hide');
   });});
</script>{/literal}
