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

use ReflectionClass;
use ReflectionMethod;

class Plugin
{
    const VERSION = "2.1-dev";

    /** @return void */
    public static function dispatch()
    {
        global $pth, $pd_router, $plugin_cf, $plugin_tx;

        self::registerUserFunctions();
        if ($plugin_cf['yanp']['feed_enabled']) {
            (new RssCommand(self::getNewsService(), new Feed, new View))->execute();
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
                ob_start();
                (new InfoCommand(new View))->execute();
                $o .= ob_get_clean();
                break;
            default:
                $o .= plugin_admin_common();
        }
    }

    /** @return void */
    private static function registerUserFunctions()
    {
        $rc = new ReflectionClass(self::class);
        foreach ($rc->getMethods(ReflectionMethod::IS_PUBLIC) as $rm) {
            if (substr_compare($rm->getName(), "Command", -strlen("Command")) === 0) {
                $name = $rm->getName();
                $lcname = "yanp_" . substr(strtolower($name), 0, -strlen("Command"));
                $params = $args = [];
                foreach ($rm->getParameters() as $rp) {
                    $param = $arg = "\${$rp->getName()}";
                    if ($rp->isOptional()) {
                        $default = var_export($rp->getDefaultValue(), true);
                        assert($default !== null);
                        $param .= " = " . $default;
                    }
                    $params[] = $param;
                    $args[] = $arg;
                }
                $parameters = implode(", ", $params);
                $arguments = implode(", ", $args);
                $body = "return \\Yanp\\Plugin::$name($arguments);";
                $code = "function $lcname($parameters) {\n\t$body\n}";
                eval($code);
            }
        }
    }

    public static function newsboxCommand(): string
    {
        ob_start();
        (new NewsboxCommand(self::getNewsService(), new View))->execute();
        return (string) ob_get_clean();
    }

    public static function feedlinkCommand(?string $icon = null): string
    {
        ob_start();
        (new FeedlinkCommand($icon, new View))->execute();
        return (string) ob_get_clean();
    }

    /** @param array<mixed> $page */
    public static function viewCommand(array $page): string
    {
        ob_start();
        (new PageDataCommand($page, new View))->execute();
        return (string) ob_get_clean();
    }

    private static function getNewsService(): NewsService
    {
        global $pd_router, $plugin_cf;

        return new NewsService(
            $pd_router,
            (int) $plugin_cf['yanp']['entries_max'],
            (bool) $plugin_cf['yanp']['html_markup']
        );
    }
}
