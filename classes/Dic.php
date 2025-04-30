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

use Plib\SystemChecker;
use XH\Pages;

class Dic
{
    public static function rssCommand(): RssCommand
    {
        global $pth, $tx, $plugin_cf, $plugin_tx;
        return new RssCommand(
            $pth["folder"]["images"],
            $pth["file"]["content"],
            $plugin_cf["yanp"],
            new Pages(),
            self::newsService(),
            new Feed($tx["site"]["title"], $tx["meta"]["description"], $plugin_tx["yanp"]),
            self::View()
        );
    }

    public static function newsboxCommand(): NewsboxCommand
    {
        return new NewsboxCommand(self::newsService(), self::view());
    }

    public static function feedLinkCommand(?string $icon): FeedLinkCommand
    {
        return new FeedLinkCommand($icon, self::view());
    }

    /** @param array<mixed> $page */
    public static function pageDataCommand(array $page): PageDataCommand
    {
        global $pth;
        return new PageDataCommand($pth["folder"]["corestyle"], $page, self::view());
    }

    public static function infoCommand(): InfoCommand
    {
        return new InfoCommand(new SystemChecker(), self::view());
    }

    private static function newsService(): NewsService
    {
        global $pd_router, $plugin_cf;
        return new NewsService(
            $pd_router,
            (int) $plugin_cf["yanp"]["entries_max"],
            (bool) $plugin_cf["yanp"]["html_markup"]
        );
    }

    private static function view(): View
    {
        global $pth, $plugin_tx;
        return new View($pth["folder"]["plugins"] . "yanp/views/", $plugin_tx["yanp"]);
    }
}
