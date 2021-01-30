<?php

/**
 * Copyright 2011-2021 Christoph M. Becker
 *
 * This file is part of Yanp_XH.
 *
 * Yanp_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Yanp_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Yanp_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Yanp;

abstract class Command
{
    abstract public function execute();

    /**
     * @return int[]
     */
    protected function getPageIds()
    {
        global $pd_router;

        $allPageData = $pd_router->find_all();
        $ids = array_keys($allPageData);
        $dates = array_map(array($this, 'getLastMod'), $ids);
        array_multisort($dates, SORT_DESC, $ids);
        return array_filter($ids, function ($id) use ($allPageData) {
            return $allPageData[$id]['yanp_description'] != '';
        });
    }

    /**
     * @param int $pageId
     * @return int
     */
    protected function getLastMod($pageId)
    {
        global $pd_router;

        $pageData = $pd_router->find_page($pageId);
        return min(
            isset($pageData['last_edit']) ? $pageData['last_edit'] : 0,
            isset($pageData['yanp_timestamp']) ? $pageData['yanp_timestamp'] : 0
        );
    }

    /**
     * @param int $pageId
     * @return string|HtmlString
     */
    protected function getDescription($pageId)
    {
        global $pd_router, $plugin_cf;

        $pageData = $pd_router->find_page($pageId);
        return $plugin_cf['yanp']['html_markup']
            ? new HtmlString($pageData['yanp_description'])
            : $pageData['yanp_description'];
    }
}
