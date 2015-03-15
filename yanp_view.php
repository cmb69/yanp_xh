<?php

/**
 * Pagedata view of Yanp_XH.
 *
 * PHP versions 4 and 5
 *
 * @category  CMSimple_XH
 * @package   Yanp
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2011-2015 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Realblog_XH
 */

/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

/**
 * Returns the (X)HTML of the pagedata form.
 *
 * @param array $page An array of page data.
 *
 * @return string
 *
 * @global array  The localization of the core.
 * @global string The script name.
 * @global string The requested page URL.
 * @global array  The paths of system files and folders.
 * @global array  The configuration of the plugins.
 */
function Yanp_view($page)
{
    global $tx, $sn, $su, $pth, $plugin_tx;

    $ptx = $plugin_tx['yanp'];
    $help_icon = tag(
        'image src="' . $pth['folder']['plugins'] . 'yanp/images/help.png"'
        . ' alt="help"'
    );
    $htm = '<form id="yanp" action="' . $sn . '?' . $su
        . '" method="post" onsubmit="return true">' . "\n"
        . '<p><strong>' . $ptx['tab_form_label'] . '</strong></p>' . "\n";
    $htm .= tag(
        'input type="hidden" name="yanp_timestamp" value="' . time() . '"'
    );
    $htm .= '<a class="pl_tooltip" href="javascript:return false">' . $help_icon
        . '<span>' . $ptx['tab_description_info'] . '</span></a>&nbsp;'
        . '<label for="yanp_description"><span>' . $ptx['tab_description_label']
        . '</span></label>' . tag('br') . "\n";
    $htm .= '<textarea id="yanp_description" name="yanp_description" cols="40"'
        . ' row="10">'
        . $page['yanp_description'] . '</textarea>' . "\n";

    $htm .= tag('input type="hidden" name="save_page_data"') . "\n"
        . '<div style="text-align: right">' . "\n"
        . tag(
            'input type="submit" value="' . ucfirst($tx['action']['save']) . '"'
        ) . "\n"
        . '</div></form>' . "\n";
    return $htm;
}

?>
