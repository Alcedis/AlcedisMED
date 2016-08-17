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

{include file='dmp_2013_0_head.tpl'}
{include file='dmp_2013_0_header.tpl'}
<body>
    <section>
{foreach from=$data.body.sections item=section}
        <paragraph>
            <caption>
                <caption_cd DN="{$section.caption}"/>
            </caption>
            <content>
                <local_markup descriptor="sciphox" ignore="all">
                    <sciphox:sciphox-ssu country="de" version="v1" type="observation">
                        <sciphox:Beobachtungen>
{foreach from=$section.observations item=observation}
                            <sciphox:Beobachtung>
                                <sciphox:Parameter DN="{$observation.parameter}"/>
{foreach from=$observation.ergebnistexte item=ergebnistext}
                                <sciphox:Ergebnistext V="{$ergebnistext}"/>
{/foreach}
{foreach from=$observation.zeitpunkte item=zeitpunkt_dttm}
                                <sciphox:Zeitpunkt_dttm V="{$zeitpunkt_dttm}"/>
{/foreach}
                            </sciphox:Beobachtung>
{/foreach}
                        </sciphox:Beobachtungen>
                    </sciphox:sciphox-ssu>
                </local_markup>
            </content>
        </paragraph>
{/foreach}
    </section>
</body>
{include file='dmp_2013_0_foot.tpl'}
