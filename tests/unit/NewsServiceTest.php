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
use XH\PageDataRouter as PageDataService;

class NewsServiceTest extends TestCase
{
    public function testGetPageIds(): void
    {
        $pageDataService = $this->createStub(PageDataService::class);
        $pageDataService->method("find_all")->willReturn([[
            "published" => "1", "yanp_description" => "latest news"
        ]]);
        $sut = new NewsService($pageDataService, 3, true);
        $this->assertEquals([0], $sut->getPageIds());
    }

    public function testGetLastMod(): void
    {
        $pageDataService = $this->createStub(PageDataService::class);
        $sut = new NewsService($pageDataService, 3, true);
        $this->assertEquals(0, $sut->getLastMod(0));
    }

    public function testGetDescription(): void
    {
        $pageDataService = $this->createStub(PageDataService::class);
        $pageDataService->method('find_page')->willReturn(["yanp_description" => "<p>some news</p>"]);
        $sut = new NewsService($pageDataService, 3, true);
        $res = $sut->getDescription(0);
        $this->assertInstanceOf(HtmlString::class, $res);
        $this->assertEquals("<p>some news</p>", $res);
    }
}
