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

{include file='base/user_control.tpl'}

<table class="listtable no-filter no-hover msg">
    <tr>
        <td class="head" style="width:50%">{#lbl_optionen#}</td>
        <td class="head">{#lbl_hilfe#}</td>
    </tr>
    <tr>
        <td class="edt">
            <ul>
                {if ( $SESSION.sess_rolle_code == "supervisor" ) }
                    {if strpos($SESSION.settings.interfaces, 'dmp_2014') !== false}
                        <li>
                            <a class="link" href="index.php?page=list.dmp_nummern_2013">{#dmpNummern2013#}</a>
                        </li>
                    {/if}
                {/if}
                <li>
                    <a class="link" href="index.php?page=user_setup">{#user_setup#}</a>
                </li>
            </ul>
        </td>
        <td class="edt">
            <ul>
                {if $demo == true}
                    <li>
                        <a class="link" href="./media/help/app/demo_manual.pdf" target="_blank">{#demo_kurzanleitung#}</a>
                    </li>
                {/if}
                <li>
                    <a class="link" href="./media/help/app/manual.pdf" target="_blank">{#handbuch#}</a>
                </li>
                {if $releaseNotes}
                    <li>
                        <a class="link" href="./{$releaseNotes}" target="_blank">{#release_notes#} (v.{$appSettings.software_version})</a>
                    </li>
                {/if}
            </ul>
        </td>
    </tr>
    {if in_array($SESSION.sess_rolle_code, array('supervisor', 'dokumentar', 'datenmanager')) === true}
    <tr>
        <td class="head">{#import#}</td>
        <td class="head">{#export#}</td>
    </tr>
    <tr>
        <td class="edt" valign="top" height="100">
            <ul>
            {if strpos($SESSION.settings.interfaces, 'patho_i') !== false}
                <li>
                    <a class="link" href="index.php?page=patho_i&amp;feature=import">{#import_patho#}</a>&nbsp;&nbsp;<a class="link" href="media/help/patho.pdf" target="_blank"><img src="media/img/base/help_small.png" border="0" alt="Hilfe"></a>
                </li>
                ({#import_startet_direkt#})
            {/if}
            </ul>
        </td>
        <td class="edt" valign="top" height="100">
            <ul id="exports_list">
                {if #view_bqs#}
                    <li>
                        <a class="link" href="index.php?page=export_bqs">{#export_bqs#}</a>
                        <a class="link" href="index.php?page=import_bqs">{#import_bqs#}</a>
                    </li>
                {/if}
                <li>
                    <a class="link" href="index.php?page=export_csv">{#export_csv#}</a>
                    <a class="link" href="media/help/exports/csv.pdf" target="_blank"><img src="media/img/base/help_small.png" border="0" alt="{#lbl_help#}"></a>
                </li>
                {if in_array('gekid', explode(',' , $SESSION.settings.interfaces)) !== false}
                    <li>
                        <a class="link" href="index.php?page=export_gekid">{#export_gekid#}</a>&nbsp;&nbsp;
                        <a class="link" href="media/help/exports/GEKID.pdf" target="_blank"><img src="media/img/base/help_small.png" border="0" alt="{#lbl_help#}"></a>
                   </li>
                {/if}
                {if strpos($SESSION.settings.interfaces, 'gekid_plus') !== false}
                    <li>
                        <a class="link" href="index.php?page=gekid_plus&amp;feature=export">{#export_gekid_plus#}</a>&nbsp;&nbsp;
                        <a class="link" href="media/help/exports/gekid_plus.pdf" target="_blank">
                            <img src="media/img/base/help_small.png" border="0" alt="{#lbl_help#}">
                        </a>
                    </li>
                {/if}
                {if strpos($SESSION.settings.interfaces, 'dmp_2014') !== false}
                    <li>
                        <a class="link" href="index.php?page=dmp_2014&amp;feature=export">{#export_dmp_2014#}</a>&nbsp;&nbsp;
                        <a class="link" href="media/help/exports/dmp_2014.pdf" target="_blank"><img src="media/img/base/help_small.png" border="0" alt="{#lbl_help#}"></a>
                    </li>
                {/if}
                {if strpos($SESSION.settings.interfaces, 'krbw') !== false}
                    <li>
                        <a class="link" href="index.php?page=krbw&amp;feature=export">{#export_krbw#}</a>&nbsp;&nbsp;
                        <a class="link" href="media/help/exports/krbw.pdf" target="_blank">
                            <img src="media/img/base/help_small.png" border="0" alt="{#lbl_help#}">
                        </a>
                    </li>
                {/if}
                {if strpos($SESSION.settings.interfaces, 'onkeyline') !== false}
                    <li>
                        <a class="link" href="index.php?page=onkeyline&amp;feature=export">{#export_onkeyline#}</a>&nbsp;&nbsp;
                        <a class="link" href="media/help/exports/onkeyline.pdf" target="_blank">
                            <img src="media/img/base/help_small.png" border="0" alt="{#lbl_help#}">
                        </a>
                    </li>
                {/if}
                {if strpos($SESSION.settings.interfaces, 'ekr_rp') !== false}
                    <li>
                        <a class="link" href="index.php?page=export_krrp">{#export_krrp#}</a>
                        <a class="link" href="media/help/exports/krrp.pdf" target="_blank"><img src="media/img/base/help_small.png" border="0" alt="{#lbl_help#}"></a>
                    </li>
                {/if}
                {if strpos($SESSION.settings.interfaces, 'eusoma_todo') !== false}
                   <li>
                       <a class="link" href="index.php?page=export_eusoma">{#export_eusoma#}</a>
                   </li>
                {/if}
                {if strpos($SESSION.settings.interfaces, 'gkr') !== false}
                    <li>
                        <a class="link" href="index.php?page=export_gkr">{#export_gkr_old#}</a>
                        <a class="link" href="media/help/exports/gkr.pdf" target="_blank">
                            <img src="media/img/base/help_small.png" border="0" alt="{#lbl_help#}">
                        </a>
                    </li>
                    <li>
                        <a class="link" href="index.php?page=gkr&amp;feature=export">{#export_gkr#}</a>&nbsp;&nbsp;
                        <a class="link" href="media/help/exports/gkr_neu.pdf" target="_blank">
                            <img src="media/img/base/help_small.png" border="0" alt="{#lbl_help#}">
                        </a>
                    </li>
                {/if}
                {if strpos($SESSION.settings.interfaces, 'onkonet_todo') !== false}
                    <li><a class="link" href="index.php?page=export_onkonet">{#export_onkonet#}</a></li>
                {/if}
                {if #activate_exp_gtds#}
                    <li>
                        <a class="link" href="index.php?page=export_gtds">{#export_gtds#}</a>
                    </li>
                {/if}
                {if #view_reg_hh#}
                    <li>
                        <a class="link" href="index.php?page=export_reg_hh">{#export_reg_hh#}</a>
                    </li>
                {/if}
                {if strpos($SESSION.settings.interfaces, 'ekr_h') !== false}
                    <li>
                        <a class="link" href="index.php?page=export_krhe">{#export_hessen#}</a>&nbsp;&nbsp;
                        <a class="link" href="media/help/exports/krhe.pdf" target="_blank">
                            <img src="media/img/base/help_small.png" border="0" alt="{#lbl_help#}">
                        </a>
                    </li>
                {/if}
                {if #view_reg_sh#}
                    <li>
                        <a class="link" href="index.php?page=export_reg_sh">{#export_sh#}</a>
                    </li>
                {/if}
                {if strpos($SESSION.settings.interfaces, 'qs181') !== false}
                    <li>
                        <a class="link" href="index.php?page=qsmed&amp;feature=exports">{#export_qsmed#}</a>
                        <a class="link" href="media/help/exports/qsmed.pdf" target="_blank">
                            <img src="media/img/base/help_small.png" border="0" alt="{#lbl_help#}">
                        </a>
                    </li>
                {/if}
                {if strpos($SESSION.settings.interfaces, 'wbc') !== false}
                    <li>
                        <a class="link" href="index.php?page=wbc&amp;feature=export">{#export_wbc#}</a>&nbsp;&nbsp;
                        <a class="link" href="media/help/exports/wbc.pdf" target="_blank">
                            <img src="media/img/base/help_small.png" border="0" alt="{#lbl_help#}">
                        </a>
                    </li>
                {/if}
                {if strpos($SESSION.settings.interfaces, 'wdc') !== false}
                    <li>
                        <a class="link" href="index.php?page=wdc&amp;feature=export">{#export_wdc#}</a>&nbsp;&nbsp;
                        <a class="link" href="media/help/exports/wdc.pdf" target="_blank">
                            <img src="media/img/base/help_small.png" border="0" alt="{#lbl_help#}">
                        </a>
                    </li>
                {/if}
                {if #activate_exp_adt#}
                    <li>
                        <a class="link" href="index.php?page=export_adt">{#export_adt#}</a>
                    </li>
                {/if}
                {if strpos($SESSION.settings.interfaces, 'patho_e') !== false}
                    <li>
                        <a class="link" href="index.php?page=patho_e&amp;feature=export">{#export_patho#}</a>&nbsp;&nbsp;
                        <a class="link" href="media/help/patho.pdf" target="_blank"><img src="media/img/base/help_small.png" border="0" alt="Hilfe"></a>
                    </li>
                {/if}
            </ul>
        </td>
    </tr>
    {/if}
</table>
