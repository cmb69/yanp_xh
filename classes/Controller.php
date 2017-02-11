<?php

/**
 * @copyright 2011-2017 Christoph M. Becker <http://3-magi.net/>
 * @license http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 */

namespace Yanp;

class Controller
{
    public function dispatch()
    {
        global $pth, $pd_router, $plugin_cf, $plugin_tx;

        if ($plugin_cf['yanp']['feed_enabled']) {
            (new RssCommand(new Feed()))->execute();
        }
        if (XH_ADM) {
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
     * @return bool
     */
    protected function isAdministrationRequested()
    {
        global $yanp;

        return function_exists('XH_wantsPluginAdministration')
            && XH_wantsPluginAdministration('yanp')
            || isset($yanp) && $yanp == 'true';
    }

    protected function handleAdministration()
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('off');

        switch ($admin) {
            case '':
                (new InfoCommand())->execute();
                break;
            default:
                $o .= plugin_admin_common($action, $admin, 'yanp');
        }
    }
}
