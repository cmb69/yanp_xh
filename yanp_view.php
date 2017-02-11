<?php

/**
 * @copyright 2011-2017 Christoph M. Becker <http://3-magi.net/>
 * @license http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 */

/**
 * @return string
 */
function Yanp_view(array $page)
{
    ob_start();
    (new Yanp\PageDataCommand($page))->execute();
    return ob_get_clean();
}
