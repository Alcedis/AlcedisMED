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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
   "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
   {*config_load file=$smarty.const.FILE_CONFIG*}
   {include file="base/head.tpl"}

   <body {if $bodyClass}class="{$bodyClass}"{/if}>

   <div id="impressum" style="display:none;">
       {include file="../templates/base/impressum.tpl"}
   </div>

    <div id="top" {if $topClass}class="{$topClass}"{/if}>
       <div id="menubar">
         {include file=base/menu.tpl}
         {if isset($SESSION.sess_user_id) && $SESSION.sess_user_id > 0}
            <a href="index.php?page=login&amp;state=logout">
                <img id="logout-img" alt="logout" title="" class="popup-trigger" src="media/img/base/logout.png"/>
               </a>
            <div class="info-popup below before" style="display:none;">Abmelden</div>
            {if isset($SESSION.sess_recht_id)}
               <a href="index.php?page=rollenauswahl">
                   <img id="role-change-img" class="popup-trigger" title="" alt="role-change" src="media/img/base/role.png"/>
               </a>
               <div class="info-popup below center" style="display:none;">Rollenauswahl</div>
            {/if}
            <div id="user">
               <div class="text">{#lbl_angemeldet_als#}</div>
               <div class="login-user">{$SESSION.sess_user_name}</div>
            </div>
         {/if}

         </div>
      </div>

      <div id="container">

      {if $infobar}
         <div id="infobar">
            <div id="info-top-left"></div>
            <div id="info-top">
                {if isset($SESSION.sess_org_name) == true || isset($SESSION.sess_rolle_bez) == true}
                    <table class="info-table" border="0" width="100%">
                        <tr>
                            {if isset($SESSION.sess_org_name)}
                                <td style="text-align:left !important">
                                    <strong>{$smarty.config.index_praxis}</strong> {$infobar_org}
                                 </td>
                             {/if}

                            {if isset($SESSION.sess_rolle_bez) == true}
                                <td style="text-align:right !important">
                                        <strong>{$smarty.config.index_rolle}</strong>
                                      {$SESSION.sess_rolle_bez}
                                </td>
                             {/if}
                             {if in_array($smarty.session.sess_rolle_code, array('admin', 'moderator')) == false}
                                 <td style="text-align:right !important; width:1%">
                                     <img class="role-info-img" src="media/img/base/info_small.png" alt="" title=""/>
                                    <div class="role-info-box" style="display:none; text-align:left">
                                      <strong>{#head_erk#}</strong>
                                      <ul>
                                          {foreach from=$smarty.session.sess_recht_erkrankung_bez item=role}
                                              <li style="text-align:left">{$role}</li>
                                          {foreachelse}
                                              <li>{#no_rights#}</li>
                                          {/foreach}
                                      </ul>
                                    </div>
                               </td>
                            {/if}
                        </tr>
                    </table>
                {/if}
            </div>
            <div id="info-top-right"></div>
            <div id="info-bottom-left"></div>
            <div id="info-bottom">
               {include file="base/info.tpl"}
            </div>
            <div id="info-bottom-right"></div>
         </div>
      {/if}
            <div id="content"{if !$infobar} class="extra"{/if}>
            <div id="content-top-left"></div>
            <div id="content-top">
                <table class="menubar-table">
                    <tr>
                        <td>
                           {if $site_img}
                <img class="back-img" src="media/img/{$site_img}" alt="img" title=""/>
                           {/if}

                           {if $back_btn}
                               <a href="index.php?{$back_btn}">
                                   <img class="back-img popup-trigger" src="media/img/base/back.png" alt="back-img" title=""/>
                               </a>
                                   <div class="info-popup above center" style="display:none;">{$smarty.config.btn_lbl_back}</div>
                           {/if}

                           <span class="caption">
                               {if isset($caption)}
                                  {$caption}
                              {else}
                                  {#caption#}
                              {/if}
                            </span>
                        </td>
                        {if $searchbar}
                        <td>
                            <div id="search-filter">
                                <span class="filter-input">
                                    Suche: <input class="search-filter" type="text" name="search-filter"/>
                                    <img id="start-search" class="glass" title="Suche" src="media/img/base/glass.png" alt="search"/>
                                </span>
                            </div>
                        </td>
                        {/if}
                        <td {if $searchbar} style="width:320px;"{/if}>
                            {if $searchbar || $filter || $status_locked || strlen(#file_help#) || $file_help || $interface}
                               <div id="page-info" >
                                  <table class="page-info-table">
                                     <tr>
                                        {if $searchbar}
                                        <td>
                                            <img alt="first" class="popup-trigger page-arrow" src="media/img/base/arrow-left-end.png" title=""/>
                                            <div class="info-popup above center" style="display:none;">{$smarty.config.btn_lbl_start}</div>
                                        </td>
                                        <td>
                                              <img alt="prev" class="popup-trigger page-arrow" src="media/img/base/arrow-left.png" title=""/>
                                              <div class="info-popup above center" style="display:none;">{$smarty.config.btn_lbl_prev_page}</div>
                                         </td>
                                         <td style="width:65px">
                                              <span class="page-counter-display">S.
                                                    <span id="cur_page">1</span><span>/</span><span id="max_page">1</span>
                                             </span>
                                         </td>
                                         <td>
                                            <img alt="next" class="popup-trigger page-arrow" src="media/img/base/arrow-right.png" title=""/>
                                            <div class="info-popup above center" style="display:none;">{$smarty.config.btn_lbl_next_page}</div>
                                         </td>
                                         <td>
                                            <img alt="last" class="popup-trigger page-arrow" src="media/img/base/arrow-right-end.png" title=""/>
                                            <div class="info-popup above center" style="display:none;">{$smarty.config.btn_lbl_end}</div>
                                         </td>
                                         <td>
                                            <select name="entries">
                                               <option value="10">10</option>
                                               <option selected="selected" value="25">25</option>
                                               <option value="50">50</option>
                                            </select>
                                        </td>
                                      {/if}
                                      {$filter}
                                      {if $interface}
                                          <td>
                                             {#lbl_relevant_interface#}
                                                {html_options name=feature_interface options=$interface selected=$interfacePreselect class="input"}
                                          </td>
                                          {/if}
                                          {if $status_locked}
                                          <td>
                                             <span>
                                                {if str_starts_with($status_locked, 'index') === true}
                                                   <a href="{$status_locked}">
                                                    <img src="media/img/base/lock.png" alt="" title=""/>
                                                     </a>
                                                   {else}
                                                       <img src="media/img/base/lock.png" alt="" title=""/>
                                                   {/if}
                                             </span>
                                          </td>
                                      {/if}
                                      {if strlen(#file_help#) || $file_help}
                                        <td>
                                            <div>
                                                <a href="media/help/{#file_help#}{$file_help}" target="_blank">
                                                    <img alt="help" class="searchbar-img" src="media/img/base/help.png" title=""/>
                                                </a>
                                            </div>
                                        </td>
                                      {/if}
                                      </tr>
                                  </table>
                              </div>
                          {/if}
                        </td>
                    </tr>
                </table>
            </div>

            <div id="content-top-right"></div>

            <div id="content-center">
                {if $convertdoc}
                    <div class="render-cpdf">
                        <input type="hidden" name="convertdoc" value="{$convertdoc}" />
                    </div>
        {/if}

                {if strlen($error)}
                    <div class="err">
                        {$error}
                    </div>
                {/if}

                {if count($warn) > 0 AND $page != 'login' AND isset($featureWarn) == false}
                    <div class="warn">
                        {foreach from=$warn item="warnMessage"}
                            {$warnMessage}
                        {/foreach}
                    </div>
                {/if}

                {if is_array($message) === true}
                    <div class="info">
                        {foreach from=$message item="message"}
                            {$message}
                        {/foreach}
                    </div>
                {elseif strlen($message)}
                    <div class="info">
                            {$message}
                    </div>
                {/if}

                {if isset($featureWarn) == true}
                    <div id="slider-message-wrap">
                       <div id="slider-message" style="display:none;">
                            {if isset($warn)}
                                 <div class="warn">
                                    {foreach from=$warn item="warnMessage"}
                                        {$warnMessage}
                                    {/foreach}
                                  </div>
                            {/if}
                              {if isset($featureWarn.feature) == true}
                                    {foreach from=$featureWarn.feature key=featureName item=missingFields}
                                        <div>
                                            <strong>{$featureName}</strong>
                                            {foreach from=$missingFields item=test}
                                                {$test}
                                            {/foreach}
                                        </div>
                                  {/foreach}
                              {/if}
                        </div>
                        <div id="message-slider">
                        <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="width: 2%;">
                                <img alt="warn" style="float:left;margin:4px 10px;vertical-align:middle;" src="media/img/base/sort-asc-deactive.png" title=""/>
                            </td>
                            <td style="width: 96%;">
                                <img alt="warn" style="margin:4px 10px;vertical-align:middle;" src="media/img/base/warn-small.png" title=""/>
                                <span style="line-height:25px;vertical-align:middle;font-weight:bold;">Warnungen</span>
                                <img alt="warn" style="margin:4px 10px;vertical-align:middle;" src="media/img/base/warn-small.png" title=""/>
                            </td>
                            <td style="width: 2%;">
                                <img alt="warn" style="float:right;margin:4px 10px;vertical-align:middle;" src="media/img/base/sort-asc-deactive.png" title=""/>
                            </td>
                        </tr>
                        </table>
                     </div>
                    </div>

                    <div style="margin-top:25px;"></div>
                    {/if}

                {if count($menubar)}
                   <div id="buttonbar">
                    {if $menubar.new}
                        <a href="{if $customMenurbarNew}{$customMenurbarNew}{else}index.php?page=rec.{$page}{$link_param}{/if}" class="button">{#btn_lbl_insert#}</a>
                      {/if}

                      {if $menubar.print}
                         <a href="#"><img class="img" src="media/img/base/printer.png" alt="" title="" /></a>
                      {/if}


                    {if $menubar.custom}
                       {foreach from=$menubar.custom item=button}
                           {$button}
                       {/foreach}
                    {/if}
                    <div class="bfl-load">
                       <div class="bfl-load-text">Lade Daten ..</div>
                       <div class="bfl-load-img"><img src="media/img/base/loader.gif" alt="" title=""/></div>
                    </div>
                   </div>
                {/if}

                {if $sidebar}
                   {include file=$file_sidebar}
                {/if}

               <form action="" method="post" enctype="multipart/form-data">
                        {include file="$body"}

                  <div>
                      {if isset($entryCount) == true}
                         <input type="hidden" name="entrycount"       value="{$entryCount}"/>
                        {/if}
                     <input type="hidden" name="page"       value="{$pageName}"/>
                     <input type="hidden" name="cookie_id"  value="{$SESSION.sess_user_id}"/>
                     {$_createuser} {$_createtime} {$_updateuser} {$_updatetime}
                  </div>
               </form>
                </div>

            {if $searchbar}
               <div id="content-bottom-search-left"></div>
               <div id="content-bottom-search">
                  <div id="search-bottom">
                     <table width="100%">
                        <tr>
                           <td style="width: 3%;">
                               <img alt="first" class="popup-trigger page-arrow" src="media/img/base/arrow-left-end.png" title=""/>
                               <div class="info-popup above center" style="display:none;">{$smarty.config.btn_lbl_start}</div>
                           </td>
                           <td style="width: 3%;">
                               <img alt="prev" class="popup-trigger page-arrow" src="media/img/base/arrow-left.png" title=""/>
                               <div class="info-popup above center" style="display:none;">{$smarty.config.btn_lbl_prev_page}</div>
                            </td>
                            <td style="text-align: center; font-weight:bold; width: 88%;">
                                <span class="page-counter-display">S.
                                   <span id="cur_page_bottom">1</span><span>/</span><span id="max_page_bottom">1</span>
                                </span>
                            </td>
                            <td style="width: 3%;">
                               <img alt="next" class="popup-trigger page-arrow" src="media/img/base/arrow-right.png" title=""/>
                               <div class="info-popup above center" style="display:none;">{$smarty.config.btn_lbl_next_page}</div>
                            </td>
                            <td  style="width: 3%;">
                               <img alt="last" class="popup-trigger page-arrow" src="media/img/base/arrow-right-end.png" title=""/>
                               <div class="info-popup above center" style="display:none;">{$smarty.config.btn_lbl_end}</div>
                            </td>
                         </tr>
                     </table>
                  </div>
               </div>
               <div id="content-bottom-search-right"></div>

            {else}
               <div id="content-bottom-left"></div>
               <div id="content-bottom"></div>
               <div id="content-bottom-right"></div>
            {/if}

         </div>
         <div id="footbar">
             <table width="100%" style="height:64px">
                 <tr>
                     <td style="width:25%; vertical-align: top">
                         <span class="bottom-link">
                             <a href="#" id="impressum-link">{#lbl_impressum#}</a>
                         </span>
                         <span class="bottom-link">
                            <a href="mailto:{#org_email_support#}">{#lbl_mailto#}</a>
                         </span>
                     </td>
                     <td style="text-align: center;vertical-align: top">
                         <span class="bottom-org-text">
                            {if strlen($appSettings.software_custom_title)}
                                {$appSettings.software_custom_title}
                            {else}
                                {$appSettings.software_title} {$appSettings.software_version}
                            {/if} - {#produkt_copyright#} {#org_name#}
                        </span>
                     </td>
                     <td style="width:25%">
                         {if isset($orgLogo) === true}
                             <div style="padding-top:6px">
                                <img src="data:image/{$orgLogoImgType};base64,{$orgLogo}"/>
                             </div>
                         {/if}
                         <!-- -->
                     </td>
                 </tr>
             </table>
         </div>
      </div>
   </body>
</html>
