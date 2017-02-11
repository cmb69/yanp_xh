<?php

/**
 * The commands.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Yanp
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2011-2017 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Yanp_XH
 */

/**
 * The commands.
 *
 * @category CMSimple_XH
 * @package  Yanp
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Yanp_XH
 */
abstract class Yanp_Command
{
    /**
     * Executes the command.
     *
     * @return void
     */
    abstract public function execute();

    /**
     * Returns the accumulated result of calling $func on all news.
     *
     * @param string $func A callback function.
     *
     * @return string
     *
     * @global object The page data router.
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
     * Returns the last modification date of a page wrt. Yanp.
     *
     * @param array $pageData An array of page data.
     *
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

?>
