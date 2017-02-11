<?php

/**
 * @copyright 2011-2017 Christoph M. Becker <http://3-magi.net/>
 * @license http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 */

namespace Yanp;

class Feed
{
    /**
     * @return string
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
     * @return string
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
