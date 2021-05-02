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

class Feed
{
    public function getTitle(): string
    {
        global $tx, $plugin_tx;

        if ($plugin_tx['yanp']['feed_title'] != '') {
            return $plugin_tx['yanp']['feed_title'];
        } else {
            return $tx['site']['title'];
        }
    }

    public function getDescription(): string
    {
        global $tx, $plugin_tx;

        if ($plugin_tx['yanp']['feed_description'] != '') {
            return $plugin_tx['yanp']['feed_description'];
        } else {
            return $tx['meta']['description'];
        }
    }
}
