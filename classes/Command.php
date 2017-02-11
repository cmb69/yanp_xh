<?php

/**
 * @copyright 2011-2017 Christoph M. Becker <http://3-magi.net/>
 * @license http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
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
