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
{if $action === 'confirm'}
    <table width="100%">
        <tr>
            <td style="text-align: center">
                <div style="margin-top: 20px">
                    <img src="media/img/app/krebsregister/state/kr_{$type}.png" />
                </div>
                <div style="margin-top: 30px; margin-bottom: 50px">
                    {#lbl_confirm#}

                    <div class="register-create-file">
                        <a class="create" href="index.php?page=register&feature=krebsregister&type={$type}&pids={$pids}&action=create">
                            {#lbl_create_file#}
                        </a>
                    </div>
                </div>
                <div style="margin-bottom: 20px">
                    <a href="index.php?{$back_btn}" style="text-decoration: underline">
                        {#lbl_back_export#}
                    </a>
                </div>
            </td>
        </tr>
    </table>
{else}
<table width="100%">
    <tr>
        <td style="text-align: center">
            <div style="margin-top: 20px">
                <img src="media/img/app/krebsregister/state/kr_{$type}.png" />
            </div>
            <div style="margin-top: 30px; margin-bottom: 50px">
                <div style="margin-bottom: 15px">{#lbl_file#}{$history.date}:</div>
                <a href="index.php?page=register&feature=krebsregister&type={$type}&id={$history.export_history_id}&action=download">
                    <b>
                        {$history.file}<br/>
                        {#lbl_download#}
                    </b>
                </a>
            </div>
            <div style="margin-bottom: 20px">
                <a href="index.php?page=list.register&feature=krebsregister">
                    {#lbl_back#}
                </a>
            </div>
        </td>
    </tr>
</table>
{/if}
