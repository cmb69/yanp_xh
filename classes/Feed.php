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
    /** @var array<string,array<string,string>> */
    private $tx;

    /** @var array<string,array<string,string>> */
    private $ptx;

    /**
     * @param array<string,array<string,string>> $tx
     * @param array<string,array<string,string>> $ptx
     */
    public function __construct(array $tx, array $ptx)
    {
        $this->tx = $tx;
        $this->ptx = $ptx;
    }

    public function getTitle(): string
    {
        if ($this->ptx['yanp']['feed_title'] != '') {
            return $this->ptx['yanp']['feed_title'];
        } else {
            return $this->tx['site']['title'];
        }
    }

    public function getDescription(): string
    {
        if ($this->ptx['yanp']['feed_description'] != '') {
            return $this->ptx['yanp']['feed_description'];
        } else {
            return $this->tx['meta']['description'];
        }
    }
}
