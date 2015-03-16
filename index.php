<?php

/**
 * Front-end of Yanp_XH.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Yanp
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2011-2015 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Yanp_XH
 */

/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

/**
 * The plugin version.
 */
define('YANP_VERSION', '@YANP_VERSION@');

/**
 * Returns the (X)HTML for the display of the complete newsbox.
 *
 * @return string
 *
 * @global object The plugin controller.
 */
function Yanp_newsbox()
{
    global $_Yanp_controller;

    return $_Yanp_controller->renderNewsbox();
}

/**
 * Returns the (X)HTML of the RSS link.
 *
 * @param string $icon An icon filename.
 *
 * @return string
 *
 * @global object The plugin controller
 */
function Yanp_feedlink($icon = null)
{
    global $_Yanp_controller;

    return $_Yanp_controller->renderFeedLink($icon);
}

/**
 * The plugin controller.
 */
$_Yanp_controller = new Yanp_Controller();
$_Yanp_controller->dispatch();

?>
