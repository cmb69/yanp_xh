<?php

/**
 * The feeds.
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
 * The feeds.
 *
 * @category CMSimple_XH
 * @package  Yanp
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Yanp_XH
 */
class Yanp_Feed
{
    /**
     * Returns the feed title.
     *
     * @return string
     *
     * @global array The localization of the core.
     * @global array The localization of the plugins.
     */
    public function getTitle()
    {
        global $tx, $plugin_tx;

        if ($plugin_tx['yanp']['feed_title'] != '') {
            return $plugin_tx['yanp']['feed_title'];
        } else {
            return $tx['site']['title'];
        }
    }

    /**
     * Returns the feed description.
     *
     * @return string
     *
     * @global array The localization of the core.
     * @global array The localization of the plugins.
     */
    public function getDescription()
    {
        global $tx, $plugin_tx;

        if ($plugin_tx['yanp']['feed_description'] != '') {
            return $plugin_tx['yanp']['feed_description'];
        } else {
            return $tx['meta']['description'];
        }
    }
}

?>
