<?php

/**
 * Copyright 2025 Christoph M. Becker
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

use PHPUnit\Framework\TestCase;

class FeedTest extends TestCase
{
    public function testGetTitleFromYanp(): void
    {
        $sut = new Feed("A Site", "Site description", ["feed_title" => "The Feed"]);
        $this->assertEquals("The Feed", $sut->getTitle());
    }

    public function testGetTitleFromSite(): void
    {
        $sut = new Feed("A Site", "Site description", ["feed_title" => ""]);
        $this->assertEquals("A Site", $sut->getTitle());
    }

    public function testGetDescriptionFromYanp(): void
    {
        $sut = new Feed("A Site", "Site description", ["feed_description" => "A Feed"]);
        $this->assertEquals("A Feed", $sut->getDescription());
    }

    public function testGetDescriptionFromSite(): void
    {
        $sut = new Feed("A Site", "Site description", ["feed_description" => ""]);
        $this->assertEquals("Site description", $sut->getDescription());
    }
}
