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
    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var array<string,string> */
    private $lang;

    /** @param array<string,string> $lang */
    public function __construct(string $title, string $description, array $lang)
    {
        $this->title = $title;
        $this->description = $description;
        $this->lang = $lang;
    }

    public function getTitle(): string
    {
        if ($this->lang['feed_title'] != '') {
            return $this->lang['feed_title'];
        } else {
            return $this->title;
        }
    }

    public function getDescription(): string
    {
        if ($this->lang['feed_description'] != '') {
            return $this->lang['feed_description'];
        } else {
            return $this->description;
        }
    }
}
