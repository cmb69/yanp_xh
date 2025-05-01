<?php

/**
 * Copyright (c) Christoph M. Becker
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

use Plib\Logger;
use Plib\SystemChecker;
use Plib\View;
use XH\Pages;
use Yanp\Model\NewsFinder;

class Dic
{
    public static function rssCommand(): RssCommand
    {
        global $pth, $plugin_cf;
        return new RssCommand(
            $pth["folder"]["plugins"] . "yanp/",
            $pth["folder"]["images"],
            $plugin_cf["yanp"],
            self::newsFinder(),
            new Logger("yanp"),
            self::view()
        );
    }

    public static function newsboxCommand(): NewsboxCommand
    {
        global $plugin_cf;
        return new NewsboxCommand(
            $plugin_cf["yanp"],
            self::newsFinder(),
            self::view()
        );
    }

    public static function feedLinkCommand(): FeedLinkCommand
    {
        global $pth;
        return new FeedLinkCommand(
            $pth["folder"]["plugins"] . "yanp/",
            $pth["folder"]["templateimages"],
            self::view()
        );
    }

    public static function pageDataCommand(): PageDataCommand
    {
        global $pth;
        return new PageDataCommand($pth["folder"]["corestyle"], self::view());
    }

    public static function infoCommand(): InfoCommand
    {
        global $pth;
        return new InfoCommand(
            $pth["folder"]["plugins"] . "yanp/",
            new SystemChecker(),
            self::view()
        );
    }

    private static function newsFinder(): NewsFinder
    {
        global $pth, $pd_router, $tx, $plugin_tx;
        return new NewsFinder(
            $pth["file"]["content"],
            $tx,
            $plugin_tx["yanp"],
            new Pages(),
            $pd_router
        );
    }

    private static function view(): View
    {
        global $pth, $plugin_tx;
        return new View($pth["folder"]["plugins"] . "yanp/views/", $plugin_tx["yanp"]);
    }
}
