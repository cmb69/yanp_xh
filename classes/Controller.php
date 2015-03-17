<?php

/**
 * The controllers.
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
 * The controllers.
 *
 * @category CMSimple_XH
 * @package  Yanp
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Yanp_XH
 */
class Yanp_Controller
{
    /**
     * Dispatches on plugin related requests.
     *
     * @return void
     *
     * @global array  The paths of system files and folders.
     * @global object The page data router.
     * @global array  The configuration of the plugins.
     * @global array  The localization of the plugins.
     */
    public function dispatch()
    {
        global $pth, $pd_router, $plugin_cf, $plugin_tx;

        if ($plugin_cf['yanp']['feed_enabled']) {
            $command = new Yanp_RssCommand(new Yanp_Feed());
            $command->execute();
        }
        if (defined('XH_ADM') && XH_ADM) {
            if (function_exists('XH_registerStandardPluginMenuItems')) {
                XH_registerStandardPluginMenuItems(false);
            }

            $pd_router->add_interest('yanp_timestamp');
            $pd_router->add_interest('yanp_description');
            $pd_router->add_tab(
                $plugin_tx['yanp']['tab_label'],
                $pth['folder']['plugins'] . 'yanp/yanp_view.php'
            );

            if ($this->isAdministrationRequested()) {
                $this->handleAdministration();
            }
        }
    }

    /**
     * Returns whether the plugin administration is requested.
     *
     * @return bool
     *
     * @global bool Whether the yanp administration is requested.
     */
    protected function isAdministrationRequested()
    {
        global $yanp;

        return function_exists('XH_wantsPluginAdministration')
            && XH_wantsPluginAdministration('yanp')
            || isset($yanp) && $yanp == 'true';
    }

    /**
     * Handles the plugin administration.
     *
     * @return void
     *
     * @global string The value of the <var>admin</var> GP parameter.
     * @global string The value of the <var>action</var> GP parameter.
     * @global string The (X)HTML to insert before the page contents.
     */
    protected function handleAdministration()
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('off');

        switch ($admin) {
        case '':
            $command = new Yanp_InfoCommand();
            $command->execute();
            break;
        default:
            $o .= plugin_admin_common($action, $admin, 'yanp');
        }
    }
}

?>
