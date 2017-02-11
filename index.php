<?php

/**
 * @copyright 2011-2017 Christoph M. Becker <http://3-magi.net/>
 * @license http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 */

if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

define('YANP_VERSION', '@YANP_VERSION@');

/**
 * @return string
 */
function Yanp_newsbox()
{
    $command = new Yanp\NewsboxCommand();
    ob_start();
    $command->execute();
    return ob_get_clean();
}

/**
 * @param string $icon
 * @return string
 */
function Yanp_feedlink($icon = null)
{
    $command = new Yanp\FeedlinkCommand($icon);
    ob_start();
    $command->execute();
    return ob_get_clean();
}

$temp = new Yanp\Controller();
$temp->dispatch();
