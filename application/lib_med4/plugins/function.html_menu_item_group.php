<?php

/*
 * AlcedisMED
 * Copyright (C) 2010-2016  Alcedis GmbH
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function smarty_function_html_menu_item_group($params, &$smarty)
{
    $lbl   = null;
    $color = null;
    $items = array();

    extract($params);

    $html = <<<HTML
<div class="menu-item menu-item-group">
    <span><img class='pointer' src='media/img/base/pointer-{$color}.png' alt='pointer-{$color}'/>{$lbl}</span>
    <ul>
HTML;

    foreach ($items as $item) {
        $html .= <<<HTML
    <li>
        <a href='index.php?page={$item['href']}'>
            <img class='pointer' src='media/img/base/pointer-{$item['color']}.png' alt='pointer-{$item['color']}'/>
            {$item['lbl']}
        </a>
    </li>
HTML;
    }

    $html .= <<<HTML
    </ul>
</div>
HTML;

    return $html;
}

?>
