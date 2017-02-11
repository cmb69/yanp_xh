<?php

/**
 * @copyright 2015-2017 Christoph M. Becker <http://3-magi.net/>
 * @license http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 */

/**
 * @param string $class
 */
function Yanp_autoload($class)
{
    global $pth;

    $parts = explode('\\', $class, 2);
    if ($parts[0] == 'Yanp') {
        include_once $pth['folder']['plugins'] . 'yanp/classes/'
            . $parts[1] . '.php';
    }
}

spl_autoload_register('Yanp_autoload');
