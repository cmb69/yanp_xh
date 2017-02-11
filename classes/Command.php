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
     * @param callable $func
     * @return string
     */
    protected function renderItems($func)
    {
        global $pd_router;

        $allPageData = $pd_router->find_all();
        $ids = array_keys($allPageData);
        $dates = array_map(array($this, 'getLastMod'), $allPageData);
        array_multisort($dates, SORT_DESC, $ids);
        $res = '';
        foreach ($ids as $id) {
            if (!empty($allPageData[$id]['yanp_description'])) {
                $res .= call_user_func($func, $id);
            }
        }
        return $res;
    }

    /**
     * @return int
     */
    protected function getLastMod(array $pageData)
    {
        return min(
            isset($pageData['last_edit']) ? $pageData['last_edit'] : 0,
            isset($pageData['yanp_timestamp']) ? $pageData['yanp_timestamp'] : 0
        );
    }
}
