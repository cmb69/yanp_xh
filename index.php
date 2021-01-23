<?php

/**
 * @copyright 2011-2017 Christoph M. Becker <http://3-magi.net/>
 * @license http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 */

define('YANP_VERSION', '1pl5');

/**
 * @return string
 */
function Yanp_newsbox()
{
    ob_start();
    (new Yanp\NewsboxCommand())->execute();
    return ob_get_clean();
}

/**
 * @param string $icon
 * @return string
 */
function Yanp_feedlink($icon = null)
{
    ob_start();
    (new Yanp\FeedlinkCommand($icon))->execute();
    return ob_get_clean();
}

(new Yanp\Controller())->dispatch();
