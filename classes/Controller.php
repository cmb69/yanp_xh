<?php

/**
 * Copyright 2011-2017 Christoph M. Becker
 *
 * This file is part of Yanp_XH.
 *
 * Yanp_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Yanp_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Yanp_XH.  If not, see <http://www.gnu.org/licenses/>.
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
