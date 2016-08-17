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

{section name=i loop=$fields.konferenz_id.value}
    {html_odd_even var="class" key=%i.index%}
    <tr>
        <td colspan="20" style="padding:10px 0 20px 0" class="{$class}">
        <div style="width:100%">
            <div class="conference-head">
                <div style="float:left; width:5%; padding-top:14px; padding-left:5px">
                    {if $fields.final.value[i] != '1'}
                        <a href="index.php?page=konferenz&amp;konferenz_id={$fields.konferenz_id.value[i]}">
                            <img class="popup-trigger" src="media/img/base/konferenz.png" alt="" title=""/>
                        </a>
                        <div class="info-popup above" style="display: none">Konferenz</div>
                    {else}
                        <a href="index.php?page=rec.konferenz_archiv&amp;konferenz_id={$fields.konferenz_id.value[i]}">
                            <img class="popup-trigger" src="media/img/base/btn_archiv.png" alt="" title=""/>
                        </a>
                        <div class="info-popup above" style="display: none">Archiv</div>
                    {/if}
                </div>
                <div style="float:left; width:36%">

                    <div class="conference-left"><span style="font-size:9pt"><b>Datum: {$fields.datum.value[i]}</b></span>
                       <span style="font-size:8pt">{$fields.uhrzeit.value[i]}</span>
                    </div>
                    <div class="conference-left"><span style="font-size:9pt"><b>Moderator:</b> {$fields.moderator_id.value[i]}</span></div>
                    <div class="conference-left"><span style="font-size:9pt"><b>Titel:</b></span> {$fields.bez.value[i]}</div>
                </div>
                <div style="float:left;width:57%">
                    <div style="float:left; padding-top:5px; width:23px">
                        {if $fields.final.value[i] == 0}
                            {if $SESSION.sess_rolle_code == 'moderator'}
                                <a href="{$form_rec}&amp;konferenz_id={$fields.konferenz_id.value[i]}" class="edit"></a>
                            {else}
                                <img src="media/img/base/edit_off.png" alt="" title=""/>
                            {/if}
                        {else}
                            {if $SESSION.sess_rolle_code == 'supervisor'}
                                <a href="{$form_rec}&amp;konferenz_id={$fields.konferenz_id.value[i]}" class="edit"></a>
                            {else}
                                <a href="{$form_rec}&amp;konferenz_id={$fields.konferenz_id.value[i]}">
                                    <img src="media/img/base/archiv.png" alt="" title=""/>
                                </a>
                             {/if}
                        {/if}
                    </div>
                    <div style="float:left; width:70px; margin-left:30px">
                        <div style="float:left; padding-top:7px; padding-right:5px; width:30px; text-align:right">
                            <b>{$fields.konferenz_patienten.value[i]}</b>
                        </div>
                        <div style="float:left">
                            {if $fields.final.value[i] == 0 && $permission.konferenz_dokument == 1}
                                <a href="index.php?page=list.konferenz_patient&amp;konferenz_id={$fields.konferenz_id.value[i]}">
                                    <img src="media/img/base/users.png" alt="" title=""/>
                                </a>
                            {else}
                                <img src="media/img/base/users_off.png" alt="" title=""/>
                            {/if}
                        </div>
                   </div>
                    <div style="float:left;margin-left:55px">
                        {if $fields.konferenz_patienten.value[i] != 0}
                            <input style="margin:0" type="submit" class="report dont_prevent_double_save button_gen_xls" name="action[report][{$fields.konferenz_id.value[i]}]" value="" alt="list-{$fields.konferenz_id.value[i]}"/>
                        {else}
                            <span>
                                <img style="margin:0" src='media/img/base/xls_small_off.png' alt="" title=""/>
                            </span>
                        {/if}
                    </div>
                    <div style="float:left;margin-left:10px">
                       {if $fields.konferenz_patienten.value[i] != 0}
                          <input style="margin:0" type="submit" class="report dont_prevent_double_save button_prnt_rpt" name="action[report][{$fields.konferenz_id.value[i]}]" value="" alt="protokoll-{$fields.konferenz_id.value[i]}"/>
                       {else}
                            <span>
                                <img style="margin:0" src='media/img/base/printer_off.png' alt="" title=""/>
                            </span>
                       {/if}
                    </div>
                    <div style="float:left;margin-left:32px;width:70px">
                        <div style="float:left; padding-top:7px; padding-right:5px; width:30px; text-align:right">
                            <b>{$fields.konferenz_dokumente.value[i]}</b>
                        </div>
                        <div style="float:left">
                            {if $fields.final.value[i] == 0 && $permission.konferenz_dokument == 1}
                                <a href="index.php?page=list.konferenz_dokument&amp;konferenz_id={$fields.konferenz_id.value[i]}">
                                   <img src="media/img/base/document.png" alt="" title=""/>
                                </a>
                            {else}
                                <img src="media/img/base/document_off.png" alt="" title=""/>
                            {/if}
                        </div>
                    </div>
                    <div style="float:left;margin-left:20px;width:95px">
                        <div style="float:right;padding-right:2px;">
                            {if $fields.final.value[i] == 0 && $SESSION.sess_rolle_code == 'moderator'}
                                <a href="index.php?page=list.konferenz_teilnehmer&amp;konferenz_id={$fields.konferenz_id.value[i]}">
                                    <img src="media/img/base/teilnehmer.png" alt="" title=""/>
                                </a>
                            {else}
                                <img src="media/img/base/teilnehmer_off.png" alt="" title=""/>
                            {/if}
                        </div>
                        <div style="float:right;padding-top:7px">
                            <strong><span class="konferenz-teilnehmer-{$fields.teilnehmer_class.value[i]}">{$fields.teilnehmer_bes.value[i]}</span></strong>/<strong>{$fields.teilnehmer.value[i]}</strong>
                        </div>
                    </div>
                    <div style="float:right;">
                        {if $showConclusion}
                            {if $fields.final.value[i] == '1' && $SESSION.sess_rolle_code == 'moderator'}
                                <a href="index.php?page=list.konferenz_abschluss&amp;konferenz_id={$fields.konferenz_id.value[i]}">
                                    <img src="media/img/base/conclusion.png" alt="" title=""/>
                                </a>
                            {else}
                                <img src="media/img/base/conclusion_off.png" alt="" title=""/>
                            {/if}
                        {/if}
                    </div>
                </div>
            </div>
        </div>
        </td>
   </tr>
{sectionelse}
   <tr>
      <td class="even no-data" colspan="11">{#no_dataset#}</td>
   </tr>
{/section}