<?php

/**
 * Pagedata view of Yanp_XH.
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

/**
 * Returns the (X)HTML of the pagedata form.
 *
 * @param array $page An array of page data.
 *
 * @return string
 *
 * @global object The plugin controller.
 */
function Yanp_view(array $page)
{
    $command = new Yanp_PageDataCommand($page);
    ob_start();
    $command->execute();
    return ob_get_clean();
}

?>
