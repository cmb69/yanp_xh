<?php

/**
 * Copyright 2011-2021 Christoph M. Becker
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

class Plugin
{
    const VERSION = "2.2-dev";

    /** @return void */
    public static function dispatch()
    {
        global $pth, $pd_router, $plugin_cf, $plugin_tx;

        if ($plugin_cf['yanp']['feed_enabled']) {
            Dic::rssCommand()->execute();
        }
        /** @phpstan-ignore if.alwaysFalse */
        if (XH_ADM) {
            XH_registerStandardPluginMenuItems(false);

            $pd_router->add_interest('yanp_timestamp');
            $pd_router->add_interest('yanp_description');
            $pd_router->add_tab(
                $plugin_tx['yanp']['tab_label'],
                $pth['folder']['plugins'] . 'yanp/yanp_view.php'
            );

            if (XH_wantsPluginAdministration('yanp')) {
                self::handleAdministration();
            }
        }
    }

    /** @return void */
    private static function handleAdministration()
    {
        global $admin, $o;

        $o .= print_plugin_admin('off');

        switch ($admin) {
            case '':
                $o .= Dic::infoCommand()->execute();
                break;
            default:
                $o .= plugin_admin_common();
        }
    }
}
