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

// phpcs:disable PSR1.Files.SideEffects

use Plib\Request;
use XH\PageDataRouter;
use Yanp\Dic;

if (!defined("CMSIMPLE_XH_VERSION")) {
    http_response_code(403);
    exit;
}

const YANP_VERSION = "2.2";

function yanp_newsbox(): string
{
    return Dic::newsboxCommand()->execute(Request::current());
}

function yanp_feedlink(?string $icon = null): string
{
    return Dic::feedLinkCommand()->execute($icon, Request::current());
}

/**
 * @var PageDataRouter $pd_router
 * @var array<string,array<string,string>> $plugin_cf
 */

$pd_router->add_interest("yanp_timestamp");
$pd_router->add_interest("yanp_description");

Dic::rssCommand()->execute(Request::current())();
